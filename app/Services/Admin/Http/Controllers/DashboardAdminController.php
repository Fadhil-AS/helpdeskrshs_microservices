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

        } else {
            $laporanModel = new Laporan();
            $laporanTable = $laporanModel->getTable();
            if (!method_exists($laporanModel, $name)) {
                return ['labels' => $definedLabels, 'data' => array_fill(0, count($definedLabels), 0)];
            }

            $relation = $laporanModel->{$name}();
            $relatedTable = $relation->getRelated()->getTable();
            $foreignKey = $relation->getQualifiedForeignKeyName();
            $ownerKey = $relation->getQualifiedOwnerKeyName();

            $queryBuilder = (clone $baseQuery)->join($relatedTable, $foreignKey, '=', $ownerKey)
                ->selectRaw("{$relatedTable}.{$relationColumn} as label, count({$laporanTable}.ID_COMPLAINT) as total")
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
        $role = session('role');
        $userBagian = session('user')->ID_BAGIAN ?? null;

        $unitsQuery = UnitKerja::query();

        if ($role === 'direksi' && !empty($userBagian)) {
            $unitsQuery->where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $userBagian)
                   ->where('SUPER', '0');
        } else {
            $unitsQuery->where('STATUS', '1')->where(function ($query) {
                $query->where('ID_PARENT_BAGIAN', ' ')
                    ->orWhere('ID_PARENT_BAGIAN', 0)
                    ->orWhereNull('ID_PARENT_BAGIAN')
                    ->orWhere('ID_PARENT_BAGIAN', '1');
            });
        }

        $unitKerjaList = $unitsQuery->orderBy('NAMA_BAGIAN')->get();

        return view('Services.Admin.Dashboard.mainAdmin', [
            'unitKerjaList' => $unitKerjaList,
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

    private function applyUnitKerjaFilter(Builder $query, ?string $unitKerjaId)
    {
        if (!empty($unitKerjaId)) {
            $laporanTable = (new Laporan)->getTable();
            $query->where("{$laporanTable}.ID_BAGIAN", $unitKerjaId);
        }
    }

    private function getAllDescendantIds($parentId)
    {
        $directChildren = UnitKerja::where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $parentId)->get();

        $allDescendantIds = [];
        foreach ($directChildren as $child) {
            $allDescendantIds[] = $child->ID_BAGIAN;
            $allDescendantIds = array_merge($allDescendantIds, $this->getAllDescendantIds($child->ID_BAGIAN));
        }
        return $allDescendantIds;
    }

    private function getAggregatedUnitKerjaData(Builder $baseQuery): array
    {
        $role = session('role');
        $userBagian = optional(session('user'))->ID_BAGIAN;
        $unitsQuery = UnitKerja::query();

        if ($role === 'direksi' && !empty($userBagian)) {
            $unitsQuery->where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $userBagian)
                       ->where('SUPER', '0');

        } else {
            $unitsQuery->where('STATUS', '1')
                       ->where(function($query) {
                           $query->where('ID_PARENT_BAGIAN', ' ')
                                 ->orWhereNull('ID_PARENT_BAGIAN')
                                 ->orWhere('ID_PARENT_BAGIAN', 0)
                                 ->orWhere('ID_PARENT_BAGIAN', '1');
                       });
        }

        $unitsToDisplay = $unitsQuery->orderBy('NAMA_BAGIAN')->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($unitsToDisplay as $unit) {
            $chartLabels[] = $unit->NAMA_BAGIAN;

            $idsToCount = $this->getAllDescendantIds($unit->ID_BAGIAN);
            $idsToCount[] = $unit->ID_BAGIAN;

            $laporanTable = (new Laporan)->getTable();
            $count = (clone $baseQuery)->whereIn("{$laporanTable}.ID_BAGIAN", $idsToCount)->count();
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
        $timeFilter = $request->input('time_filter');
        $role = session('role');
        $userBagian = session('user')->ID_BAGIAN;

        $baseQuery = Laporan::query();
        $laporanTable = (new Laporan)->getTable();

        if ($role === 'direksi' && !empty($userBagian)) {
            $specialChildren = UnitKerja::where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $userBagian)
                                        ->where('SUPER', '0')
                                        ->get();

            $allowedUnitIds = [];
            foreach ($specialChildren as $child) {
                $allowedUnitIds[] = $child->ID_BAGIAN;
                $descendants = $this->getAllDescendantIds($child->ID_BAGIAN);
                $allowedUnitIds = array_merge($allowedUnitIds, $descendants);
            }

            if (empty($allowedUnitIds)) {
                $baseQuery->whereRaw('1 = 0');
            } else {
                $baseQuery->whereIn("{$laporanTable}.ID_BAGIAN", $allowedUnitIds);
            }

        } else if ($role === 'super-admin' && !empty($userBagian)) {
            $allMyUnitIds = $this->getAllDescendantIds($userBagian);
            $allMyUnitIds[] = $userBagian;
            $baseQuery->whereIn("{$laporanTable}.ID_BAGIAN", $allMyUnitIds);
        }
        $this->applyTimeFilter($baseQuery, $timeFilter);

        if (!empty($unitKerjaId)) {
            $this->applyUnitKerjaFilter($baseQuery, $unitKerjaId);
        }

        $definedLabels = [
            'grading'               => ['Hijau', 'Kuning', 'Merah'],
            'sumberMedia'           => JenisMedia::where('STATUS', '1')->pluck('JENIS_MEDIA')->toArray(),
            'statusPengaduan'       => ['Open', 'On Progress', 'Menunggu Konfirmasi', 'Banding', 'Close'],
            'jenisLaporan'          => JenisLaporan::where('STATUS', '1')->pluck('JENIS_LAPORAN')->toArray(),
            'klasifikasiPengaduan'  => KlasifikasiPengaduan::where('STATUS', '1')->pluck('KLASIFIKASI_PENGADUAN')->toArray(),
            'penyelesaianPengaduan' => PenyelesaianPengaduan::where('STATUS', '1')->pluck('PENYELESAIAN_PENGADUAN')->toArray(),
        ];

        if (!empty($unitKerjaId)) {
            $unit = UnitKerja::find($unitKerjaId);
            $definedLabels['unitKerja'] = $unit ? [$unit->NAMA_BAGIAN] : [];
        } else {
            $definedLabels['unitKerja'] = [];
        }

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
            if ($key === 'unitKerja' && empty($unitKerjaId)) {
                $aggregateQuery = Laporan::query();
                if ($role === 'direksi' && !empty($userBagian)) {
                    $allMyUnitIds = $this->getAllDescendantIds($userBagian);
                    $allMyUnitIds[] = $userBagian;
                    $aggregateQuery->whereIn('ID_BAGIAN', $allMyUnitIds);
                }
                $this->applyTimeFilter($aggregateQuery, $timeFilter);
                $data = $this->getAggregatedUnitKerjaData($aggregateQuery);
            } else {
                $data = $this->generateChartDataWithDefinedLabels(clone $baseQuery, $config['type'], $config['name'], $definedLabels[$key] ?? [], $config['column'] ?? null);
            }

            $chartData[$key] = array_merge($baseConfigs[$key], $data);
        }


        return response()->json($chartData);
    }
}
