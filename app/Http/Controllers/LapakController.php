<?php

namespace App\Http\Controllers;


use App\Menu;
use App\Lapak;
use Illuminate\Http\Request;

class LapakController extends Controller
{
    //

    public function update_lapak(Request $request, $id)
    {


    	$lapak = Lapak::findOrfail($id);

    	if ($request->foto_usaha) {
    		$nama_file="Usaha_".time()."jpeg";
    		$tujuan_upload = public_path().'/Lapak/Usaha/';
    		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_usaha)))
    		{
    			$data=['foto_usaha' =>$nama_file];
    		}
    	}

    	if ($request->foto_profile) {
    		$nama_file="Usaha_".time()."jpeg";
    		$tujuan_upload = public_path().'/Lapak/Profile/';
    		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_profile)))
    		{
    			$data=['foto_profile' =>$nama_file];
    		}
		}

		if ($request->foto_ktp) {
    		$nama_file="Usaha_".time()."jpeg";
    		$tujuan_upload = public_path().'/Lapak/Ktp/';
    		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_ktp)))
    		{
    			$data=['foto_ktp' =>$nama_file];
    		}
    	}

		if ($request->foto_umkm) {
    		$nama_file="Usaha_".time()."jpeg";
    		$tujuan_upload = public_path().'/Lapak/Umkm/';
    		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_umkm)))
    		{
    			$data=['foto_umkm' =>$nama_file];
    		}

    	}

    	$data = [
    		'nama' =>$request->nama,
    		'nama_usaha' =>$request->nama_usaha,
    		'alamat' =>$request->alamat,
    		'foto_usaha' =>$request->foto_usaha,
    		'foto_profile' =>$request->foto_profile,
    		'foto_ktp' =>$request->foto_ktp,
    		'foto_umkm' =>$request->foto_umkm,
    		'jam_operasional' =>$request->jam_operasional,
    		'jenis_usaha' =>$request->jenis_usaha,
    		'keterangan' =>$request->keterangan,
    		'status' =>$request->status,
    		'latitude' =>$request->latitude,
    		'longitude' =>$request->longitude,
    		'token' =>$request->token,
    		'otp' =>$request->otp,

    	];

    	$lapak->update($data);
    }


    public function lapak_tambah_menu(Request $request){

	 $data = [
        	'id_lapak' => $request->id_lapak,
        	'nama_menu' => $request->nama_menu,
        	'foto_menu' => $request->foto_menu,
        	'deskripsi_menu' => $request->deskripsi_menu,
        	'harga' => $request->harga,
        	'status' => $request->status,
        	'diskon' => $request->diskon,
        	
        ];
     	
        if (Menu::create($data)) {
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


 	public function lapak_get_menu($id){

 		$get_menu = Menu::where('id_lapak',$id)->get();

 		return response()->json([

 			'Hasil Menu' => $get_menu

 		]);

 }

}
