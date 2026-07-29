<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Skill;
use App\Models\User;
use App\Models\AsnProfile;

class AsnController extends Controller
{
    //
    public function asnIndex()
    {
        return view('asn.index');
    }

    public function createTugasForm()
    {
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();

        return view('asn.create-task.create', compact('skillList'));
    }
    public function taskNotDone()
    {
        return view('asn.task-not-done.index');
    }
    public function taskDone()
    {
        return view('asn.task-done.index');
    }

    public function showFormProfil()
    {
        $profil = User::with('asnProfile')->findOrFail(Auth::id());

        return view('asn.profil', compact('profil'));
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
}
