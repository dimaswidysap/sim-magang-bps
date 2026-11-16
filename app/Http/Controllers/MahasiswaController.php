<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\MagangLogbook;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class MahasiswaController extends Controller
{
    public function mahasiswaIndex()
    {
        $user = Auth::user();
        $profil = $user->mahasiswaProfile;
        $mahasiswaProfileId = $profil?->id;

        $jumlahSelesai = $mahasiswaProfileId ? Tugas::getJumlahTugasSelesai($mahasiswaProfileId) : 0;
        $jumlahBelumSelesai = $mahasiswaProfileId ? Tugas::getJumlahTugasBelumSelesai($mahasiswaProfileId) : 0;

        $bulanList = collect();
        $tanggalAktif = collect();
        $logbookMenungguValidasi = collect();

        if ($profil) {
            $logbookMenungguValidasi = MagangLogbook::where('mahasiswa_profile_id', $profil->id)
                ->belumValidasi()
                ->orderByDesc('tanggal_kegiatan')
                ->orderByDesc('id')
                ->get();
        }

        if ($profil && $profil->tanggal_mulai && $profil->tanggal_selesai) {
            $kursor = $profil->tanggal_mulai->copy()->startOfMonth();
            $akhir = $profil->tanggal_selesai->copy()->startOfMonth();

            while ($kursor->lte($akhir)) {
                $bulanList->push([
                    'label' => $kursor->translatedFormat('F Y'),
                    'tahun' => $kursor->year,
                    'bulan' => $kursor->month,
                    'jumlah_hari' => $kursor->daysInMonth,
                ]);
                $kursor->addMonth();
            }

            $tanggalDariTugas = Logbook::where('mahasiswa_profile_id', $profil->id)
                ->whereNotNull('created_at')
                ->selectRaw('DISTINCT DATE(created_at) as tanggal')
                ->pluck('tanggal');

            // Logbook mandiri masuk ke kalender logbook hanya setelah divalidasi pembimbing
            $tanggalDariMandiri = MagangLogbook::where('mahasiswa_profile_id', $profil->id)
                ->tervalidasi()
                ->whereNotNull('tanggal_kegiatan')
                ->selectRaw('DISTINCT DATE(tanggal_kegiatan) as tanggal')
                ->pluck('tanggal');

            $tanggalAktif = $tanggalDariTugas->merge($tanggalDariMandiri)->unique()->values();
        }

        return view('pages.mahasiswa.index', compact(
            'jumlahSelesai',
            'jumlahBelumSelesai',
            'bulanList',
            'tanggalAktif',
            'profil',
            'logbookMenungguValidasi'
        ));
    }

    public function logbookDetailTanggal($tanggal)
    {
        $profil = auth()->user()->mahasiswaProfile;

        if (!$profil) {
            abort(404);
        }

        $logbookTugas = Logbook::where('mahasiswa_profile_id', $profil->id)
            ->whereDate('created_at', $tanggal)
            ->with(['tugas.asn', 'tugas.skills'])
            ->get();

        $logbookMandiri = MagangLogbook::where('mahasiswa_profile_id', $profil->id)
            ->tervalidasi()
            ->whereDate('tanggal_kegiatan', $tanggal)
            ->get();

        if ($logbookTugas->isEmpty() && $logbookMandiri->isEmpty()) {
            abort(404, 'Tidak ada kegiatan pada tanggal ini.');
        }

        return view('pages.mahasiswa.logbook-detail', [
            'logbookTugas' => $logbookTugas,
            'logbookMandiri' => $logbookMandiri,
            'tanggal' => $tanggal,
        ]);
    }

    public function tugas(Request $request)
    {
        $profil = Auth::user()->mahasiswaProfile?->loadMissing('pembimbing');
        $status = $request->query('status', 'tersedia');
        $search = trim((string) $request->query('search'));

        $dataTugas = Tugas::with(['asn', 'skills', 'attachments'])
            ->untukPembimbingMahasiswa($profil)
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('asn', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.mahasiswa.tugas.index', compact('dataTugas'));
    }

    public function tugasSaya(Request $request)
    {
        $profil = Auth::user()->mahasiswaProfile;
        $statusTugas = $request->query('status', 'diambil');
        $search = trim((string) $request->query('search'));

        $bulan = $statusTugas === 'selesai' ? $this->parseValidMonth($request->query('bulan')) : null;
        $hari = $statusTugas === 'selesai' ? $this->parseValidDate($request->query('hari')) : null;

        if ($hari && !$bulan) {
            $bulan = substr($hari, 0, 7);
        } elseif ($bulan && $hari && substr($hari, 0, 7) !== $bulan) {
            $hari = null;
        }

        if (!$profil) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 10);

            return view('pages.mahasiswa.tugas-saya.index', [
                'dataTugas' => $emptyPaginator,
                'bulan' => $bulan,
                'hari' => $hari,
            ])->with('error', 'Lengkapi profil Anda dulu untuk bisa mengambil tugas.');
        }

        $dataTugas = Tugas::milikMahasiswa($profil->id)
            ->when($statusTugas, fn ($query, $statusTugas) => $query->where('status', $statusTugas))
            ->when($statusTugas === 'selesai', function ($query) use ($bulan, $hari) {
                if ($hari) {
                    $query->whereDate('selesai_at', $hari);
                } elseif ($bulan) {
                    $parsedBulan = Carbon::createFromFormat('Y-m', $bulan);
                    $query->whereYear('selesai_at', $parsedBulan->year)
                        ->whereMonth('selesai_at', $parsedBulan->month);
                }
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('asn', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
                });
            })
            ->with(['asn', 'skills', 'anggotaDiterima.mahasiswaProfile.user'])
            ->when(
                $statusTugas === 'selesai',
                fn ($query) => $query->orderByDesc('selesai_at')->orderByDesc('id'),
                fn ($query) => $query->orderByDesc('created_at')
            )
            ->paginate(10)
            ->withQueryString();

        return view('pages.mahasiswa.tugas-saya.index', compact('dataTugas', 'bulan', 'hari'));
    }

    public function detailTugasSaya($id)
    {
        $profil = auth()->user()->mahasiswaProfile;

        if (!$profil) {
            abort(404);
        }

        $detailTugas = Tugas::milikMahasiswa($profil->id)
            ->with([
                'mahasiswaProfile.user',
                'anggotaDiterima.mahasiswaProfile.user',
                'asn',
                'attachments',
                'submissions' => fn ($query) => $query->latest(),
            ])
            ->findOrFail($id);

        return view('pages.mahasiswa.tugas-saya.view', compact('detailTugas'));
    }

    public function profil()
    {
        $profil = User::with('mahasiswaProfile.skills')->findOrFail(Auth::id());

        return view('pages.mahasiswa.profil-view', compact('profil'));
    }

    public function showFormProfil()
    {
        $profil = User::with('mahasiswaProfile.skills')->findOrFail(Auth::id());

        $skillList = Skill::query()->orderBy('nama_skill', 'asc')->get();

        $selectedSkillIds = $profil->mahasiswaProfile ? $profil->mahasiswaProfile->skills->pluck('id')->toArray() : [];

        return view('pages.mahasiswa.profil', compact('profil', 'skillList', 'selectedSkillIds'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:13',
                'instansi_asal' => 'required|string|max:255',
                'nim' => [
                    'required',
                    'string',
                    Rule::unique('mahasiswa_profiles', 'nim')
                        ->where(fn ($query) => $query->where('instansi_asal', $request->instansi_asal))
                        ->ignore(optional($user->mahasiswaProfile)->id),
                ],
                'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
                'jurusan' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'periode_magang_id' => 'nullable|exists:periode_magang,id',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
            ],
            [
                'foto_profil.image' => 'File foto profil harus berupa gambar.',
                'foto_profil.mimes' => 'Format foto profil yang diperbolehkan hanya: jpeg, png, jpg, atau webp.',
                'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'phone.string' => 'Nomor telepon harus berupa teks angka.',
                'phone.max' => 'Nomor telepon maksimal 13 karakter.',
                'instansi_asal.required' => 'Instansi asal wajib diisi.',
                'instansi_asal.string' => 'Instansi asal harus berupa teks.',
                'instansi_asal.max' => 'Instansi asal maksimal 255 karakter.',
                'nim.required' => 'NIM wajib diisi.',
                'nim.string' => 'NIM harus berupa teks.',
                'nim.unique' => 'NIM dengan instansi asal yang sama sudah terdaftar. Silakan gunakan NIM lain.',
                'jenjang.in' => 'Jenjang harus salah satu dari: SMA/SMK, D3, D4, S1, S2.',
                'jurusan.string' => 'Jurusan harus berupa teks.',
                'jurusan.max' => 'Jurusan maksimal 255 karakter.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
                'periode_magang_id.exists' => 'Periode magang yang dipilih tidak terdaftar dalam sistem.',
                'skills.array' => 'Format skills tidak valid.',
                'skills.*.exists' => 'Skill yang dipilih tidak terdaftar dalam sistem.',
            ],
        );

        $profile = $user->mahasiswaProfile;
        $fotoProfilPath = $profile?->foto_profil_path;

        if ($request->hasFile('foto_profil')) {
            if ($fotoProfilPath && Storage::disk('public')->exists($fotoProfilPath)) {
                Storage::disk('public')->delete($fotoProfilPath);
            }

            $fotoProfilPath = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $profile = MahasiswaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'foto_profil_path' => $fotoProfilPath,
                'periode_magang_id' => $validated['periode_magang_id'] ?? null,
                'nim' => $validated['nim'],
                'instansi_asal' => $validated['instansi_asal'],
                'jenjang' => $validated['jenjang'] ?? null,
                'jurusan' => $validated['jurusan'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'alamat' => $request->input('alamat'),
            ],
        );

        $profile->skills()->sync($validated['skills'] ?? []);

        return redirect()->route('profil-magang')->with('success', 'Profil berhasil diperbarui.');
    }

    private function parseValidMonth(mixed $value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}$/', $value)) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m', $value);
            return $date && $date->format('Y-m') === $value ? $value : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function parseValidDate(mixed $value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $value);
            return $date && $date->format('Y-m-d') === $value ? $value : null;
        } catch (Throwable) {
            return null;
        }
    }
}
