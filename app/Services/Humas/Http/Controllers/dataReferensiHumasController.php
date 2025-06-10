<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use App\Services\Ticketing\Models\JenisLaporan;

class DataReferensiHumasController extends Controller {
    public function getDataReferensiHumas(){
        $klasifikasiPengaduan = KlasifikasiPengaduan::latest()->paginate(5, ['*'], 'klasifikasi_page');
        $jenisMedia = JenisMedia::latest()->paginate(5, ['*'], 'media_page');
        $penyelesaianPengaduan = PenyelesaianPengaduan::latest()->paginate(5, ['*'], 'penyelesaian_page');
        $jenisLaporan = JenisLaporan::latest()->paginate(5, ['*'], 'laporan_page');
        return view('Services.Humas.DataReferensi.mainDataReferensi', [
            'klasifikasiPengaduan' => $klasifikasiPengaduan,
            'jenisMedia' => $jenisMedia,
            'penyelesaianPengaduan' => $penyelesaianPengaduan,
            'jenisLaporan' => $jenisLaporan,
        ]);
    }
}
