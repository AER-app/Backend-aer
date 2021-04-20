<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";

    protected $fillable = [
        'id_lapak','nama_menu','foto_menu','deskripsi_menu','harga','status','diskon'
    ];

    public function lapak()
    {
        return $this->belongsTo('App\Lapak', 'id_lapak');
    }
    
}
