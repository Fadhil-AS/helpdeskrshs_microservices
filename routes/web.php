<?php

use Illuminate\Support\Facades\Route;
use App\Services\Ticketing\Http\Controllers\LaporanController;
use App\Services\Ticketing\Http\Controllers\LacakTicketingController;
use App\Services\Ticketing\Http\Controllers\PasienController;
use App\Services\SSD\Http\Controllers\SSDController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PasienController::class, 'getPasien']);

Route::prefix('ticketing')->name('ticketing.')->group(function() {
    // form pengaduan
    Route::get('/pengaduan', [LaporanController::class, 'getBuatLaporan'])->name('buat-laporan');
    Route::post('/pengaduan', [LaporanController::class, 'storeLaporan'])->name('store-laporan');
    Route::post('/upload-file', [LaporanController::class, 'uploadFile'])->name('upload-file');

    // lacak ticketing
    Route::get('/lacak-ticketing', [LacakTicketingController::class, 'getLacakTicketing'])->name('lacak');
    Route::post('/lacak/search', [LacakTicketingController::class, 'searchTicket'])->name('lacak.search');
    Route::post('/simpan-feedback', [LacakTicketingController::class, 'simpanFeedback'])->name('simpan-feedback');
    Route::post('/lacak-ticketing/tanggapi/{id_complaint}', [LacakTicketingController::class, 'tanggapiPenyelesaian'])->name('lacak.tanggapi');
});

Route::get('/ssd', [SSDController::class, 'getSSD']);
