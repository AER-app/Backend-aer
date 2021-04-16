<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaksiOffline extends Model
{
    
    protected $table = "history_transaksi_offline";
   	protected $fillable = [
       'id_order','id_driver','id_customer','id_customer_offline','id_lapak','id_lapak_offline'
    ];
}
