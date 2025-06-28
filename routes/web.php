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
use App\Services\Humas\Http\Controllers\UserComplaintController;
use App\Services\Humas\Http\Controllers\KlasifikasiPengaduanController;
use App\Services\Humas\Http\Controllers\JenisMediaController;
use App\Services\Humas\Http\Controllers\PenyelesaianPengaduanController;
use App\Services\Humas\Http\Controllers\JenisLaporanController;
use App\Services\UnitKerja\Http\Controllers\DashboardUnitKerjaController;
use App\Services\Admin\Http\Controllers\DashboardAdminController;
use App\Services\Chatbot\Http\Controllers\ChatbotController;
use App\Services\Chatbot\Models\Chatbot;
use App\Services\Login\Http\Controllers\LoginController;
use App\Services\GantiPassword\Http\Controllers\GantiPasswordController;

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

// route login
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [LoginController::class, 'getLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/gantiPassword', [GantiPasswordController::class, 'getGantiPass'])->name('gantiPassword');

// route humas services
Route::prefix('humas')->name('humas.')->middleware('humas')->group(function(){
    // pelaporan
    Route::get('/pelaporanHumas', [PelaporanHumasController::class, 'getPelaporanHumas'])->name('pelaporan-humas');
    Route::post('/pelaporanHumas', [PelaporanHumasController::class, 'storePelaporanHumas'])->name('pelaporan-humas.store');
    Route::get('/pelaporanHumas/{id_complaint}/detail', [PelaporanHumasController::class, 'showPelaporanDetail'])->name('pelaporan-humas.detail');
    Route::put('/pelaporanHumas/{id_complaint}', [PelaporanHumasController::class, 'updatePelaporanHumas'])->name('pelaporan-humas.update');


    // unit kerja humas
    Route::get('/unitKerjaHumas', [UnitKerjaHumasController::class, 'getunitKerjaHumas'])->name('unit-kerja-humas');
    Route::post('/unitKerjaHumas', [UnitKerjaHumasController::class, 'storeUnitKerja'])->name('unit-kerja-humas.store');
    Route::put('/unitKerjaHumas/{unitKerja}', [UnitKerjaHumasController::class, 'updateUnitKerja'])->name('unit-kerja-humas.update');
    Route::delete('/unitKerjaHumas/{unitKerja}', [UnitKerjaHumasController::class, 'destroyUnitKerja'])->name('unit-kerja-humas.destroy');

    // admin unit kerja
    Route::get('/userComplaint', [UserComplaintController::class, 'getUserComplaint'])->name('user-complaint.index');
    Route::post('/userComplaint', [UserComplaintController::class, 'storeUserComplaint'])->name('user-complaint.store');
    Route::put('/userComplaint/{userComplaint}', [UserComplaintController::class, 'updateUserComplaint'])->name('user-complaint.update');
    Route::delete('/userComplaint/{userComplaint}', [UserComplaintController::class, 'destroyUserComplaint'])->name('user-complaint.destroy');

    // direksi humas
    Route::get('/DireksiHumas', [DireksiHumasController::class, 'getDireksiHumas'])->name('direksi-humas');
    Route::post('/DireksiHumas', [DireksiHumasController::class, 'storeDireksiHumas'])->name('direksi-humas.store');
    Route::put('/DireksiHumas/{direksi}', [DireksiHumasController::class, 'updateDireksiHumas'])->name('direksi-humas.update');
    Route::delete('/DireksiHumas/{direksi}', [DireksiHumasController::class, 'destroyDireksiHumas'])->name('direksi-humas.destroy');

    // data referensi humas
    Route::get('/DataReferensiHumas', [DataReferensiHumasController::class, 'getDataReferensiHumas'])->name('data-referensi-humas');

    // klasifikasi pengaduan
    Route::post('/klasifikasi-pengaduan', [KlasifikasiPengaduanController::class, 'store'])->name('klasifikasi-pengaduan.store');
    Route::put('/klasifikasi-pengaduan/{id_klasifikasi}', [KlasifikasiPengaduanController::class, 'update'])->name('klasifikasi-pengaduan.update');
    Route::delete('/klasifikasi-pengaduan/{id_klasifikasi}', [KlasifikasiPengaduanController::class, 'destroy'])->name('klasifikasi-pengaduan.destroy');

    //jenis media
    Route::post('/jenis-media', [JenisMediaController::class, 'store'])->name('jenis-media.store');
    Route::put('/jenis-media/{id_jenis_media}', [JenisMediaController::class, 'update'])->name('jenis-media.update');
    Route::delete('/jenis-media/{id_jenis_media}', [JenisMediaController::class, 'destroy'])->name('jenis-media.destroy');

    // penyelesaian pengaduan
    Route::post('/penyelesaian-pengaduan', [PenyelesaianPengaduanController::class, 'store'])->name('penyelesaian-pengaduan.store');
    Route::put('/penyelesaian-pengaduan/{id_penyelesaian}', [PenyelesaianPengaduanController::class, 'update'])->name('penyelesaian-pengaduan.update');
    Route::delete('/penyelesaian-pengaduan/{id_penyelesaian}', [PenyelesaianPengaduanController::class, 'destroy'])->name('penyelesaian-pengaduan.destroy');

    // jenis laporan
    Route::post('/jenis-laporan', [JenisLaporanController::class, 'store'])->name('jenis-laporan.store');
    Route::put('/jenis-laporan/{id_jenis_laporan}', [JenisLaporanController::class, 'update'])->name('jenis-laporan.update');
    Route::delete('/jenis-laporan/{id_jenis_laporan}', [JenisLaporanController::class, 'destroy'])->name('jenis-laporan.destroy');
});


Route::prefix('unitKerja')->name('unitKerja.')->middleware('unit_kerja')->group(function(){
    // dashboard unit kerja
    Route::get('/dashboard', action: [DashboardUnitKerjaController::class, 'getDashboard'])->name('dashboard');
    Route::get('/dashboard/detail/{id_complaint}', [DashboardUnitKerjaController::class, 'show'])->name('dashboard.show');
    Route::post('/dashboard/update/{id_complaint}', [DashboardUnitKerjaController::class, 'update'])->name('dashboard.update');

});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function(){
    Route::get('/dashboard', action: [DashboardAdminController::class, 'getDashboard'])->name('dashboard');
    Route::get('/admin/dashboard/chart-data', [DashboardAdminController::class, 'getFilteredChartData'])->name('dashboard.chart-data');
});



// Route::prefix('chatbot')->name('chatbot.')->group(function(){
//     Route::get('/chat', action: [ChatbotController::class, 'getChatbot'])->name('chat');

//     //Pengiriman Chat
//     Route::post('/chatbot', [ChatbotController::class, 'handleChat'])->name('handle');

//     //Upload File Excel
//     Route::post('/upload', [ChatbotController::class, 'uploadData'])->name('handle');
// });

Route::post('/chatbot', [ChatbotController::class, 'handleChat']);

//Upload File Excel
Route::post('/upload', [ChatbotController::class, 'uploadData']);

//View Chatbot
Route::get('/chat', function () {
    return view('Services.Chatbot.mainChatbot');
});


//Menampilkan data di tabel
Route::get('/upload', function () {
    $files = Chatbot::all(); // Ambil semua kolom, termasuk id
    return view('Services.Chatbot.uploadData', compact('files'));
});

//Hapus File
Route::delete('/file/{id}', function ($id) {
    $file = Chatbot::findOrFail($id);
    $file->delete();

    return redirect('/upload')->with('status', 'File berhasil dihapus.');
})->name('delete.file');

