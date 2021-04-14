<?php

namespace App\Http\Controllers;
// include composer autoload
require 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\User;
use App\Driver;
use App\Posting;

class DriverController extends Controller
{
    public function index()
    {
    }

    public function update(Request $request, $id_user)
    {
        $driver = Driver::where('id_user', $id_user)->first();

        if ($request->foto_ktp) {
            File::delete('/Images/Driver/Ktp/' . $driver->foto_ktp);
            $nama_file = "Driver_Ktp_" . time() . "jpeg";
            $tujuan_upload = public_path() . '/Images/Driver/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
                $data['foto_ktp'] = $nama_file;
            }
        }
        if ($request->foto_kk) {
            File::delete('/Images/Driver/Kk/' . $driver->foto_kk);
            $nama_file = "Driver_Kk_" . time() . "jpeg";
            $tujuan_upload = public_path() . '/Images/Driver/Kk/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_kk))) {
                $data['foto_kk'] = $nama_file;
            }
        }
        if ($request->foto_sim) {
            File::delete('/Images/Driver/Sim/' . $driver->foto_sim);
            $nama_file = "Driver_Sim_" . time() . "jpeg";
            $tujuan_upload = public_path() . '/Images/Driver/Sim/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_sim))) {
                $data['foto_sim'] = $nama_file;
            }
        }
        if ($request->foto_stnk) {
            File::delete('/Images/Driver/Stnk/' . $driver->foto_stnk);
            $nama_file = "Driver_Stnk_" . time() . "jpeg";
            $tujuan_upload = public_path() . '/Images/Driver/Stnk/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_stnk))) {
                $data['foto_stnk'] = $nama_file;
            }
        }
        if ($request->foto_motor) {
            File::delete('/Images/Driver/Motor/' . $driver->foto_motor);
            $nama_file = "Driver_Motor_" . time() . "jpeg";
            $tujuan_upload = public_path() . '/Images/Driver/Motor/';
            if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_motor))) {
                $data['foto_motor'] = $nama_file;
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

        $update = $driver->update($data);

        if ($update) {
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
        $data = [
            'judul_posting' => $request->judul_posting,
            'deskripsi_posting' => $request->deskripsi_posting,
            'harga' => $request->harga,
            'status' => $request->status,
            'durasi' => $request->durasi,
            'id_driver' => $id,
        ];

        if ($request->foto_posting) {
            $nama_file = "Driver_Posting_" . time() . ".jpeg";
            $img = Image::make($request->file('foto_posting')->getRealPath());
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . '/Images/Driver/Posting/Thumbnail/' . $nama_file);
            $img->resize(900, 900, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . '/Images/Driver/Posting/Normal/' . $nama_file);

            $data['foto_posting'] = $nama_file;
        }

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
