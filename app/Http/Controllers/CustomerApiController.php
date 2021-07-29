<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Lapak;
use App\Driver;
use App\Order;
use App\OrderDetail;
use App\OrderPosting;
use App\Menu;
use App\Kategori;
use App\Posting;
use App\PostingLapak;
use App\Jastip;
use App\PromoOngkir;
use DB;
use App\Haversine;
use Carbon\Carbon;
use App\MenuDetail;
use App\Slideshow;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    
    private function HargaOngkir($jarak_final)
    {
        if ($jarak_final <= 2.1) {
              $ongkir = 7000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 8500;
            }elseif ($jarak_final <= 3.2 || $jarak_final <= 4.1) {
              $ongkir = 10000;
            }elseif ($jarak_final <= 4.2 || $jarak_final <= 5.1) {
              $ongkir = 12000;
            }elseif ($jarak_final <= 5.2 || $jarak_final <= 6.1) {
              $ongkir = 14000;
            }elseif ($jarak_final <= 6.2 || $jarak_final <= 7.1) {
              $ongkir = 16000;
            }elseif ($jarak_final <= 7.2 || $jarak_final <= 8.1) {
              $ongkir = 18000;
            }elseif ($jarak_final <= 8.2 || $jarak_final <= 9.1) {
              $ongkir = 20000;
            }elseif ($jarak_final <= 9.2 || $jarak_final <= 10) {
              $ongkir = 22000;
            }else{
              $ongkir = 'Jarak anda terlalu jauh';
            }
            return $ongkir;
    }
    private function HargaOngkirPosting($jarak_final)
    {
        if ($jarak_final <= 2.1) {
              $ongkir = 7000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 8500;
            }elseif ($jarak_final <= 3.2 || $jarak_final <= 4.1) {
              $ongkir = 10000;
            }elseif ($jarak_final <= 4.2 || $jarak_final <= 5.1) {
              $ongkir = 12000;
            }elseif ($jarak_final <= 5.2 || $jarak_final <= 6.1) {
              $ongkir = 14000;
            }elseif ($jarak_final <= 6.2 || $jarak_final <= 7.1) {
              $ongkir = 16000;
            }elseif ($jarak_final <= 7.2 || $jarak_final <= 8.1) {
              $ongkir = 18000;
            }elseif ($jarak_final <= 8.2 || $jarak_final <= 9.1) {
              $ongkir = 20000;
            }elseif ($jarak_final <= 9.2 || $jarak_final <= 10) {
              $ongkir = 22000;
            }else{
              $ongkir = 'Jarak anda terlalu jauh';
            }
            return $ongkir;
    }
    private function HargaOngkirJastip($jarak_final)
    {
        if ($jarak_final <= 2.1) {
              $ongkir = 6000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 7500;
            }elseif ($jarak_final <= 3.2 || $jarak_final <= 4.1) {
              $ongkir = 9000;
            }elseif ($jarak_final <= 4.2 || $jarak_final <= 5.1) {
              $ongkir = 11000;
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
    

    //ambil data menu untuk ditampilkan di beranda customer
    public function customer_get_menu_all(Request $request)
    {

        $menu = DB::table('menu')
        ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
        ->select('menu.*',  'lapak.latitude_lap','lapak.longitude_lap','lapak.nama_usaha') 
        ->where('menu.status', 'tersedia')
        ->where('lapak.status', '!=', 'bermasalah')
        ->get();

        $hitung = new Haversine();
        
        $data = [];
        foreach ($menu as $lokasi) {
            # code...  
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance( $request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap,$lokasi->longitude_lap,"K");
            $jarak_final = round($jarak,1);
            
            $ongkir = $this->HargaOngkir($jarak_final);

            $data[] = [
                'menu' => $lokasi,
                'jarak' => $jarak_final,
                'harga_diskon' => $lokasi->harga-$diskon,
                'status_lapak' => $lapak->status,
                'ongkir' => $ongkir
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 12);

        return response()->json([

            'Hasil Menu' => $datas->values()->all()
        ]);		
        
 	}

    public function customer_get_menu_diskon(Request $request)
    {

        $menu = DB::table('menu')
            ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
            ->select('menu.*',  'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.nama_usaha')
            ->where('menu.status', 'tersedia')
            ->where('lapak.status', 1)
            ->where('menu.diskon', '>', '0')
            ->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'harga_diskon' => $lokasi->harga - $diskon,
                'status_lapak' => $lapak->status,
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 12);

        return response()->json([
            'Hasil Menu' => $datas->values()->all()
        ]);
    }

    public function customer_get_menu_terdekat(Request $request)
    {

        $menu = DB::table('menu')
            ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
            ->select('menu.*',  'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.nama_usaha')
            ->where('menu.status', 'tersedia')
            ->where('lapak.status', 1)
            ->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'status_lapak' => $lapak->status,
                'harga_diskon' => $lokasi->harga - $diskon,
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);
        
        
        return response()->json([
            'Hasil Menu' => $datas->values()->all()
        ]);
    }
    
    public function customer_get_kategori()
	{
		$jenis = Kategori::all();
		return response()->json([
			'Hasil kategori' => $jenis
		]);
	}
    
    public function customer_get_menu_kategori(Request $request, $id_kategori)
    {
    
        $menu = DB::table('menu_detail')
            ->join('menu', 'menu_detail.id_menu', '=', 'menu.id')
            ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
            ->select('menu.*',  'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.nama_usaha')
            ->where('menu.status', 'tersedia')
            ->where('lapak.status', 1)
            ->where('menu_detail.id_kategori', $id_kategori)
            ->get();
        //return $menu;
        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'status_lapak' => $lapak->status,
                'harga_diskon' => $lokasi->harga - $diskon,
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([
            'Hasil Menu' => $datas->values()->all()
        ]);
    }
    
    
    public function customer_get_menu_jenis(Request $request)
    {

        $menu = DB::table('menu')
            ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
            ->select('menu.*',  'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.nama_usaha')
            ->where('lapak.status', 1)
            ->where('menu.status', 'tersedia')
            ->where('menu.jenis', $request->jenis)
            ->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            
            $lapak = lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'status_lapak' => $lapak->status,
                'harga_diskon' => $lokasi->harga - $diskon,
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([
            'Hasil Menu' => $datas->values()->all()
        ]);
    }
    
    
    public function customer_get_menu_lapak_terbaru(Request $request)
    {
        $lapak_terbaru = Lapak::select('id')
            ->where('lapak.status', '!=', 'bermasalah')
            ->orderBy('id','DESC')
            ->first();
            
        //return $lapak_terbaru;
        $hitung = new Haversine();
        $data = [];
            $menu = Menu::where('id_lapak',$lapak_terbaru->id)->where('status', 'tersedia')->orderBy('id','DESC')->get();
            //return $menu;
        foreach ($menu as $key => $value) {
            
           // return $menu;
            $lapak = Lapak::find($value->id_lapak);
            $diskon = $value->harga * ($value->diskon / 100);
           
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $value->latitude_lap, $value->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lapak->latitude_lap, $lapak->longitude_lap, "K");
           

            $data[] = [
                'menu' => $value,
                'jarak' => round($jarak, 1),
                'harga_diskon' => $value->harga - $diskon,
                'status_lapak' => $lapak->status,
                'total_orderan' => $value->total_orderan
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([

            'Hasil Menu' => $datas->values()->all()
        ]);
    }

    //fungsi untuk ambil semua posting driver 
    public function customer_get_posting_driver_all(Request $request)
    {
        setlocale(LC_TIME, 'nl_NL.utf8');
        Carbon::setLocale('id');

        $tgl = Carbon::now();

        $posting = Posting::orderBy('id', 'DESC')->get();
        $pos = null;

        foreach($posting as $value => $v){
            $posting = Posting::where('id', $v->id)->where('batas_durasi', '>', $tgl)->first();
            if($posting){
                $pos[] = $posting;
            }
        }

        $hitung = new Haversine();
        $data = [];
        
        if($pos != null) {
            
            $posting = $pos[0];
            $order_posting = OrderPosting::where('id_posting', $posting->id)->where('status_order', '>=', 4)->first();
            
            if($order_posting){
                $data = [];
            } else{
        
                foreach ($pos as $lokasi) {
                    $driver = Driver::find($lokasi->id_driver);
                    $lapak = Lapak::find($lokasi->id_lapak);
                    $nama_driver = User::find($driver->id_user);
                    $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_posting, $lokasi->longitude_posting, "K");
                    $lokasi['id_user'] = $driver->id_user;
                    
                    $jarak_final = round($jarak,1);
                    
                    $ongkir = $this->HargaOngkir($jarak_final);
                
                    $data[] = [
                        'menu' => $lokasi,
                        'jarak' => round($jarak, 1),
                        'nama_driver' => $nama_driver->nama,
                        'ongkir' => $ongkir
                    ];
                }
            }
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);
        
        return response()->json([
            'Hasil Posting' => $datas->values()->all()
        ]);
    }

    public function customer_get_menu_terlaris(Request $request)
    {
        $menu_terlaris = DB::table('order_detail')
            ->join('menu', 'order_detail.id_menu', '=', 'menu.id')
            ->select(DB::raw('id_menu, count(order_detail.id) as total_orderan'))
            ->groupBy('id_menu')
            ->where('menu.status', 'tersedia')
            ->orderBy('total_orderan', 'DESC')
            ->get();

        $hitung = new Haversine();
        $data=[];

        foreach ($menu_terlaris as $key => $value) {
            $menu = Menu::find($value->id_menu);
            $lapak = Lapak::find($menu->id_lapak);
            $diskon = $menu->harga * ($menu->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            $menu['nama_usaha'] = $lapak->nama_usaha;

            $data[] = [
                'menu' => $menu,
                'jarak' => round($jarak, 1),
                'harga_diskon' => $menu->harga - $diskon,
                'status_lapak' => $lapak->status,
                'total_orderan' => $value->total_orderan
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([
            'Hasil Menu' => $datas->values()->all()
        ]);
    }

    public function customer_get_menu_terbaru(Request $request)
    {
        $menu_terbaru = PostingLapak::select('id_lapak')
            ->distinct('id_lapak')->where('status', 'tersedia')->get();
            

        //return $menu_terbaru;
        $hitung = new Haversine();
        $data = [];
        foreach ($menu_terbaru as $key => $value) {
            $menu = DB::table('posting_lapak')
                ->join('lapak', 'posting_lapak.id_lapak', '=', 'lapak.id')
                ->select('posting_lapak.*','lapak.id_user','lapak.nama_usaha','lapak.nama_pemilik_usaha','lapak.alamat',
                'lapak.foto_usaha','lapak.foto_profile','lapak.foto_ktp','lapak.foto_umkm','lapak.foto_npwp','lapak.nomor_rekening','lapak.nama_pemilik_rekening',
                'lapak.status','lapak.status_tombol','lapak.latitude_lap','lapak.longitude_lap',
                'lapak.id_provinsi','lapak.id_kabupaten','lapak.id_kecamatan1','lapak.id_kecamatan2')
                ->where('id_lapak', $value->id_lapak)
                ->where('lapak.status', 1)
                ->where('posting_lapak.status', 'tersedia')
                ->orderBy('posting_lapak.id', 'DESC')
                ->take(3)
                ->get();
            // return $menu;
            foreach($menu as $value => $v){
        
                $lapak = Lapak::find($v->id_lapak);
                $diskon = $v->harga * ($v->diskon / 100);
                // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lapak->latitude_lap, $lapak->longitude_lap, "K");
                $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
                
                $v->nama_usaha = $lapak->nama_usaha;
                $v->foto_menu = $v->foto_posting_lapak;
                
                $data[] = [
                    'menu' => $v,
                    'jarak' => round($jarak, 1),
                    'harga_diskon' => $v->harga - $diskon,
                    'status_lapak' => $lapak->status,
                    // 'total_orderan' => $value->total_orderan
                ];
            }

        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([

            'Hasil Menu' => $datas->values()->all()
        ]);
    }

    //fungsi searching nama menu 
    public function customer_cari_menu(Request $request)
    {

        $cari = $request->nama_menu;

        $cari_menu = Menu::where('nama_menu', 'like', "%" . $cari . "%")
            ->where('status', 'tersedia')
            ->orderBy('id', 'DESC')
            ->get();
            
        $hitung = new Haversine();

         foreach ($cari_menu as $key => $value) {
            
            $lapak = Lapak::find($value->id_lapak);
            
            $diskon = $value->harga * ($value->diskon / 100);
           
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lapak->latitude_lap, $lapak->longitude_lap, "K");
           

            $data[] = [
                'menu' => $value,
                'jarak' => round($jarak, 1),
                'status_lapak' => $lapak->status,
                'harga_diskon' => $value->harga - $diskon
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 15);

        return response()->json([

            'Hasil Menu' => $datas->values()->all()
        ]);
    }

    //ambil data menu dari menu yang dipilih
    public function customer_get_detail_menu($id)
    {

        $get_detail_menu = Menu::where('id', $id)->get();

        return response()->json([

            'Hasil Detail Menu' => $get_detail_menu

        ]);
    }


    //ambil data detail lapak dari menu yang dipilih
    public function customer_get_detail_lapak($id_lapak)
    {

        $get_detail_lapak = Lapak::where('id', $id_lapak)->get();

        return response()->json([

            'Hasil Detail Lapak' => $get_detail_lapak

        ]);
    }

    //ambil semua data lapak yang terbaru 
    public function customer_get_lapak_terbaru()
    {
        $lapak_terbaru = Lapak::orderBy('id', 'DESC')
            ->where('lapak.status', '!=', 'bermasalah')
            ->limit(5)
            ->get();

        return response()->json([
            'Hasil data' => $lapak_terbaru
        ]);
    }

    //ambil data semua  menu dari lapak yang dipilih
    public function customer_get_menu_lapak(Request $request, $id_lapak)
    {

        $get_menu_lapak = Menu::where('id_lapak', $id_lapak)->where('status', 'tersedia')->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($get_menu_lapak as $lokasi) {
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $lokasi['nama_usaha'] = $lapak->nama_usaha;
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'status_lapak' => $lapak->status,
                'harga_diskon' => $lokasi->harga - $diskon,
            ];
        }

        return response()->json([

            'Hasil semua Menu dari lapak' => $data

        ]);
    }

    //get data profil sesuai user login
    public function customer_get_profil($id)
    {
        $user = User::where('id', $id)->where('role', 'customer')->first();
        $customer_get_profil = Customer::where('id_user', $id)->first();
        $customer_get_profil['nama'] = $user->nama;
        $customer_get_profil['email'] = $user->email;
        $customer_get_profil['role'] = $user->role;
        $customer_get_profil['no_telp'] = $user->no_telp;

        return response()->json([

            'Profile' => [$customer_get_profil]

        ]);
    }

    //update profil dari customer
    public function customer_update_profile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $customer = Customer::where('id_user', $id)->first();

        $data_user = [
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'token' => $request->token,
        ];

        $data = [
            'alamat' => $request->alamat,
        ];

        if ($request->foto_profile) {
            $nama_file = "Customer_Profile_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Customer/Profile/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
                $data['foto_profile'] = $nama_file;
            }
        }

        if ($request->foto_ktp) {
            $nama_file = "Customer_Ktp_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Customer/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
                $data['foto_ktp'] = $nama_file;
            }
        }

        if ($customer->update($data) && $user->update($data_user)) {
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
    
    public function customer_lihat_order($id_customer)
    {
        $order = Order::where('id_customer', $id_customer)->where('status_order', '>=', 2)->where('status_order','!=', 5)->orderBy('updated_at', 'DESC')->get();
        // $jastip = Jastip::where('id_customer', $id_customer)->orderBy('updated_at', 'DESC')->get();
        $jastip = DB::table('jastip')
            ->join('order', 'jastip.id_order', '=', 'order.id')
            ->select('jastip.*', 'order.id_lapak', 'order.jarak', 'order.kode_order', 'order.status_order')
            ->where('jastip.id_customer', $id_customer)
            ->where('order.status_order', '!=', 5)
            ->orderBy('updated_at', 'DESC')
            ->get();
        
        $order_posting = OrderPosting::where('id_customer', $id_customer)->where('status_order','!=', 5)->orderBy('updated_at', 'DESC')->get();
        
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
            $posting['ongkir'] = $val->ongkir;
            
        }
            $data_akhir = collect();
            $data_akhir->push($data, $data_jastip, $data_posting);
            $data_akhir = $data_akhir->collapse()->sortByDesc('tanggal_orderan')->values()->all();

        return response()->json([
            'Hasil' => $data_akhir
        ]);
    }
    
    public function customer_get_ongkir(Request $request, $id)
    {
        $hitung = new Haversine();
        
        $lapak = Lapak::find($id);
        $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
        $jarak_final = round($jarak, 1);

        $ongkir = $this->HargaOngkir($jarak_final);
        $ongkir_jastip = $this->HargaOngkirJastip($jarak_final);
        $data[] = [
            'jarak' => $jarak_final,
            'ongkir' => $ongkir,
            'ongkir_jastip' => $ongkir_jastip
            ]; 
        return response()->json([
            'Hasil Ongkir' => $data
        ]);
    }
  
    
    public function customer_get_posting_ongkir(Request $request, $id_posting)
    {
        $hitung = new Haversine();

        $posting = Posting::findOrFail($id_posting);
        $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $posting->latitude_posting, $posting->longitude_posting, "K");
        $jarak_final = round($jarak, 1);

        $ongkir = $this->HargaOngkirPosting($jarak_final);
        $data[] = [
            'jarak' => $jarak_final,
            'ongkir' => $ongkir
        ];
        return response()->json([
            'Hasil Ongkir' => $data
        ]);
    }

    public function slideshow()
    {
        $data = Slideshow::all();

        return response()->json([
            'Hasil Slideshow' => $data
        ]);
    }
    
    public function customer_cari_lapak(Request $request)
    {
        $lapak = Lapak::where('lapak.status', '!=', 'bermasalah')
            ->get();
        
        foreach($lapak as $value => $v){
            
            $hitung = new Haversine();
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $v->latitude_lap, $v->longitude_lap, "K");
            $data[] = [
                'id_lapak' => $v->id,
                'nama_usaha' => $v->nama_usaha,
                'jarak' => round($jarak, 1)
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->values()->forPage($request->page, 15);
        
        return response()->json([
            'Semua Lapak' => $datas
        ]);
    }
    
    public function customer_get_menu_with_kategori(Request $request,$jenis)
	{
		
		  $menu = DB::table('menu')
        ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
        ->select('menu.*',  'lapak.latitude_lap','lapak.longitude_lap','lapak.nama_usaha') 
        ->where('menu.status', 'tersedia')
        ->where('lapak.status', 1)
        ->where('jenis',$jenis)
        ->get();

        $hitung = new Haversine();
        
        $data = [];
        foreach ($menu as $lokasi) {
            # code...  
            $lapak = Lapak::find($lokasi->id_lapak);
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance( $request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap,$lokasi->longitude_lap,"K");
            $jarak_final = round($jarak,1);
            
            $ongkir = $this->HargaOngkir($jarak_final);

            $data[] = [
                'menu' => $lokasi,
                'jarak' => $jarak_final,
                'harga_diskon' => $lokasi->harga-$diskon,
                'status_lapak' => $lapak->status,
                'ongkir' => $ongkir
            ];
        }
        
        $datas = collect($data)->SortBy('jarak')->forPage($request->page, 12);

        return response()->json([

            'Hasil Menu' => $datas->values()->all()
        ]);	
	}
}




