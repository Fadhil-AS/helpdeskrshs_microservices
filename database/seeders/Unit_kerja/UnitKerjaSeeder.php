<?php

namespace Database\Seeders\Unit_kerja;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataUnitKerja = [
            [
                'ID_BAGIAN' => 'A',
                'NAMA_BAGIAN' => 'DIREKTUR UTAMA',
                'NAMA_BAGIAN_SINGULAR' => 'DIREKTUR UTAMA',
                'NAMA_ALTERNATIF' => 'DIRUT',
                'ID_PARENT_BAGIAN' => '1',
                'SUPER' => 1,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'A01',
                'NAMA_BAGIAN' => 'KOMITE MEDIK',
                'NAMA_BAGIAN_SINGULAR' => 'KOMITE MEDIK',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'A02',
                'NAMA_BAGIAN' => 'KOMITE ETIK DAN HUKUM',
                'NAMA_BAGIAN_SINGULAR' => 'KOMITE ETIK DAN HUKUM',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'A03',
                'NAMA_BAGIAN' => 'KOMITE MUTU DAN KESELAMATAN PASIEN',
                'NAMA_BAGIAN_SINGULAR' => 'KOMITE MUTU DAN KESELAMATAN PASIEN',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'B',
                'NAMA_BAGIAN' => 'BIDANG MEDIK',
                'NAMA_BAGIAN_SINGULAR' => 'BIDANG MEDIK',
                'NAMA_ALTERNATIF' => 'DIRMED',
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 1,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'B01',
                'NAMA_BAGIAN' => 'BIDANG MEDIK',
                'NAMA_BAGIAN_SINGULAR' => 'BIDANG MEDIK',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'B',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'B02',
                'NAMA_BAGIAN' => 'BIDANG KEPERAWATAN',
                'NAMA_BAGIAN_SINGULAR' => 'BIDANG KEPERAWATAN',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'B',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'B03',
                'NAMA_BAGIAN' => 'INSTALASI RAWAT JALAN',
                'NAMA_BAGIAN_SINGULAR' => 'INSTALASI RAWAT JALAN',
                'NAMA_ALTERNATIF' => null,
                'ID_PARENT_BAGIAN' => 'B',
                'SUPER' => 0,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'C',
                'NAMA_BAGIAN' => 'DIREKTORAT SDM DAN PENDIDIKAN',
                'NAMA_BAGIAN_SINGULAR' => 'DIREKTORAT SDM DAN PENDIDIKAN',
                'NAMA_ALTERNATIF' => 'DIRSDM',
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 1,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'D',
                'NAMA_BAGIAN' => 'DIREKTORAT KEUANGAN',
                'NAMA_BAGIAN_SINGULAR' => 'DIREKTORAT KEUANGAN',
                'NAMA_ALTERNATIF' => 'DIRKEU',
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 1,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
            [
                'ID_BAGIAN' => 'E',
                'NAMA_BAGIAN' => 'DIREKTORAT UMUM DAN OPERASIONAL',
                'NAMA_BAGIAN_SINGULAR' => 'DIREKTORAT UMUM DAN OPERASIONAL',
                'NAMA_ALTERNATIF' => 'DIRUM',
                'ID_PARENT_BAGIAN' => 'A',
                'SUPER' => 1,
                'STATUS' => 1,
                'TGL_INSROW' => Carbon::createFromFormat('d/m/Y', '21/1/2016'),
            ],
        ];
        foreach ($dataUnitKerja as $data) {
            DB::connection('mysql')->table('UNIT_KERJA')->insert([
                'ID_BAGIAN' => $data['ID_BAGIAN'],
                'NAMA_BAGIAN' => $data['NAMA_BAGIAN'],
                'NAMA_BAGIAN_SINGULAR' => $data['NAMA_BAGIAN_SINGULAR'],
                'NAMA_ALTERNATIF' => $data['NAMA_ALTERNATIF'],
                'ID_PARENT_BAGIAN' => $data['ID_PARENT_BAGIAN'],
                'SUPER' => $data['SUPER'],
                'STATUS' => $data['STATUS'],
                'TGL_INSROW' => $data['TGL_INSROW'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
