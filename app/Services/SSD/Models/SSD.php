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
}
