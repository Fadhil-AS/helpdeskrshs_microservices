<?php

namespace App\Services\Humas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Humas extends Authenticatable
{
    protected $table = 'humas';
    protected $primaryKey = 'ID_HUMAS';

    protected $fillable = [
        'USERNAME',
        'NIP',
        'NAMA',
        'no_tlpn_humas',
        'no_tlpn_rshs',
    ];

    protected $hidden = [
        'PASSWORD',
    ];
}
