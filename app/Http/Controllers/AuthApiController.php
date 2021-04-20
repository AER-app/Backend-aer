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
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();

        return response()->json([
            'Kecamatan' => $kecamatan
        ], Response::HTTP_OK);
    }

    public function lapak_postregister(Request $request)
    {
        
        $cekemail = User::where('email', $request->email)->first();
        $cekno_telp = User::where('no_telp', $request->no_telp)->first();
        if ($cekemail) {

            $pesan = "Email Sudah Digunakan";

            return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
        }

        if ($cekno_telp) {

            $pesan = "Nomor Telepon Sudah Digunakan";

            return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
        }

        $data = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'lapak',
            'status' => '0',
        ]);

        $lastid = User::create($data)->id;

        $data_lapak = ([
            'id_user' => $lastid,
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'nomor_rekening' => $request->nomor_rekening,
            'jam_operasional' => $request->jam_operasional,
            'jenis_usaha' => $request->jenis_usaha,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'latitude_lap' => $request->latitude_lap,
            'longitude_lap' => $request->longitude_lap,
            'id_provinsi' => '35',
            'id_kabupaten' => '3510',
            'id_kecamatan1' => $request->id_kecamatan1,
            'id_kecamatan2' => $request->id_kecamatan2,
        ]);

        if ($request->foto_usaha) {
            $nama_file = "Usaha_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
                $data_lapak['foto_usaha'] = $nama_file;
            }
        }

        if ($request->foto_profile) {
            $nama_file = "Profile_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Profile/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
                $data_lapak['foto_profile'] = $nama_file;
            }
        }

        if ($request->foto_ktp) {
            $nama_file = "Ktp_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
                $data_lapak['foto_ktp'] = $nama_file;
            }
        }

        if ($request->foto_umkm) {
            $nama_file = "Umkm_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_umkm))) {
                $data_lapak['foto_umkm'] = $nama_file;
            }
        }

        if ($request->foto_npwp) {
            $nama_file = "Npwp_" . time() . ".jpeg";
            $tujuan_upload = public_path() . '/Images/Lapak/Npwp/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_npwp))) {
                $data_lapak['foto_npwp'] = $nama_file;
            }
        }

        $lapak = Lapak::create($data_lapak);


        if ($lastid && $lapak) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "vailed_regiser",
                "code"   => 400,
            ];
        }

        return response()->json($out, $out['code']);
    }

    //proses register customer
    public function customer_register(Request $request)
    {
        $cekemail = User::where('email', $request->email)->first();
        if ($cekemail) {

        $pesan = "Email Sudah Digunakan";

        return response()->json($pesan, Response::HTTP_UNAUTHORIZED);
        } 

        $data_lapak = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'status' => '1',
            'token' => $request->token,
            'otp' => $request->otp,

        ]);

        $lastid = User::create($data_lapak)->id;

        $customer = Customer::create([
            'id_user' => $lastid,
            'longitude_cus' => $request->longitude_cus,
            'latitude_cus' => $request->latitude_cus,

        ]);


        if ($lastid && $customer) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "failed_regiser",
                "code"   => 400,
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

    // VALIDATOR RESPONSE

    // $validator = Validator::make($request->all(), [
    //     'title' => ['required'],
    // ]);

    // if($validator->fails()){
    //     return response()->json($validator->errors(),
    //     Response::HTTP_UNPROCESSABLE_ENTITY)
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
