<?php

namespace App\Services\Humas\Export;

use App\Services\Ticketing\Models\Laporan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID Pengaduan',
            'Judul',
            'Tanggal Masuk',
            'Media',
            'Status',
            'Unit Kerja Terkait',
            'Grading',
            'Keluhan'
        ];
    }

    public function map($laporan): array
    {
        $unitKerjaList = $laporan->unit_kerja_list->isNotEmpty()
            ? $laporan->unit_kerja_list->pluck('NAMA_BAGIAN')->implode(', ')
            : 'Belum dipilih unit kerja';

        return [
            $laporan->ID_COMPLAINT,
            $laporan->JUDUL_COMPLAINT ?? 'Belum ada judul',
            Carbon::parse($laporan->TGL_COMPLAINT)->format('d F Y H:i'),
            $laporan->jenisMedia->JENIS_MEDIA,
            $laporan->STATUS,
            $unitKerjaList,
            $laporan->GRANDING ?? 'Belum Dinilai',
            $laporan->ISI_COMPLAINT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:H' . ($event->sheet->getHighestRow());

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF00B9AD'],
                    ],
                ]);
            },
        ];
    }
}
