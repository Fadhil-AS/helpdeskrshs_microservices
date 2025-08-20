<?php

namespace App\Services\SSD\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSD extends Model
{
    use HasFactory;

    protected $connection = 'ssd';
    protected $table = 'ssd';
    protected $primaryKey = 'ID_SSD';

    protected $fillable = [
        'ID_KATEGORI_SSD',
        'PERTANYAAN_SSD',
        'JAWABAN_SSD',
        'STATUS',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSSD::class, 'ID_KATEGORI_SSD', 'ID_KATEGORI_SSD');
    }
}
