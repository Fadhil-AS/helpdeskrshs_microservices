<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DireksiHumasController extends Controller {
    public function getDireksiHumas(){
        return view('Services.Humas.Direksi.mainDireksi');
    }
}
