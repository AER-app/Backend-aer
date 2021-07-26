<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LapakOffline extends Model
{
    //
    protected $table = "lapak_offline";
   	protected $fillable = [
       'nama_usaha','no_telp','longitude_lap','latitude_lap'
    ];
}
