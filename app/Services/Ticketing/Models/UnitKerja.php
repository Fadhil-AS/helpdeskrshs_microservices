<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model {
    protected $table = 'unit_kerja';
    protected $primaryKey = 'ID_BAGIAN';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_BAGIAN',
        'NAMA_BAGIAN',
        'NAMA_BAGIAN_SINGULAR',
        'NAMA_ALTERNATIF',
        'ID_PARENT_BAGIAN',
        'SUPER',
        'STATUS',
        'TGL_INSROW',
    ];
}
