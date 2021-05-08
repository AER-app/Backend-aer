<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\CustomerOffline;
use App\Lapak;
use App\Menu;
use App\Driver;
use App\Order;
use App\Jastip;
use App\JastipDetail;
use App\OrderDetail;
use App\OrderDetailOffline;
use App\OrderOffline;
use DB;
use App\Haversine;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    
    //proses tamboh orderan 
    public function order_tambah_order(Request $request){

        
        $id_menu = $request->id_menu;
        $id_jastip = $request->id_jastip;
        $no_telp = $request->no_telp;
        $note = $request->note;
        $jarak = $request->jarak;
        $harga = $request->harga;


        $data = ([
            'kode_order' => $request->kode_order,
            'id_customer' => $request->id_customer,
            'id_lapak' => $request->id_lapak,
            'ongkir' => $request->ongkir,
            'total_harga' => $request->total_harga,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'status_order' => 'waiting',
            'jarak' => $request->jarak,
        ]);

        $lastid = Order::create($data)->id;


        foreach ($id_menu as $key => $menu) {

            $order_detail = OrderDetail::create([
            'id_order' => $lastid,
            'id_menu' => $menu,
            'id_jastip' => $id_jastip,
            'no_telp' => $no_telp,
            'note' => $note,
            'harga' => $harga
                
            ]);
        }

               
        if ($lastid && $order_detail) {
            $out = [
                "message" => "tambah-order_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "tambah-order_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

    }

      

    //get orderan untuk driver
    public function order_driver_get_order()
    {
        
        $driver = DB::table('driver')
        ->join('users', 'driver.id_user', '=', 'users.id')
        ->select('driver.*','users.token')
        ->take(3)->orderBy('id','desc')->where('status_driver','1')->get();

        $hitung = new Haversine();
        
        
        $orderan = DB::table('order')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->where('id_driver', null)
        ->orderBy('id','DESC')
        ->select('order.*','lapak.id_kecamatan1')->first();


        $length =count($driver);
        $tes = null;
        $show_order = null;


        for ($i=0; $i < $length ; $i++) {
            if ($driver[$i]->id_kecamatan1 == $orderan->id_kecamatan1 || $driver[$i]->id_kecamatan2 == $orderan->id_kecamatan1) {
        
        
                $tes[] = $driver[$i];

            }
        }

       

        $hasil = array();
        foreach ($tes as $value) {

            
            //$jarak = round($hitung->distance($value->latitude_driver, $value->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $hasil[] =['driver id_user'=>$value->id_user,'token'=>$value->token, 'orderan'=> $orderan] ;
        }
       
        $c = collect($hasil);
        $sort = $c->SortBy('KM');
        return $sort->values()->all();
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }


    //get orderan untuk driver
    public function order_driver_detail_order($id_order)
    {
        
        // $driver = Driver::take(5)->orderBy('id','desc')->where('status_driver','1')->get();
        $hitung = new Haversine();
        
        // $lapak = DB::table('order')
        // ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        // ->where('id_driver', null)
        // ->select('lapak.id_kecamatan1')->first();


        // $length =count($driver);
        // $tes = null;
        // $show_order = null;


        $show_order = DB::table('order')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->join('customer', 'order.id_customer', '=', 'customer.id')
        ->join('driver', 'order.id_driver', '=', 'driver.id')
        ->select('order.*', 'lapak.latitude_lap', 'lapak.longitude_lap', 'customer.latitude_cus', 'customer.longitude_cus','driver.longitude_driver','driver.latitude_driver')
        ->where('order.id' ,$id_order)
        ->orderBy('id','DESC')
        ->first();

       // return response()->json([
       //      'lihat orderan' => $show_order
       //  ]);

        $lat_lapak = $show_order->latitude_lap;
        $long_lapak = $show_order->longitude_lap;

        $hasil = array();
        

            
            $jarak = round($hitung->distance($show_order->latitude_driver, $show_order->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $hasil[] =['orderan' => $show_order,'KM' => $jarak] ;
        
       
        return response()->json([
            'lihat orderan' => $hasil
        ]);
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }




    //driver menerima orderan
    public function order_driver_terima_order(Request $request, $id){

        $terima_order = Order::findOrFail($id);

        $data = [
            'id_driver' => $request->id_driver,
            'status' => 'proses',
           
        ];

        if ($terima_order->update($data)) {
            $out = [
                "message" => "success",
                "code" => 201
            ];
        } else {
            $out = [
                "message" => "vailed",
                "code" => 404
            ];
        }
        

        return response()->json($out, $out['code']);

    }

    //get semua menu jastip
    public function order_get_menu_jastip(){

        $jastip = DB::table('order_detail')
            ->join('menu', 'order_detail.id_menu', '=', 'menu.id')
            ->join('order', 'order_detail.id_order', '=', 'order.id')
            ->select('order_detail.*', 'menu.nama_menu','menu.diskon','order.jarak','order.jumlah_jastip')
            ->where('order.jumlah_jastip', '<' ,2) 
            ->where('order.status_order','proses')
            ->whereNotNull('order.id_driver')
            ->get();


            $data = [];
            foreach ($jastip as $jast) {
                # code...
                //$customer = Customer::where('id_user',$id_user)->first();     
                $diskon = $jast->harga * ($jast->diskon / 100);
                $data[] = [
                    'menu' => $jast,
                    'harga_diskon' => $jast->harga-$diskon,
                ];
            }

             return response()->json([

            'Hasil Menu jastip' => $data
        ]);     
    }


    //proses tambah jastip dari orderan yang muncul
    public function order_tambah_jastip(Request $request){

        $id_customer = $request->id_customer;
        $jumlah_jastip = $request->jumlah_jastip;
        $id_jastip = $request->id_jastip;
        $id_menu = $request->id_menu;
        

        $data = ([
            'id_order' => $request->id_order,
            'id_driver' => $request->id_driver,
            'kode_jastip' => rand(10000, 99999),
            'status_jastip' => 1,
            'longitude_cus' => $request->longitude_cus,
            'latitude_cus' => $request->latitude_cus,
        ]);

        $lastid = Jastip::create($data)->id; 

        foreach ($id_menu as $value => $v) {
                $jastip_detail = JastipDetail::create([
                    'id_customer' => $v['id_customer'],
                    'id_menu' => $v['id_menu'],        
                    'jumlah_menu' => $v['jumlah_menu'],
                    'id_jastip' => $lastid,
            ]);
        }

        $jumlah_jastip = Order::where('id',$request->id_order)
                        ->first();

             $data = [
            'jumlah_jastip' => $jumlah_jastip->jumlah_jastip+1,
          
            
        ];

        $jumlah_jastip->update($data);

        if ($lastid && $jastip_detail && $jumlah_jastip) {
            $out = [
                "message" => "tambah-jastip_success",
                "code"    => 201,
            ];
            
        } else {
            $out = [
                "message" => "tambah-jastip_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

    }



    //proses tambah jastip dari orderan yang muncul
    public function order_tambah_order_customer_offline (Request $request){

        
        $id_menu = $request->id_menu;
        $no_telp = $request->no_telp;
        $note = $request->note;
        $jarak = $request->jarak;
        $harga = $request->harga;

        
        $data = ([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        $lastid = CustomerOffline::create($data)->id;


        $data2 = ([
            'kode_order_offline' => $request->kode_order_offline,
            'id_customer_offline' => $lastid,
            'id_driver' => $request->id_driver,
            'id_lapak' => $request->id_lapak,
            'ongkir' => $request->ongkir,
            'total_harga' => $request->total_harga,
            'status_order_offline' => $request->status_order_offline,
        ]);

        $lastid2 = OrderOffline::create($data2)->id;


        $order_detail_offline = OrderDetailOffline::create([
                'id_order_offline' => $lastid2,
                'id_menu' => $id_menu,
                'no_telp' => $no_telp,
                'note' => $note,
                'jarak' => $jarak,
                'harga' => $harga,
    
        ]);


        
        if ($lastid && $lastid2 && $order_detail_offline) {
            $out = [
                "message" => "tambah-offline_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "tambah-jastip_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

    }





}
