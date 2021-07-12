<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryCariDriver extends Model
{
    protected $table = "history_cari_driver";
   	protected $fillable = [
       'id_driver','id_user_driver','id_order'
    ];
}