<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPosting extends Model
{

    protected $table = "order_posting";
    protected $fillable = [
        'id_customer', 'id_driver','id_posting', 'jumlah_pesanan', 'keterangan','ongkir','total_harga', 'longitude_cus', 'latitude_cus', 'jarak'
    ];
}
