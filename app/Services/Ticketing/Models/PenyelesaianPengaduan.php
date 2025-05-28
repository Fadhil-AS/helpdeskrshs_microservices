<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;

class PenyelesaianPengaduan extends Model {
    protected $table = 'penyelesaian_pengaduan';
    protected $primaryKey = 'ID_PENYELESAIAN';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_PENYELESAIAN',
        'PENYELESAIAN_PENGADUAN',
        'STATUS',
    ];
}
