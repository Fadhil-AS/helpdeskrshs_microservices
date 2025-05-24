<?php

namespace App\Services\SSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SSDController extends Controller
{
    public function getSSD()
    {
        return view('Services.SSD.mainSSD');
    }
}
