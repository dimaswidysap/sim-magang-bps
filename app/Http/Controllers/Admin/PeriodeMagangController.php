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
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255|unique:periode_magang,nama_periode',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'status' => 'required|in:akan_datang,berlangsung,selesai',
            'keterangan' => 'nullable|string',
        ]);

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

        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255|unique:periode_magang,nama_periode,' . $periode->id,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'status' => 'required|in:akan_datang,berlangsung,selesai',
            'keterangan' => 'nullable|string',
        ]);

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
