<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    
    protected $table = "slideshow";
   	protected $fillable = [
       'judul_slideshow','deskripsi_slideshow','foto_slideshow','link','menu','kategori','status'
    ];
    
    
    public function ambilGambarSlideshow()
    {
        if(!$this->foto_slideshow){
            return asset('assets/img/avatar/a.png');
        }else{
            return asset('Images/Slideshow/'.$this->foto_slideshow);
        }
    }
}
