<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeMagang;

class PeriodeMagangController extends Controller
{
    //
    public function formPeriodeCreate()
    {
        return view('pages.admin.periode-magang.create');
    }

    public function storePeriode(Request $request)
    {
        $validated = $request->validate(
            [
                'nama_periode' => 'required|string|max:255|unique:periode_magang,nama_periode',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'kuota' => 'nullable|integer|min:1|max:50',
                'status' => 'required|in:akan_datang,berlangsung,selesai',
                'keterangan' => 'nullable|string',
            ],
            [
                // Pesan error untuk field nama_periode
                'nama_periode.required' => 'Nama periode wajib diisi.',
                'nama_periode.string' => 'Nama periode harus berupa teks.',
                'nama_periode.max' => 'Nama periode maksimal 255 karakter.',
                'nama_periode.unique' => 'Nama periode sudah digunakan. Silakan gunakan nama periode lain.',

                // Pesan error untuk field tanggal_mulai
                'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',

                // Pesan error untuk field tanggal_selesai
                'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',

                // Pesan error untuk field kuota
                'kuota.integer' => 'Kuota harus berupa angka.',
                'kuota.min' => 'Kuota minimal 1.',
                'kuota.max' => 'Kuota maksimal 50.',

                // Pesan error untuk field status
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: akan_datang, berlangsung, atau selesai.',

                // Pesan error untuk field keterangan
                'keterangan.string' => 'Keterangan harus berupa teks.',
            ],
        );

        PeriodeMagang::create($validated);

        return redirect()->route('admin-periode-magang')->with('success', 'Periode magang berhasil ditambahkan.');
    }

    public function formPeriodeEdit($id)
    {
        $periode = PeriodeMagang::findOrFail($id);

        return view('pages.admin.periode-magang.update', compact('periode'));
    }

    public function updatePeriode(Request $request, $id)
    {
        $periode = PeriodeMagang::findOrFail($id);

        $validated = $request->validate(
            [
                'nama_periode' => 'required|string|max:255|unique:periode_magang,nama_periode,' . $periode->id,
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'kuota' => 'nullable|integer|min:1|max:50',
                'status' => 'required|in:akan_datang,berlangsung,selesai',
                'keterangan' => 'nullable|string',
            ],
            [
                // Pesan error untuk field nama_periode
                'nama_periode.required' => 'Nama periode wajib diisi.',
                'nama_periode.string' => 'Nama periode harus berupa teks.',
                'nama_periode.max' => 'Nama periode maksimal 255 karakter.',
                'nama_periode.unique' => 'Nama periode sudah digunakan. Silakan gunakan nama periode lain.',

                // Pesan error untuk field tanggal_mulai
                'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',

                // Pesan error untuk field tanggal_selesai
                'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',

                // Pesan error untuk field kuota
                'kuota.integer' => 'Kuota harus berupa angka.', // Ubah dari kuota.string menjadi kuota.integer
                'kuota.min' => 'Kuota minimal 1.',
                'kuota.max' => 'Kuota maksimal 50.',

                // Pesan error untuk field status
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: akan_datang, berlangsung, atau selesai.',

                // Pesan error untuk field keterangan
                'keterangan.string' => 'Keterangan harus berupa teks.',
            ],
        );

        $periode->update($validated);

        return redirect()->route('admin-periode-magang')->with('success', 'Periode magang berhasil diperbarui.');
    }

    public function destroyPeriode($id)
    {
        $periode = PeriodeMagang::findOrFail($id);

        // nullOnDelete di mahasiswa_profiles & tugas - mahasiswa dan tugas
        // yang terhubung ke periode ini TIDAK ikut terhapus, cuma
        // periode_magang_id-nya jadi null.
        $jumlahMahasiswa = $periode->mahasiswaProfiles()->count();

        $periode->delete();

        return redirect()
            ->route('admin-periode-magang')
            ->with('success', "Periode magang berhasil dihapus. {$jumlahMahasiswa} data mahasiswa yang terhubung tetap ada, cuma kehilangan keterkaitan periodenya.");
    }
}
