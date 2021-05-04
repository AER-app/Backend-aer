<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalLapak extends Model
{
    protected $table = "jadwal_lapak";
    protected $fillable = [
        'id_lapak', 'hari', 'jam_buka', 'jam_tutup'
    ];
}
