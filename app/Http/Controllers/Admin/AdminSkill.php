<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;

class AdminSkill extends Controller
{
    //
    public function createSkill()
    {
        return view('pages.admin.skill.create');
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate(
            [
                'nama_skill' => 'required|string|max:255|unique:skills,nama_skill',
            ],
            [
                // Pesan error untuk field nama_skill
                'nama_skill.required' => 'Nama skill wajib diisi.',
                'nama_skill.string' => 'Nama skill harus berupa teks.',
                'nama_skill.max' => 'Nama skill maksimal 255 karakter.',
                'nama_skill.unique' => 'Nama skill sudah digunakan. Silakan gunakan nama skill lain.',
            ],
        );

        Skill::create($validated);

        return redirect()->route('admin-skill')->with('success', 'Skill berhasil ditambahkan.');
    }

    public function formSkillEdit($id)
    {
        $skill = Skill::findOrFail($id);

        return view('pages.admin.skill.update', compact('skill'));
    }

    public function updateSkill(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'nama_skill' => 'required|string|max:255|unique:skills,nama_skill,' . $skill->id,
        ]);

        $skill->update($validated);

        return redirect()->route('admin-skill')->with('success', 'Skill berhasil diperbarui.');
    }
    public function destroySkill($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        // cascadeOnDelete di tugas_skill & mahasiswa_profile_skill otomatis
        // menghapus baris relasinya - skill ini otomatis "lepas" dari semua
        // mahasiswa dan tugas yang sebelumnya menandainya, tanpa menghapus
        // data mahasiswa/tugas itu sendiri.

        return redirect()->route('admin-skill')->with('success', 'Skill berhasil dihapus.');
    }
}
