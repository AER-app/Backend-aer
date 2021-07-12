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

class OrderOfflineController extends Controller
{
    
    public function dashboard()
    {
        
        $total_driver = Driver::all()->count();
        $total_customer = CustomerOffline::all()->count();
        $total_order = OrderOffline::all()->count();

        return view('admin.order_offline.dashboard', compact('total_driver', 'total_customer', 'total_order'));
    }
    
    public function index(){
        
        $data = OrderOffline::orderBy('id', 'DESC')->get();
        
        return view('admin.order_offline.index', compact('data'));
    }
    
    public function autocomplete(Request $request)
    {
        
        $q = "";
        if ($request->has('q')) {
            $query = $request->q;
            
            $lapak_offline = LapakOffline::select('nama_usaha')->where('nama_usaha', 'like', '%' .$query. '%')->get();
            $data_1 = array();
                foreach($lapak_offline as $hsl){
                    
                    $data_1[] = $hsl->nama_usaha;
                }
            $lapak = Lapak::select('nama_usaha')->where('nama_usaha', 'like', '%' .$query. '%')->get();
                $data_2 = array();
                foreach($lapak as $hsl2){
                    
                    $data_2[] = $hsl2->nama_usaha;
                }
            $data_akhir = collect();
            $data_akhir->push($data_1, $data_2);
            $data_akhir = $data_akhir->collapse()->values()->all();
        }
        
        return response()->json($data_akhir);
    }
    
    public function create(Request $request)
    {
        if($request->no_hp){
            $customer = CustomerOffline::where('no_telp', $request->no_telp)->first();
        } else{
            $customer = null;
        }

        return view('admin.order_offline.tambah', compact('customer'));
    }
    
    public function store(Request $request)
    {
        $customer_offline = CustomerOffline::where('no_telp', $request->no_telp)->first();
        
        if($request->input('CariNoTelp')){
            
            $no_telp = $request->no_telp;
            
            if($customer_offline){
                
                return back()
                    ->with('no_telp', $no_telp)
                    ->with('nama_customer', $customer_offline->nama);
            }else{
                return back()
                    ->with('error', 'Nomer Handphone belum terdaftar')
                    ->with('no_telp', $no_telp);
            }
        }
        
        if($request->input('HitungJarak')){
            
            $hitung = new Haversine();
            
            $lapak = Lapak::where('nama_usaha', $request->nama_lapak)->first();
            
            $lapak_offline = LapakOffline::where('nama_usaha', $request->nama_lapak)->first();
            
            $latitude_lap = $request->latitude_lap;
            $longitude_lap = $request->longitude_lap;
            
            if($lapak){
                $latitude_lap = $lapak->latitude_lap;
                $longitude_lap = $lapak->longitude_lap;
            } elseif($lapak_offline){
                $latitude_lap = $lapak_offline->latitude_lap;
                $longitude_lap = $lapak_offline->longitude_lap;
            }
            
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $latitude_lap, $longitude_lap, "K");
            $jarak_final = round($jarak, 1);
    
            $ongkir = $this->HargaOngkir($jarak_final);
            return back()
                ->with('no_telp', $request->no_telp)
                ->with('latitude_cus', $request->latitude_cus)
                ->with('longitude_cus', $request->longitude_cus)
                ->with('latitude_lap', $latitude_lap)
                ->with('longitude_lap', $longitude_lap)
                ->with('nama_customer', $request->nama_customer)
                ->with('nama_lapak', $request->nama_lapak)
                ->with('catatan', $request->catatan)
                ->with('jarak', $jarak_final)
                ->with('ongkir', $ongkir);
        }
        
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
        
        $lapak = Lapak::where('nama_usaha', $request->nama_lapak)->first();
        
        $lapak_offline = LapakOffline::where('nama_usaha', $request->nama_lapak)->first();
        
        if(!$lapak_offline && !$lapak){
            $data_lap = [
                'nama_usaha' => $request->nama_lapak,
                'latitude_lap' => $request->latitude_lap,
                'longitude_lap' => $request->longitude_lap,
            ];
            
            LapakOffline::create($data_lap);
        }
        
        $latitude_lap = $request->latitude_lap;
        $longitude_lap = $request->longitude_lap;
        $id_lapak = null;
        if($lapak){
            $id_lapak = $lapak->id;
        }
        
        $data = [
            'id_customer_offline' => $lastid_cus,
            'latitude_cus' => $request->latitude_cus,
            'longitude_cus' => $request->longitude_cus,
            'latitude_lap' => $latitude_lap,
            'longitude_lap' => $longitude_lap,
            'nama_lapak' => $request->nama_lapak,
            'id_lapak' => $id_lapak,
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

		return redirect()->route('kelola.order-offline')->with('success', 'Orderan Offline Berhasil ditambahkan');
        
    }
    
    public function order_offline_delete($id)
    {
        $order_offline = OrderOffline::find($id);

        $order_offline->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
    
    private function HargaOngkir($jarak_final){
        if ($jarak_final <= 2.1) {
              $ongkir = 7000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 9000;
            }elseif ($jarak_final <= 3.2 || $jarak_final <= 4.1) {
              $ongkir = 11000;
            }elseif ($jarak_final <= 4.2 || $jarak_final <= 5.1) {
              $ongkir = 13000;
            }elseif ($jarak_final <= 5.2 || $jarak_final <= 6.1) {
              $ongkir = 15000;
            }elseif ($jarak_final <= 6.2 || $jarak_final <= 7.1) {
              $ongkir = 17000;
            }elseif ($jarak_final <= 7.2 || $jarak_final <= 8.1) {
              $ongkir = 19000;
            }elseif ($jarak_final <= 8.2 || $jarak_final <= 9.1) {
              $ongkir = 21000;
            }elseif ($jarak_final <= 9.2 || $jarak_final <= 10) {
              $ongkir = 23000;
            }else{
              $ongkir = 'Jarak anda terlalu jauh';
            }
            return $ongkir;
    }
    
    
    public function order_offline_cek_diterima_driver(Request $request)
	{
	    $order = OrderOffline::find($request->id_order_offline);
	    
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
	
		//driver menerima orderan
	public function order_offline_driver_terima_order(Request $request, $id_order_offline)
	{
        
        $terima_order = OrderOffline::where('id_driver', 'null')->where('id',$id_order_offline)->first();
    
        setlocale(LC_TIME, 'nl_NL.utf8');
        Carbon::setLocale('id');
        $tgl = Carbon::now();
        
		$data = [
			'id_driver' => $request->id_driver,
			'status' => '2',
		];
		
		$driver = Driver::findOrFail($request->id_driver);
		
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
				"message" => "vailed",
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




