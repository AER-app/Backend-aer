<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoOngkir extends Model
{
    protected $table = "promo_ongkir";
   	protected $fillable = [
       'persen_promo','durasi','batas_durasi','jenis_promo'
    ];
}
