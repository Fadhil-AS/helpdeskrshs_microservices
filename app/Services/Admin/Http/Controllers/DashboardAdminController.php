<?php

namespace App\Services\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function getDashboard(){
        return view('Services.Admin.Dashboard.mainAdmin');
    }
}
