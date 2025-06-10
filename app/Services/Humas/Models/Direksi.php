<?php

namespace App\Services\Humas\Models;

use Illuminate\Database\Eloquent\Model;

class Direksi extends Model {
    protected $table = 'complaint_direksi';
    protected $primaryKey = 'ID_DIREKSI';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'ID_DIREKSI',
        'NAMA',
        'NO_TLPN',
        'KET',
        'created_at',
        'updated_at'
    ];
}
