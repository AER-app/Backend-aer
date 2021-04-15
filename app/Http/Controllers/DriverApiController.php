<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\User;
use App\Driver;
use App\Posting;
use Image;

class DriverController extends Controller
{
    public function index()
    {
    }

    // 

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $driver = Driver::where('id_user', $user->id)->first();

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
