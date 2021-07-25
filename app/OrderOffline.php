<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderOffline extends Model
{
    protected $table = 'order_offline';
    
    protected $fillable = [
         'id_customer_offline', 'id_driver', 'latitude_cus','longitude_cus','latitude_lap',
         'longitude_lap','jarak','ongkir','catatan', 'status_order', 'status_order_offline', 'nama_lapak', 'id_lapak'
    ];
    
    public function customer_offline()
    {
        return $this->belongsTo('App\CustomerOffline', 'id_customer_offline');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'id_driver');
    }
    
}
