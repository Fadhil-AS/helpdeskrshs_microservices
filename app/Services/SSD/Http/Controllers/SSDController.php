<?php

namespace App\Services\SSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SSD\Models\KategoriSSD;

class SSDController extends Controller
{
    public function getSSD()
    {
        $semuaKategori = KategoriSSD::with('ssd')->get();

        return view('Services.SSD.mainSSD', [
            'semuaKategori' => $semuaKategori
        ]);
    }
}
