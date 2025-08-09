<?php

namespace App\Services\SPI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\Laporan;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\JenisLaporan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use Illuminate\Support\Facades\DB;

class SPIController extends Controller {
    public function getPelaporanSPI(Request $request)
    {
        $targetKlasifikasi = ['Sponsorship', 'Gratifikasi'];
        $query = Laporan::whereHas('klasifikasiPengaduan', function ($q) use ($targetKlasifikasi) {
            $q->whereIn('KLASIFIKASI_PENGADUAN', $targetKlasifikasi);
        });

        if ($request->has('status') && $request->status != '') {
            $query->where('STATUS', $request->status);
        }

        $complaints = $query->with(['unitKerja', 'jenisMedia'])
                            ->orderBy('TGL_INSROW', 'desc')
                            ->paginate(10);
        return view('Services.SPI.mainSPI', ['dataComplaint' => $complaints]);
    }

    public function showPelaporanDetail($id_complaint)
    {
        $laporan = Laporan::with([
            'unitKerja',
            'jenisMedia',
            'jenisLaporan',
            'klasifikasiPengaduan',
            'penyelesaianPengaduan'
        ])->findOrFail($id_complaint);

        return response()->json($laporan);
    }
}
