<?php

use Illuminate\Support\Facades\Route;
use App\Services\Ticketing\Http\Controllers\LaporanController;
use App\Services\Ticketing\Http\Controllers\LacakTicketingController;
use App\Services\Ticketing\Http\Controllers\PasienController;
use App\Services\SSD\Http\Controllers\SSDController;
use App\Services\Humas\Http\Controllers\PelaporanHumasController;
use App\Services\Humas\Http\Controllers\UnitKerjaHumasController;
use App\Services\Humas\Http\Controllers\DireksiHumasController;
use App\Services\Humas\Http\Controllers\DataReferensiHumasController;

// Route::get('/', function () {
//     return view('welcome');
// });

// route ticketing services
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

// route ssd services
Route::get('/ssd', [SSDController::class, 'getSSD']);

// route humas services
Route::prefix('humas')->name('humas.')->group(function(){
    // pelaporan
    Route::get('/pelaporanHumas', [PelaporanHumasController::class, 'getPelaporanHumas'])->name('pelaporan-humas');

    // unit kerja dan admin unit kerja humas
    Route::get('/unitKerjaHumas', [UnitKerjaHumasController::class, 'getunitKerjaHumas'])->name('unit-kerja-humas');

    // direksi humas
    Route::get('/DireksiHumas', [DireksiHumasController::class, 'getDireksiHumas'])->name('direksi-humas');

    // data referensi humas
    Route::get('/DataReferensiHumas', [DataReferensiHumasController::class, 'getDataReferensiHumas'])->name('data-referensi-humas');
});
