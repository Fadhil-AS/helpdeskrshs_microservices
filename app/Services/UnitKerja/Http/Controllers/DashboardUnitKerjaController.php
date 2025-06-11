<?php

namespace App\Services\UnitKerja\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UnitKerja;

class DashboardUnitKerjaController extends Controller {
    public function getDashboard (){
        return view ('services.unitKerja.dashboard.mainUnitKerja');
    }
}
