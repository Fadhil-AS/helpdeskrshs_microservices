<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Services\Humas\Models\Humas;
use Illuminate\Validation\Rule;

class PengaturanSSDController extends Controller {
    public function getPengaturanSSD(): View
    {
        return view('Services.Humas.PengaturanSSD.mainPengaturanSSD');
    }
}
