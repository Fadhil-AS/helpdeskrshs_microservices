<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\KlasifikasiPengaduanFactory;

class KlasifikasiPengaduan extends Model {
    use HasFactory;

    protected $table = 'klasifikasi_pengaduan';
    protected $primaryKey = 'ID_KLASIFIKASI';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_KLASIFIKASI',
        'KLASIFIKASI_PENGADUAN',
        'STATUS',
    ];

    protected static function newFactory(): KlasifikasiPengaduanFactory
    {
        return KlasifikasiPengaduanFactory::new();
    }
}
