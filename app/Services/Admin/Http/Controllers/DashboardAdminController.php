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
use Illuminate\Support\Collection;

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

    private function getScopedSuperZeroUnits(string $role, ?string $userBagian): Collection
    {
        $unitsQuery = UnitKerja::query();
        $unitsQuery->where('NAMA_BAGIAN', '!=', 'SATUAN PENGAWAS INTERNAL');

        if ($role === 'direksi' && !empty($userBagian)) {
            $unitsQuery->where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $userBagian)->where('SUPER', '0');
        } elseif ($role === 'unit_kerja' && !empty($userBagian)) {
            $unitsQuery->where('ID_BAGIAN', $userBagian);
        } elseif ($role === 'humas') {
            $unitsQuery->where('SUPER', '0');
        } else {
            $unitsQuery->where('STATUS', '1')->where(function ($query) {
                $query->where('ID_PARENT_BAGIAN', ' ')
                    ->orWhere('ID_PARENT_BAGIAN', 0)
                    ->orWhereNull('ID_PARENT_BAGIAN')
                    ->orWhere('ID_PARENT_BAGIAN', '1');
            });
        }

        return $unitsQuery->orderBy('NAMA_BAGIAN')->get();
    }

    public function getDashboard(Request $request)
    {
        $role = session('role');
        $userBagian = session('user')->ID_BAGIAN ?? null;

        $unitKerjaList = $this->getScopedSuperZeroUnits($role, $userBagian);
        $statusCounts = $this->getStatusCounts($request);

        return view('Services.Admin.Dashboard.mainAdmin', array_merge($statusCounts, [
            'unitKerjaList' => $unitKerjaList,
            'userRole'      => $role,
        ]));


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

    private function applyDireksiHierarchyFilter(Builder $query, string $direksiId): void
    {
        $laporanTable = (new Laporan)->getTable();
        $specialChildren = UnitKerja::where(DB::raw("TRIM(ID_PARENT_BAGIAN)"), $direksiId)
                                    ->where('SUPER', '0')
                                    ->get();

        $allowedUnitIds = [];
        foreach ($specialChildren as $child) {
            $allowedUnitIds[] = $child->ID_BAGIAN;
            $descendants = $this->getAllDescendantIds($child->ID_BAGIAN);
            $allowedUnitIds = array_merge($allowedUnitIds, $descendants);
        }

        if (empty($allowedUnitIds)) {
            $query->whereRaw('1 = 0');
        } else {
            $query->whereIn("{$laporanTable}.ID_BAGIAN", $allowedUnitIds);
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
        $unitsToDisplay = $this->getScopedSuperZeroUnits($role, $userBagian);

        $chartLabels = [];
        $chartData = [];

        foreach ($unitsToDisplay as $unit) {
            $chartLabels[] = $unit->NAMA_BAGIAN;

            $idsToCount = [$unit->ID_BAGIAN];

            if ($role !== 'unit_kerja') {
                $descendants = $this->getAllDescendantIds($unit->ID_BAGIAN);
                $idsToCount = array_merge($idsToCount, $descendants);
            }
            $idsToCount = array_unique($idsToCount);

            $count = (clone $baseQuery)->where(function ($query) use ($idsToCount) {
                $query->whereIn('ID_BAGIAN', $idsToCount);
                foreach ($idsToCount as $id) {
                    $query->orWhereJsonContains('ID_BAGIAN_LAINNYA', $id);
                }
            })->count();

            $chartData[] = $count;
        }

        return [
            'labels' => $chartLabels,
            'data'   => $chartData,
        ];
    }



    private function getAggregatedDataForOwnUnit(Builder $baseQuery, string $unitId): array
    {
        $ownUnit = UnitKerja::find($unitId);
        if (!$ownUnit) {
            return ['labels' => [], 'data' => []];
        }

        $chartLabels = [$ownUnit->NAMA_BAGIAN];

        $count = (clone $baseQuery)->where(function ($query) use ($unitId) {
            $query->where('ID_BAGIAN', $unitId)
                  ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $unitId);
        })->count();

        $chartData = [$count];

        return [
            'labels' => $chartLabels,
            'data'   => $chartData,
        ];
    }

    public function getFilteredChartData(Request $request)
    {
        $unitKerjaId = $request->input('unit_kerja_id');
        $timeFilter = $request->input('time_filter');

        $baseQuery = $this->getBaseQueryForUser();

        $this->applyTimeFilter($baseQuery, $timeFilter);
        if (!empty($unitKerjaId)) {
            $baseQuery->where(function ($q) use ($unitKerjaId) {
                $q->where('ID_BAGIAN', $unitKerjaId)
                  ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $unitKerjaId);
            });
        }

        $relevantMediaIds = (clone $baseQuery)->pluck('ID_JENIS_MEDIA')->unique()->toArray();
        $sumberMediaLabels = JenisMedia::whereIn('ID_JENIS_MEDIA', $relevantMediaIds)
                                         ->where('STATUS', '1')
                                         ->pluck('JENIS_MEDIA')
                                         ->toArray();

        $relevantKlasifikasiIds = (clone $baseQuery)->pluck('ID_KLASIFIKASI')->unique()->toArray();
        $klasifikasiLabels = KlasifikasiPengaduan::whereIn('ID_KLASIFIKASI', $relevantKlasifikasiIds)
                                                   ->where('STATUS', '1')
                                                   ->pluck('KLASIFIKASI_PENGADUAN')
                                                   ->toArray();

        $definedLabels = [
            'grading'               => ['Hijau', 'Kuning', 'Merah'],
            'sumberMedia'           => $sumberMediaLabels,
            'statusPengaduan'       => ['Open', 'On Progress', 'Menunggu Konfirmasi', 'Close', 'Banding'],
            'jenisLaporan'          => JenisLaporan::where('STATUS', '1')->pluck('JENIS_LAPORAN')->toArray(),
            'klasifikasiPengaduan'  => $klasifikasiLabels,
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
            'statusPengaduan' => [ 'title' => 'Status Pengaduan', 'subtitle' => 'Distribusi pengaduan berdasarkan status penanganan', 'type' => 'pie', 'backgroundColor' => ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1'] ],
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
            if ($key === 'unitKerja') {
                if (empty($unitKerjaId)) {
                    $data = $this->getAggregatedUnitKerjaData(clone $baseQuery);
                } else {
                    $count = (clone $baseQuery)->count();
                    $unit = UnitKerja::find($unitKerjaId);
                    $labels = $unit ? [$unit->NAMA_BAGIAN] : [];
                    $data = ['labels' => $labels, 'data' => [$count]];
                }
            } else {
                $data = $this->generateChartDataWithDefinedLabels(clone $baseQuery, $config['type'], $config['name'], $definedLabels[$key] ?? [], $config['column'] ?? null);
            }
            $chartData[$key] = array_merge($baseConfigs[$key], $data);
        }

        return response()->json($chartData);
    }

    private function getStatusCounts(Request $request)
    {
        $baseQuery = $this->getBaseQueryForUser();

        $this->applyTimeFilter($baseQuery, $request->input('time_filter'));

        $statusCounts = (clone $baseQuery)
            ->select('STATUS', DB::raw('count(*) as total'))
            ->groupBy('STATUS')
            ->pluck('total', 'STATUS');

        $allOngoingReports = (clone $baseQuery)->whereIn('STATUS', ['Open', 'On Progress', 'Menunggu Konfirmasi', 'Close', 'Banding'])->get();

        $countKlarifikasiSudah = 0;
        $countKlarifikasiBelum = 0;

        foreach ($allOngoingReports as $laporan) {
            if ($laporan->status_klarifikasi === 'Sudah' && $laporan->STATUS === 'On Progress') {
                $countKlarifikasiSudah++;
            } elseif ($laporan->status_klarifikasi === 'Belum' && $laporan->STATUS === 'On Progress') {
                $countKlarifikasiBelum++;
            }
        }

        return [
            'countOpen' => $statusCounts['Open'] ?? 0,
            'countMenunggu' => $statusCounts['Menunggu Konfirmasi'] ?? 0,
            'countClose' => $statusCounts['Close'] ?? 0,
            'countBanding' => $statusCounts['Banding'] ?? 0,
            'countKlarifikasiSudah' => $countKlarifikasiSudah,
            'countKlarifikasiBelum' => $countKlarifikasiBelum,
        ];
    }

    private function getBaseQueryForUser(): Builder
    {
        $role = session('role');
        $userBagian = optional(session('user'))->ID_BAGIAN;

        $baseQuery = Laporan::query();
        $laporanTable = (new Laporan)->getTable();

        $allowedUnitIds = [];
        if (in_array($role, ['direksi', 'unit_kerja', 'humas'])) {
            $scopedUnits = $this->getScopedSuperZeroUnits($role, $userBagian);

            foreach ($scopedUnits as $unit) {
                $allowedUnitIds[] = $unit->ID_BAGIAN;
                if ($role !== 'unit_kerja') {
                    $descendants = $this->getAllDescendantIds($unit->ID_BAGIAN);
                    $allowedUnitIds = array_merge($allowedUnitIds, $descendants);
                }
            }
        }
        if ($role === 'humas') {
            $baseQuery->where(function ($query) use ($allowedUnitIds, $laporanTable) {
                $query->whereNull("{$laporanTable}.ID_BAGIAN")
                      ->orWhere(function ($subQuery) use ($allowedUnitIds, $laporanTable) {
                          if (!empty($allowedUnitIds)) {
                              $subQuery->whereIn("{$laporanTable}.ID_BAGIAN", array_unique($allowedUnitIds));
                              foreach (array_unique($allowedUnitIds) as $unitId) {
                                  $subQuery->orWhereJsonContains("{$laporanTable}.ID_BAGIAN_LAINNYA", $unitId);
                              }
                          }
                      });
            });
        } elseif (!empty($allowedUnitIds)) {
            $baseQuery->where(function ($query) use ($allowedUnitIds, $laporanTable) {
                $query->whereIn("{$laporanTable}.ID_BAGIAN", array_unique($allowedUnitIds));
                foreach (array_unique($allowedUnitIds) as $unitId) {
                    $query->orWhereJsonContains("{$laporanTable}.ID_BAGIAN_LAINNYA", $unitId);
                }
            });
        } elseif (in_array($role, ['direksi', 'unit_kerja'])) {
            $baseQuery->whereRaw('1 = 0');
        }

        if ($role === 'humas') {
            $baseQuery->whereHas('klasifikasiPengaduan', function ($q) {
                $q->where('KLASIFIKASI_PENGADUAN', 'Etik');
            });
        }

        return $baseQuery;
    }

}
