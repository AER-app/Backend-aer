<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Lapak;
use App\Driver;
use App\Order;
use App\OrderDetail;
use App\Menu;
use App\Posting;
use DB;
use App\Haversine;
use Carbon\Carbon;
use App\MenuDetail;
use App\Slideshow;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    
    private function HargaOngkir($jarak_final){
        if ($jarak_final <= 2.1) {
              $ongkir = 7000;
            }elseif ($jarak_final <= 2.2 || $jarak_final <= 3.1) {
              $ongkir = 9000;
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
              $ongkir = 'kadohan bos';
            }
            return $ongkir;
    }

    //ambil data menu untuk ditampilkan di beranda customer
    public function customer_get_menu_all(Request $request){

    $menu = DB::table('menu')
        ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
        ->select('menu.*',  'lapak.latitude_lap','lapak.longitude_lap','lapak.nama_usaha') 
        ->where('menu.status', 'tersedia')
        ->get();

        $hitung = new Haversine();
        
        $data = [];
        foreach ($menu as $lokasi) {
            # code...  
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance( $request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap,$lokasi->longitude_lap,"K");
            $jarak_final = round($jarak,1);
            
            $ongkir = $this->HargaOngkir($jarak_final);

            $data[] = [
                'menu' => $lokasi,
                'jarak' => $jarak_final,
                'harga_diskon' => $lokasi->harga-$diskon,
                'ongkir' => $ongkir
            ];
        }
        
        $datas = collect($data)->SortBy('jarak');

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
            ->where('menu.diskon', '>', '0')
            ->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lokasi->latitude_lap, $lokasi->longitude_lap, "K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak, 1),
                'harga_diskon' => $lokasi->harga - $diskon,
            ];
        }

        return response()->json([
            'Hasil Menu' => $data
        ]);
    }

    //fungsi untuk ambil semua posting driver 
    public function customer_get_posting_driver_all(Request $request)
    {
        setlocale(LC_TIME, 'nl_NL.utf8');
        Carbon::setLocale('id');

        $tgl = Carbon::now();

        $posting = Posting::all();
        $pos = null;

        foreach($posting as $value => $v){
            $posting = Posting::where('id', $v->id)->where('batas_durasi', '>', $tgl)->first();
            if($posting){
                $pos[] = $posting;
            }
            
        }

        $hitung = new Haversine();
        $data = [];
        if($pos != null){
        
            foreach ($pos as $lokasi) {
                $driver = Driver::find($lokasi->id_driver);
                $nama_driver = User::find($driver->id_user);
                $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lokasi->latitude_posting, $lokasi->longitude_posting, "K");
                $lokasi['id_user'] = $driver->id_user;
                $data[] = [
                    'menu' => $lokasi,
                    'jarak' => round($jarak, 1),
                    'nama_driver' => $nama_driver->nama
                ];
            }
        }
        return response()->json([
            'Hasil Posting' => $data
        ]);
    }

    public function customer_get_menu_terlaris(Request $request)
    {
        $menu_terlaris = DB::table('order_detail')
            ->join('menu', 'order_detail.id_menu', '=', 'menu.id')
            ->select(DB::raw('id_menu, count(order_detail.id) as total_orderan'))
            ->groupBy('id_menu')
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
                'total_orderan' => $value->total_orderan
            ];
        }

        return response()->json([
            'Hasil Menu' => $data
        ]);
    }

    public function customer_get_menu_terbaru(Request $request)
    {
        $menu_terbaru = Menu::select('id_lapak')
            ->distinct('id_lapak')->get();

        // return $menu_terbaru;
        $hitung = new Haversine();
        $data = [];
        foreach ($menu_terbaru as $key => $value) {
            $menu = Menu::orderBy('id', 'DESC')->where('id_lapak', $value->id_lapak)->first();
            // return $menu;
            $lapak = Lapak::find($menu->id_lapak);
            $diskon = $menu->harga * ($menu->diskon / 100);
            $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            // $jarak =  $hitung->distance(-8.1885154, 114.359096, $lapak->latitude_lap, $lapak->longitude_lap, "K");
            $menu['nama_usaha'] = $lapak->nama_usaha;

            $data[] = [
                'menu' => $menu,
                'jarak' => round($jarak, 1),
                'harga_diskon' => $menu->harga - $diskon,
                'total_orderan' => $value->total_orderan
            ];
        }

        return response()->json([

            'Hasil Menu' => $data
        ]);
    }

    //fungsi searching nama menu 
    public function customer_cari_menu(Request $request)
    {

        $cari = $request->customer_cari_menu;

        $cari_menu = Menu::where('nama_menu', 'like', "%" . $cari . "%")
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([

            'Hasil Cari Menu' => $cari_menu

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
            ->limit(5)->get();

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
    
    public function customer_lihat_order($id)
    {
        $order = Order::where('id_customer', $id)->get();
        $data = [];
        foreach ($order as $order => $val) {
            $data[] = $val;
            
            
            $lapak = Lapak::findOrFail($val->id_lapak);
            $driver = Driver::findOrFail($val->id_driver);
            
            $nama_usaha = Lapak::findOrFail($val->id_lapak);
            $val['nama_usaha'] = $nama_usaha->nama_usaha;
            $val['no_telp_lapak'] = $lapak->user->no_telp;
            $val['foto_profile_lapak'] = $lapak->foto_profile;
            $val['nama_driver'] = $driver->user->nama;
            $val['no_telp_driver'] = $driver->user->no_telp;
            $val['foto_profile_driver'] = $driver->foto_profile;

            $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select('menu.nama_menu', 'order_detail.*')
                ->where('id_order', $val->id)
                ->get();

            foreach ((array)$order_detail as $key => $value) {

                // $menu = Menu::find($value->id_menu);
                $val['detail_orderan'] = $value;
            }
        }

        return response()->json([
            'Hasil' => $data
        ]);
    }
    
    public function customer_get_ongkir(Request $request, $id)
    {
        $hitung = new Haversine();
        
        $lapak = Lapak::find($id);
        $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $lapak->latitude_lap, $lapak->longitude_lap, "K");
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
    
    public function customer_get_posting_ongkir(Request $request, $id_posting)
    {
        $hitung = new Haversine();

        $posting = Posting::find($id_posting);
        $jarak =  $hitung->distance($request->latitude_cus, $request->longitude_cus, $posting->latitude_posting, $posting->longitude_posting, "K");
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

    public function slideshow()
    {
        $data = Slideshow::all();

        return response()->json([
            'Hasil Slideshow' => $data
        ]);
    }
}