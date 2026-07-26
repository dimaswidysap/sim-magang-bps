<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsnController extends Controller
{
    //
    public function asnIndex(){
        return view('asn.index');
    }
}
