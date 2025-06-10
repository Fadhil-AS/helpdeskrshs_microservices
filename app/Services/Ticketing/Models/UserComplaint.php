<?php

namespace App\Services\Ticketing\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Ticketing\Models\UnitKerja;

class UserComplaint extends Model {
    protected $table = 'user_complaint';
    protected $primaryKey = 'NO_REGISTER';
    public $incrementing = false;
    // protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'TGL_INSROW';
    const UPDATED_AT = 'TGL_UPDATE';

    protected $fillable = [
        'NO_REGISTER',
        'ID_BAGIAN',
        'USERNAME',
        'PASSWORD',
        'NAME',
        'GROUPS',
        'SPESIAL_CODE',
        'TGL_INSROW',
        'NIP',
        'VALIDASI',
        'NO_TLPN',
        'TGL_UPDATE',
        'PASSWORD_REAL'
    ];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'ID_BAGIAN', 'ID_BAGIAN');
    }
}
