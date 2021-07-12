<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jastip extends Model
{

    protected $table = "jastip";
    protected $fillable = [
        'id_order', 'id_driver', 'id_customer', 'id_menu','id_lapak', 'kode_jastip', 'status_jastip', 'status_order',
        'latitude_cus', 'longitude_cus', 'ongkir', 'note', 'total_harga'
    ];
    
    
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'id_customer');
    }
    
    public function driver()
    {
        return $this->belongsTo('App\Driver', 'id_driver');
    }
}
