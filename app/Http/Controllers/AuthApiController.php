<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Lapak;
use App\Driver;
use App\Customer;


class AuthApiController extends Controller
{


    public function lapak_register(Request $request)
    {
      
        
        $nama_usaha = $request->nama_usaha;
        $alamat = $request->alamat;
        $foto_usaha = $request->foto_usaha;
        $foto_profile = $request->foto_profile;
        $foto_ktp = $request->foto_ktp;
        $foto_umkm = $request->foto_umkm;
        $foto_npwp = $request->foto_npwp;
        $nomor_rekening = $request->nomor_rekening;
        $jam_operasional = $request->jam_operasional;
        $jenis_usaha = $request->jenis_usaha;
        $keterangan = $request->keterangan;
        $status = $request->status;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $id_provinsi = $request->id_provinsi;
        $id_kabupaten = $request->id_kabupaten;
        $id_kecamatan1 = $request->id_kecamatan1;
        $id_kecamatan2 = $request->id_kecamatan2;




        $data = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => '1',
            'token' => $request->token,
            'otp' => $request->otp,
            
        ]);

        $lastid = User::create($data)->id;

        $lapak = Lapak::create([
                'id_user' => $lastid,
                'nama_usaha' => $nama_usaha,
                'alamat' => $alamat,
                'foto_usaha' => $foto_usaha,
                'foto_profile' => $foto_profile,
                'foto_ktp' => $foto_ktp,
                'foto_umkm' => $foto_umkm,
                'foto_npwp' => $foto_npwp,
                'nomor_rekening' => $nomor_rekening,
                'jam_operasional' => $jam_operasional,
                'jenis_usaha' => $jenis_usaha,
                'keterangan' => $keterangan,
                'status' => $status,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'id_provinsi' => $id_provinsi,
                'id_kabupaten' => $id_kabupaten,
                'id_kecamatan1' => $id_kecamatan1,
                'id_kecamatan2' => $id_kecamatan2,
        ]);    
    
        
        if ($lastid && $lapak) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "vailed_regiser",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);
    }



    //proses register customer
    public function customer_register(Request $request)
    {
      
        
      
        $longitude = $request->longitude;
        $latitude = $request->latitude;

        $data = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'status' => '1',
            'token'=>$request->token,
            'otp'=>$request->otp,
            
        ]);

        $lastid = User::create($data)->id;

        $customer = Customer::create([
                'id_user'=>$lastid,
                'longitude'=>$longitude,
                'latitude'=>$latitude,
                
        ]);    
    
        
        if ($lastid && $customer) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "vailed_regiser",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);
    }


    

    // public function register_driver(Request $request)
    // {   
    //     $nama = $request->nama;
    //     $data = ([
    //         'email' => $request->email,
    //         'no_telp' => $request->no_telp,
    //         'password' => bcrypt($request->password),
    //         'role' => $request->role,
    //         'status' => 'aktif',
    //     ]);

    //     $lastid = User::create($data)->id;

    //     $driver =   Driver::create([
    //                     'nama' => $nama,
    //                     'id_user' => $lastid,
    //                     'token' => $request->token
    //                 ]);

    //     if ($lastid && $driver) {
    //         $out = [
    //             "message" => "register_success",
    //             "code" => 201
    //         ];
    //     } else {
    //         $out = [
    //             "message" => "vailed_register",
    //             "code" => 404
    //         ];
    //     }
        

    //     return response()->json($out, $out['code']);
    // }


    public function login(Request $request)
    {
        $no_telp = $request->input('no_telp');
        $password = $request->input('password');
        $logins = User::where('no_telp', $no_telp)->first();
    
        if (!$tok) {
            $a = $request->token;
            $token = ([
                'token' => $a
            ]);
            $logins->update($token);
        }

        if (Hash::check($password, $logins->password)) {

            $result["success"] = "1";
            $result["message"] = "success";
            //untuk memanggil data sesi Login
            $result["id"] = $logins->id;
            $result["username"] = $logins->username;
            $result["password"] = $logins->password;
            $result["email"] = $logins->email;
            $result["role"] = $logins->role;
            
            return response()->json($result);
        } else {
            $result["success"] = "0";
            $result["message"] = "Login Gagal";
            return response()->json($result);
        }
    }




}
