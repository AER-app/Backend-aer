<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailOffline extends Model
{
    protected $table = "order_detail_offline";
   	protected $fillable = [
       'id_order_offline','id_menu','no_telp','note','jarak','harga'
    ];
}
