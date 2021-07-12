<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastNotif extends Model
{
    
    protected $table = "broadcast_notif";
   	protected $fillable = [
       'judul','isi','role','gambar'
    ];
    
}