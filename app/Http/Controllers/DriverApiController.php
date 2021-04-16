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

    public function update(Request $request)
    {
        $driver = Driver::where('id_user', $id_user)->first();

		if ($request->foto_ktp) {
			$nama_file = "Ktp_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Ktp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
				$data['foto_ktp'] = $nama_file;
			}
		}

		if ($request->foto_kk) {
			$nama_file = "Kk_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Kk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_kk))) {
				$data['foto_kk'] = $nama_file;
			}
		}

		if ($request->foto_sim) {
			$nama_file = "Sim_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Sim/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_sim))) {
				$data['foto_sim'] = $nama_file;
			}
		}

		if ($request->foto_stnk) {
			$nama_file = "Stnk_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Stnk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_stnk))) {
				$data['foto_stnk'] = $nama_file;
			}
		}
		
		if ($request->foto_motor) {
			$nama_file = "Stnk_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Motor/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_motor))) {
				$data['foto_motor'] = $nama_file;
			}
		}

		$data = [
			'nama' => $request->nama,
			'nama_usaha' => $request->nama_usaha,
			'alamat' => $request->alamat,
			'nomor_rekening' => $request->nomor_rekening,
			'jam_operasional' => $request->jam_operasional,
			'jenis_usaha' => $request->jenis_usaha,
			'keterangan' => $request->keterangan,
			'status' => $request->status,
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
			'token' => $request->token,
			'otp' => $request->otp,

		];

		if ($driver->update($data)) {
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
        $user = Driver::where('id_user', $id)->first();
        $posting = Posting::where('id_driver', $user->id)->get();

        return response()->json([
            'posting_driver' => [$posting]
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
