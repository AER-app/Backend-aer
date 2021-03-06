<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Driver;
use App\Order;
use App\OrderOffline;
use App\OrderPosting;
use App\Posting;
use App\Jastip;
use Image;
use App\Lapak;
use App\Menu;
use App\Customer;
use App\CustomerOffline;
use DB;
use Carbon\Carbon;
use App\Notif;

class DriverApiController extends Controller
{
    public function index()
    {
    }

    public function update(Request $request, $id_user)
    {
		$data_user = [
			'nama' => $request->nama,
		];

		$data = [
			'alamat' => $request->alamat,
// 			'jenis_motor' => $request->jenis_motor,
// 			'warna_motor' => $request->warna_motor,
// 			'plat_nomor' => $request->plat_nomor,
			'latitude_driver' => $request->latitude_driver,
			'longitude_driver' => $request->longitude_driver,
		];

        $driver = Driver::where('id_user', $id_user)->first();
		$user = User::find($id_user);
		
		if ($request->profile) {
			$nama_file = "Profile_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Profile/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->profile))) {
				$data ['profile'] = $nama_file;
			}
		}
		if ($request->foto_ktp) {
			$nama_file = "Ktp_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Ktp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
				$data ['foto_ktp'] = $nama_file;
			}
		}
		if ($request->foto_kk) {
			$nama_file = "Kk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Kk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_kk))) {
				$data ['foto_kk'] = $nama_file;
			}
		}

		if ($request->foto_sim) {
			$nama_file = "Sim_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Sim/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_sim))) {
				$data ['foto_sim'] = $nama_file;
			}
		}

		if ($request->foto_stnk) {
			$nama_file = "Stnk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Stnk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_stnk))) {
				$data ['foto_stnk'] = $nama_file;
			}
		}
		
		if ($request->foto_motor) {
			$nama_file = "Stnk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Motor/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_motor))) {
				$data ['foto_motor'] = $nama_file;
			}
		}

		if ($driver->update($data) && $user->update($data_user)) {
			return response()->json([
				"message" => "success"
			], Response::HTTP_CREATED);
		} else {
			return response()->json([
				"message" => "failed",
			], Response::HTTP_BAD_REQUEST);
		}
    }

    public function profile($id_user)
    {
        $user = User::where('id', $id_user)->where('role', 'driver')->first();
        $driver = Driver::where('id_user', $user->id)->first();
        $driver['nama'] = $user->nama;
        $driver['role'] = $user->role;
        $driver['email'] = $user->email;
        $driver['no_telp'] = $user->no_telp;
		
        return response()->json([
            'driver' => [$driver]
        ]);
    }

    public function get_posting_driver($id_user)
    {
        $user = Driver::where('id_user', $id_user)->first();
        $posting = Posting::where('status', 1)->where('id_driver', $user->id)->orderBy('id', 'DESC')->get();
        
        if(count($posting)==0){
            $posting_driver = [];
        } else {
            foreach ($posting as $key => $value) {
    			$nama = User::find($user->id_user);
    			$posting_driver[] = [
    			    "nama" => $nama->nama,
    			    "id" => $value->id,
    			    "judul_posting" => $value->judul_posting,
    			    "deskripsi_posting" => $value->deskripsi_posting,
    			    "foto_posting" => $value->foto_posting,
    			    "harga" => $value->harga,
    			    "status" => $value->status,
    			    "durasi" => $value->durasi,
    			    "id_driver" => $value->id_driver,
    			    "longitude_posting" => $value->longitude_posting,
    			    "latitude_posting" => $value->latitude_posting,
    			];
    		}
        }
		
            return response()->json([
                'posting_driver' => $posting_driver
    		], Response::HTTP_OK);
    }

    public function driver_posting(Request $request, $id_user)
    {
		$str = Str::length($request->foto_posting);
		// Jika file gambar lebih dari 1.15 Mb 
		if ($str >= 2500000) {
			$pesan = "Foto terlalu besar";

            return $pesan;
		} else {

			$driver = Driver::where('id_user', $id_user)->first();
			
			setlocale(LC_TIME, 'nl_NL.utf8');
            Carbon::setLocale('id');
    
            $tgl = Carbon::now();
			$batas_durasi = $tgl->addMinutes($request->durasi);
			$data = [
				'judul_posting' => $request->judul_posting,
				'deskripsi_posting' => $request->deskripsi_posting,
				'harga' => $request->harga,
				'status' => $request->status,
				'durasi' => $request->durasi,
				'batas_durasi' => $batas_durasi,
				'latitude_posting' => $request->latitude_posting,
				'longitude_posting' => $request->longitude_posting,
				'id_driver' => $driver->id,
			];

			if ($request->foto_posting) {
				$foto_posting = Str::limit($request->foto_posting, 500000);
				$nama_file = "Driver_Posting_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Driver/Posting/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_posting))) {
					$data ['foto_posting'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path().'/Images/Driver/Posting/Thumbnail/'.$nama_file);

			}
			

			if (Posting::create($data) && $driver->update(['status_order_driver' => 1])) {
				return response()->json([
					"message" => "success"
				], Response::HTTP_CREATED);
			} else {
				return response()->json([
					"message" => "failed",
				], Response::HTTP_BAD_REQUEST);
			}
		}

    }

	public function driver_delete_posting($id)
	{
		$posting_driver = Posting::findOrFail($id);
		if ($posting_driver->foto_posting) {
			File::delete('Images/Driver/Posting/Normal/'.$posting_driver->foto_posting);
			File::delete('Images/Driver/Posting/Thumbnail/'.$posting_driver->foto_posting);
		}

		if ($posting_driver->update(['status' => 0])) {
			$out = [
				"message" => "delete-menu_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "delete-menu_failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);

	}
	
	public function driver_lihat_order($id_driver)
	{
		$order = Order::where('id_driver', $id_driver)->where('status_order', '!=', '5')
		        ->orderBy('updated_at', 'DESC')
		        ->get();
		$order_offline = OrderOffline::where('id_driver', $id_driver)->where('status_order', '!=', '5')->where('status_order', '!=', '0')
		        ->orderBy('updated_at', 'DESC')
		        ->get();
// 		$order_posting = OrderPosting::where('id_driver', $id_driver)->orderBy('updated_at', 'DESC')->get();
        $order_posting = OrderPosting::where('id_driver', $id_driver)
              ->where('status_order', '!=', '5')
              ->select([
              // This aggregates the data and makes available a 'count' attribute
              DB::raw('count(id_posting) as `count`'), 
              // This throws away the timestamp portion of the date
              DB::raw('id_posting as id_posting')
            // Group these records according to that day
            ])->groupBy('id_posting')
            ->orderBy('id_posting', 'Desc')
            // And restrict these results to only those created in the last week
            // ->where('created_at', '>=', Carbon\Carbon::now()->subWeeks(1))
            ->get();
            
		$data = [];
        $data_posting = [];
        $data_order_offline = [];
        foreach ($order as $order => $val) {
            $data[] = $val;
            
            $lapak = Lapak::findOrFail($val->id_lapak);
            $customer = Customer::findOrFail($val->id_customer);

            $val['nama_usaha'] = $lapak->nama_usaha;
            $val['no_telp_lapak'] = $lapak->user->no_telp;
            $val['foto_profile_lapak'] = $lapak->foto_profile;
            $val['nama_customer'] = $customer->user->nama;
            $val['no_telp_customer'] = $customer->user->no_telp;
            $val['foto_profile_customer'] = $customer->foto_profile;
    		$val['total_harga_t_ongkir'] = $val->total_harga - $val->ongkir;
    		
    		$tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;
            
          
            
            $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select( 'order_detail.*','menu.nama_menu')
                ->where('id_order', $val->id)
                ->get();
            // $val['no_telp_customer_2'] = $order_detail['no_telp'];

            foreach ((array)$order_detail as $key => $value) {

                // $menu = Menu::find($value->id_menu);
                $val['detail_orderan'] = $value;
            }
        }
		$data_akhir = $data;

        foreach($order_posting as $order => $val){
            $order_pos = OrderPosting::where('id_posting', $val->id_posting)->first();
            $data_posting[] = $order_pos;
            $tanggal_orderan = Carbon::parse($order_pos->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;

            // $customer = Customer::where('id', $val->id_customer)->first();
            // $val['nama_customer'] = $customer->user->nama;
            // $val['no_telp_customer'] = $customer->user->no_telp;
            // $val['foto_profile_customer'] = $customer->foto_profile;
            
    		$order_pos['total_harga_t_ongkir'] = $order_pos->total_harga - $order_pos->ongkir;
            $posting = Posting::find($order_pos->id_posting);
            $order_pos['nama_menu'] = $posting->judul_posting;
            $order_pos['harga_menu'] = $posting->harga;
            
            $or_posting = DB::table('order_posting')
                    ->join('customer', 'order_posting.id_customer', '=', 'customer.id')
                    ->join('users', 'customer.id_user', '=', 'users.id')
                    ->join('posting', 'order_posting.id_posting', '=', 'posting.id')
                    ->select('order_posting.*', 'users.nama', 'users.no_telp','posting.judul_posting', 'posting.harga')
                    ->where('id_posting', $val->id_posting)
                    ->get();
            
            foreach ((array)$or_posting as $key => $value) {
                
                $order_pos['detail_orderan'] = $value;
                // $menu = Menu::find($value->id_menu);
            }
            
        }

        foreach($order_offline as $order => $val){
            
            $data_order_offline[] = $val;
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $customer_offline = CustomerOffline::find($val->id_customer_offline);
            $val['tanggal_orderan'] = $tanggal_orderan;
            
            $val['no_telp_customer'] = $customer_offline->no_telp;
            $val['nama_customer'] = $customer_offline->nama;
            
        }
        
            $data_akhir = collect();
            $data_akhir->push($data, $data_posting, $data_order_offline);
            $data_akhir = $data_akhir->collapse()->sortByDesc('tanggal_orderan')->values()->all();

        return response()->json([
            'Hasil' => $data_akhir
        ]);
	}
	
	public function driver_aktif(Request $request, $id_driver)
	{
		$driver = Driver::findOrFail($id_driver);

		$data = [
			'status_driver' => $request->status_driver,
		];

		$update = $driver->update($data);

		if ($update) {
			$out = [
				"message" => "driver-status_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "driver-status_failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);
	}

	public function driver_update_lokasi(Request $request, $id_driver)
	{
		$driver = Driver::findOrFail($id_driver);

		$data = [
			'latitude_driver' => $request->latitude_driver,
			'longitude_driver' => $request->longitude_driver,
		];
		
// 		return $driver;
		
		$this->cek_order_driver($id_driver);

		$update = $driver->update($data);
		

		if ($update) {
			$out = [
				"message" => "driver-lokasi_berubah_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "driver-lokasi_berubah_failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);
	}
	
	public function driver_tutup_jastip($id_order)
	{
		$order = Order::find($id_order);
		
		$customer = Customer::find($order->id_customer);
		$tokenCus[] = $customer->user->token;
		$namaCustomer = $customer->user->nama;
		$judul = "Haii $namaCustomer";
		
		$notif = new Notif();
		
		$jastip = Jastip::where('id_order', $id_order)->get();
		
		$notif->sendCustomer($tokenCus, $namaCustomer ,"Orderanmu sedang diantar", $judul,"ada");
		
		foreach($jastip as $val => $value){

		    $jas = Jastip::find($value->id);
    		$customer_jas = Customer::find($value->id_customer);
    		$tokenCus_jas[] = $customer_jas->user->token;
    		$namaCustomer_jas = $customer_jas->user->nama;
    		$judul_jas = "Haii $namaCustomer_jas";
		    
		    $jas->update([
		            'status_order' => 4
		        ]);
		        
    		$notif->sendCustomer($tokenCus_jas, $namaCustomer_jas ,"Orderan Jastipmu sedang diantar", $judul_jas,"ada");
		}

		$order->update([
			'status_order' => 4,
		]);

		return response()->json([
			'message' => 'success'
		]);
	}
	
	public function driver_antar_order_posting($id_posting)
	{
		$order = OrderPosting::where('id_posting', $id_posting)->get();
		
		foreach($order as $value => $v){
		    
		    $order_posting = OrderPosting::find($v->id);
		    
    		$customer = Customer::find($v->id_customer);
    		$tokenCus[] = $customer->user->token;
    		$namaCustomer = $customer->user->nama;
    		$judul = "Haii.... !!";
    		
    		$notif = new Notif();
    		
    		$notif->sendCustomer($tokenCus, $namaCustomer ,"Orderan Postingmu sedang diantar", $judul,"ada");
    
    		$order_posting->update([
    			'status_order' => 4,
    		]);
		}

		return response()->json([
			'message' => 'success'
		]);
	}
	
	public function driver_antar_order_order_offline($id_order_offline)
	{
		$order = OrderOffline::find($id_order_offline);
		
		$order->update([
			'status_order' => 4,
		]);
	
		return response()->json([
			'message' => 'success'
		]);
	}
	
	private function cek_order_driver($id_driver)
	{
	    $order = Order::where('id_driver', $id_driver)->where('status_order', '!=', 5)->get();
	    $jastip = Jastip::where('id_driver', $id_driver)->where('status_order', '!=', 5)->get();
	    $order_posting = OrderPosting::where('id_driver', $id_driver)->where('status_order', '!=', 5)->get();
	    $order_offline = OrderOffline::where('id_driver', $id_driver)->where('status_order', '!=', 5)->get();
	    
	    if(count($order) == 0 && count($jastip) == 0 && count($order_posting) == 0 && count($order_offline) == 0){
	        
	        $driver = Driver::find($id_driver);
	        $driver->update([
	                'status_order_driver' => 0
	            ]);
	    }
	    
	}
	
}
