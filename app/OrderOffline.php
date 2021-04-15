<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderOffline extends Model
{
    
    protected $table = "order_offline";
   	protected $fillable = [
       'kode_order_offline','id_customer','id_customer_offline','id_driver','id_lapak','id_lapak_offline','ongkir','total_harga','status_order_offline'
    ];
}
