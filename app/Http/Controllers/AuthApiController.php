<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Customer;
use App\Driver;
use App\Kecamatan;
use App\Lapak;
use Illuminate\Database\QueryException;


class AuthApiController extends Controller
{


    public function lapak_register()
    {
        $kecamatan = Kecamatan::where('city_id', 3510)->get();

        return response()->json($kecamatan, Response::HTTP_OK);
    }

    public function lapak_postregister(Request $request)
    {
        // VALIDATOR RESPONSE
        $validator = Validator::make($request->all(), [
            'foto_usaha' => ['required'],
            'foto_profile' => ['required'],
            'foto_ktp' => ['required'],
            'foto_umkm' => ['required'],
            'foto_npwp' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $nama = $request->nama;
        $nama_usaha = $request->nama_usaha;
        $alamat = $request->alamat;
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
        $token = $request->token;

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
        $dataLapak =([
            'id_user' => $lastid,
            'nama' => $nama,
            'nama_usaha' => $nama_usaha,
            'alamat' => $alamat,
            'nomor_rekening' => $nomor_rekening,
            'jam_operasional' => $jam_operasional,
            'jenis_usaha' => $jenis_usaha,
            'keterangan' => $keterangan,
            'status' => $status,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'id_provinsi' => $id_provinsi,
            'token' => $token,
             'otp' => $request->otp,
            'id_kabupaten' => $id_kabupaten,
            'id_kecamatan1' => $id_kecamatan1,
            'id_kecamatan2' => $id_kecamatan2,
        ]);
        
        if ($request->foto_usaha) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
                $dataLapak['foto_usaha'] = $nama_file;
            }
        }

        if ($request->foto_profile) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Profile/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
                $dataLapak['foto_profile'] = $nama_file;
            }
        }

        if ($request->foto_ktp) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
                $dataLapak['foto_ktp'] = $nama_file;
            }
        }

        if ($request->foto_umkm) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_umkm))) {
                $dataLapak['foto_umkm'] = $nama_file;
            }
        }

        if ($request->foto_npwp) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_npwp))) {
                $dataLapak['foto_npwp'] = $nama_file;
            }
        }
       
        // $lapak = Lapak::create([
        //     'id_user' => $lastid,
        //     'nama' => $nama,
        //     'nama_usaha' => $nama_usaha,
        //     'alamat' => $alamat,
        //     'nomor_rekening' => $nomor_rekening,
        //     'jam_operasional' => $jam_operasional,
        //     'jenis_usaha' => $jenis_usaha,
        //     'keterangan' => $keterangan,
        //     'status' => $status,
        //     'latitude' => $latitude,
        //     'longitude' => $longitude,
        //     'id_provinsi' => $id_provinsi,
        //     'id_kabupaten' => $id_kabupaten,
        //     'id_kecamatan1' => $id_kecamatan1,
        //     'id_kecamatan2' => $id_kecamatan2,
        // ]);
    
        $lapak = Lapak::create($dataLapak);

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
        $longitude_cus = $request->longitude_cus;
        $latitude_cus = $request->latitude_cus;

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
                'longitude_cus'=>$longitude_cus,
                'latitude_cus'=>$latitude_cus,
                
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


   public function driver_login(Request $request)
    {
        $no_telp = $request->input('no_telp');
        $password = $request->input('password');
        $logins = User::where('status', 1)->where('no_telp', $no_telp)->where('role', 'driver')->first();
        $ps = Str::random(5);
        if ($logins == null) {
            $ps = $ps;
        } else {
            $ps = $logins->password;
        }
        
        if (Hash::check($password, $ps)) {

            $result["success"] = "1";
            $result["message"] = "success";
            //untuk memanggil data sesi Login
            $result["id"] = $logins->id;
            $result["nama"] = $logins->nama;
            $result["password"] = $logins->password;
            $result["email"] = $logins->email;
            $result["no_telp"] = $logins->no_telp;
            $result["role"] = $logins->role;

            return response()->json($result, Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => "Login Gagal"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function lapak_login(Request $request)
    {
        $no_telp = $request->input('no_telp');
        $password = $request->input('password');
        $logins = User::where('status', 1)->where('no_telp', $no_telp)->where('role', 'lapak')->first();
        $ps = Str::random(5);
        if ($logins == null) {
            $ps = $ps;
        } else {
            $ps = $logins->password;
        }
        
        if (Hash::check($password, $ps)) {

            $result["success"] = "1";
            $result["message"] = "success";
            //untuk memanggil data sesi Login
            $result["id"] = $logins->id;
            $result["nama"] = $logins->nama;
            $result["password"] = $logins->password;
            $result["email"] = $logins->email;
            $result["no_telp"] = $logins->no_telp;
            $result["role"] = $logins->role;

            return response()->json($result, Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => "Login Gagal"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function customer_login(Request $request)
    {
        $no_telp = $request->input('no_telp');
        $password = $request->input('password');
        $logins = User::where('status', 1)->where('no_telp', $no_telp)->where('role', 'customer')->first();
        $ps = Str::random(5);
        if ($logins == null) {
            $ps = $ps;
        } else {
            $ps = $logins->password;
        }
        
        if (Hash::check($password, $ps)) {

            $result["success"] = "1";
            $result["message"] = "success";
            //untuk memanggil data sesi Login
            $result["id"] = $logins->id;
            $result["nama"] = $logins->nama;
            $result["password"] = $logins->password;
            $result["email"] = $logins->email;
            $result["no_telp"] = $logins->no_telp;
            $result["role"] = $logins->role;

            return response()->json($result, Response::HTTP_OK);
        } else {

            
            return response()->json([
                'message' => "Login Gagal"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

}
