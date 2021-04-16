<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Lapak;
use App\Menu;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{

    //ambil data menu untuk ditampilkan di beranda customer
    public function customer_get_menu_all()
    {

        $get_menu_all = Menu::all();

        return response()->json([

            'Hasil Menu' => $get_menu_all
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

            'Profil' => $customer_get_profil

        ]);
    }


    //update profil dari customer
    public function customer_update_profile(Request $request, $id)
    {

        $customer = Customer::where('id_user', $id)->first();

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
            'alamat' => $request->alamat,
            'foto_profile' => $request->foto_profile,
            'foto_ktp' => $request->foto_ktp,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,

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
