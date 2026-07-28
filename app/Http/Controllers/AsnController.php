<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skill;

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
}
