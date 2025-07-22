<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use App\Services\Ticketing\Models\JenisLaporan;

class Laporan extends Model {
    protected $table = 'data_complaint';
    protected $primaryKey = 'ID_COMPLAINT';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'TGL_INSROW';
    const UPDATED_AT = 'TGL_INSROW';
    protected $appends = ['file_list'];

    protected $fillable = [
        'ID_COMPLAINT',
        'ID_COMPLAINT_REFERENSI',
        'ID_BAGIAN',
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
        'GRANDING',
        'PETUGAS_PELAPOR',
        'NO_MEDREC',
        // 'PENANGGUNG_JAWAB',
        'TGL_SELESAI',
        // 'DATA_PENGADUAN',
        'SMS_DIREKSI',
        'FILE_PENGADUAN',
        'FILE_BUKTI_KLARIFIKASI',
        'FILE_TINDAK_LANJUT_HUMAS',
        'TINDAK_LANJUT_HUMAS',
        'DISPOSISI',
        // 'INFO_DIREKSI',
        'PERMASALAHAN',
        // 'KD_PENGADUAN',
        'RATING_LAPORAN',
        'FEEDBACK_PELAPOR',
    ];

    public function getFileListAttribute()
    {
        $files = $this->attributes['FILE_PENGADUAN'];

        if ($files) {
            return explode(';', $files);
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
        return $this->belongsTo(UnitKerja::class, 'ID_BAGIAN', 'ID_BAGIAN');
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
                  ->orWhere('JUDUL_COMPLAINT', 'like', '%' . $search . '%')
                  ->orWhereHas('jenisMedia', function ($subQuery) use ($search) {
                    $subQuery->where('JENIS_MEDIA', 'like', '%' . $search . '%');
                });
            });
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->where('STATUS', $status);
        });
    }
}
