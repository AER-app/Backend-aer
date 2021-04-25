<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    protected $table = 'posting';

    protected $fillable = [
        'judul_posting', 'deskripsi_posting', 'foto_posting', 'harga', 'status', 'durasi', 'id_driver',
        'latitude_posting', 'longitude_posting'
    ];
}
