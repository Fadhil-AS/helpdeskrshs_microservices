<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;

class JenisMedia extends Model {
    protected $table = 'jenis_media';
    protected $primaryKey = 'ID_JENIS_MEDIA';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_JENIS_MEDIA',
        'JENIS_MEDIA',
        'STATUS',
    ];
}
