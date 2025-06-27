<?php

namespace App\Services\LupaPassword\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LupasPasswordController extends Controller {
    public function getLupaPass(){
        return view('Services.LupaPassword.mainLupaPassword');
    }
}
