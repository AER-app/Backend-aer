<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lapak extends Model
{
    protected $table = "lapak";

    protected $fillable = [
        'id_user','nama','nama_usaha','alamat','foto_usaha','foto_profile','foto_ktp','foto_umkm','foto_npwp','nomor_rekening','jam_operasional','jenis_usaha','keterangan','status','latitude_lap','longitude_lap','created_at','updated_at','id_provinsi','id_kabupaten','id_kecamatan1','id_kecamatan2'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }
}
