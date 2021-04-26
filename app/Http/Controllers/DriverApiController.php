<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Order;
use App\Posting;
use DB;
use App\Haversine;



class DriverApiController extends Controller
{
    
    public function index()
    {
        
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        if ($request->foto_ktp) {
            $nama_file = "Ktp_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_ktp))) 
            {
                $data = ['foto_ktp' => $nama_file];
            } 
        }
        if ($request->foto_kk) {
            $nama_file = "Kk_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Kk/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_kk))) 
            {
                $data = ['foto_kk' => $nama_file];
            } 
        }
        if ($request->foto_sim) {
            $nama_file = "Sim_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Sim/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_sim))) 
            {
                $data = ['foto_sim' => $nama_file];
            } 
        }
        if ($request->foto_stnk) {
            $nama_file = "Stnk_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Stnk/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_stnk))) 
            {
                $data = ['foto_stnk' => $nama_file];
            } 
        }
        if ($request->foto_motor) {
            $nama_file = "Motor_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Motor/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_motor))) 
            {
                $data = ['foto_motor' => $nama_file];
            } 
        }

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_motor' => $request->jenis_motor,
            'plat_motor' => $request->plat_motor,
            'warna_motor' => $request->warna_motor,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($driver->update($data)) {
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



    public function profile($id)
    {
        $user = User::findOrFail($id);
        $driver = Driver::where('id_user', $user->id)->first();
        
        return response()->json([
            'user' => $user, 'driver' => $driver
        ]);
    }


    public function get_posting_driver($id)
    {
        $driver = Posting::where('id_driver', $id)->get();

        return response()->json([
            'posting_driver' => $driver
        ]);
    }


    public function posting_driver(Request $request, $id)
    {
        if ($request->foto_posting) {
            $nama_file = "Posting_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Posting/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_posting))) 
            {
                $data = ['foto_posting' => $nama_file];
            } 
        }

        $data = [
            'judul_posting' => $request->judul_posting,
            'deskripsi_posting' => $request->deskripsi_posting,
            'harga' => $request->harga,
            'status' => $request->status,
            'durasi' => $request->durasi,
            'id_driver' => $id,
        ];

        if (Posting::create($data)) {
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
}
