<?php

use Illuminate\Support\Facades\Route;
use App\Services\Ticketing\Http\Controllers\LacakTicketingController;
use App\Services\Ticketing\Http\Controllers\LaporanController;
use App\Services\Ticketing\Http\Controllers\PasienController;
use App\Services\SSD\Http\Controllers\SSDController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PasienController::class, 'getPasien']);

Route::prefix('ticketing')->name('ticketing.')->group(function() {
    Route::get('/lacak-ticketing', [LacakTicketingController::class, 'getLacakTicketing']);
    Route::get('/pengaduan', [LaporanController::class, 'getBuatLaporan'])->name('buat-laporan');
    Route::post('/pengaduan', [LaporanController::class, 'storeLaporan'])->name('store-laporan');
    Route::post('/upload-file', [LaporanController::class, 'uploadFile'])->name('upload-file');
});

Route::get('/ssd', [SSDController::class, 'getSSD']);
