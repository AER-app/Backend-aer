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
use App\JadwalLapak;
use App\Kecamatan;
use App\Lapak;
use App\Testimoni;
use Illuminate\Database\QueryException;

class AuthApiController extends Controller
{

public function _sendEmailAkunOtp($akun_otp)
{
    $message = new \App\Mail\PendaftaranAkun($akun_otp);
    \Mail::to($akun_otp->user->email)->send($message);
    
}

public function received($id)
{
    $akun = User::findOrFail($id);
    $customer = Customer::where('id_user', $id)->first();

    $this->_sendEmailAkunOtp($customer);

}
public function _sendLupaPassword($akun_otp)
{
    $message = new \App\Mail\LupaPassword($akun_otp);
    \Mail::to($akun_otp->email)->send($message);
    
}

public function received_lupapassword($id)
{
    $akun = User::findOrFail($id);

    $this->_sendLupaPassword($akun);

}

public function lapak_register()
{
    $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();

    return response()->json([
        'Kecamatan' => $kecamatan
    ], Response::HTTP_OK);
}

public function lapak_postregister(Request $request)
{

    $cekemail = User::where('email', $request->email)->where('role', 'lapak')->first();
    $cekno_telp = User::where('no_telp', $request->no_telp)->where('role', 'lapak')->first();
    if ($cekemail) {

        $pesan = "Email Sudah Digunakan";

        return $pesan;
    } else if ($cekno_telp) {

        $pesan = "Nomor Telepon Sudah Digunakan";

        return $pesan;
    } else {

        $data = ([
            'nama' => $request->nama_pemilik_usaha,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'otp' => rand(100000, 999999),
            'password' => bcrypt($request->password),
            'role' => 'lapak',
            'status' => '1',
        ]);

        $lastid = User::create($data)->id;

        $data_lapak = ([
            'id_user' => $lastid,
            'nama_usaha' => $request->nama_usaha,
            'nama_pemilik_usaha' => $request->nama_pemilik_usaha,
            'alamat' => $request->alamat,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
            'status' => 'tutup',
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

        $lapak = Lapak::create($data_lapak)->id;

        $hari = (['senin','selasa','rabu','kamis','jumat','sabtu','minggu']);
            foreach($hari as $h){
            $data_jadwal = [ 
                'hari' => $h,
                'id_lapak' => $lapak,
                'status_buka' => 0
            ];
            
            JadwalLapak::create($data_jadwal);
                
        }

        if ($lastid && $lapak) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "failed_register",
                "code"   => 400,
            ];
        }

        return response()->json($out, $out['code']);
    }
}

//proses register customer
public function customer_register(Request $request)
{
    $cekemail = User::where('email', $request->email)->where('role', 'customer')->first();
    $cekno_telp = User::where('no_telp', $request->no_telp)->where('role', 'customer')->first();
    if ($cekemail) {

        return "Email Sudah Digunakan";

        // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
    } else if ($cekno_telp) {

        return "Nomor Telepon Sudah Digunakan";

        // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
    } else {

        $data_customer = ([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'status' => '0',
            'token' => $request->token,
            'otp' => rand(100000, 999999),

        ]);

        $lastid = User::create($data_customer)->id;

        $customer = Customer::create([
            'id_user' => $lastid,
            'alamat' => $request->alamat,
            'longitude_cus' => $request->longitude_cus,
            'latitude_cus' => $request->latitude_cus,

        ]);

        $this->received($lastid);
        
        if ($lastid) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
                "id_user" => $lastid
            ];
        } else {
            $out = [
                "message" => "failed_regiser",
                "code"   => 400,
            ];
        }

        return response()->json($out, $out['code']);
    }
}

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

        if ($request->token) {
            $a = $request->token;
            $token = ([
                'token' => $a
            ]);
            $logins->update($token);
            $result["token"] = $a;
        }
        
        if($request->latitude_driver){
            $driver = Driver::where('id_user', $logins->id)->first();
            $driver->update([
                    'latitude_driver' => $request->latitude_driver,
                    'longitude_driver' => $request->longitude_driver,
                    'status_driver' => '1'
                ]);
        }
            $driver = Driver::where('id_user', $logins->id)->first();
            $driver->update([
                    'status_driver' => '1'
                ]);

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
    $ditinjau = User::where('status', 0)->where('no_telp', $no_telp)->where('role', 'lapak')->first();
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

        if ($request->token) {
            $a = $request->token;
            $token = ([
                'token' => $a
            ]);
            $logins->update($token);
            $result["token"] = $a;
        }

        return response()->json($result, Response::HTTP_OK);
    } elseif ($ditinjau) {
        return response()->json([
            'message' => "ditinjau"
        ]);
    } else {
        return response()->json([
            'message' => "failed"
        ]);
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

        if ($request->token) {
            $a = $request->token;
            $token = ([
                'token' => $a
            ]);
            $logins->update($token);
            $result["token"] = $a;
        }

        return response()->json($result, Response::HTTP_OK);
    } else {

        return response()->json([
            'message' => "Login Gagal"
        ], Response::HTTP_UNAUTHORIZED);
    }
}

public function cek_otp(Request $request)
{

    $user = User::where('no_telp', $request->no_telp)
            ->where('role', $request->role)
            ->first();
    if ($request->otp == $user->otp) {
        return response()->json([
            'message' => "Berhasil"
        ], Response::HTTP_OK);
    } else {
        return response()->json([
            'message' => "Gagal Kode OTP Salah"
        ], Response::HTTP_NOT_FOUND);
    }
    
}

public function logout(Request $request, $id_user)
{
    $user = User::findOrFail($id_user);
    
    $data = [
            'token' => null
        ];
    
    if($user->role == 'driver'){
        $driver = Driver::where('id_user', $user->id)->first();
        $driver->update([
                'status_driver' => '0'
            ]);
    } elseif($user->role == 'lapak') {
        $lapak = lapak::where('id_user', $user->id)->first();
        $lapak->update([
                'status' => '0',
                'status_tombol' => '0'
            ]);
    }

    $update_token  = $user->update($data);

    if ($update_token) {
        $out = [
            "message" => "logout_success",
            "code"    => 201,
        ];
    } else {
        $out = [
            "message" => "logout_failed",
            "code"   => 404,
        ];
    }

    return response()->json($out, $out['code']);

}

    public function testimoni(Request  $request)
    {
        
        $data = ([
            
            'id_user' => $request->id_user,
            'isi' => $request->isi,
        ]);
        
        $testimoni_user = Testimoni::create($data);
        
        if ($testimoni_user) {
            $out = [
                "message" => "tambah_testimoni_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "tambah_testimoni_failed",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);          
    }

    public function aktivasi_otp(Request $request, $id_user)
    {
        $user = User::where('id', $id_user)->where('otp', $request->kode)->first();
        $data = [
            'status' => 1,
            'otp' => null
        ];
        
        if ($user) {
            $user->update($data);
            $out = [
                "message" => "aktivasi_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "aktivasi_failed",
                "code"   => 400,
            ];
        }

        return response()->json($out);
    }

    public function lupa_password(Request $request)
    {
        $user = User::where('email', $request->email)->where('role', $request->role)->first();
        
        if ($user) {

            $user->update([
                'otp' => rand(100000, 999999),
            ]);
            $out = [
                "message" => "success",
                "code"    => 201,
                "id_user" => $user->id
            ];

            $this->received_lupapassword($user->id);

        } else {
            $out = [
                "message" => "Email tidak ada",
                "code"   => 400,
            ];
        }

        return response()->json($out);
    }

    public function otp_lupa_password(Request $request, $id_user)
    {
        $user = User::where('id', $id_user)->where('otp', $request->otp)->first();
        
        if ($user) {

            $user->update([
                'password' => bcrypt($request->password),
                'otp' => null,
            ]);
            
            $out = [
                "message" => "ganti_password_success",
                "code"    => 201,
                "id_user" => $user->id
            ];

        } else {
            $out = [
                "message" => "OTP Salah",
                "code"   => 400,
            ];
        }

        return response()->json($out);
    }


}
