<?php

namespace App\Services\GantiPassword\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GantiPasswordController extends Controller {
    public function getGantiPass(){
        return view('Services.GantiPassword.mainGantiPassword');
    }
}
