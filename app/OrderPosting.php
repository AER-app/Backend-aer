<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPosting extends Model
{

    protected $table = "order_posting";
    protected $fillable = [
        'id_customer','id_driver','id_posting','ongkir','total_harga','status_order_posting', 'status_order',
        'jumlah_pesanan', 'note', 'longitude_cus', 'latitude_cus', 'jarak'
    ];
}
