<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PeriodeMagang;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;


class MahasiswaController extends Controller
{
    //
    public function mahasiswaIndex()
    {
        return view('pages.mahasiswa.index');
    }

    public function tugas()
    {
        $dataTugas= Tugas::with('asn')->get();
        return view('pages.mahasiswa.tugas.index',compact('dataTugas'));
    }
    public function tugasSaya()
    {
        return view('pages.mahasiswa.tugas-saya.index');
    }

    public function showFormProfil()
    {
        $profil = User::with('mahasiswaProfile.skills')->findOrFail(Auth::id());

        $periodeList = PeriodeMagang::orderBy('tanggal_mulai', 'desc')->get();
        $skillList = Skill::query()->orderBy('nama_skill','asc')->get();

        $selectedSkillIds = $profil->mahasiswaProfile ? $profil->mahasiswaProfile->skills->pluck('id')->toArray() : [];

        return view('pages.mahasiswa.profil', compact('profil', 'periodeList', 'skillList', 'selectedSkillIds'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nim' => 'required|string|unique:mahasiswa_profiles,nim,' . optional($user->mahasiswaProfile)->id,
            'instansi_asal' => 'required|string|max:255',
            'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
            'jurusan' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'periode_magang_id' => 'nullable|exists:periode_magang,id',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $profile = MahasiswaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'periode_magang_id' => $validated['periode_magang_id'] ?? null,
                'nim' => $validated['nim'],
                'instansi_asal' => $validated['instansi_asal'],
                'jenjang' => $validated['jenjang'] ?? null,
                'jurusan' => $validated['jurusan'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            ],
        );

        // sync() - skill yang di-uncheck otomatis hilang, yang baru dicentang
        // otomatis ditambah. ?? [] jaga-jaga kalau semua di-uncheck.
        $profile->skills()->sync($validated['skills'] ?? []);

        return redirect()->route('mahasiswa-profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
