<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Lapak;
use App\OrderDetail;
use App\Menu;
use DB;
use App\Haversine;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{

    //ambil data menu untuk ditampilkan di beranda customer
    public function customer_get_menu_all(Request $request){

    $menu = DB::table('menu')
        ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
        ->select('menu.*',  'lapak.latitude_lap','lapak.longitude_lap','lapak.nama_usaha') 
        ->get();

       

        $hitung = new Haversine();
      
        $data = [];
        foreach ($menu as $lokasi) {
            # code...
            //$customer = Customer::where('id_user',$id_user)->first();     
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap,$lokasi->longitude_lap,"K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak,1),
                'harga_diskon' => $lokasi->harga-$diskon,
            ];
        }

        return response()->json([

            'Hasil Menu' => $data
        ]);		
 	}


  //fungsi untuk ambil semua posting driver 
  public function customer_get_posting_driver_all(){


    $posting = DB::table('posting')
        ->join('driver', 'posting.id_driver', '=', 'driver.id')
        ->select('posting.*', 'driver.id_user') 
        ->get();
               

        $hitung = new Haversine();
      
        $data = [];
        foreach ($posting as $lokasi) {
            # code...
            //$customer = Customer::where('id_user',$id_user)->first();     
            //$diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_posting,$lokasi->longitude_posting,"K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak,1),
               
            ];
        }

        return response()->json([

            'Hasil Posting' => $data
        ]);   
  }


  public function customer_get_menu_terlaris(){

    $menu_terlaris = DB::table('order_detail')
    ->join('menu','order_detail.id_menu', '=' , 'menu.id')  
    ->select(DB::raw('id_menu, count(order_detail.id) as total_orderan'))
    ->groupBy('id_menu')
    ->orderBy('total_orderan','DESC')
    ->get();


       return response()->json([

            'Hasil Menu Terlaris' => $menu_terlaris
        ]);   
      
  }


  public function customer_get_menu_terbaru(){

    $menu_terbaru = Menu::orderBy('id','DESC')
          ->get();

    return response()->json([

            'Hasil Menu' => $menu_terbaru
        ]);
    
  }


 	//fungsi searching nama menu 
	public function customer_cari_menu(Request $request){

	    $cari = $request->customer_cari_menu;

	        $cari_menu = Menu::where('nama_menu','like',"%".$cari."%")
	        ->orderBy('id','DESC')
	        ->get();
	        
	        return response()->json([

	 			'Hasil Cari Menu' => $cari_menu

	 		]);
	}


 	//ambil data menu dari menu yang dipilih
	public function customer_get_detail_menu($id){

	 	$get_detail_menu = Menu::where('id',$id)->get();

	 		return response()->json([

	 			'Hasil Detail Menu' => $get_detail_menu

	 		]);
	}


	//ambil data detail lapak dari menu yang dipilih
	public function customer_get_detail_lapak($id_lapak){

	 	$get_detail_lapak = Lapak::where('id',$id_lapak)->get();

	 		return response()->json([

	 			'Hasil Detail Lapak' => $get_detail_lapak

	 		]);
	}


	//ambil data semua  menu dari lapak yang dipilih
	public function customer_get_menu_lapak($id_lapak){

	 	$get_menu_lapak = Menu::where('id_lapak',$id_lapak)->get();

	 		return response()->json([

	 			'Hasil semua Menu dari lapak' => $get_menu_lapak

	 		]);
	}

	//get data profil sesuai user login
	public function customer_get_profile($id){

        $customer_get_profil = Customer::where('id',$id)->get();

        return response()->json([

            'Profil' => $customer_get_profil

        ]);

    }


	//update profil dari customer
	public function customer_update_profile(Request $request, $id)
    {

    	$customer = Customer::where('id_user',$id)->first();

  //   	if ($request->foto_usaha) {
  //   		$nama_file="Usaha_".time()."jpeg";
  //   		$tujuan_upload = public_path().'/Customer/Foto_profile/';
  //   		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_usaha)))
  //   		{
  //   			$data=['foto_usaha' =>$nama_file];
  //   		}
  //   	}

  //   	if ($request->foto_profile) {
  //   		$nama_file="Usaha_".time()."jpeg";
  //   		$tujuan_upload = public_path().'/Customer/Foto_ktp/';
  //   		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_profile)))
  //   		{
  //   			$data=['foto_profile' =>$nama_file];
  //   		}
		// }

    	$data = [
    		'alamat' =>$request->alamat,
    		'foto_profile' =>$request->foto_profile,
    		'foto_ktp' =>$request->foto_ktp,
    		'longitude_cus' =>$request->longitude_cus,
    		'latitude_cus' =>$request->latitude_cus,
    		
    	];

    	 if ($customer->update($data)) {
            $out = [
                "message" => "update-profil_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "update-profil_failed",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);
    }


}
