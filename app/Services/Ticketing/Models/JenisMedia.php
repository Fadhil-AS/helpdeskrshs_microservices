<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\JenisMediaFactory;

class JenisMedia extends Model {
    use HasFactory;

    protected $table = 'jenis_media';
    protected $primaryKey = 'ID_JENIS_MEDIA';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'ID_JENIS_MEDIA',
        'JENIS_MEDIA',
        'STATUS',
    ];

    protected static function newFactory(): JenisMediaFactory
    {
        return JenisMediaFactory::new();
    }
}
