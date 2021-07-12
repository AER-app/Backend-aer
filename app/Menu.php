<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";

    protected $fillable = [
        'id_lapak','nama_menu','foto_menu','deskripsi_menu','harga','status','diskon'
        ,'rating', 'jenis'
    ];

    public function lapak()
    {
        return $this->belongsTo('App\Lapak', 'id_lapak');
    }
    
    public function ambilGambarMenu()
    {
        if(!$this->foto_menu){
            return asset('assets/img/avatar/a.png');
        }else{
            return asset('Images/Lapak/Menu/Thumbnail/'.$this->foto_menu);
        }
    }
    
}
