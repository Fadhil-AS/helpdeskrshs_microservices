<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketingController extends Controller
{
    public function getTicketing()
    {
        return view('Services.Ticketing.mainTicketing');
    }
}
