<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AsnProfile;

class AdminAsn extends Controller
{
    //
   public function detailAsn($id)
{
    // 1. Panggil relasi with() sebelum findOrFail()
    $detailAsn = User::with('asnProfile')->findOrFail($id);

    // 2. Kirim data ke view menggunakan compact()
    return view('admin.asn.view', compact('detailAsn'));
}

}
