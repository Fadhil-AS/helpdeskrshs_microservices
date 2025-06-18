<?php

namespace App\Services\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ticketing\Models\Laporan;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use App\Services\Ticketing\Models\JenisLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DashboardAdminController extends Controller
{
    private function generateChartDataWithDefinedLabels(Builder $baseQuery, string $type, string $name, array $definedLabels, ?string $relationColumn = null): array
    {
        $queryData = null;
        $queryBuilder = null;

        if (empty($definedLabels)) {
            return ['labels' => [], 'data' => []];
        }

        if ($type === 'field') {
            $queryBuilder = (clone $baseQuery)->select($name . ' as label', DB::raw('count(*) as total'))
                ->whereIn($name, $definedLabels)
                ->groupBy('label');

            $queryData = $queryBuilder->get()->pluck('total', 'label');

        } else { // type 'relation'
            $laporanModel = new Laporan();
            if (!method_exists($laporanModel, $name)) {
                return ['labels' => $definedLabels, 'data' => array_fill(0, count($definedLabels), 0)];
            }

            $relation = $laporanModel->{$name}();
            $relatedTable = $relation->getRelated()->getTable();
            $foreignKey = $relation->getQualifiedForeignKeyName();
            $ownerKey = $relation->getQualifiedOwnerKeyName();

            $queryBuilder = (clone $baseQuery)->join($relatedTable, $foreignKey, '=', $ownerKey)
                ->selectRaw("{$relatedTable}.{$relationColumn} as label, count(data_complaint.ID_COMPLAINT) as total")
                ->whereIn("{$relatedTable}.{$relationColumn}", $definedLabels)
                ->groupBy('label');

            $queryData = $queryBuilder->get()->pluck('total', 'label');
        }

        $data = [];
        foreach ($definedLabels as $label) {
            $data[] = $queryData[$label] ?? 0;
        }

        return ['labels' => $definedLabels, 'data' => $data];
    }

    public function getDashboard()
    {
        // try {
        //     $tableName = (new UnitKerja)->getTable();
        //     // Kita cari data HANYA untuk "DIREKTUR UTAMA"
        //     $dirutData = DB::select("SELECT ID_BAGIAN, NAMA_BAGIAN, ID_PARENT_BAGIAN, STATUS FROM {$tableName} WHERE NAMA_BAGIAN = 'DIREKTUR UTAMA'");

        //     dd([
        //         'CATATAN' => 'Melihat data mentah untuk baris DIREKTUR UTAMA',
        //         'DATA_DITEMUKAN' => $dirutData
        //     ]);

        // } catch (\Exception $e) {
        //     dd('GAGAL MENCARI DATA DIREKTUR UTAMA: ' . $e->getMessage());
        // }

        $unitKerjaList = UnitKerja::where('STATUS', '1')->orderBy('NAMA_BAGIAN')->get();

        $topLevelUnitList = UnitKerja::where('STATUS', '1')
            ->where(function ($query) {
                $query->where('ID_PARENT_BAGIAN', ' ')
                      ->orWhere('ID_PARENT_BAGIAN', 0)
                      ->orWhereNull('ID_PARENT_BAGIAN')
                      ->orWhere('ID_PARENT_BAGIAN', '1');
            })
            ->orderBy('NAMA_BAGIAN')
            ->get();

        return view('Services.Admin.Dashboard.mainAdmin', [
            'unitKerjaList' => $unitKerjaList,
            'topLevelUnitList' => $topLevelUnitList,
        ]);


    }

    private function applyTimeFilter(Builder $query, ?string $timeFilter)
    {
        if ($timeFilter === 'bulanan') {
            $query->where('TGL_COMPLAINT', '>=', Carbon::now()->subDays(30));
        } elseif ($timeFilter === 'triwulan') {
            $query->where('TGL_COMPLAINT', '>=', Carbon::now()->subDays(90));
        } elseif ($timeFilter === 'semester') {
            $query->where('TGL_COMPLAINT', '>=', Carbon::now()->subDays(180));
        }
    }

    private function applyUnitKerjaFilter(Builder $query, ?string $unitKerjaId, ?string $subUnitId)
    {
        $laporanTable = (new Laporan)->getTable();

        if (!empty($subUnitId) && $subUnitId !== 'Semua Sub Unit') {
            $query->where("{$laporanTable}.ID_BAGIAN", $subUnitId);
        }
        elseif (!empty($unitKerjaId) && $unitKerjaId !== 'Semua Unit Kerja') {
            $subUnitIds = UnitKerja::where('ID_PARENT_BAGIAN', $unitKerjaId)->pluck('ID_BAGIAN');

            if ($subUnitIds->isEmpty()) {
                $query->where("{$laporanTable}.ID_BAGIAN", $unitKerjaId);
            } else {
                $query->whereIn("{$laporanTable}.ID_BAGIAN", $subUnitIds);
            }
        }
    }

    private function getAllDescendantIds($parentId)
    {
        $directChildren = UnitKerja::where('ID_PARENT_BAGIAN', $parentId)->get();
        $allDescendantIds = [];
        foreach ($directChildren as $child) {
            $allDescendantIds[] = $child->ID_BAGIAN;
            $allDescendantIds = array_merge($allDescendantIds, $this->getAllDescendantIds($child->ID_BAGIAN));
        }
        return $allDescendantIds;
    }

    private function getAggregatedUnitKerjaData(Builder $baseQuery): array
    {
        $topLevelUnits = UnitKerja::where('STATUS', '1')
            ->where(function($query) {
                $query->where('ID_PARENT_BAGIAN', ' ')
                      ->orWhereNull('ID_PARENT_BAGIAN')
                      ->orWhere('ID_PARENT_BAGIAN', 0);
            })
            ->orderBy('NAMA_BAGIAN')
            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($topLevelUnits as $topUnit) {
            $chartLabels[] = $topUnit->NAMA_BAGIAN;
            $idsToCount = $this->getAllDescendantIds($topUnit->ID_BAGIAN);
            $idsToCount[] = $topUnit->ID_BAGIAN;
            $count = (clone $baseQuery)->whereIn('ID_BAGIAN', $idsToCount)->count();
            $chartData[] = $count;
        }

        return [
            'labels' => $chartLabels,
            'data'   => $chartData,
        ];
    }

    public function getFilteredChartData(Request $request)
    {
        $unitKerjaId = $request->input('unit_kerja_id');
        $subUnitId = $request->input('sub_unit_id');

        $definedLabels = [
            'grading'               => ['Hijau', 'Kuning', 'Merah'],
            'sumberMedia'           => JenisMedia::where('STATUS', '1')->pluck('JENIS_MEDIA')->toArray(),
            'statusPengaduan'       => ['Open', 'On Progress', 'Menunggu Konfirmasi', 'Banding', 'Close'],
            'jenisLaporan'          => JenisLaporan::where('STATUS', '1')->pluck('JENIS_LAPORAN')->toArray(),
            'klasifikasiPengaduan'  => KlasifikasiPengaduan::where('STATUS', '1')->pluck('KLASIFIKASI_PENGADUAN')->toArray(),
            'penyelesaianPengaduan' => PenyelesaianPengaduan::where('STATUS', '1')->pluck('PENYELESAIAN_PENGADUAN')->toArray(),
        ];

        $queryForUnitKerjaLabels = UnitKerja::where('STATUS', '1');
        if ($unitKerjaId && $unitKerjaId !== 'Semua Unit Kerja') {
            $queryForUnitKerjaLabels->where('ID_PARENT_BAGIAN', $unitKerjaId);
        } else {
            $queryForUnitKerjaLabels->where(function($query) {
                $query->where('ID_PARENT_BAGIAN', ' ')
                      ->orWhereNull('ID_PARENT_BAGIAN')
                      ->orWhere('ID_PARENT_BAGIAN', 0);
            });
        }
        $definedLabels['unitKerja'] = $queryForUnitKerjaLabels->pluck('NAMA_BAGIAN')->toArray();

        if ($unitKerjaId && empty($definedLabels['unitKerja'])) {
            $definedLabels['unitKerja'] = [];
        }

        $baseQuery = Laporan::query();
        $this->applyTimeFilter($baseQuery, $request->input('time_filter'));
        $this->applyUnitKerjaFilter($baseQuery, $unitKerjaId, $subUnitId);

        $baseConfigs = [
            'grading' => [ 'title' => 'Grading (Hijau, Kuning, Merah)', 'subtitle' => 'Distribusi pengaduan berdasarkan tingkat waktu penanganan komplain', 'type' => 'bar', 'backgroundColor' => ['#347433', '#FFD600', '#D50000'] ],
            'sumberMedia' => [ 'title' => 'Sumber Media', 'subtitle' => 'Distribusi pengaduan berdasarkan sumber media pelaporan', 'type' => 'bar', 'backgroundColor' => '#e65100' ],
            'statusPengaduan' => [ 'title' => 'Status Pengaduan', 'subtitle' => 'Distribusi pengaduan berdasarkan status penanganan', 'type' => 'pie', 'backgroundColor' => ['#28a745', '#ffc107', '#17a2b8', '#6f42c1', '#dc3545'] ],
            'unitKerja' => [ 'title' => 'Unit Kerja', 'subtitle' => 'Distribusi pengaduan berdasarkan unit kerja tujuan', 'type' => 'bar', 'backgroundColor' => '#E0440E' ],
            'jenisLaporan' => [ 'title' => 'Jenis Laporan', 'subtitle' => 'Distribusi pengaduan berdasarkan jenis laporan', 'type' => 'pie', 'backgroundColor' => ['#2962FF', '#D84315', '#FF9800', '#2E7D32'] ],
            'klasifikasiPengaduan' => [ 'title' => 'Klasifikasi Pengaduan', 'subtitle' => 'Distribusi pengaduan berdasarkan klasifikasi pengaduan', 'type' => 'pie', 'backgroundColor' => ['#2962FF', '#D84315', '#FF9800'] ],
            'penyelesaianPengaduan' => [ 'title' => 'Penyelesaian Pengaduan', 'subtitle' => 'Distribusi pengaduan berdasarkan status penyelesaian', 'type' => 'bar', 'backgroundColor' => '#e65100' ]
        ];

        $chartMap = [
            'grading'               => ['type' => 'field', 'name' => 'GRANDING'],
            'sumberMedia'           => ['type' => 'relation', 'name' => 'jenisMedia', 'column' => 'JENIS_MEDIA'],
            'statusPengaduan'       => ['type' => 'field', 'name' => 'STATUS'],
            'unitKerja'             => ['type' => 'relation', 'name' => 'unitKerja', 'column' => 'NAMA_BAGIAN'],
            'jenisLaporan'          => ['type' => 'relation', 'name' => 'jenisLaporan', 'column' => 'JENIS_LAPORAN'],
            'klasifikasiPengaduan'  => ['type' => 'relation', 'name' => 'klasifikasiPengaduan', 'column' => 'KLASIFIKASI_PENGADUAN'],
            'penyelesaianPengaduan' => ['type' => 'relation', 'name' => 'penyelesaianPengaduan', 'column' => 'PENYELESAIAN_PENGADUAN'],
        ];

        $chartData = [];
        foreach ($chartMap as $key => $config) {
            if ($key === 'unitKerja' && empty($request->input('unit_kerja_id'))) {
                $data = $this->getAggregatedUnitKerjaData(clone $baseQuery);
            } else {
                $data = $this->generateChartDataWithDefinedLabels(
                    clone $baseQuery,
                    $config['type'],
                    $config['name'],
                    $definedLabels[$key],
                    $config['column'] ?? null
                );
            }
            $chartData[$key] = array_merge($baseConfigs[$key], $data);
        }

        return response()->json($chartData);
    }
}
