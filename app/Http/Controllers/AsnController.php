<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AsnController extends Controller
{
    //
    public function asnIndex(){
        return view('asn.index');
    }

    public function createTugas(){
        return view('asn.create-task.create');
    }
    public function taskNotDone(){
        return view('asn.task-not-done.index');
    }
    public function taskDone(){
        return view('asn.task-done.index');
    }
}
