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
    private function getChartDataByField(string $fieldName): array
    {
        $data = Laporan::select($fieldName . ' as label', DB::raw('count(*) as total'))
            ->whereNotNull($fieldName)
            ->where($fieldName, '!=', '')
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'labels' => $data->pluck('label')->toArray(),
            'data'   => $data->pluck('total')->toArray(),
        ];
    }

    private function getChartDataByRelation(string $relationName, string $relationColumn): array
    {
        $laporanModel = new Laporan();
        $relation = $laporanModel->{$relationName}();
        $relatedTable = $relation->getRelated()->getTable();
        $foreignKey = $relation->getQualifiedForeignKeyName();
        $ownerKey = $relation->getQualifiedOwnerKeyName();

        $data = Laporan::join($relatedTable, $foreignKey, '=', $ownerKey)
            ->selectRaw("{$relatedTable}.{$relationColumn} as label, count(data_complaint.ID_COMPLAINT) as total")
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'labels' => $data->pluck('label')->toArray(),
            'data'   => $data->pluck('total')->toArray(),
        ];
    }

    // GANTI LAGI FUNGSI INI DENGAN KODE DEBUGGING YANG BARU
    private function generateChartDataWithDefinedLabels(Builder $baseQuery, string $type, string $name, array $definedLabels, ?string $relationColumn = null): array
    {
        $queryData = null;
        $queryBuilder = null;

        if ($type === 'field') {
            $queryBuilder = (clone $baseQuery)->select($name . ' as label', DB::raw('count(*) as total'))
                ->whereIn($name, $definedLabels)
                ->groupBy('label');

            // --- DEBUG BARU (SEBELUM .get()) ---
            // Kita debug chart pertama (grading) untuk melihat query dasarnya
            // if ($name === 'GRANDING') {
            //     dd($queryBuilder->toSql(), $queryBuilder->getBindings());
            // }
            // ------------------------------------

            $queryData = $queryBuilder->get()->pluck('total', 'label');

        } else { // type 'relation'
            $laporanModel = new Laporan();
            if (!method_exists($laporanModel, $name)) return ['labels' => $definedLabels, 'data' => array_fill(0, count($definedLabels), 0)];

            $relation = $laporanModel->{$name}();
            $relatedTable = $relation->getRelated()->getTable();
            $foreignKey = $relation->getQualifiedForeignKeyName();
            $ownerKey = $relation->getQualifiedOwnerKeyName();

            $queryBuilder = (clone $baseQuery)->join($relatedTable, $foreignKey, '=', $ownerKey)
                ->selectRaw("{$relatedTable}.{$relationColumn} as label, count(data_complaint.ID_COMPLAINT) as total")
                ->whereIn("{$relatedTable}.{$relationColumn}", $definedLabels)
                ->groupBy('label');

            // --- DEBUG BARU (SEBELUM .get()) ---
            // Kita juga debug di sini. Jika 'grading' lolos, mungkin chart relasi ini yang error.
            // Ganti 'sumberMedia' dengan nama relasi lain jika perlu.
            // if ($name === 'sumberMedia') {
            //      dd($queryBuilder->toSql(), $queryBuilder->getBindings());
            // }
            // ------------------------------------

            $queryData = $queryBuilder->get()->pluck('total', 'label');
        }

        $data = [];
        foreach ($definedLabels as $label) {
            $data[] = $queryData[$label] ?? 0;
        }

        return ['labels' => $definedLabels, 'data' => $data];
    }

    public function getDashboard(){
        $unitKerjaList = UnitKerja::where('STATUS', '1')->orderBy('NAMA_BAGIAN')->get();
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

    // GANTI FUNGSI LAMA ANDA DENGAN YANG BARU INI
    private function applyUnitKerjaFilter(Builder $query, ?string $unitKerjaId, ?string $subUnitId)
    {
        // Dapatkan nama tabel dari model Laporan secara dinamis
        // Ini untuk menghindari hard-coding nama tabel 'data_complaint'
        $laporanTable = (new Laporan)->getTable();

        // Prioritaskan filter Sub Unit jika ada
        if (!empty($subUnitId) && is_numeric($subUnitId)) {
            // Gunakan nama tabel untuk membuat kolom tidak ambigu
            $query->where("{$laporanTable}.ID_BAGIAN", $subUnitId);
        }
        // Jika tidak, gunakan filter Unit Kerja utama
        elseif (!empty($unitKerjaId) && is_numeric($unitKerjaId)) {
            // 1. Ambil semua ID sub-unit yang berada di bawah unit kerja yang dipilih
            $subUnitIds = UnitKerja::where('ID_PARENT_BAGIAN', $unitKerjaId)
                                      ->pluck('ID_BAGIAN');

            // 2. Jika tidak ada sub-unit, maka filter berdasarkan unit kerja itu sendiri
            if ($subUnitIds->isEmpty()) {
                $query->where("{$laporanTable}.ID_BAGIAN", $unitKerjaId);
            } else {
                // 3. Jika ada sub-unit, filter berdasarkan SEMUA ID sub-unit tersebut
                $query->whereIn("{$laporanTable}.ID_BAGIAN", $subUnitIds);
            }
        }
        // Jika keduanya kosong, tidak ada filter unit kerja yang diterapkan
    }

    public function getFilteredChartData(Request $request)
    {
        $unitKerjaId = $request->input('unit_kerja_id');
        $subUnitId = $request->input('sub_unit_id');

        // --- LOGIKA LABEL DINAMIS YANG DIPERBAIKI ---
        $definedLabels = [
            'grading'               => ['Hijau', 'Kuning', 'Merah'],
            'sumberMedia'           => JenisMedia::where('STATUS', '1')->pluck('JENIS_MEDIA')->toArray(),
            'statusPengaduan'       => ['Open', 'On Progress', 'Menunggu Konfirmasi', 'Banding', 'Close'],
            'jenisLaporan'          => JenisLaporan::where('STATUS', '1')->pluck('JENIS_LAPORAN')->toArray(),
            'klasifikasiPengaduan'  => KlasifikasiPengaduan::where('STATUS', '1')->pluck('KLASIFIKASI_PENGADUAN')->toArray(),
            'penyelesaianPengaduan' => PenyelesaianPengaduan::where('STATUS', '1')->pluck('PENYELESAIAN_PENGADUAN')->toArray(),
        ];

        $queryForUnitKerjaLabels = UnitKerja::where('STATUS', '1');
        if ($unitKerjaId && !$subUnitId) {
            $queryForUnitKerjaLabels->where('ID_PARENT_BAGIAN', $unitKerjaId);
        } else {
            $queryForUnitKerjaLabels->whereNull('ID_PARENT_BAGIAN');
        }
        $definedLabels['unitKerja'] = $queryForUnitKerjaLabels->pluck('NAMA_BAGIAN')->toArray();

        if ($unitKerjaId && empty($definedLabels['unitKerja'])) {
            $definedLabels['unitKerja'] = [];
        }
        // --- AKHIR LOGIKA LABEL DINAMIS ---

        // Buat query dasar
        $baseQuery = Laporan::query();
        $this->applyTimeFilter($baseQuery, $request->input('time_filter'));
        // Terapkan filter unit kerja ke SEMUA chart
        $this->applyUnitKerjaFilter($baseQuery, $unitKerjaId, $subUnitId);

// Definisikan metadata statis untuk setiap chart
        $baseConfigs = [
            'grading' => [
                'title' => 'Grading (Hijau, Kuning, Merah)',
                'subtitle' => 'Distribusi pengaduan berdasarkan tingkat waktu penanganan komplain',
                'type' => 'bar',
                'backgroundColor' => ['#347433', '#FFD600', '#D50000'] ],
            'sumberMedia' => [
                'title' => 'Sumber Media',
                'subtitle' => 'Distribusi pengaduan berdasarkan sumber media pelaporan',
                'type' => 'bar',
                'backgroundColor' => '#e65100'
            ],
            'statusPengaduan' => [
                'title' => 'Status Pengaduan',
                'subtitle' => 'Distribusi pengaduan berdasarkan status penanganan',
                'type' => 'pie',
                'backgroundColor' => ['#28a745', '#ffc107', '#17a2b8', '#6f42c1', '#dc3545']
            ],
            'unitKerja' => [
                'title' => 'Unit Kerja',
                'subtitle' => 'Distribusi pengaduan berdasarkan unit kerja tujuan',
                'type' => 'bar',
                'backgroundColor' => '#E0440E'
            ],
            'jenisLaporan' => [
                'title' => 'Jenis Laporan',
                'subtitle' => 'Distribusi pengaduan berdasarkan jenis laporan',
                'type' => 'pie',
                'backgroundColor' => ['#2962FF', '#D84315', '#FF9800', '#2E7D32']
            ],
            'klasifikasiPengaduan' => [
                'title' => 'Klasifikasi Pengaduan',
                'subtitle' => 'Distribusi pengaduan berdasarkan klasifikasi pengaduan',
                'type' => 'pie',
                'backgroundColor' => ['#2962FF', '#D84315', '#FF9800']
            ],
            'penyelesaianPengaduan' => [
                'title' => 'Penyelesaian Pengaduan',
                'subtitle' => 'Distribusi pengaduan berdasarkan status penyelesaian',
                'type' => 'bar',
                'backgroundColor' => '#6f42c1'
            ]
        ];

        // Buat peta konfigurasi yang jelas untuk setiap chart
        $chartMap = [
            'grading'               => ['type' => 'field', 'name' => 'GRANDING'],
            'sumberMedia'           => ['type' => 'relation', 'name' => 'jenisMedia', 'column' => 'JENIS_MEDIA'],
            'statusPengaduan'       => ['type' => 'field', 'name' => 'STATUS'],
            'unitKerja'             => ['type' => 'relation', 'name' => 'unitKerja', 'column' => 'NAMA_BAGIAN'],
            'jenisLaporan'          => ['type' => 'relation', 'name' => 'jenisLaporan', 'column' => 'JENIS_LAPORAN'],
            'klasifikasiPengaduan'  => ['type' => 'relation', 'name' => 'klasifikasiPengaduan', 'column' => 'KLASIFIKASI_PENGADUAN'],
            'penyelesaianPengaduan' => ['type' => 'relation', 'name' => 'penyelesaianPengaduan', 'column' => 'PENYELESAIAN_PENGADUAN'],
        ];

        // Buat query dasar dan terapkan filter waktu
        $chartData = [];
        foreach ($chartMap as $key => $config) {
            // dd('Checkpoint 4: Mencoba memproses chart dengan key: ' . $key);
            $data = $this->generateChartDataWithDefinedLabels(
                clone $baseQuery,
                $config['type'],
                $config['name'],
                $definedLabels[$key],
                $config['column'] ?? null
            );
            $chartData[$key] = array_merge($baseConfigs[$key], $data);
        }

        return response()->json($chartData);
    }


}
