<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JastipDetail extends Model
{
    protected $table = "jastip_detail";
    protected $fillable = [
        'id_customer', 'id_jastip', 'id_menu', 'jumlah_pesanan', 'total_harga', 'note'
    ];
    
    
    public function jastip()
    {
        return $this->belongsTo('App\Jastip', 'id_jastip');
    }
    
    public function menu()
    {
        return $this->belongsTo('App\Menu', 'id_menu');
    }
}
