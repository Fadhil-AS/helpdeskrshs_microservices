<?php

use Illuminate\Support\Facades\Route;
use App\Services\Ticketing\Http\Controllers\TicketingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('ticketing')->group(function() {
    Route::get('/', [TicketingController::class, 'getTicketing']);
});
