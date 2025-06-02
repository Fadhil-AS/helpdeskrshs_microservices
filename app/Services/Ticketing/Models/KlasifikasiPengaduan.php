<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiPengaduan extends Model {
    protected $table = 'klasifikasi_pengaduan';
    protected $primaryKey = 'ID_KLASIFIKASI';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_KLASIFIKASI',
        'KLASIFIKASI_PENGADUAN',
        'STATUS',
    ];
}
