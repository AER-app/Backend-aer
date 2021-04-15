<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostingLapak extends Model
{
    
    protected $table = 'posting_lapak';
    protected $fillable = [
        'id_lapak','nama_menu','foto_menu','deskripsi_menu','harga','status','diskon'
    ];
}
