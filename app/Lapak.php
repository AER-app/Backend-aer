<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lapak extends Model
{
    //
    protected $table = "lapak";
   	protected $fillable = [
        'id_user','nama','nama_usaha','alamat','foto_usaha','foto_profile','foto_ktp','foto_umkm','jam_operasional','jenis_usaha','keterangan','status','latitude','longitude','token','otp','created_at','updated_at'
    ];
}
