<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";
    protected $fillable = [
        'kode_order', 'id_customer', 'id_driver', 'id_lapak', 'ongkir', 'total_harga', 'longitude_cus', 'latitude_cus', 'status_order', 'jarak','durasi','batas_durasi',
        'jumlah_jastip'
    ];
    
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'id_customer');
    }
    public function lapak()
    {
        return $this->belongsTo('App\Lapak', 'id_lapak');
    }
    public function driver()
    {
        return $this->belongsTo('App\Driver', 'id_driver');
    }
}
