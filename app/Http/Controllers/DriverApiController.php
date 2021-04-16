<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\User;
use App\Driver;
use App\Posting;
use Image;

class DriverApiController extends Controller
{
    public function index()
    {
    }

    // 

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $driver = Driver::where('id_user', $user->id)->first();

        $driver['nama'] = $user->nama;
        $driver['alamat'] = $user->alamat;
        $driver['email'] = $user->email;
        $driver['no_telp'] = $user->no_telp;

        return response()->json([
            'driver' => [$driver]
        ]);
    }

    public function get_posting_driver($id)
    {
        $driver = Posting::where('id_driver', $id)->get();

        return response()->json([
            'posting_driver' => $driver
        ]);
    }

    public function driver_posting(Request $request, $id)
    {
        $driver = Driver::where('id_user', $id)->first();
        $data = [
            'judul_posting' => $request->judul_posting,
            'deskripsi_posting' => $request->deskripsi_posting,
            'harga' => $request->harga,
            'status' => $request->status,
            'durasi' => $request->durasi,
            'id_driver' => $driver->id,
        ];

        if ($request->foto_posting) {
            $nama_file = "Driver_Posting_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Posting/Normal/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_posting))) {
				$data ['foto_posting'] = $nama_file;
			}

            $img = Image::make($tujuan_upload . $nama_file);
            $img->resize(200, 200)->save(public_path().'/Images/Driver/Posting/Thumbnail/'.$nama_file);

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
