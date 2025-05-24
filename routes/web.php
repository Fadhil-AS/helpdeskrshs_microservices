<?php

use Illuminate\Support\Facades\Route;
use App\Services\Ticketing\Http\Controllers\LacakTicketingController;
use App\Services\Ticketing\Http\Controllers\LaporanController;
use App\Services\Ticketing\Http\Controllers\PasienController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('ticketing')->group(function() {
    Route::get('/lacak-ticketing', [LacakTicketingController::class, 'getLacakTicketing']);
    Route::get('/pengaduan', [LaporanController::class, 'getBuatLaporan']);
    Route::get('/pasien', [PasienController::class, 'getPasien']);
});
