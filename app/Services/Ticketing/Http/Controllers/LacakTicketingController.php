<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LacakTicketingController extends Controller
{
    public function getLacakTicketing()
    {
        return view('Services.Ticketing.lacakTicket.mainLacakTicketing');
    }
}
