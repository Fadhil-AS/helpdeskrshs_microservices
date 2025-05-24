<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function getBuatLaporan()
    {
        return view('Services.Ticketing.buatLaporan.mainBuatLaporan');
    }
}
