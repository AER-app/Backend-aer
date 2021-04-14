<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\User;
use App\Driver;
use App\Lapak;

class AuthController extends Controller
{

    // public function lapak_register(Request $request)
    // {
    //     $nama = $request->nama;
    //     $token = $request->token;

    //     $data = ([
    //         'email' => $request->email,
    //         'no_telp' => $request->no_telp,
    //         'password' => bcrypt($request->password),
    //         'role' => $request->role,
    //         'status' => 'aktif'
            
    //     ]);

    //     $lastid = User::create($data)->id;

    //     $lapak = Lapak::create([
    //             'nama'=>$nama,
    //             'id_user'=>$lastid,
    //             'token'=>$token,
    //     ]);    
    
        
    //     if ($lastid && $lapak) {
    //         $out = [
    //             "message" => "register_success",
    //             "code"    => 201,
    //         ];
    //     } else {
    //         $out = [
    //             "message" => "vailed_regiser",
    //             "code"   => 404,
    //         ];
    //     }

    //     return response()->json($out, $out['code']);
    // }

    //proses register customer
    public function customer_register(Request $request)
    {
        
        $token = $request->token;
        $otp = $request->otp;
        $longitude = $request->longitude;
        $latitude = $request->latitude;

        $data = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'status' => '1'
            
        ]);

        $lastid = User::create($data)->id;

        $customer = Customer::create([
                'id_user'=>$lastid,
                'longitude'=>$longitude,
                'latitude'=>$latitude,
                'token'=>$token,
                'otp'=>$otp,
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

    // public function driver_register(Request $request)
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

        if (Hash::check($password, $logins->password)) {

            $result["success"] = "1";
            $result["message"] = "success";
            //untuk memanggil data sesi Login
            $result["id"] = $logins->id;
            $result["nama"] = $logins->nama;
            $result["password"] = $logins->password;
            $result["email"] = $logins->email;
            $result["no_telp"] = $logins->no_telp;
            $result["role"] = $logins->role;
            
            return response()->json($result);
        } else {
            $result["success"] = "0";
            $result["message"] = "Login Gagal";
            return response()->json($result);
        }
    }




}
