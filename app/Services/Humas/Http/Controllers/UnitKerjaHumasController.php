<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitKerjaHumasController extends Controller {
    public function getUnitKerjaHumas(){
        return view('Services.Humas.unitKerjaHumas.mainUnitKerja');
    }
}
