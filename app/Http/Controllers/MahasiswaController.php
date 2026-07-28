<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    //
    public function mahasiswaIndex(){
        return view('mahasiswa.index');
    }

    public function tugas(){
        return view('mahasiswa.tugas.index');
    }
    public function tugasSaya(){
        return view('mahasiswa.tugas-saya.index');
    }
}
