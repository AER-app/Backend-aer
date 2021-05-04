<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lapak extends Model
{
    protected $table = "lapak";

    protected $fillable = [
        'id_user','nama_usaha','nama_pemilik_usaha','alamat','foto_usaha','foto_profile',
        'foto_ktp','foto_umkm','foto_npwp','nomor_rekening','nama_pemilik_rekening',
        'status','latitude_lap','longitude_lap', 'jam_operasional',
        'created_at','updated_at','id_provinsi','id_kabupaten','id_kecamatan1','id_kecamatan2'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function menu()
    {
        return $this->hasMany(Menu::class);
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
            return asset('Images/Lapak/Profile/'.$this->foto_profile);
        }
    }

    public function ambilGambarKtp()
    {
        if(!$this->foto_ktp){
            return asset('Images/Lapak/Ktp/default.jpg');
        }else{
            return asset('Images/Lapak/Ktp/'.$this->foto_ktp);
        }
    }

    public function ambilGambarUmkm()
    {
        if(!$this->foto_umkm){
            return asset('Images/Lapak/Umkm/default.jpg');
        }else{
            return asset('Images/Lapak/Umkm/'.$this->foto_umkm);
        }
    }

    public function ambilGambarNpwp()
    {
        if(!$this->foto_npwp){
            return asset('Images/Lapak/Npwp/default.jpg');
        }else{
            return asset('Images/Lapak/Npwp/'.$this->foto_npwp);
        }
    }

    public function ambilGambarUsaha()
    {
        if(!$this->foto_usaha){
            return asset('Images/Lapak/Usaha/default.jpg');
        }else{
            return asset('Images/Lapak/Usaha/'.$this->foto_usaha);
        }
    }
}
