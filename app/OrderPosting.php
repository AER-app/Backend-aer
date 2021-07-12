<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPosting extends Model
{

    protected $table = "order_posting";
    protected $fillable = [
        'id_customer','id_driver','id_posting','ongkir','total_harga','status_order_posting', 'status_order',
        'jumlah_pesanan', 'note', 'longitude_cus', 'latitude_cus', 'jarak'
    ];
    
    public function driver()
    {
        return $this->belongsTo('App\Driver', 'id_driver');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'id_driver');
    }
    
    public function posting()
    {
        return $this->belongsTo('App\Posting', 'id_posting');
    }
}
