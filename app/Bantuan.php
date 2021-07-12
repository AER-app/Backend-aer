<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    protected $table = "bantuan";

    protected $fillable = [
        'judul','isi'
    ];
}
