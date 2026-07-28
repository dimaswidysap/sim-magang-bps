<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Front End',
            'Back End',
            'Excel',
            'IT Support',
            'Desain Grafis',
            'Vedeo Editor',
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['nama_skill' => $skill]);
        }

        $this->command->info(count($skills) . ' skill berhasil dibuat.');
    }
}
