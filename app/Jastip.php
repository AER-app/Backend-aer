<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jastip extends Model
{
    
    protected $table = "jastip";
   	protected $fillable = [
       'id_order','id_driver','id_customer','kode_jastip','status_jastip'
    ];

}