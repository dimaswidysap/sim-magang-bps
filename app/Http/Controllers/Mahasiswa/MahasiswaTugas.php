<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;

class MahasiswaTugas extends Controller
{
    //
    public function detailTugas($id)
    {
        $detailTugas = Tugas::with('asn')->findOrFail($id);
        return view('pages.mahasiswa.tugas.view', compact('detailTugas'));
    }


}
