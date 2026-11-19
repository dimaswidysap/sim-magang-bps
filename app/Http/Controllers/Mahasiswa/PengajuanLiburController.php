<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanLibur;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanLiburController extends Controller
{
    public function index(Request $request)
    {
        $profil = Auth::user()->mahasiswaProfile?->load('pembimbing.user');

        if (! $profil) {
            return redirect()
                ->route('mahasiswa-profil-form')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $filterStatus = (string) $request->query('status', 'semua');
        if (! in_array($filterStatus, ['semua', 'pending', 'disetujui', 'ditolak'], true)) {
            $filterStatus = 'semua';
        }

        $baseQuery = PengajuanLibur::query()
            ->where('mahasiswa_profile_id', $profil->id);

        $jumlahSemua = (clone $baseQuery)->count();
        $jumlahPending = (clone $baseQuery)->where('status', 'pending')->count();
        $jumlahDisetujui = (clone $baseQuery)->where('status', 'disetujui')->count();
        $jumlahDitolak = (clone $baseQuery)->where('status', 'ditolak')->count();

        $daftarPengajuan = (clone $baseQuery)
            ->when($filterStatus !== 'semua', fn ($q) => $q->where('status', $filterStatus))
            ->with(['pembimbing.user'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.mahasiswa.pengajuan-libur.index', compact(
            'profil',
            'daftarPengajuan',
            'filterStatus',
            'jumlahSemua',
            'jumlahPending',
            'jumlahDisetujui',
            'jumlahDitolak'
        ));
    }

    public function create()
    {
        $profil = Auth::user()->mahasiswaProfile?->load('pembimbing.user');

        if (! $profil) {
            return redirect()
                ->route('mahasiswa-profil-form')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if (! $profil->asn_profile_id) {
            return redirect()
                ->route('mahasiswa.pengajuan-libur.index')
                ->with('error', 'Anda belum memiliki pembimbing ASN yang ditetapkan, sehingga belum dapat mengajukan surat libur.');
        }

        return view('pages.mahasiswa.pengajuan-libur.create', compact('profil'));
    }

    public function store(Request $request)
    {
        $profil = Auth::user()->mahasiswaProfile;

        if (! $profil || ! $profil->asn_profile_id) {
            return redirect()
                ->route('mahasiswa.pengajuan-libur.index')
                ->with('error', 'Anda belum memiliki pembimbing ASN yang ditetapkan.');
        }

        $validated = $this->validatePengajuan($request, true);

        if ($this->hasBentrokTanggal($profil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai'])) {
            return back()
                ->withInput()
                ->withErrors(['tanggal_mulai' => 'Rentang tanggal tersebut bertabrakan dengan pengajuan libur Anda yang masih pending atau sudah disetujui.']);
        }

        $file = $request->file('file_lampiran');
        $path = $file->store('surat-libur', 'public');

        PengajuanLibur::create([
            'mahasiswa_profile_id' => $profil->id,
            'asn_profile_id' => $profil->asn_profile_id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => $validated['alasan'],
            'file_lampiran' => $path,
            'nama_file_asli' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('mahasiswa.pengajuan-libur.index')
            ->with('success', 'Pengajuan surat libur berhasil dikirim dan menunggu persetujuan pembimbing ASN.');
    }

    public function show($id)
    {
        $profil = Auth::user()->mahasiswaProfile;
        abort_unless($profil, 403);

        $pengajuan = PengajuanLibur::where('mahasiswa_profile_id', $profil->id)
            ->with(['mahasiswaProfile.user', 'pembimbing.user'])
            ->findOrFail($id);

        return view('pages.mahasiswa.pengajuan-libur.show', compact('pengajuan'));
    }

    public function edit($id)
    {
        $profil = Auth::user()->mahasiswaProfile;
        abort_unless($profil, 403);

        $pengajuan = PengajuanLibur::where('mahasiswa_profile_id', $profil->id)
            ->findOrFail($id);

        if (! $pengajuan->bisaDieditAtauDihapus()) {
            return redirect()
                ->route('mahasiswa.pengajuan-libur.index')
                ->with('error', 'Pengajuan libur yang sudah disetujui atau ditolak ASN tidak dapat diubah.');
        }

        return view('pages.mahasiswa.pengajuan-libur.edit', compact('pengajuan', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $profil = Auth::user()->mahasiswaProfile;
        abort_unless($profil, 403);

        $pengajuan = PengajuanLibur::where('mahasiswa_profile_id', $profil->id)
            ->findOrFail($id);

        if (! $pengajuan->bisaDieditAtauDihapus()) {
            return redirect()
                ->route('mahasiswa.pengajuan-libur.index')
                ->with('error', 'Pengajuan libur yang sudah diproses ASN tidak dapat diubah.');
        }

        $validated = $this->validatePengajuan($request, false);

        if ($this->hasBentrokTanggal($profil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai'], $pengajuan->id)) {
            return back()
                ->withInput()
                ->withErrors(['tanggal_mulai' => 'Rentang tanggal tersebut bertabrakan dengan pengajuan libur Anda lainnya.']);
        }

        $filePath = $pengajuan->file_lampiran;
        $namaAsli = $pengajuan->nama_file_asli;

        if ($request->hasFile('file_lampiran')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->file('file_lampiran');
            $filePath = $file->store('surat-libur', 'public');
            $namaAsli = $file->getClientOriginalName();
        }

        $pengajuan->update([
            'asn_profile_id' => $profil->asn_profile_id ?? $pengajuan->asn_profile_id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => $validated['alasan'],
            'file_lampiran' => $filePath,
            'nama_file_asli' => $namaAsli,
        ]);

        return redirect()
            ->route('mahasiswa.pengajuan-libur.index')
            ->with('success', 'Pengajuan surat libur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profil = Auth::user()->mahasiswaProfile;
        abort_unless($profil, 403);

        $pengajuan = PengajuanLibur::where('mahasiswa_profile_id', $profil->id)
            ->findOrFail($id);

        if (! $pengajuan->bisaDieditAtauDihapus()) {
            return redirect()
                ->route('mahasiswa.pengajuan-libur.index')
                ->with('error', 'Pengajuan libur yang sudah disetujui atau ditolak ASN tidak dapat dihapus.');
        }

        if ($pengajuan->file_lampiran && Storage::disk('public')->exists($pengajuan->file_lampiran)) {
            Storage::disk('public')->delete($pengajuan->file_lampiran);
        }

        $pengajuan->delete();

        return redirect()
            ->route('mahasiswa.pengajuan-libur.index')
            ->with('success', 'Pengajuan surat libur berhasil dihapus.');
    }

    /**
     * Validasi input form pengajuan libur:
     * Menerima semua jenis gambar (kecuali SVG), PDF, dan Word (.doc, .docx).
     */
    private function validatePengajuan(Request $request, bool $fileRequired): array
    {
        $fileRules = [
            $fileRequired ? 'required' : 'nullable',
            'file',
            'max:5120',
            'mimes:jpg,jpeg,png,webp,gif,bmp,tif,tiff,ico,avif,heic,heif,pdf,doc,docx',
            function (string $attribute, mixed $value, Closure $fail) {
                $ext = strtolower((string) $value->getClientOriginalExtension());
                $mime = strtolower((string) $value->getMimeType());

                if ($ext === 'svg' || str_contains($mime, 'svg')) {
                    $fail('Format file SVG tidak diperbolehkan. Gunakan format gambar lain, PDF, atau Word.');
                }
            },
        ];

        return $request->validate(
            [
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
                'alasan' => ['required', 'string', 'min:5', 'max:2000'],
                'file_lampiran' => $fileRules,
            ],
            [
                'tanggal_mulai.required' => 'Tanggal mulai libur wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.required' => 'Tanggal selesai libur wajib diisi.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
                'alasan.required' => 'Alasan pengajuan libur wajib diisi.',
                'alasan.min' => 'Alasan minimal 5 karakter.',
                'alasan.max' => 'Alasan maksimal 2000 karakter.',
                'file_lampiran.required' => 'Lampiran surat/bukti libur wajib diunggah.',
                'file_lampiran.file' => 'Lampiran harus berupa file yang valid.',
                'file_lampiran.max' => 'Ukuran file lampiran maksimal 5 MB.',
                'file_lampiran.mimes' => 'File harus berupa gambar (kecuali SVG), PDF, atau Word (.doc/.docx).',
            ]
        );
    }

    private function hasBentrokTanggal(int $mahasiswaProfileId, string $mulai, string $selesai, ?int $ignoreId = null): bool
    {
        return PengajuanLibur::query()
            ->where('mahasiswa_profile_id', $mahasiswaProfileId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->whereDate('tanggal_mulai', '<=', $selesai)
            ->whereDate('tanggal_selesai', '>=', $mulai)
            ->exists();
    }
}
