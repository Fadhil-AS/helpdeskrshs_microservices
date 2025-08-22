<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use App\Services\Ticketing\Models\JenisLaporan;
use Illuminate\Support\Facades\DB;

class Laporan extends Model {
    protected $table = 'data_complaint';
    protected $primaryKey = 'ID_COMPLAINT';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'TGL_INSROW';
    const UPDATED_AT = 'TGL_INSROW';
    protected $appends = [
        'pengaduan_files',
        'klarifikasi_files',
        'tindak_lanjut_files',
        'unit_kerja_list',
        'status_klarifikasi',
        'klarifikasi_list'
    ];

    protected $fillable = [
        'ID_COMPLAINT',
        'ID_COMPLAINT_REFERENSI',
        'ID_BAGIAN',
        'ID_BAGIAN_LAINNYA',
        'ID_KLASIFIKASI',
        'ID_JENIS_MEDIA',
        'ID_PENYELESAIAN',
        'ID_JENIS_LAPORAN',
        'TGL_COMPLAINT',
        'JENIS_PELAPOR',
        'NAME',
        'NO_TLPN',
        'ISI_COMPLAINT',
        'TGL_INSROW',
        'STATUS',
        'EVALUASI_COMPLAINT',
        'JUDUL_COMPLAINT',
        'PETUGAS_EVALUASI',
        'TGL_PENUGASAN',
        'TGL_EVALUASI',
        'TGL_TINDAK_LANJUT_HUMAS',
        'GRANDING',
        'PETUGAS_PELAPOR',
        'NO_MEDREC',
        'TGL_SELESAI',
        'SMS_DIREKSI',
        'FILE_PENGADUAN',
        'FILE_BUKTI_KLARIFIKASI',
        'FILE_TINDAK_LANJUT_HUMAS',
        'TINDAK_LANJUT_HUMAS',
        'DISPOSISI',
        'PERMASALAHAN',
        'RATING_LAPORAN',
        'FEEDBACK_PELAPOR',
    ];

    protected $casts = [
        'FILE_PENGADUAN'           => 'array',
        'FILE_BUKTI_KLARIFIKASI'   => 'array',
        'FILE_TINDAK_LANJUT_HUMAS' => 'array',
        'ID_BAGIAN_LAINNYA'        => 'array',
        'EVALUASI_COMPLAINT'       => 'array',
    ];

    public function getPengaduanFilesAttribute(): array
    {
        $value = $this->attributes['FILE_PENGADUAN'] ?? null;

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && !empty($value)) {
            return [$value];
        }

        return [];
    }

    public function getKlarifikasiFilesAttribute(): array
    {
        $value = $this->attributes['FILE_BUKTI_KLARIFIKASI'] ?? null;
        $klarifikasiData = [];

        if (is_array($value)) {
            $klarifikasiData = $value;
        } elseif (is_string($value)) {
            $klarifikasiData = json_decode($value, true) ?: [];
        }

        $flattenedFiles = [];
        if (is_array($klarifikasiData)) {
            foreach ($klarifikasiData as $item) {
                if (!empty($item['files']) && is_array($item['files'])) {
                    $flattenedFiles = array_merge($flattenedFiles, $item['files']);
                }
            }
        }
        return $flattenedFiles;

    }

    public function getTindakLanjutFilesAttribute(): array
    {
        $value = $this->attributes['FILE_TINDAK_LANJUT_HUMAS'] ?? null;

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public function previous()
    {
        return $this->belongsTo(self::class, 'ID_COMPLAINT_REFERENSI', 'ID_COMPLAINT');
    }

    public function followUps()
    {
        return $this->hasMany(self::class, 'ID_COMPLAINT_REFERENSI', 'ID_COMPLAINT');
    }

    public function unitKerja()
    {
        $idBagianData = $this->attributes['ID_BAGIAN'] ?? null;
        if (!$idBagianData) {
            return $this->belongsTo(UnitKerja::class, 'ID_BAGIAN', 'ID_BAGIAN')->whereRaw('1 = 0');
        }

        $ids = explode(',', $idBagianData);
        $firstId = trim($ids[0]);
        return $this->belongsTo(UnitKerja::class, 'ID_BAGIAN', 'ID_BAGIAN')->where('ID_BAGIAN', '=', $firstId);
    }

    public function klasifikasiPengaduan()
    {
        return $this->belongsTo(KlasifikasiPengaduan::class, 'ID_KLASIFIKASI', 'ID_KLASIFIKASI');
    }

    public function jenisMedia()
    {
        return $this->belongsTo(JenisMedia::class, 'ID_JENIS_MEDIA', 'ID_JENIS_MEDIA');
    }

    public function penyelesaianPengaduan()
    {
        return $this->belongsTo(PenyelesaianPengaduan::class, 'ID_PENYELESAIAN', 'ID_PENYELESAIAN');
    }

    public function jenisLaporan()
    {
        return $this->belongsTo(JenisLaporan::class, 'ID_JENIS_LAPORAN', 'ID_JENIS_LAPORAN');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('ID_COMPLAINT', 'like', '%' . $search . '%')
                  ->orWhere('JUDUL_COMPLAINT', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->where('STATUS', $status);
        });

        $query->when($filters['unit_kerja'] ?? false, function ($query, $unitKerjaId) {
            return $query->where(function ($q) use ($unitKerjaId) {
                $q->where('ID_BAGIAN', $unitKerjaId)
                  ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $unitKerjaId);
            });
        });

        $query->when($filters['periode'] ?? false, function ($query, $periode) use ($filters) {
            $tahun = $filters['tahun'] ?? date('Y');

            switch ($periode) {
                case 'bulan':
                    $bulan = $filters['bulan'] ?? date('m');
                    $query->whereYear('TGL_COMPLAINT', $tahun)->whereMonth('TGL_COMPLAINT', $bulan);
                    break;
                case 'triwulan':
                    $triwulan = $filters['triwulan'] ?? 1;
                    $startMonth = ($triwulan - 1) * 3 + 1;
                    $endMonth = $startMonth + 2;
                    $query->whereYear('TGL_COMPLAINT', $tahun)
                          ->whereBetween(DB::raw('MONTH(TGL_COMPLAINT)'), [$startMonth, $endMonth]);
                    break;
                case 'semester':
                    $semester = $filters['semester'] ?? 1;
                    $startMonth = ($semester - 1) * 6 + 1;
                    $endMonth = $startMonth + 5;
                    $query->whereYear('TGL_COMPLAINT', $tahun)
                          ->whereBetween(DB::raw('MONTH(TGL_COMPLAINT)'), [$startMonth, $endMonth]);
                    break;
            }
        });
    }

    private function processFiles(string $fileData = null): array
    {
        if (empty($fileData)) {
            return [];
        }

        $decoded = json_decode($fileData, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (str_contains($fileData, ';')) {
            return explode(';', $fileData);
        }

        return [$fileData];
    }

    public function getUnitKerjaListAttribute()
    {
        $firstId = $this->attributes['ID_BAGIAN'] ?? null;
        $otherIdsJson = $this->attributes['ID_BAGIAN_LAINNYA'] ?? [];

        $otherIds = $otherIdsJson ? json_decode($otherIdsJson, true) : [];
        if (!is_array($otherIds)) {
            $otherIds = [];
        }

        $allIds = array_merge([$firstId], $otherIds);
        $validIds = array_unique(array_filter($allIds));

        if (empty($validIds)) {
            return collect();
        }

        return UnitKerja::whereIn('ID_BAGIAN', $validIds)->get();
    }

    public function getKlarifikasiListAttribute()
    {
        $evaluasi = $this->attributes['EVALUASI_COMPLAINT'] ?? '[]';
        return json_decode($evaluasi, true) ?: [];
    }

    public function getStatusKlarifikasiAttribute()
    {
        $unitKerjaTujuan = $this->unit_kerja_list;
        $klarifikasiTersimpan = $this->klarifikasi_list;

        if ($unitKerjaTujuan->isEmpty()) {
            return '-';
        }

        $jumlahUnitKerja = $unitKerjaTujuan->count();
        $jumlahKlarifikasi = count($klarifikasiTersimpan);

        return ($jumlahKlarifikasi >= $jumlahUnitKerja) ? 'Sudah' : 'Belum';
    }
}
