<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";
   	protected $fillable = [
       'kode_order','id_customer','id_driver','id_lapak','ongkir','total_harga','longitude','latitude','status_order','jarak'
    ];
}
