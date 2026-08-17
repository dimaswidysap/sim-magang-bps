<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // Bisa diakses admin, ASN, mahasiswa (semua role) - lihat saja
    public function index()
    {
        $daftarBerita = Berita::with('user')
            ->latest()
            ->get();

        return view('pages.berita.index', compact('daftarBerita'));
    }

    public function show($id)
    {
        $berita = Berita::with(['user', 'attachments'])->findOrFail($id);

        return view('pages.berita.show', compact('berita'));
    }

    // Hanya admin & ASN (dicek via middleware 'role:admin,asn' di route)
    public function create()
    {
        return view('pages.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'lampiran' => 'nullable|array',
            'lampiran.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
        ]);

        $berita = Berita::create([
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
        ]);

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('berita-attachments', 'public');

                $berita->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('berita-index')->with('success', 'Berita berhasil dipublikasikan.');
    }

    /**
     * PENTING: otorisasi kepemilikan dicek DI SINI, bukan cuma di
     * middleware role. Middleware cuma pastikan "admin atau ASN", tapi
     * TIDAK tahu apakah user ini pembuat berita yang bersangkutan.
     * Tanpa pengecekan user_id ini, ASN lain bisa edit berita ASN lain.
     */
    public function edit($id)
    {
        $berita = Berita::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.berita.update', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::with('attachments')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'lampiran' => 'nullable|array',
            'lampiran.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
            'hapus_lampiran' => 'nullable|array', // id attachment lama yang mau dihapus
            'hapus_lampiran.*' => 'exists:berita_attachments,id',
        ]);

        $berita->update([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
        ]);

        // Hapus lampiran lama yang dicentang untuk dihapus
        if (! empty($validated['hapus_lampiran'])) {
            $berita->attachments()
                ->whereIn('id', $validated['hapus_lampiran'])
                ->get()
                ->each(function ($attachment) {
                    \Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                });
        }

        // Tambah lampiran baru
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('berita-attachments', 'public');

                $berita->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('berita-index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::with('attachments')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hapus file fisik lampiran dari storage sebelum baris database
        // dihapus - cascadeOnDelete cuma hapus baris DB, TIDAK menghapus
        // file fisiknya dari disk.
        foreach ($berita->attachments as $attachment) {
            \Storage::disk('public')->delete($attachment->file_path);
        }

        $berita->delete();

        return redirect()->route('berita-index')->with('success', 'Berita berhasil dihapus.');
    }
}
