<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'driver';

    protected $fillable = [
        'id_user', 'nama', 'alamat', 'foto_profile', 'foto_ktp', 'foto_kk',
        'foto_sim', 'foto_stnk', 'foto_motor', 'jenis_motor', 'plat_nomor', 'warna_motor',
        'latitude', 'longitude', 'token', 'otp'
    ];
}
