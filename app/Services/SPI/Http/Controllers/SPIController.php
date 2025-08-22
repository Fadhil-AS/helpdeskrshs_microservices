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
        $query = Laporan::orderBy('TGL_INSROW', 'desc')
            ->whereHas('klasifikasiPengaduan', function ($q) use ($targetKlasifikasi) {
                $q->whereIn('KLASIFIKASI_PENGADUAN', $targetKlasifikasi);
            });

        $query->filter($request->only(['search', 'status']));

        $complaints = $query->with(['unitKerja', 'jenisMedia'])
                            ->paginate(10)->appends($request->query());
        return view('Services.SPI.mainSPI', ['dataComplaint' => $complaints]);
    }

    public function showPelaporanDetail($id_complaint)
    {
        try {
            $laporan = Laporan::with([
                'unitKerja',
                'jenisMedia',
                'jenisLaporan',
                'klasifikasiPengaduan',
                'penyelesaianPengaduan'
            ])->findOrFail($id_complaint);

            $dataAsArray = $laporan->toArray();

            array_walk_recursive($dataAsArray, function (&$item, $key) {
                if (is_string($item)) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                }
            });
            return response()->json($dataAsArray);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Data pengaduan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }
}
