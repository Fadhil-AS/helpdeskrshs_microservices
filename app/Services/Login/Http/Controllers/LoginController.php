<?php

namespace App\Services\Login\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller {
    public function getLogin(){
        return view('Services.Login.mainLogin');
    }
}
