<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customer";
   	protected $fillable = [
       'id_user','alamat','foto_profile','foto_ktp','longitude_cus','latitude_cus','token','otp'
    ];
}
