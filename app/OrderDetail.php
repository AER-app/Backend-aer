<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

  protected $table = "order_detail";
  protected $fillable = [
    'id_order', 'id_menu', 'id_jastip', 'no_telp', 'note', 'jarak', 'harga', 'jumlah_pesanan'
  ];
}
