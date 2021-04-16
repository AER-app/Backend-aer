<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPosting extends Model
{
    
    protected $table = "order_posting";
   	protected $fillable = [
       'id_customer','id_driver','jumlah_pesanan','keterangan','longitude','latitude'
    ];
}
