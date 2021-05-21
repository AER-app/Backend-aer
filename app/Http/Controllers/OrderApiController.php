<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\CustomerOffline;
use App\Lapak;
use App\Menu;
use App\Haversine;
use App\Notif;
use DB;
use App\Driver;
use App\Order;
use App\Jastip;
use App\OrderDetail;
use App\OrderDetailOffline;
use App\OrderOffline;

use Illuminate\Http\Request;

class OrderApiController extends Controller
{

	//proses tamboh orderan 
	public function order_tambah_order(Request $request)
	{

		$menu = $request->menu;
		$no_telp = $request->no_telp;
		
		$jarak = $menu[0];

		$data = ([
			'kode_order' => rand(10000, 99999),
			'id_customer' => $request->id_customer,
			'id_lapak' => $request->id_lapak,
			'ongkir' => $request->ongkir,
			'total_harga' => $request->total_harga,
			'longitude_cus' => $request->long_cus,
			'latitude_cus' => $request->lat_cus,
			'jarak' => $jarak['jarak'],
			'status_order' => 'waiting',
		]);

		$lastid = Order::create($data)->id;

		foreach ($menu as $value => $v) {
			$order_detail = OrderDetail::create([
				'id_order' => $lastid,
				'id_menu' => $v['id'],
				'no_telp' => $no_telp,
				'note' => $v['catatan'],
				'harga' => $v['harga_menu'],
				'jumlah_pesanan' => $v['jumlah_pilihan']
			]);
		}
		
		$this->order_driver_get_order();

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

	//proses driver terima order
	public function order_driver_get_order()
	{
        
        $driver = DB::table('driver')
        ->join('users', 'driver.id_user', '=', 'users.id')
        ->select('driver.*','users.token')
        ->orderBy('id','desc')->where('status_driver','1')->get();

        $hitung = new Haversine();
        
        $orderan = DB::table('order')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->where('id_driver', null)
        ->orderBy('id','DESC')
        ->select('order.*','lapak.id_kecamatan1', 'lapak.latitude_lap', 'lapak.longitude_lap')
        ->first();

        $lat_lapak = $orderan->latitude_lap;
        $long_lapak = $orderan->longitude_lap;

        $length =count($driver);
        $tes = null;

        for ($i=0; $i < $length ; $i++) {
            if ($driver[$i]->id_kecamatan1 == $orderan->id_kecamatan1 || $driver[$i]->id_kecamatan2 == $orderan->id_kecamatan1) {
                $tes[] = $driver[$i];
            } else {
                $pesan = "Belum ada driver yang siap";
                $data = [
					'status_order' => 'failed' 
				];
				$or = Order::find($orderan->id);   

				$or->update($data);
                return $pesan;
            }
        }
        
        $token = array();
        $hasil = array();
        foreach ($tes as $value) {
            $jarak = round($hitung->distance($value->latitude_driver, $value->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $hasil[] =['driver id_user'=>$value->id_user,'token'=>$value->token, 'orderan'=> $orderan, 'KM'=> $jarak] ;
			$token[] = $value->token;
		}
       
        $c = collect($hasil);
        $sort = $c->SortBy('KM');
        $notif = new Notif();
		$lapak = Lapak::findOrFail($orderan->id_lapak);

        $notif->sendDriver($token,$orderan->id,$lapak->nama_usaha,"Orderan Baru Nih","ORDERAN");
        
        return $sort->take(3)->values()->all();
        
        
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
        ->select('order.*', 'lapak.latitude_lap', 'lapak.longitude_lap','driver.longitude_driver','driver.latitude_driver')
        ->where('order.id' ,$id_order)
        ->orderBy('id','DESC')
        ->first();

        // return response()->json([
        //      'data orderan' => $show_order
        //  ]);

        $lat_lapak = $show_order->latitude_lap;
        $long_lapak = $show_order->longitude_lap;
        
        $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select('menu.nama_menu', 'order_detail.*')
                ->where('id_order', $id_order)
                ->get();

            foreach ((array)$order_detail as $key => $value) {

                $menu = $value;
            }

        $hasil = array();
            
            $jarak = round($hitung->distance($show_order->latitude_driver, $show_order->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $jarak_customer = round($hitung->distance($show_order->latitude_cus, $show_order->longitude_cus, $show_order->latitude_driver, $show_order->longitude_driver, "K"), 1);
            $hasil[] =['orderan' => $show_order, 'menu' => $menu,'KM lapak' => $jarak, 'KM customer' => $jarak_customer] ;
        
       
        return response()->json([
            'lihat orderan' => $hasil
        ]);
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }

	//driver menerima orderan
	public function order_driver_terima_order(Request $request, $id)
	{

		$terima_order = Order::findOrFail($id);

        if($terima_order->id_driver != null){
            $pesan = "Orderan sudah ada yang ambil";

            return $pesan;
        }
		$data = [
			'id_driver' => $request->id_driver,
			'status_order' => 'proses',
		];
		
		$notif = new Notif();
		$lapak = Lapak::findOrFail($terima_order->id_lapak);
		$tokenLapak = $lapak->user->token;
		$namaLapak = $lapak->nama_usaha;

        $notif->sendLapak($tokenLapak, $namaLapak ,"Orderan Baru Untuk Lapak","ada ORDERAN untuk Lapak");

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
	
	//driver masukkan kode order dari lapak
	public function order_driver_kode_order(Request $request, $id)
	{
		$kode_order = Order::findOrFail($id);
		$customer = Customer::findOrFail($kode_order->id_customer);
		$driver = Driver::findOrFail($kode_order->id_driver);
		
		$tokenCus = $customer->user->token;
		$namaCustomer = $customer->user->nama;
		$namaDriver = $driver->user->nama;
		$judul = "Haii $namaCustomer";
		
		if ($kode_order->kode_order == $request->kode_order) {
			$data = [
				'status_order' => 'driver di lapak',
				'kode_order' => 'selesai'
			];
	
			$kode_order->update($data);
			
    		$notif = new Notif();
			
			$notif->sendCustomer($tokenCus, $namaCustomer ,"Orderanmu udah dipesan oleh driver $namaDriver nih", $judul);
			$pesan = "Kode order benar";
			return $pesan;
		} else {
			$pesan = "Maaf, Kode order salah";
			return $pesan;
		}
	}

	//get menu jastip
	public function order_get_menu_jastip()
	{

		$jastip = DB::table('order_detail')
			->join('menu', 'order_detail.id_menu', '=', 'menu.id')
			->join('order', 'order_detail.id_order', '=', 'order.id')
			->join('lapak', 'order.id_lapak', '=', 'lapak.id')
			->select('order_detail.*', 'menu.nama_menu', 'menu.diskon', 'menu.foto_menu', 'order.jarak', 'order.jumlah_jastip', 'lapak.nama_usaha')
			->where('order.jumlah_jastip', '<', 2)
			->where('order.status_order', 'proses')
			->whereNotNull('order.id_driver')
			->get();


		$data = [];
		foreach ($jastip as $jast) {
			# code...
			//$customer = Customer::where('id_user',$id_user)->first();     
			$diskon = $jast->harga * ($jast->diskon / 100);
			$data[] = [
				'menu' => $jast,
				'harga_diskon' => $jast->harga - $diskon,
			];
		}

		return response()->json([

			'Hasil Menu jastip' => $data
		]);
	}

	//proses tambah jastip dari orderan yang muncul
	public function order_tambah_jastip(Request $request)
	{

		$id_customer = $request->id_customer;
        $jumlah_jastip = $request->jumlah_jastip;
        $id_jastip = $request->id_jastip;
        

		$data = ([
        	'id_order' => $request->id_order,
        	'id_driver' => $request->id_driver,
            'id_menu' => $request->id_menu,
        	'kode_jastip' => rand(10000, 99999),
        	'status_jastip' => 1,
            'longitude_cus' => $request->longitude_cus,
            'latitude_cus' => $request->latitude_cus,
        ]);

		$lastid = Jastip::create($data)->id; 

        foreach ($id_menu as $key => $value) {
                $jastip_detail = JastipDetail::create([
                    'id_customer' => $id_customer,
                    'id_menu' => $value,        
                    'jumlah_menu' => $jumlah_menu,
                    'id_jastip' => $lastid,
            ]);
        }

        $jumlah_jastip = OrderDetail::where('id_order',$request->id_order)
                        ->where('id_menu',$request->id_menu)
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
	public function order_tambah_order_customer_offline(Request $request)
	{

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