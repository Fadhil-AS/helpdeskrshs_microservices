<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function getPasien()
    {
        return view('Services.Ticketing.pasien.mainPasien');
    }
}
