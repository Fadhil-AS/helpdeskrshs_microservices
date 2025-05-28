<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLaporan extends Model {
    protected $table = 'jenis_laporan';
    protected $primaryKey = 'ID_JENIS_LAPORAN';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_JENIS_LAPORAN',
        'JENIS_LAPORAN',
        'STATUS',
    ];
}
