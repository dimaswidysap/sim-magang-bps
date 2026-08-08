<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Skill;
use App\Models\User;
use App\Models\AsnProfile;
use App\Models\Tugas;

class AsnController extends Controller
{
    //
    public function asnIndex()
    {
        return view('pages.asn.index');
    }

    public function createTugasForm()
    {
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();

        return view('pages.asn.create-task.create', compact('skillList'));
    }
    public function taskNotDone()
    {
        $tugasBelumSelesai = Tugas::milikAsn(auth()->id())
            ->belumSelesai()
            ->with(['mahasiswaProfile.user', 'skills'])
            ->orderBy('deadline')
            ->get();

        return view('pages.asn.task-not-done.index', compact('tugasBelumSelesai'));
    }
    public function taskDone()
    {
        // Mengambil ID dari user yang sedang login.
        // Pastikan login menggunakan guard yang sesuai agar Auth::id() mengembalikan asn_id
        $asnId = Auth::id();

        // Jika asn_id bukan ID utama dari user yang login (misal merelasikan tabel),
        // gunakan: $asnId = Auth::user()->asn_id;

        // Mengambil data menggunakan Local Scope dari model
        $tugasSelesai = Tugas::selesaiByAsn($asnId)->get();

        // Me-return data ke view (silakan sesuaikan nama view Anda)

        return view('pages.asn.task-done.index', compact('tugasSelesai'));
    }

    public function tugasSelesaiDetail($id)
    {
        $tugasDetail = Tugas::with(['mahasiswaProfile', 'asn', 'anggota'])->findOrFail($id);

        return view('pages.asn.task-done.view', compact('tugasDetail'));
    }

    public function showFormProfil()
    {
        $profil = User::with('asnProfile')->findOrFail(Auth::id());

        return view('pages.asn.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nip' => 'required|string|unique:asn_profiles,nip,' . optional($user->asnProfile)->id,
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        AsnProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
            ],
        );

        return redirect()->route('asn-profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroyTugas($id)
    {
        $tugas = Tugas::where('id', $id)
            ->where('asn_id', auth()->id()) // cuma ASN pembuat yang boleh hapus
            ->firstOrFail();

        // cascadeOnDelete otomatis menghapus baris terkait di:
        // - tugas_submissions (semua riwayat upload & review mahasiswa)
        // - tugas_skill (relasi skill yang dibutuhkan tugas ini)
        // - tugas_anggota (semua undangan/anggota tim, apapun statusnya)
        $tugas->delete();

        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil dihapus beserta seluruh data terkait.');
    }

    public function pengumpulanTugas()
    {
        return view('pages.asn.pengumpulan');
    }
}
