<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LapakOffline extends Model
{
    //
    protected $table = "lapak_offline";
   	protected $fillable = [
       'nama_lapak_offline','alamat_lapak_offline','no_telp','longitude','latitude'
    ];
}
