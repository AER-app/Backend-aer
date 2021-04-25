<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaksi extends Model
{

    protected $table = "history_transaksi";
    protected $fillable = [
        'id_order', 'id_customer', 'id_driver', 'id_lapak', 'tanggal_order'
    ];
}
