<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelaporanHumasController extends Controller {
    public function getPelaporanHumas(){
        return view('Services.Humas.Pelaporan.mainPelaporan');
    }
}
