<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuDetail extends Model
{
    
    protected $table = "menu_detail";
   	protected $fillable = [
       'id_menu','id_kategori'
    ];
}
