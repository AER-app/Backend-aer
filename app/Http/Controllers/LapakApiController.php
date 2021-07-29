<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Menu;
use App\MenuDetail;
use App\Lapak;
use App\Driver;
use App\Customer;
use App\PostingLapak;
use App\User;
use App\Order;
use App\Jastip;
use App\PostingLapakDetail;
use App\Posting;
use App\Kategori;
use App\JadwalLapak;
use Image;
use Carbon\Carbon;
use DB;
use App\Haversine;


class LapakApiController extends Controller
{

	public function lapak_update(Request $request, $id_user)
	{
		$user = User::findOrFail($id_user);
		$lapak = Lapak::where('id_user', $id_user)->first();
		$data = [];

		if ($request->foto_usaha) {
			$nama_file = "Usaha_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
				$data['foto_usaha'] = $nama_file;
			}
		}
		
		if ($request->foto_profile) {
			$nama_file = "Usaha_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Profile/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
				$data['foto_profile'] = $nama_file;
			}
		}
		
		if ($lapak->update($data)) {
			$out = [
				"message" => "update-profil_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "update-profil_failed",
				"code"   => 400,
			];
		}

		return response()->json($out, $out['code']);
	}

	//menambahkan postingan pada role lapak
	public function lapak_tambah_posting(Request $request)
	{
		$str = Str::length($request->foto_menu);
		//dari android 2Mb 
		// Jika file gambar lebih dari 1.15 Mb 
		if ($str >= 2500000) {
			$pesan = "Foto terlalu besar";

            return $pesan;
            // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
		} else {
		    
		    $kategori = $request->kategori;

			$data = [
				'id_lapak' => $request->id_lapak,
				'deskripsi_menu' => $request->deskripsi_menu,
				'status' => $request->status,
				'id_menu' => $request->id_menu,
				'rating' => "0",
			];
			
			$menu = Menu::find($request->id_menu);
			$data['nama_menu'] = $menu->nama_menu;
			$data['harga'] = $menu->harga;
			$data['diskon'] = $menu->diskon;

			if ($request->foto_menu) {
				$foto_posting_lapak = Str::limit($request->foto_menu, 500000);
				$nama_file = "Lapak_Posting_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Lapak/Posting/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_posting_lapak))) {
					$data['foto_posting_lapak'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path() . '/Images/Lapak/Posting/Thumbnail/' . $nama_file);
			}

			$lastid = PostingLapak::create($data)->id;

			foreach ($kategori as $value => $v) {
    			$menu_detail = PostingLapakDetail::create([
    				'id_posting_lapak' => $lastid,
    				'id_kategori' => $v['id']
    			]);
            }

			if ($lastid) {
				$out = [
					"message" => "tambah-posting_success",
					"code"    => 201,
				];
			} else {
				$out = [
					"message" => "tambah-posting_failed",
					"code"   => 404,
				];
			}

			return response()->json($out, $out['code']);
		}
	}
	
	public function lapak_jadwal($id_lapak)
	{
		$jadwal_lapak = JadwalLapak::where('id_lapak', $id_lapak)->get();

		return response()->json([
			'Jadwal Lapak' => $jadwal_lapak
		]);
	}
	
	public function lapak_update_jadwal(Request $request, $id_jadwal)
	{
		$jadwal_lapak = JadwalLapak::find($id_jadwal);
		
		if($request->duaempatjam == 0){

    		$data = [
    			'status_buka' => $request->status_buka,
    			'24_jam' => 0,
    			'jam_buka' => $request->jam_buka,
    			'jam_tutup' => $request->jam_tutup,
    		];
    		
		}else{
		    
    		$data = [
    			'status_buka' => $request->status_buka,
    			'24_jam' => $request->duaempatjam,
    			'jam_buka' => 0,
    			'jam_tutup' => 0,
    		];
		}

		if ($jadwal_lapak->update($data)) {
			$out = [
				"message" => "update-jadwal_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "update-jadwal_failed",
				"code"   => 400,
			];
		}

		return response()->json($out, $out['code']);
	}

	public function lapak_tambah_menu(Request $request)
	{
		$str = Str::length($request->foto_menu);
		// Jika file gambar lebih dari 2.15 Mb 
		if ($str >= 2500000) {
			$pesan = "Foto terlalu besar";

            return $pesan;
            // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
		} else {
		    
		    $kategori = $request->kategori;

			$data = [
				'id_lapak' => $request->id_lapak,
				'nama_menu' => $request->nama_menu,
				'deskripsi_menu' => $request->deskripsi_menu,
				'harga' => $request->harga,
				'jenis' => $request->jenis,
				'status' => $request->status,
				'diskon' => $request->diskon,
				'rating' => 0,
			];


			if ($request->foto_menu) {
				$foto_menu = Str::limit($request->foto_menu, 500000);
				$nama_file = "Lapak_Menu_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Lapak/Menu/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_menu))) {
					$data['foto_menu'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path() . '/Images/Lapak/Menu/Thumbnail/' . $nama_file);
			}

			$lastid = Menu::create($data)->id;

            foreach ($kategori as $value => $v) {
    			$menu_detail = MenuDetail::create([
    				'id_menu' => $lastid,
    				'id_kategori' => $v['id']
    			]);
            }

			if ($lastid) {
				$out = [
					"message" => "tambah-menu_success",
					"code"    => 201,
				];
			} else {
				$out = [
					"message" => "tambah-menu_failed",
					"code"   => 404,
				];
			}

			return response()->json($out, $out['code']);
		}
	}

	public function lapak_get_menu($id)
	{
		$lapak =  Lapak::where('id_user', $id)->first();

		$get_menu = Menu::where('id_lapak', $lapak->id)->where('status', 'tersedia')->orderBy('id', 'DESC')->get();

		// $get_menu = lapak::withCount('menu')->orderBy('lapak_count', 'DESC')->get();

		return response()->json([

			'Hasil Menu' => $get_menu

		]);
	}
	
	
	
	public function lapak_update_menu(Request $request, $id)
	{
			$menu = Menu::find($id);
			
			$data = [
				'id_lapak' => $menu->id_lapak,
				// 'nama_menu' => $request->nama_menu,
				// 'deskripsi_menu' => $request->deskripsi_menu,
				// 'harga' => $request->harga,
				'diskon' => $request->diskon,
				// 'rating' => $request->rating,
			];

			// $menu_detail = MenuDetail::create([
			// 	'id_menu' => $lastid,
			// 	'id_kategori' => $request->id_kategori
			// ]);

			if ($menu->update($data)) {
				$out = [
					"message" => "update-menus_success",
					"code"    => 201,
				];
			} else {
				$out = [
					"message" => "update-menu_failed",
					"code"   => 404,
				];
			}

			return response()->json($out, $out['code']);
	}

	public function lapak_get_profile($id)
	{
	    
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $tgl = Carbon::now();
        
        // $lapak = Lapak::all();
        
        $jam_sekarang = date_format($tgl, 'H:i:s');
        
        $hari_sekarang = Carbon::now()->isoFormat('dddd');
		
		$user = User::where('id', $id)->where('role', 'lapak')->first();
		$get_profil = Lapak::where('id_user', $id)->first();
         
		$get_profil['nama'] = $user->nama;
		$get_profil['email'] = $user->email;
		$get_profil['no_telp'] = $user->no_telp;
		$get_profil['role'] = $user->role;
        
		return response()->json([
			'Profile' => [$get_profil]
		]);
	}
    
    
    
    
	public function lapak_get_kategori()
	{
		$jenis = Kategori::all();
		return response()->json([
			'Hasil Menu' => $jenis
		]);
	}

	//mengambil menu pada role lapak
	public function lapak_get_posting_lapak($id)
	{

		$get_posting_lapak = PostingLapak::where('id_lapak', $id)->get();

		return response()->json([

			'Hasil Menu' => $get_posting_lapak

		]);
	}
	
	public function lapak_update_posting(Request $request, $id)
	{

		$posting = PostingLapak::find($id);
	
		$data = [
			'id_lapak' => $posting->id_lapak,
// 			'nama_menu' => $request->nama_menu,
// 			'deskripsi_menu' => $request->deskripsi_menu,
// 			'harga' => $request->harga,
// 			'status' => $request->status,
			'diskon' => $request->diskon,
// 			'rating' => "0",
		];
		
		if ($request->foto_menu) {
				$foto_menu = Str::limit($request->foto_menu, 500000);
				$nama_file = "Lapak_Menu_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Lapak/Posting/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_menu))) {
					$data['foto_menu'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path() . '/Images/Lapak/Posting/Thumbnail/' . $nama_file);
		}

		if ($posting->update($data)) {
			$out = [
				"message" => "update-posting_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "update-posting_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}

	public function lapak_delete_posting($id)
	{
		$posting_lapak = PostingLapak::findOrFail($id);
		
		if ($posting_lapak->foto_posting_lapak) {
			File::delete('Images/Lapak/Posting/Normal/' . $posting_lapak->foto_posting_lapak);
			File::delete('Images/Lapak/Posting/Thumbnail/' . $posting_lapak->foto_posting_lapak);
		}
		$pld = PostingLapakDetail::where('id_posting_lapak', $posting_lapak->id)->get();
        
		$del = $pld->each->delete();
		$del_pos = $posting_lapak->delete();

		if ($del && $del_pos) {
			$out = [
				"message" => "delete-success",
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
	
	public function lapak_delete_menu($id)
	{
		$menu = Menu::findOrFail($id);
		if ($menu->foto_menu) {
			File::delete('Images/Lapak/Menu/Normal/' . $menu->foto_menu);
			File::delete('Images/Lapak/Menu/Thumbnail/' . $menu->foto_menu);
		}
		
		$delete_menu_detail = MenuDetail::where('id_menu', $menu->id)->get();
		
        foreach($delete_menu_detail as $value => $v){
            $detail_menu = MenuDetail::find($v->id);
            $detail_menu->delete();
        }
		
		$posting_lapak = PostingLapak::where('id_menu', $id)->get();
		
		
        foreach($posting_lapak as $value => $v){
			$p = PostingLapak::find($v->id);
			if ($p->foto_menu) {
				File::delete('Images/Lapak/Posting/Normal/' . $p->foto_posting_lapak);
				File::delete('Images/Lapak/Posting/Thumbnail/' . $p->foto_posting_lapak);
			}
            
            $p->delete();
        }

		$del_menu = $menu->update([
				'status' => 'tidak ada'
			]);

		if ($del_menu) {
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
	
	public function lapak_lihat_order($id_lapak)
    {
       
        
        $order = Order::where('id_lapak', $id_lapak)->where('status_order', '<', 3)
        ->whereNotNull('id_driver')
        ->orderBy('updated_at', 'DESC')
        ->get();
       
        
        //return $order;
        
        $data = [];
			
        foreach ($order as $order => $val) {
            $data[] = [
                'order' => $val,
            ];
            
            $driver = Driver::where('id', $val->id_driver)->with('user')->first();
            $customer = Customer::where('id', $val->id_customer)->with('user')->first();
            $total_tanpa_ongkir = $val->total_harga - $val->ongkir;
            
            $val['nama_customer'] = $customer->user->nama;
            $val['nama_driver'] = $driver->user->nama;
            $val['no_telp_driver'] = $driver->user->no_telp;
            $val['total_order_tanpa_ongkir'] = $total_tanpa_ongkir;
            
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;

            $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select('menu.nama_menu', 'menu.harga','menu.diskon','order_detail.*')
                ->where('id_order', $val->id)
                ->get();
                
            $tot_harga_jastip = Jastip::where('id_order', $val->id)->sum('total_harga');
            $ongkir = Jastip::where('id_order', $val->id)->sum('ongkir');
            
            $tot_harga_jastip_t_ongkir = $tot_harga_jastip - $ongkir;
            
            $val['total_jastip_tanpa_ongkir'] = $tot_harga_jastip_t_ongkir;
            $val['total_harga_semua_tanpa_ongkir'] = $total_tanpa_ongkir + $tot_harga_jastip_t_ongkir;
            
            $menu_jastip = [];
            
            $order_jastip = DB::table('jastip_detail')
                ->join('jastip', 'jastip_detail.id_jastip', '=', 'jastip.id')
                ->join('menu', 'jastip_detail.id_menu', '=', 'menu.id')
                ->join('order', 'jastip.id_order', '=', 'order.id')
                ->select('menu.nama_menu', 'menu.harga', 'menu.diskon', 'jastip_detail.*')
                ->where('jastip.id_order', $val->id)
                ->get();
            
            foreach ((array)$order_jastip as $key => $value) {
                
                $menu_jastip = $value;
            }
                
            $val['detail_jastip'] = $menu_jastip;

            foreach ((array)$order_detail as $key => $value) {
                // $menu = Menu::find($value->id_menu);
                $val['detail_orderan'] = $value;
            }
        }

        return response()->json([
            'Hasil' => $data
        ]);
    }
    
    
    public function lapak_lihat_jastip($id_driver)
    {
       
        
        // $order = Order::where('id_lapak', $id_lapak)->where('status_order', '<', 3)
        // ->whereNotNull('id_driver')
        // ->orderBy('updated_at', 'DESC')
        // ->first();
        
        // $jastip_lapak = DB::table('jastip_detail')
        // ->join('jastip','jastip_detail.id_jastip','=','jastip.id')
        // ->join('order','jastip.id_order','=','order.id')
        // ->join('menu','jastip_detail.id_menu','=','menu.id')
        // ->select('jastip.*','menu.nama_menu')
        // ->where('jastip.id_order',275)
        // ->get();
        
        
        $jastip_lapak = Jastip::where('id_driver', $id_driver)
        ->orderBy('updated_at', 'DESC')
        ->get();
    
        
    
        
        $data = [];
			
        foreach ($jastip_lapak as $jastip_lapak => $val) {
            $data[] = [
                'Jastip' => $val,
            ];
            
            $driver = Driver::where('id', $val->id_driver)->with('user')->first();
            $customer = Customer::where('id', $val->id_customer)->with('user')->first();
            $total_tanpa_ongkir = $val->total_harga - $val->ongkir;
            
            $val['nama_customer'] = $customer->user->nama;
            $val['nama_driver'] = $driver->user->nama;
            $val['no_telp_driver'] = $driver->user->no_telp;
            $val['total_tanpa_ongkir'] = $total_tanpa_ongkir;
            
            $tanggal_orderan = Carbon::parse($val->created_at)->isoFormat('D-M-Y H:m:s');
            $val['tanggal_orderan'] = $tanggal_orderan;

            $jastip_detail = Menu::leftJoin('jastip_detail', function ($join) {
                $join->on('menu.id', '=', 'jastip_detail.id_menu');
            })
                ->select('menu.nama_menu', 'jastip_detail.*')
                ->where('id_jastip', $val->id)
                ->get();

            foreach ((array)$jastip_detail as $key => $value) {

                // $menu = Menu::find($value->id_menu);
                $val['detail_Jastip'] = $value;
            }
        }

        return response()->json([
            'Hasil' => $data
        ]);
    }
    
    
	public function lapak_aktif(Request $request, $id_lapak)
	{
		$lapak = Lapak::find($id_lapak);

        if($request->status_lapak == 0){
    		$data = [
    			'status' => $request->status_lapak,
    			'status_tombol' => $request->status_lapak,
    		];
        }
        else{
    		$data = [
    			'status_tombol' => $request->status_lapak,
    		];
        }

		$update = $lapak->update($data);

		if ($update) {
			$out = [
				"message" => "lapak-status_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "lapak-status_failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);
	}
	
	public function lapak_tambah_jadwal($id_lapak)
	{
	     $hari = (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']);
                foreach($hari as $h){
                $data_jadwal = [ 
                    'hari' => $h,
                    'id_lapak' => $id_lapak,
                    'status_buka' => 0
                ];
                
                    JadwalLapak::create($data_jadwal);
                    
                }
                
        if ($hari) {
			$out = [
				"message" => "lapak-tambah-jadwal-success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);
	
	}
	
	
	
	public function lapak_get_detail_lapak(Request $request,$id_lapak)
	{
	    
	//	$lapak =  Lapak::where('id', $id_lapak)->first();
         $lapak = DB::table('menu')
            ->join('lapak','menu.id_lapak', '=', 'lapak.id')
            ->select('lapak.id','lapak.nama_usaha','lapak.foto_usaha','lapak.status')
            ->where('lapak.id', $id_lapak)
            ->first();
            
		if(!$lapak){
			$lapak = Lapak::find($id_lapak);
			return response()->json([
				'Detail Lapak' => [
					'id' => $lapak->id,
					'nama_usaha' => $lapak->nama_usaha,
					'foto_usaha' => $lapak->foto_usaha,
					'status' => $lapak->status,
				],
				'Hasil Menu' => [],
				
			]);
		}

		$get_detail = Menu::where('id_lapak', $lapak->id)->where('status', 'tersedia')->orderBy('id', 'DESC')->get();

		$hitung = new Haversine();
        $hasil_menu = [];
        foreach ($get_detail as $detail => $v) {
            
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $v->latitude_lap, $v->longitude_lap, "K");
            $diskon = $v->harga * ($v->diskon / 100);
            $jarak_final = round($jarak,1);
            $v['harga_diskon'] = $v->harga - $diskon;
            $v['jarak'] = $jarak_final;
            $hasil_menu[]=$v;
        }

		return response()->json([
            'Detail Lapak' => $lapak,
			'Hasil Menu' => $hasil_menu,
		]);
	}
}
