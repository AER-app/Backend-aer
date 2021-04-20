<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customer";

    protected $fillable = [
        'id_user','alamat','foto_profile','foto_ktp','longitude_cus','latitude_cus','token','otp'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function ambilGambar()
    {
        if(!$this->foto_ktp){
            return asset('Admin/Customer/Ktp/default.jpg');
        }else{
            return asset('Admin/Customer/Ktp/'.$this->foto_ktp);
        }
    }
}
