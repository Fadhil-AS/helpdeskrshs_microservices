<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DataNomorController extends Controller {
    public function getDataNomor(): View
    {
        return view('Services.Humas.DataNomor.mainDataNomor');
    }
}
