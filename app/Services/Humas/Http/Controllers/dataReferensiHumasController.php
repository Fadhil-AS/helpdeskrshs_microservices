<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataReferensiHumasController extends Controller {
    public function getDataReferensiHumas(){
        return view('Services.Humas.DataReferensi.mainDataReferensi');
    }
}
