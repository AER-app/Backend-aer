<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostingLapakDetail extends Model
{
    
    protected $table = "posting_lapak_detail";
   	protected $fillable = [
       'id_posting_lapak','id_kategori'
    ];
}
