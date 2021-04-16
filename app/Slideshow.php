<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    
    protected $table = "slideshow";
   	protected $fillable = [
       'judul_slideshow','deskripsi_slideshow','foto_slideshow','link','menu','kategori','status'
    ];
}
