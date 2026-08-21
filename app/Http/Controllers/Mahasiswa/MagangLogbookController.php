<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangLogbook;
use Illuminate\Support\Facades\Storage;

class MagangLogbookController extends Controller
{
    //
    public function magangLogbookForm()
    {
        return view('pages.mahasiswa.logbook.create');
    }

    public function formEdit($id)
    {
        $profil = auth()->user()->mahasiswaProfile;

        $logbook = MagangLogbook::where('id', $id)
            ->where('mahasiswa_profile_id', $profil?->id ?? 0)
            ->firstOrFail();

        return view('pages.mahasiswa.logbook.update', compact('logbook'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'tanggal_kegiatan' => 'required|date|before_or_equal:today',
                'judul_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'file_lampiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'tanggal_kegiatan.required' => 'Tanggal kegiatan wajib diisi.',
                'tanggal_kegiatan.before_or_equal' => 'Tanggal kegiatan tidak boleh melebihi hari ini.',
                'judul_kegiatan.required' => 'Judul kegiatan wajib diisi.',
                'deskripsi_kegiatan.required' => 'Deskripsi kegiatan wajib diisi.',
                'file_lampiran.image' => 'File lampiran harus berupa gambar.',
                'file_lampiran.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
                'file_lampiran.max' => 'Ukuran gambar maksimal 2MB.',
            ],
        );

        $mahasiswaProfile = Auth::user()->mahasiswaProfile;

        if (!$mahasiswaProfile) {
            return back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $filePath = null;
        if ($request->hasFile('file_lampiran')) {
            $filePath = $request->file('file_lampiran')->store('logbook-lampiran', 'public');
        }

        MagangLogbook::create([
            'mahasiswa_profile_id' => $mahasiswaProfile->id,
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'],
            'file_lampiran' => $filePath,
        ]);

        return redirect()->route('mahasiswa-index')->with('success', 'Logbook berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $profil = auth()->user()->mahasiswaProfile;

        $logbook = MagangLogbook::where('id', $id)
            ->where('mahasiswa_profile_id', $profil?->id ?? 0)
            ->firstOrFail();

        $validated = $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'file_lampiran' => 'nullable|file|max:10240|mimes:png,jpg,jpeg',
            'hapus_lampiran' => 'nullable|boolean',
        ]);

        // Kalau ada file baru diupload, hapus file lama dari disk dulu
        // sebelum diganti - mencegah file lama menumpuk tak terpakai.
        if ($request->hasFile('file_lampiran')) {
            if ($logbook->file_lampiran) {
                Storage::disk('public')->delete($logbook->file_lampiran);
            }
            $path = $request->file('file_lampiran')->store('logbook-mandiri', 'public');
        } elseif ($request->boolean('hapus_lampiran')) {
            // Mahasiswa centang "hapus lampiran" tanpa upload file baru
            if ($logbook->file_lampiran) {
                Storage::disk('public')->delete($logbook->file_lampiran);
            }
            $path = null;
        } else {
            // Tidak ada perubahan - pertahankan file lama
            $path = $logbook->file_lampiran;
        }

        $logbook->update([
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'],
            'file_lampiran' => $path,
        ]);

        return redirect()->route('mahasiswa-index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profil = auth()->user()->mahasiswaProfile;

        $logbook = MagangLogbook::where('id', $id)
            ->where('mahasiswa_profile_id', $profil?->id ?? 0)
            ->firstOrFail();

        // Hapus file fisik dari disk SEBELUM hapus baris database -
        // cascadeOnDelete di migration cuma hapus baris DB, tidak
        // menyentuh file fisik di storage.
        if ($logbook->file_lampiran) {
            Storage::disk('public')->delete($logbook->file_lampiran);
        }

        $logbook->delete();

        return redirect()->route('mahasiswa-index')->with('success', 'Kegiatan berhasil dihapus.');
    }

}
