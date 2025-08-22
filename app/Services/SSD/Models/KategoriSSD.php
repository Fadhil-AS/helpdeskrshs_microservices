<?php

namespace App\Services\SSD\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSSD extends Model
{
    use HasFactory;

    protected $connection = 'ssd';
    protected $table = 'kategori_ssd';
    protected $primaryKey = 'ID_KATEGORI_SSD';

    protected $fillable = [
        'NAMA_KATEGORI',
        'DESKRIPSI',
    ];

    public function ssd()
    {
        return $this->hasMany(SSD::class, 'ID_KATEGORI_SSD', 'ID_KATEGORI_SSD');
    }
}
