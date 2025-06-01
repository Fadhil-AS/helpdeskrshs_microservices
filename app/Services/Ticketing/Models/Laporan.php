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
    // const CREATED_AT = 'TGL_INSROW';
    // const UPDATED_AT = 'TGL_INSROW';

    protected $fillable = [
        'ID_COMPLAINT',
        'ID_COMPLAINT_REFERENSI',
        'ID_BAGIAN',
        'ID_KLASIFIKASI',
        'ID_JENIS_MEDIA',
        'ID_PENYELESAIAN',
        'ID_JENIS_LAPORAN',
        'TGL_COMPLAINT',
        'NAME',
        'NO_TLPN',
        'TGL_INSROW',
        'STATUS',
        'EVALUASI_COMPLAINT',
        'JUDUL_COMPLAINT',
        'TGL_EVALUASI',
        'GRANDING',
        'PETUGAS_PELAPOR',
        'KETERANGAN',
        'NO_MEDREC',
        'PENANGGUNG_JAWAB',
        'TGL_SELESAI',
        'DATA_PENGADUAN',
        'SMS_DIREKSI',
        'FILE_PENGADUAN',
        'TINDAK_LANJUT_HUMAS',
        'DISPOSISI',
        'INFO_DIREKSI',
        'PERMASALAHAN',
        'KD_PENGADUAN',
        'RATING_LAPORAN',
        'FEEDBACK_PELAPOR',
    ];

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
}
