<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\CustomerOffline;
use Carbon\Carbon;
use App\Lapak;
use App\LapakOffline;
use App\Menu;
use App\Haversine;
use App\Notif;
use DB;
use App\Driver;
use App\Posting;
use App\Order;
use App\OrderPosting;
use App\Jastip;
use App\JastipDetail;
use App\OrderDetail;
use App\OrderDetailOffline;
use App\OrderOffline;
use App\HistoryCariDriver;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class OrderApiController extends Controller
{

	//proses tamboh orderan 
	public function order_tambah_order(Request $request)
	{
        
		$menu = $request->menu;
		$no_telp = $request->no_telp;
		
		$jarak = $menu[0];
		    setlocale(LC_TIME, 'nl_NL.utf8');
            Carbon::setLocale('id');
    
            $tgl = Carbon::now();
			$batas_durasi = $tgl->addSecond(15);

		$data = ([
			'kode_order' => rand(100, 999),
			'id_customer' => $request->id_customer,
			'id_lapak' => $request->id_lapak,
			'ongkir' => $request->ongkir,
			'total_harga' => $request->total_harga,
			'longitude_cus' => $request->long_cus,
			'latitude_cus' => $request->lat_cus,
			'jarak' => $jarak['jarak'],
			'durasi'=>15,
			'batas_durasi'=>$batas_durasi,
			'status_order' => '1',
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
		
		return $this->order_driver_get_order();

	}
	
	
	//tambah orderan postingan driver
	public function order_tambah_order_posting(Request $request)
	{

		$data = ([
			
			'id_customer' => $request->id_customer,
			'id_driver' => $request->id_driver,
			'id_posting' => $request->id_posting,
			'jumlah_pesanan'=>$request->jumlah_pesanan,
			'note' => $request->keterangan,
			'ongkir' => $request->ongkir,
			'total_harga' => $request->total_harga,
			'longitude_cus' => $request->longitude_cus,
			'latitude_cus' => $request->latitude_cus,
		    'jarak' => $request->jarak,
			'status_order_posting' =>'1',
			'status_order' =>'3',
			
		]);
		
		$driver = Driver::find($request->id_driver);
		$tokendriver = $driver->user->token;
		$namadriver = $driver->user->nama;
		
		$tambah_order_posting = OrderPosting::create($data);
		
		$notif = new Notif();
		
		$notif->sendDriver([$tokendriver] , $driver->id ,$namadriver,"Ada Orderan Baru dari Postingan Anda ","ORDERAN POSTING","posting");

		
		if ($notif) {
			$out = [
				"message" => "tambah-order_posting_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-order_posting_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}
	
	
	//get orderan posting untuk driver
    public function order_driver_detail_order_posting($id_posting)
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

        $show_order_posting = DB::table('order_posting')
        ->join('customer', 'order_posting.id_customer', '=', 'customer.id')
        ->join('users', 'customer.id_user', '=', 'users.id')
        ->select('users.nama','users.no_telp','order_posting.*')
        ->where('order_posting.id_posting' ,$id_posting)
        ->orderBy('order_posting.id','DESC')
        ->get();

        // return response()->json([
        //      'data orderan' => $show_order
        //  ]);

        // foreach($show_order_posting as $value => $v){
            
        //     $lat_lapak = $v->latitude_lap;
        //     $long_lapak = $v->longitude_lap;
            
        // }
        
        // $hasil = array();
            
        //     $jarak_customer = round($hitung->distance($show_order->latitude_cus, $show_order->longitude_cus, $show_order->latitude_driver, $show_order->longitude_driver, "K"), 1);
        //     $hasil[] =['orderan' => $show_order, 'menu' => $menu, 'KM customer' => $jarak_customer] ;
        
       
        return response()->json([
            'lihat orderan posting' => $show_order_posting
        ]);
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }

	//proses driver terima order
	public function order_driver_get_order()
	{
        
        $driver = DB::table('driver')
        ->join('users', 'driver.id_user', '=', 'users.id')
        ->select('driver.*','users.token')
        ->orderBy('id','desc')
        ->where('status_driver','1')
        ->where('status_order_driver', '0')
        ->where('saldo', '>', 5000)
        ->get();

        $hitung = new Haversine();
        
        $orderan = DB::table('order')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->where('id_driver', null)
        ->orderBy('id','DESC')
        ->select('order.*','lapak.id_kecamatan1', 'lapak.latitude_lap', 'lapak.longitude_lap')
        ->first();
        
        if(count($driver) == 0){
            // $pesan = "Belum ada driver yang siap";
            $out = [
				"message" => "Belum ada driver yang siap",
				"code"    => 202,
			];
            
			$or = Order::find($orderan->id);
			$or_det = OrderDetail::where('id_order', $orderan->id)->get(); 

			$or_det->each->delete();
			$or->delete();
    
            // $or->update(['status' => 0]);
			
            return response()->json($out, $out['code']);
        }

        $lat_lapak = $orderan->latitude_lap;
        $long_lapak = $orderan->longitude_lap;

        $length =count($driver);
        $tes = [];

        for ($i=0; $i < $length ; $i++) {
            if ($driver[$i]->id_kecamatan1 == $orderan->id_kecamatan1 || $driver[$i]->id_kecamatan2 == $orderan->id_kecamatan1)
                $tes[] = $driver[$i];
        }
        
        
        if($tes == []){
            // $pesan = "Belum ada driver yang siap";
            $out = [
				"message" => "belum ada driver di kecamatan anda",
				"code"    => 203,
			];
			
			$or = Order::find($orderan->id);
			$or_det = OrderDetail::where('id_order', $orderan->id)->get(); 

			$or_det->each->delete();
			$or->delete();
			
            return response()->json($out, $out['code']);
        }
        
        $token = array();
        $hasil = array();
        foreach ($tes as $value) {
            $jarak = round($hitung->distance($value->latitude_driver, $value->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $hasil[] =['driver_id_user'=>$value->id_user,'token'=>$value->token, 'orderan'=> $orderan, 'KM'=> $jarak] ;
			$token[] = $value->token;
		}
       
        $c = collect($hasil);
        $sort = $c->SortBy('KM');
        
		$token = collect($hasil)->SortBy('KM')->take(3)->pluck('token')->values()->all();
		
        $notif = new Notif();
		$lapak = Lapak::findOrFail($orderan->id_lapak);
		
		$hasil = $sort->take(3)->values()->all();

        $notif->sendDriver($token,$orderan->id,$lapak->nama_usaha,"Orderan Baru Nih ke Lapak $lapak->nama_usaha","ORDERAN","orderan");
        
        foreach($hasil as $data => $v){
            $history_cari_driver = HistoryCariDriver::create([
                    'id_order' => $orderan->id,
                    'id_user_driver' => $v['driver_id_user']
                ]);
        }
        
		if ($hasil) {
			$out = [
				"message" => "tambah-order_success",
				"code"    => 201,
				"id_order" => $orderan->id
			];
		} else {
			$out = [
				"message" => "tambah-order_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
        // return $hasil;
            // return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200 );
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
        ->join('users', 'customer.id_user', '=', 'users.id')
        ->select('order.*', 'lapak.latitude_lap','lapak.longitude_lap','lapak.nama_usaha','users.nama','driver.longitude_driver','driver.latitude_driver')
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
    
    public function order_driver_detail_order_offline($id_order_offline)
    {
        
        $hitung = new Haversine();
        
        $show_order = DB::table('order_offline')
        // ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->join('customer_offline', 'order_offline.id_customer_offline', '=', 'customer_offline.id')
        // ->join('driver', 'order.id_driver', '=', 'driver.id')
        // ->join('users', 'customer.id_user', '=', 'users.id')
        ->select('order_offline.*', 'customer_offline.nama')
        ->where('order_offline.id' ,$id_order_offline)
        ->orderBy('id','DESC')
        ->get();

        // $hasil = array();
            
        //     $jarak = round($hitung->distance($show_order->latitude_driver, $show_order->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
        //     $jarak_customer = round($hitung->distance($show_order->latitude_cus, $show_order->longitude_cus, $show_order->latitude_driver, $show_order->longitude_driver, "K"), 1);
        //     $hasil[] =['orderan' => $show_order, 'menu' => $menu,'KM lapak' => $jarak, 'KM customer' => $jarak_customer] ;
        
       
        return response()->json([
            'lihat orderan' => $show_order
        ]);
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }

	//driver menerima orderan
	public function order_driver_terima_order(Request $request, $id_order)
	{
        
        
		    $terima_order = Order::findOrFail($id_order);
        
            setlocale(LC_TIME, 'nl_NL.utf8');
            Carbon::setLocale('id');
            $tgl = Carbon::now();
            
            $hapus_orderan = Order::where('id', $id_order)->where('batas_durasi', '>', $tgl)->first();

        if($hapus_orderan){
            if($terima_order->id_driver != null){
            
                return response()->json([
                    "message" => "gagal",
                    "code" => 404
                ]);
    
            }
        
        }else{
            return response()->json([
                    "message" => "gagal",
                    "code" => 404
                ]);
        }
		$data = [
			'id_driver' => $request->id_driver,
			'status_order' => '2',
		];
		
		$driver = Driver::findOrFail($request->id_driver);
		
		$driver->update([
		        'status_order_driver' => '1'
		    ]);
		
		$notif = new Notif();
		$lapak = Lapak::findOrFail($terima_order->id_lapak);
		$customer = Customer::findOrFail($terima_order->id_customer);
		$tokenLapak[] = $lapak->user->token;
		$tokenCust[] = $customer->user->token;
			$namaCust = $customer->user->nama;
		
		$namaLapak = $lapak->nama_usaha;
        $notif->sendCustomer($tokenCust, $namaCust ,"Pesanan","Pesanan Sudah Mendapat Driver","ada");
        $notif->sendLapak($tokenLapak, $namaLapak ,"Orderan Baru Untuk Lapak","ORDERAN untuk Lapak", "ada");

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
		
		$tokenCus[] = $customer->user->token;
		$namaCustomer = $customer->user->nama;
		$namaDriver = $driver->user->nama;
		$judul = "Haii $namaCustomer";
		
		if ($kode_order->kode_order == $request->kode_order) {
			$data = [
				'status_order' => '3',
				'kode_order' => 'selesai'
			];
	
			$kode_order->update($data);
			
    		$notif = new Notif();
			
			$notif->sendCustomer($tokenCus, $namaCustomer ,"Orderanmu udah dipesan oleh driver $namaDriver nih", $judul,"ada");
			$pesan = "Kode order benar";
			return $pesan;
		} else {
			$pesan = "Maaf, Kode order salah";
			return $pesan;
		}
	}

	//get menu jastip
	public function order_get_menu_jastip(Request $request)
	{

		$jastip = DB::table('order_detail')
			->join('menu', 'order_detail.id_menu', '=', 'menu.id')
			->join('order', 'order_detail.id_order', '=', 'order.id')
			->join('lapak', 'order.id_lapak', '=', 'lapak.id')
			->select('order_detail.*', 'menu.nama_menu', 'menu.diskon', 'menu.foto_menu','order.id_driver', 'order.status_order', 'order.jumlah_jastip', 'order.id_lapak', 'lapak.nama_usaha')
			->where('order.jumlah_jastip', '<', 3)
			->where('order.status_order', 2)
			->orWhere('order.status_order', 3)
			->whereNotNull('order.id_driver')
			->orderBy('id', 'DESC')
			->get();
			
        $hitung = new Haversine();

		$data = [];
		foreach ($jastip as $jast) {
		    $lapak = Lapak::find($jast->id_lapak);
			$diskon = $jast->harga * ($jast->diskon / 100);
			
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
			$data[] = [
				'menu' => $jast,
				'harga_diskon' => $jast->harga - $diskon,
				'jarak' => round($jarak, 1)
			];
		}
		
		$datas = collect($data)->SortBy('jarak');

		return response()->json([

			'Hasil Menu jastip' => $datas->values()->all()
		]);
	}
	
	//fungsi mengbah status orderan selesai
	public function order_customer_orderan_diterima(Request $request, $id_order)
	{
		$order_selesai = Order::where('id',$id_order)->where('status_order','4')->first();
		
		$driver = Driver::find($order_selesai->id_driver);
		$potongan_saldo_order = $order_selesai->ongkir * 0.15;
		
		$total_harga_t_ongkir = $order_selesai->total_harga - $order_selesai->ongkir;
		$potongan_lapak_order = $total_harga_t_ongkir * 0;
		
		if($order_selesai){
    		$data = [
    			'status_order' => '5'
    		];
		}
		
		$jastip_selesai = Jastip::where('id_order', $id_order)->get();
		
		$potongan_saldo_jastip = 0;
		foreach($jastip_selesai as $value => $v){
		    $jastip = Jastip::find($v->id);
		    
		    $potongan_jastip = $jastip->ongkir * 0.15;
		    $total_harga_t_ongkir_jastip = $jastip->total_harga - $jastip->ongkir;
		    
    		$potongan_lapak_jastip = $total_harga_t_ongkir_jastip * 0;
    		
    		$dat = [
    			'status_order' => '5',
    		];
    		
    		$potongan_saldo_jastip += $potongan_jastip + $potongan_lapak_jastip;
    		
    		$jastip->update($dat);
		}
		
		$potongan_semua = $potongan_saldo_order + $potongan_lapak_order + $potongan_saldo_jastip;
		
		$hasil_potongan_saldo_order = $driver->saldo - $potongan_semua;
		
		$driver->update([
		        'saldo' => $hasil_potongan_saldo_order,
		        'status_order_driver' => '0'
		    ]);
		
		if ($order_selesai->update($data)) {
            $out = [
                "message" => "orderan-selesai_success",
                "code"    => 201,
            ];
            
        } else {
            $out = [
                "message" => "orderan-selesai_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

	}
	
	public function order_cek_diterima_driver(Request $request)
	{
	    $order = Order::find($request->id_order);
	    
	    if($order->id_driver){
	        return response()->json([
	            'status' => 1
	            ]);
	    }else{
	        return response()->json([
	            'status' => 0
	            ]);
	    }
	}
	
// 	public function order_customer_orderan_jastip_diterima(Request $request, $id_jastip)
// 	{
// 		$jastip_selesai = Jastip::where('id',$id_jastip)->where('id_customer',$request->id_customer)->first();
		
// 		if($jastip_selesai){
//     		$data = [
//     			'status_order' => '5',
//     		];
// 		}
		
// 		if ($jastip_selesai->update($data)) {
//             $out = [
//                 "message" => "orderan-selesai_success",
//                 "code"    => 201,
//             ];
            
//         } else {
//             $out = [
//                 "message" => "orderan-selesai_failed",
//                 "code"   => 404,
//             ];
//         }
 
//         return response()->json($out, $out['code']);

// 	}

	public function order_customer_orderan_offline_diterima(Request $request, $id_order_offline)
	{
		$order_offline_selesai = OrderOffline::where('id',$id_order_offline)->where('id_customer_offline',$request->id_customer)->first();
	
		if($order_offline_selesai){
    		$data = [
    			'status_order' => '5',
    		];
		}
		
		if ($order_offline_selesai->update($data)) {
            $out = [
                "message" => "orderan-selesai_success",
                "code"    => 201,
            ];
            
        } else {
            $out = [
                "message" => "orderan-selesai_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

	}
	
	public function order_customer_orderan_posting_diterima(Request $request, $id_posting)
	{
		$order_selesai = OrderPosting::where('id_posting', $id_posting)->get();
        
		$id_driver = $order_selesai[0];
        $potongan_saldo_order_posting = 0;
        foreach($order_selesai as $value => $v){
            
    		$driver = Driver::find($v->id_driver);
    		$potongan_ongkir = $v->ongkir * 0.15; 
    		
    	    $total_harga_t_ongkir= $v->total_harga - $v->ongkir;
    	    
    		$potongan_total_harga = $total_harga_t_ongkir * 0.1;
    		
    		$potongan_saldo_order_posting += $potongan_ongkir + $potongan_total_harga;

    		$order_posting = OrderPosting::find($v->id);
    		$order_posting->update([
    		        'status_order' => '5'
    		    ]);
        }
		
    	$potongan_semua = $potongan_saldo_order_posting;
		
		$hasil_potongan_saldo_order = $driver->saldo - $potongan_semua;
		
		$driver_posting = Driver::find($id_driver['id_driver']);
		
		$driver_posting->update([
		        'saldo' => $hasil_potongan_saldo_order,
		        'status_order_driver' => '0'
		    ]);
		
		if ($driver_posting) {
            $out = [
                "message" => "orderan-selesai_success",
                "code"    => 201,
            ];
            
        } else {
            $out = [
                "message" => "orderan-selesai_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);

	}


	//get orderan customer selesai
	public function order_customer_get_order_selesai($id_customer)
	{
        $order = Order::where('id_customer', $id_customer)->where('status_order', 5)->orderBy('updated_at', 'DESC')->get();
        // $jastip = Jastip::where('id_customer', $id_customer)->orderBy('updated_at', 'DESC')->get();
        $jastip = DB::table('jastip')
            ->join('order', 'jastip.id_order', '=', 'order.id')
            ->select('jastip.*', 'order.id_lapak', 'order.jarak', 'order.kode_order', 'order.status_order')
            ->where('jastip.id_customer', $id_customer)
            ->where('order.status_order', 5)
            ->orderBy('updated_at', 'DESC')
            ->get();
        
        $order_posting = OrderPosting::where('id_customer', $id_customer)->where('status_order', 5)->orderBy('updated_at', 'DESC')->get();
        
        $data = [];
        $data_jastip = [];
        $data_posting = [];
        foreach ($order as $order => $val) {
            $data[] = $val;

            $lapak = Lapak::findOrFail($val->id_lapak);

            $val['nama_usaha'] = $lapak->nama_usaha;
            $val['no_telp_lapak'] = $lapak->user->no_telp;
            $val['foto_profile_lapak'] = $lapak->foto_usaha;
            
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;

            if ($val->id_driver) {
                $driver = Driver::findOrFail($val->id_driver);
                $val['nama_driver'] = $driver->user->nama;
                $val['no_telp_driver'] = $driver->user->no_telp;
                $val['foto_profile_driver'] = $driver->foto_profile;
            }

            $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select('menu.nama_menu', 'order_detail.*', 'menu.diskon')
                ->where('id_order', $val->id)
                ->get();

            foreach ((array)$order_detail as $key => $value) {

                // $menu = Menu::find($value->id_menu);
                $val['detail_orderan'] = $value;
            }
            
        }
        $data_akhir = $data;

        foreach($jastip as $order =>$val){
            $data_jastip[] = $val;
            
            $order = Order::find($val->id_order);
            $lapak = Lapak::findOrFail($order->id_lapak);

            $val->nama_usaha = $lapak->nama_usaha;
            $val->no_telp_lapak = $lapak->user->no_telp;
            $val->foto_profile_lapak = $lapak->foto_usaha;
            // $val->total_harga = $order->total_harga;  // Masih total harga order - harus dirubah
            
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val->tanggal_orderan = $tanggal_orderan;

            if ($val->id_driver) {
                $driver = Driver::findOrFail($val->id_driver);
                $val->nama_driver = $driver->user->nama;
                $val->no_telp_driver = $driver->user->no_telp;
                $val->foto_profile_driver = $driver->foto_profile;
            }

            $jastip_detail = Menu::leftJoin('jastip_detail', function ($join) {
                $join->on('menu.id', '=', 'jastip_detail.id_menu');
            })
                ->select('menu.nama_menu','menu.harga', 'jastip_detail.*', 'menu.diskon')
                ->where('id_jastip', $val->id)
                ->get();

            foreach ((array)$jastip_detail as $key => $value) {

                $val->detail_orderan = $value;
            }
        }
        
        foreach($order_posting as $order => $val){
            $data_posting[] = $val;
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;
            
            $driver = Driver::where('id', $val->id_driver)->first();
            $val['nama_driver'] = $driver->user->nama;
            $val['no_telp_driver'] = $driver->user->no_telp;
            $val['foto_profile_driver'] = $driver->foto_profile;

            $posting = Posting::where('id', $val->id_posting)->first();
            $val['nama_menu'] = $posting->judul_posting;
            $val['harga_menu'] = $posting->harga;
            $val['detail_orderan'] = [$posting];
            $posting['jumlah_pesanan'] = $val->jumlah_pesanan;
        }
            $data_akhir = collect();
            $data_akhir->push($data, $data_jastip, $data_posting);
            $data_akhir = $data_akhir->collapse()->sortByDesc('tanggal_orderan')->values()->all();

        return response()->json([
            'Hasil' => $data_akhir
        ]);
	}

	//get orderan driver selesai
	public function order_driver_get_order_selesai($id_driver)
	{
		
		$order_lapak_selesai = Order::where('id_driver', $id_driver)
        		->where('status_order', '5')->select([
                  DB::raw('count(id) as `count`'), 
                  DB::raw('DATE(updated_at) as day')
                ])->groupBy('day')
                ->orderBy('day', 'Desc')
                ->get();
                
		$order_posting_selesai = OrderPosting::where('id_driver', $id_driver)
        		->where('status_order', '5')->select([
                  DB::raw('count(id) as `count`'), 
                  DB::raw('DATE(updated_at) as day')
                ])->groupBy('day')
                ->orderBy('day', 'Desc')
                ->get();
                
        $data_tanggal = collect();
        $data_tanggal->push($order_lapak_selesai, $order_posting_selesai);
        $data_tanggal = $data_tanggal->collapse()->unique('day')->values()->all();
        
        $data = [];
        
        // return $order_lapak_selesai;
        foreach($data_tanggal as $key){
            // $order = Order::where('updated_at', 'like', '%' . $key->day. '%')->get();
            $order = DB::table('order')  
        		->join('customer', 'order.id_customer', '=', 'customer.id')
        		->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        		->join('users', 'customer.id_user', '=', 'users.id')
        		->select('order.*','users.nama', 'lapak.nama_usaha')
        		->where('order.id_driver', $id_driver)
        		->where('order.updated_at', 'like', '%' . $key->day. '%')
        		->where('status_order', '5')
        		->orderBy('id', 'DESC')
        		->get();
            
            $jastip = DB::table('jastip')
                ->join('customer', 'jastip.id_customer', '=', 'customer.id')
        		->join('lapak', 'jastip.id_lapak', '=', 'lapak.id')
        		->join('users', 'customer.id_user', '=', 'users.id')
        		->select('jastip.*','users.nama', 'lapak.nama_usaha')
                ->where('id_driver', $id_driver)
        		->where('jastip.updated_at', 'like', '%' . $key->day. '%')
        		->where('status_order', '5')
        		->orderBy('id', 'DESC')
                ->get();
                
            $order_posting = DB::table('order_posting')
                ->join('customer', 'order_posting.id_customer', '=', 'customer.id')
        		->join('users', 'customer.id_user', '=', 'users.id')
        		->select('order_posting.*','users.nama')
                ->where('id_driver', $id_driver)
        		->where('order_posting.updated_at', 'like', '%' . $key->day. '%')
        		->where('status_order', '5')
        		->orderBy('id', 'DESC')
                ->get();
            
            foreach($order_posting as $value => $v){
                $v->nama_usaha = ' order dari
postingan anda';
            }
        		
        	$day = Carbon::createFromFormat('Y-m-d', $key->day)->format('d-m-Y');
            
            $data_akhir = collect();
            $data_akhir->push($order, $jastip, $order_posting);
            $data_akhir = $data_akhir->collapse()->sortByDesc('updated_at')->values()->all();
            // $order_lapak_selesai['order'] = $order; 
            $data[] = [
                'day' => $day,
                'order' => $data_akhir
            ];
        }
             
		return response()->json([

			'Hasil order' => $data
		]);
	}

	//get orderan lapak selesai
	public function order_lapak_get_order_selesai($id_lapak)
	{

		$order_lapak_selesai = Order::where('id_lapak', $id_lapak)
        		->where('status_order', '5')->select([
              // This aggregates the data and makes available a 'count' attribute
              DB::raw('count(id) as `count`'), 
              // This throws away the timestamp portion of the date
              DB::raw('DATE(updated_at) as day')
            // Group these records according to that day
            ])->groupBy('day')
            ->orderBy('day', 'Desc')
            // And restrict these results to only those created in the last week
            // ->where('created_at', '>=', Carbon\Carbon::now()->subWeeks(1))
            ->get();
        
        $data = [];
        
        // return $order_lapak_selesai;
        foreach($order_lapak_selesai as $key){
            // $order = Order::where('updated_at', 'like', '%' . $key->day. '%')->get();
            $order = DB::table('order')
                ->where('order.id_lapak', $id_lapak)
        		->where('order.updated_at', 'like', '%' . $key->day. '%')
        		->where('status_order', '5')
        		->select('order.*')
        		->orderBy('id', 'DESC')
        		->get();
            
        		
        	foreach($order as $value => $v){
        	    $customer = Customer::find($v->id_customer);
        	    $driver = Driver::find($v->id_driver);
                $total_harga_t_ongkir = $v->total_harga - $v->ongkir;
                
        	    $v->total_harga_tanpa_ongkir = $total_harga_t_ongkir;
        	    $v->nama_customer = $customer->user->nama;
        	    $v->nama_driver = $driver->user->nama;
        	}
        	
        	$day = Carbon::createFromFormat('Y-m-d', $key->day)->format('d-m-Y');
            
            // $order_lapak_selesai['order'] = $order; 
            $data[] = [
                'day' => $day,
                'order' => $order
            ];
        }
             
		return response()->json([

			'Hasil order' => $data
		]);
	}

	//proses tambah jastip dari orderan yang muncul
	public function order_tambah_jastip(Request $request)
	{

		$id_customer = $request->id_customer;
		$id_menu = $request->menu;

		$data = ([
        	'id_order' => $request->id_order,
        	'id_driver' => $request->id_driver,
        	'id_customer' => $request->id_customer,
            'id_lapak' => $request->id_lapak,
        	'kode_jastip' => rand(100, 999),
        	'ongkir' => $request->ongkir,
        	'total_harga' => $request->total_harga,
			'note' => $request->note,
        	'status_jastip' => 1,
            'longitude_cus' => $request->long_cus,
            'latitude_cus' => $request->lat_cus,
        ]);

		$lastid = Jastip::create($data)->id; 

        foreach ($id_menu as $key => $value) {
                $jastip_detail = JastipDetail::create([
                    'id_customer' => $id_customer,
                    'id_menu' => $value['id_menu'],        
                    'jumlah_pesanan' => $value['jumlah_pesanan'],
                    'id_jastip' => $lastid,
            ]);
        }

        $jumlah_jastip = Order::where('id',$request->id_order)
                        ->first();
        $data = [
            'jumlah_jastip' => $jumlah_jastip->jumlah_jastip+1,
        ];

        $jumlah_jastip->update($data);
        
        $driver = Driver::find($request->id_driver);
        $token[] = $driver->user->token;
        $nama_driver = $driver->user->nama;
        
        $notif = new Notif();
        
        $notif->sendDriver($token,$request->id_order,$nama_driver,"$nama_driver, Ada Orderan Jastip baru","ORDERAN JASTIP","jastip");

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
	
	
	//detail orderan jastip
    public function order_driver_detail_jastip($id_order)
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

        $show_jastip = DB::table('jastip')
        ->join('order', 'jastip.id_order', '=', 'order.id')
        ->join('customer', 'jastip.id_customer', '=', 'customer.id')
        ->join('driver', 'jastip.id_driver', '=', 'driver.id')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->join('users', 'customer.id_user', '=', 'users.id')
        ->select('jastip.*', 'lapak.longitude_lap','lapak.latitude_lap','driver.longitude_driver','driver.latitude_driver', 
            'users.no_telp', 'users.nama', 'customer.foto_profile')
        ->where('jastip.id_order' ,$id_order)
        ->get();
        
        // return $show_jastip;
        
        foreach($show_jastip as $value => $v){
            
            $lat_lapak = $v->latitude_lap;
            $long_lapak = $v->longitude_lap;
            
            $tot_harga_t_ongkir = $v->total_harga - $v->ongkir;
            
            $v->total_tanpa_ongkir = $tot_harga_t_ongkir;
            
            $jastip_detail = DB::table('jastip_detail')
            ->join('jastip','jastip_detail.id_jastip', '=', 'jastip.id')
            ->join('menu','jastip_detail.id_menu', '=', 'menu.id')
            ->join('customer','jastip_detail.id_customer', '=', 'customer.id')
            ->select('jastip_detail.*','menu.nama_menu', 'menu.harga')
            ->where('jastip.id_order', $v->id_order)
            ->where('jastip_detail.id_jastip', $v->id)
            ->get();
            
                foreach ((array)$jastip_detail as $key => $value) {
    
                    $v->detail_jastip = $value;
                }
                
        }

        // $hasil = array();
            
        //     $jarak = round($hitung->distance($show_jastip->latitude_driver, $show_jastip->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
        //     $jarak_customer = round($hitung->distance($show_jastip->latitude_cus, $show_jastip->longitude_cus, $show_jastip->latitude_driver, $show_jastip->longitude_driver, "K"), 1);
        //     $hasil[] =['orderan_jastip' => $show_jastip,'KM lapak' => $jarak, 'KM customer' => $jarak_customer] ;
        
       
        return response()->json([
            'lihat jastip' => $show_jastip
        ]);
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
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
