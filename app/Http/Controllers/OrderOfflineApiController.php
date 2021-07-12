<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Haversine;
use App\OrderOffline;
use App\CustomerOffline;
use App\HistoryCariDriver;
use DB;
use App\Driver;
use App\Notif;
use App\Lapak;
use App\LapakOffline;
use App\Kecamatan;

use Carbon\Carbon;

class OrderOfflineApiController extends Controller
{
    public function index()
    {
        return view ('admin.orderan_offline.index');
    }
    
    public function order_offline_create(Request $request)
    {
        
        $customer_offline = CustomerOffline::where('no_telp', $request->no_telp)->first();
        
        if($customer_offline){
            
            $lastid_cus = $customer_offline->id;
        } else {
            $data_cus = [
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'latitude_cus' => $request->latitude_cus,
                'longitude_cus' => $request->longitude_cus,
            ];
            
            $lastid_cus = CustomerOffline::create($data_cus)->id;
        }
        
        $lapak_offline = LapakOffline::where('nama_usaha', $request->nama_lapak)->first();
        
        if(!$lapak_offline){
            $data_lap = [
                'nama_usaha' => $request->nama_lapak,
                'latitude_lap' => $request->latitude_lap,
                'longitude_lap' => $request->longitude_lap,
            ];
            
            LapakOffline::create($data_lap);
        }
        
        $data = [
            'id_customer_offline' => $lastid_cus,
            'latitude_cus' => $request->latitude_cus,
            'longitude_cus' => $request->longitude_cus,
            'latitude_lap' => $request->latitude_lap,
            'longitude_lap' => $request->longitude_lap,
            'nama_lapak' => $request->nama_lapak,
            'id_lapak' => $request->id_lapak,
            'jarak' => $request->jarak,
            'ongkir' => $request->ongkir,
            'catatan' => $request->catatan,
            'status_order' => 1,
        ];
        
        $offline = OrderOffline::create($data)->id;
        
		return $this->order_driver_get_order();
		
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
        
        $orderan = DB::table('order_offline')
        ->where('id_driver', null)
        ->orderBy('id','DESC')
        ->select('order_offline.*')
        ->first();
        
        if(count($driver) == 0){
            // $pesan = "Belum ada driver yang siap";
            $out = [
				"message" => "Belum ada driver yang siap",
				"code"    => 202,
			];
            
			$or = OrderOffline::find($orderan->id);
			$or->update(['status_order' => 0]);
			
            return response()->json($out, $out['code']);
        }

        $lat_lapak = $orderan->latitude_lap;
        $long_lapak = $orderan->longitude_lap;

        $length =count($driver);
        $tes = [];

        for ($i=0; $i < $length ; $i++) {
                $tes[] = $driver[$i];
        }
        
        
        if($tes == []){
            // $pesan = "Belum ada driver yang siap";
            $out = [
				"message" => "belum ada driver di kecamatan anda",
				"code"    => 203,
			];
			
			$or = OrderOffline::find($orderan->id);

			$or->update(['status_order' => 0]);
// 			$or_det->each->delete();
// 			$or->delete();
			
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
		
		$hasil = $sort->take(3)->values()->all();

        $notif->sendDriver($token, $orderan->id, $orderan->id,"Orderan Baru Nih dari Orderan Offline","ORDERAN OFFLINE","offline");
        
        foreach($hasil as $data => $v){
            $history_cari_driver = HistoryCariDriver::create([
                    'id_order_offline' => $orderan->id,
                    'id_user_driver' => $v['driver_id_user']
                ]);
        }
        
		if ($hasil) {
			$out = [
				"message" => "tambah-order_success",
				"code"    => 201,
				"id_order_offline" => $orderan->id
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
    
    public function lihat_order_offline()
    {
        $order_offline = OrderOffline::orderBy('id', 'DESC')->take(50)->get();
        
        foreach($order_offline as $value => $v){
            $cus_offline = CustomerOffline::find($v->id_customer_offline);
            $v['nama_customer'] = $cus_offline->nama;
            $v['notelp_customer'] = $cus_offline->no_telp;
            
            if($v->id_driver){
                $driver = Driver::find($v->id_driver);
                $v['nama_driver'] = $driver->user->nama;
                $v['notelp_driver'] = $driver->user->no_telp;
            }
        }
        
        return response()->json([
                'Hasil' => $order_offline
            ]);
    }
    
    public function cek_notelp_customer(Request $request)
    {
        
        $cus_offline = CustomerOffline::where('no_telp', $request->no_telp)->first();
        
        if($cus_offline){
            
            $data = [
                'nama' => $cus_offline->nama,
                'no_telp' => $cus_offline->no_telp,
                'latitude_cus' => $cus_offline->latitude_cus,
                'longitude_cus' => $cus_offline->longitude_cus,
                'code' => 200
            ];
            
            return response()->json([
                'Hasil' => [$data]
            ]);
        } else {
            return response()->json([
                'Hasil' => [['code' => 404]]
            ]);
            
        }
    }
    
    private function HargaOngkir($jarak_final){
        if ($jarak_final <= 2.1) {
              $ongkir = 7000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 8500;
            }elseif ($jarak_final <= 3.2 || $jarak_final <= 4.1) {
              $ongkir = 10000;
            }elseif ($jarak_final <= 4.2 || $jarak_final <= 5.1) {
              $ongkir = 11500;
            }elseif ($jarak_final <= 5.2 || $jarak_final <= 6.1) {
              $ongkir = 13000;
            }elseif ($jarak_final <= 6.2 || $jarak_final <= 7.1) {
              $ongkir = 15000;
            }elseif ($jarak_final <= 7.2 || $jarak_final <= 8.1) {
              $ongkir = 17000;
            }elseif ($jarak_final <= 8.2 || $jarak_final <= 9.1) {
              $ongkir = 19000;
            }elseif ($jarak_final <= 9.2 || $jarak_final <= 10) {
              $ongkir = 21000;
            }else{
              $ongkir = 'Jarak anda terlalu jauh';
            }
            return $ongkir;
    }
    
    public function get_ongkir(Request $request)
    {
        $hitung = new Haversine();
        
        $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $request->latitude_lap, $request->longitude_lap, "K");
        $jarak_final = round($jarak, 1);

        $ongkir = $this->HargaOngkir($jarak_final);
        $data[] = [
            'jarak' => $jarak_final,
            'ongkir' => $ongkir
            ]; 
            
        return response()->json([
            'Hasil Ongkir' => $data
        ]);
    }
    
    public function order_offline_cek_diterima_driver(Request $request)
	{
	    $order = OrderOffline::find($request->id_order_offline);
	    
	    if($order->id_driver){
	        return response()->json([
	            'status_order' => 1
	            ]);
	    }else{
	        return response()->json([
	            'status_order' => 0
	            ]);
	    }
	}
	
		//driver menerima orderan
	public function order_offline_driver_terima_order(Request $request, $id_order_offline)
	{
        $terima_order = OrderOffline::where('id', $id_order_offline)->where('id_driver', null)->first();
        $order = OrderOffline::where('id', $id_order_offline)->first();
        
	    setlocale(LC_TIME, 'nl_NL.utf8');
        Carbon::setLocale('id');
        $batas_terima_order = $order->created_at;
        $tgl = Carbon::now();
        $loading = $batas_terima_order->addSecond(15);
        
        if(!$terima_order){
            $out = [
				"message" => "gagal",
				"code" => 404
			];
			
			return response()->json($out, $out['code']);
        }
        
        if($tgl > $loading){
            $out = [
				"message" => "gagal",
				"code" => 404
			];
			
			return response()->json($out, $out['code']);
        }
        
		$data = [
			'id_driver' => $request->id_driver,
			'status_order' => 3,
		];
		
		$driver = Driver::findOrFail($request->id_driver);
		
		$notif = new Notif();
		
		if($terima_order->id_lapak){
		    
    		$lapak = Lapak::findOrFail($terima_order->id_lapak);
    		$tokenLapak[] = $lapak->user->token;
    		
    		$namaLapak = $lapak->nama_usaha;
    		
            $notif->sendLapak($tokenLapak, $namaLapak ,"Orderan Baru Nih Untuk Lapak Kamu","Hi,", "ada");

		}
        
		$driver->update([
		        'status_order_driver' => '1'
		    ]);

		if ($terima_order->update($data)) {
			$out = [
				"message" => "success",
				"code" => 201
			];
		} else {
			$out = [
				"message" => "gagal",
				"code" => 404
			];
		}


		return response()->json($out, $out['code']);
	}
	
	public function cari_lapak_offline(Request $request){
	    
	    $lapak = Lapak::where('id_kecamatan1', $request->id_kecamatan)
	        ->orWhere('id_kecamatan2', $request->id_kecamatan)
	        ->orderBy('nama_usaha', 'ASC')
	        ->get();
	        
	   return response()->json([
            'Hasil Lapak' => $lapak
        ]);
	    
	}
	
	public function kecamatan(){
	    
	    
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();
        
        return response()->json([
            'Kecamatan Banyuwangi' => $kecamatan
        ]);
	} 
}




