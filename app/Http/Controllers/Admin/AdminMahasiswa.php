<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsnProfile;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminMahasiswa extends Controller
{
    public function showForm()
    {
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();
        $pembimbingList = $this->getDaftarPembimbing();

        return view('pages.admin.mahasiswa.create', compact('skillList', 'pembimbingList'));
    }

    public function detailMahasiswa($id)
    {
        $detailUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with(['mahasiswaProfile.skills', 'mahasiswaProfile.pembimbing.user'])
            ->firstOrFail();

        return view('pages.admin.mahasiswa.view', compact('detailUser'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'asn_profile_id' => 'required|exists:asn_profiles,id',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            ],
            [
                'name.required' => 'Silakan isi nama lengkap Anda.',
                'name.max' => 'Nama terlalu panjang, maksimal 255 huruf.',
                'email.required' => 'Silakan isi alamat email.',
                'email.email' => 'Mohon masukkan alamat email yang valid (contoh: user@domain.com).',
                'email.unique' => 'Email ini sudah digunakan. Silakan gunakan email yang berbeda.',
                'password.required' => 'Silakan buat kata sandi.',
                'password.min' => 'Kata sandi harus memiliki minimal 8 karakter untuk keamanan.',
                'asn_profile_id.required' => 'Silakan pilih pembimbing magang.',
                'asn_profile_id.exists' => 'Pembimbing magang yang dipilih tidak valid.',
                'tanggal_mulai.date' => 'Format tanggal masuk magang tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai magang tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai magang harus sama dengan atau setelah tanggal masuk.',
            ],
        );

        $tanggalMulai = $validated['tanggal_mulai'] ?? null;
        $tanggalSelesai = $validated['tanggal_selesai'] ?? null;

        $statusOtomatis = MahasiswaProfile::tentukanStatusDariTanggal(
            $tanggalMulai,
            $tanggalSelesai,
            'pending'
        );

        DB::transaction(function () use ($validated, $tanggalMulai, $tanggalSelesai, $statusOtomatis) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);

            MahasiswaProfile::create([
                'user_id' => $user->id,
                'asn_profile_id' => $validated['asn_profile_id'],
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'status' => $statusOtomatis,
            ]);
        });

        Cache::forget('magang_status_sync_lock');

        $tanpaTanggal = empty($tanggalMulai) && empty($tanggalSelesai);
        $pesanSukses = $tanpaTanggal
            ? "Data mahasiswa berhasil ditambahkan dengan status '{$statusOtomatis}'. Karena tanggal masuk dan selesai dikosongkan, akun akan otomatis terhapus setelah 1 bulan apabila peserta tidak melengkapinya."
            : "Data mahasiswa berhasil ditambahkan dengan status '{$statusOtomatis}'. Mahasiswa perlu melengkapi profil (NIM, instansi, dll) setelah login.";

        return redirect()
            ->route('admin-mahasiswa')
            ->with('success', $pesanSukses);
    }

    public function formMahasiswaEdit($id)
    {
        $dataUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with(['mahasiswaProfile.skills', 'mahasiswaProfile.pembimbing.user'])
            ->firstOrFail();

        $skillList = Skill::query()->orderBy('nama_skill', 'asc')->get();
        $pembimbingList = $this->getDaftarPembimbing();

        $selectedSkillIds = $dataUser->mahasiswaProfile
            ? $dataUser->mahasiswaProfile->skills->pluck('id')->toArray()
            : [];

        return view('pages.admin.mahasiswa.update', compact('dataUser', 'skillList', 'selectedSkillIds', 'pembimbingList'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $user = User::query()->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'is_active' => 'required',
                'asn_profile_id' => 'nullable|exists:asn_profiles,id',
                'instansi_asal' => 'nullable|string|max:255',
                'nim' => [
                    'nullable',
                    'string',
                    Rule::unique('mahasiswa_profiles', 'nim')
                        ->where(function ($query) use ($request) {
                            return $query->where('instansi_asal', $request->instansi_asal);
                        })
                        ->ignore(optional($user->mahasiswaProfile)->id),
                ],
                'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
                'jurusan' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:13',
                'tanggal_lahir' => 'nullable|date',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'status' => 'required|in:pending,aktif,selesai,dibatalkan',
                'periode_magang_id' => 'nullable|exists:periode_magang,id',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                'alamat' => 'nullable|string',
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain. Silakan gunakan email lain.',
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.min' => 'Kata sandi minimal 8 karakter.',
                'is_active.required' => 'Status aktif wajib dipilih.',
                'asn_profile_id.exists' => 'Pembimbing magang yang dipilih tidak terdaftar dalam sistem.',
                'instansi_asal.string' => 'Instansi asal harus berupa teks.',
                'instansi_asal.max' => 'Instansi asal maksimal 255 karakter.',
                'nim.string' => 'NIM harus berupa teks.',
                'nim.unique' => 'NIM dengan instansi asal yang sama sudah terdaftar. Silakan gunakan NIM lain.',
                'jenjang.in' => 'Jenjang harus salah satu dari: SMA/SMK, D3, D4, S1, S2.',
                'jurusan.string' => 'Jurusan harus berupa teks.',
                'jurusan.max' => 'Jurusan maksimal 255 karakter.',
                'phone.string' => 'Nomor telepon harus berupa angka.',
                'phone.max' => 'Nomor telepon maksimal 13 karakter.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: pending, aktif, selesai, atau dibatalkan.',
                'periode_magang_id.exists' => 'Periode magang yang dipilih tidak terdaftar dalam sistem.',
                'skills.array' => 'Format skills tidak valid.',
                'skills.*.exists' => 'Skill yang dipilih tidak terdaftar dalam sistem.',
            ],
        );

        $statusFinal = MahasiswaProfile::tentukanStatusDariTanggal(
            $validated['tanggal_mulai'] ?? null,
            $validated['tanggal_selesai'] ?? null,
            $validated['status']
        );

        DB::transaction(function () use ($validated, $user, $statusFinal) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
                'is_active' => $validated['is_active'] ?? false,
            ]);

            $profile = MahasiswaProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'asn_profile_id' => $validated['asn_profile_id'] ?? null,
                    'periode_magang_id' => $validated['periode_magang_id'] ?? null,
                    'nim' => $validated['nim'] ?? null,
                    'instansi_asal' => $validated['instansi_asal'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'jurusan' => $validated['jurusan'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                    'status' => $statusFinal,
                    'alamat' => $validated['alamat'] ?? null,
                ],
            );

            $profile->skills()->sync($validated['skills'] ?? []);
        });

        Cache::forget('magang_status_sync_lock');

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroyMahasiswa(Request $request, $id)
    {
        $user = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Mengambil daftar pembimbing (ASN aktif) beserta jumlah peserta yang sedang dibimbing,
     * diurutkan dari beban bimbingan paling sedikit.
     */
    private function getDaftarPembimbing(): Collection
    {
        return AsnProfile::query()
            ->whereHas('user', function ($query) {
                $query->where('role', 'asn')
                    ->where('is_active', true);
            })
            ->with('user')
            ->withCount(['mahasiswaBimbinganAktif as jumlah_bimbingan'])
            ->get()
            ->sortBy(fn (AsnProfile $asn) => [
                $asn->jumlah_bimbingan,
                strtolower($asn->user->name ?? ''),
            ])
            ->values();
    }
}
