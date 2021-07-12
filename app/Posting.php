<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    protected $table = 'posting';

    protected $fillable = [
        'judul_posting', 'deskripsi_posting', 'foto_posting', 'harga', 'status', 'durasi', 'id_driver',
        'latitude_posting', 'longitude_posting', 'batas_durasi'
    ];
    
    
    public function driver()
    {
        return $this->belongsTo('App\Driver', 'id_driver');
    }

    public function ambilGambarPosting()
    {
        if(!$this->foto_posting){
            return asset('assets/img/avatar/a.png');
        }else{
            return asset('Images/Driver/Posting/Normal/'.$this->foto_posting);
        }
    }
}
