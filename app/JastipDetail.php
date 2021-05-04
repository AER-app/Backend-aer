<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JastipDetail extends Model
{
    protected $table = "jastip_detail";
    protected $fillable = [
        'id_customer', 'id_jastip', 'id_menu', 'jumlah_menu'
    ];
}
