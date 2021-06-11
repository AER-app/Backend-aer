<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'driver';

    protected $fillable = [
        'id_user', 'alamat', 'foto_profile', 'foto_ktp', 'foto_kk',
        'foto_sim', 'foto_stnk', 'foto_motor', 'jenis_motor', 'plat_nomor', 'warna_motor', 'status_driver', 'status_order_driver',
        'latitude_driver', 'longitude_driver', 'id_kecamatan1', 'id_kecamatan2', 'id_provinsi', 'id_kabupaten'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function kecamatan1()
    {
        return $this->belongsTo('App\Kecamatan', 'id_kecamatan1');
    }

    public function kecamatan2()
    {
        return $this->belongsTo('App\Kecamatan', 'id_kecamatan2');
    }

    public function ambilGambarProfile()
    {
        if(!$this->foto_profile){
            return asset('assets/img/avatar/avatar-5.png');
        }else{
            return asset('Images/Driver/Profile/'.$this->foto_profile);
        }
    }

    public function ambilGambarKtp()
    {
        if(!$this->foto_ktp){
            return asset('Images/Driver/Ktp/default.jpg');
        }else{
            return asset('Images/Driver/Ktp/'.$this->foto_ktp);
        }
    }
    
    public function ambilGambarKk()
    {
        if(!$this->foto_kk){
            return asset('Images/Driver/Kk/default.jpg');
        }else{
            return asset('Images/Driver/Kk/'.$this->foto_kk);
        }
    }

    public function ambilGambarSim()
    {
        if(!$this->foto_sim){
            return asset('Images/Driver/Sim/default.jpg');
        }else{
            return asset('Images/Driver/Sim/'.$this->foto_sim);
        }
    }

    public function ambilGambarStnk()
    {
        if(!$this->foto_stnk){
            return asset('Images/Driver/Stnk/default.jpg');
        }else{
            return asset('Images/Driver/Stnk/'.$this->foto_stnk);
        }
    }

    public function ambilGambarMotor()
    {
        if(!$this->foto_sim){
            return asset('Images/Driver/Sim/default.jpg');
        }else{
            return asset('Images/Driver/Sim/'.$this->foto_sim);
        }
    }
}
