<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOffline extends Model
{

    protected $table = "customer_offline";
    protected $fillable = [
        'nama', 'alamat', 'no_telp', 'longitude', 'latitude'
    ];
}
