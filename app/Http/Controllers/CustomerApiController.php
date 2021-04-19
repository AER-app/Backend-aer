<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Lapak;
use DB;
use App\Haversine;
use App\Menu;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{

    //ambil data menu untuk ditampilkan di beranda customer
    public function customer_get_menu_all($id_user)
    {

        $menu = DB::table('menu')
        ->join('lapak', 'menu.id_lapak', '=', 'lapak.id')
        ->select('menu.*',  'lapak.latitude','lapak.longitude','lapak.nama_usaha')
        ->get();

        $hitung = new Haversine();

        $data = [];
        foreach ($menu as $lokasi) {
            # code...
            $customer = Customer::where('id_user', $id_user)->first();
            $diskon = $lokasi->harga * ($lokasi->diskon / 100);
            $jarak =  $hitung->distance($customer->latitude, $customer->longitude,$lokasi->latitude,$lokasi->longitude,"K");
            $data[] = [
                'menu' => $lokasi,
                'jarak' => round($jarak,2),
                'harga_diskon' => $lokasi->harga-$diskon,
            ];
        }
        // $get_menu_terlaris = Order::withCount('id_menu')->orderBy('comments_count', 'DESC')->limit(10)->get();

        $get_menu_terbaru = Menu::orderBy('created_at', 'DESC')->get();
        $get_menu_diskon = Menu::where('diskon', '>', 0)->orderBy('updated_at', 'DESC')->get();
        return response()->json([
            'Hasil Menu' => $data,
            'Menu Diskon' => $get_menu_diskon,
            'Menu Terbaru' => $get_menu_terbaru
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


    //ambil data semua  menu dari lapak yang dipilih
    public function customer_get_menu_lapak($id_lapak)
    {

        $get_menu_lapak = Menu::where('id_lapak', $id_lapak)->get();

        return response()->json([

            'Hasil semua Menu dari lapak' => $get_menu_lapak

        ]);
    }

    //get data profil sesuai user login
    public function customer_get_profil($id)
    {
        $user = User::findOrFail($id);
        $customer_get_profil = Customer::where('id_user', $id)->first();
        $customer_get_profil ['nama'] = $user->nama;
        $customer_get_profil ['alamat'] = $user->alamat;
        $customer_get_profil ['email'] = $user->email;
        $customer_get_profil ['role'] = $user->role;
        $customer_get_profil ['no_telp'] = $user->no_telp;

        return response()->json([

            'Profil' => [$customer_get_profil]

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
            $nama_file= "Customer_Profile_".time().".jpeg";
            $tujuan_upload = public_path().'/Images/Customer/Profile/';
            if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_profile)))
            {
                $data ['foto_profile'] = $nama_file;
            }
        }

        if ($request->foto_ktp) {
            $nama_file = "Customer_Ktp_".time().".jpeg";
            $tujuan_upload = public_path().'/Images/Customer/Ktp/';
            if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_ktp)))
            {
                $data ['foto_ktp'] = $nama_file;
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
}
