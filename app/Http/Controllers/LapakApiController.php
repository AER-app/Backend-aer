<?php

namespace App\Http\Controllers;


use App\Menu;
use App\MenuDetail;
use App\Lapak;
use App\PostingLapak;
use App\PostingLapakDetail;
use App\Kategori;
use Illuminate\Http\Request;

class LapakApiController extends Controller
{
    //
    //update identitas lapak
    public function lapak_update(Request $request, $id_user)
    {


    	$lapak = Lapak::where('id_user',$id_user)->first();

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
    		$nama_file = "Usaha_".time()."jpeg";
    		$tujuan_upload = public_path().'/Lapak/Umkm/';
    		if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_umkm)))
    		{
    			$data=['foto_umkm' =>$nama_file];
    		}
    	}

        if ($request->foto_npwp) {
            $nama_file="Usaha_".time()."jpeg";
            $tujuan_upload = public_path().'/Lapak/Umkm/';
            if (file_put_contents($tujuan_upload. $nama_file, base64_decode($request->foto_npwp)))
            {
                $data=['foto_npwp' =>$nama_file];
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
            'foto_npwp' =>$request->foto_npwp,
            'nomor_rekening' =>$request->nomor_rekening,
    		'jam_operasional' =>$request->jam_operasional,
    		'jenis_usaha' =>$request->jenis_usaha,
    		'keterangan' =>$request->keterangan,
    		'status' =>$request->status,
    		'latitude_lap' =>$request->latitude_lap,
    		'longitude_lap' =>$request->longitude_lap,
    		'token' =>$request->token,
    		'otp' =>$request->otp,

    	];

    	 if ($lapak->update($data)) {
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


    public function lapak_get_kategori(){

        $jenis = Kategori::all();

         return response()->json([

            'Hasil Menu' => $jenis
        ]);

    }


    //menambahkan menu pada role lapak
    public function lapak_tambah_menu(Request $request){



        $id_menu = $request->id_menu;
        $id_kategori = $request->id_kategori;
        

	    $data = [
        	'id_lapak' => $request->id_lapak,
        	'nama_menu' => $request->nama_menu,
        	'foto_menu' => $request->foto_menu,
        	'deskripsi_menu' => $request->deskripsi_menu,
        	'harga' => $request->harga,
        	'status' => $request->status,
        	'diskon' => $request->diskon,
        	'jenis' =>$request->jenis,
        ];

        $lastid = Menu::create($data)->id;



        $menu_detail = MenuDetail::create([
                'id_menu' => $lastid,
                'id_kategori' => $id_kategori,
        ]);


     	
        if ($lastid && $menu_detail) {
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



    //menambahkan postingan pada role lapak
    public function lapak_tambah_posting(Request $request){

    $id_posting_lapak = $request->id_posting_lapak;
    $id_kategori = $request->id_kategori;

     $data = [
            'id_lapak' => $request->id_lapak,
            'nama_menu' => $request->nama_menu,
            'foto_posting_lapak' => $request->foto_posting_lapak,
            'deskripsi_menu' => $request->deskripsi_menu,
            'harga' => $request->harga,
            'status' => $request->status,
            'diskon' => $request->diskon,
            
        ];

         $lastid = PostingLapak::create($data)->id;

         $posting_detail = PostingLapakDetail::create([
                'id_posting_lapak' => $lastid,
                'id_kategori' => $id_kategori,
        ]);

        
        if ($lastid && $posting_detail) {
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



    //mengambil menu pada role lapak
 	public function lapak_get_menu($id){



 		$get_menu = Menu::where('id_lapak',$id)->get();

 		return response()->json([

 			'Hasil Menu' => $get_menu

 		]);

    }


    //mengambil menu pada role lapak
    public function lapak_get_posting_lapak($id){



        $get_posting_lapak = PostingLapak::where('id_lapak',$id)->get();

        return response()->json([

            'Hasil Menu' => $get_posting_lapak

        ]);

    }


    //mengambil data identitas pada role lapak
    public function lapak_get_profile($id){

        $get_profil = lapak::where('id',$id)->get();

        return response()->json([

            'Profil' => $get_profil

        ]);

    }

}
