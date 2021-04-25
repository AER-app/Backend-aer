<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Driver;
use App\Posting;
use Image;

class DriverApiController extends Controller
{
    public function index()
    {
    }

    public function update(Request $request, $id_user)
    {
		$data_user = [
			'nama' => $request->nama,
			'email' => $request->email,
			'no_telp' => $request->no_telp,
			'token' => $request->token,
			'password' => bcrypt($request->password),
		];

		$data = [
			'alamat' => $request->alamat,
			'jenis_motor' => $request->jenis_motor,
			'warna_motor' => $request->warna_motor,
			'plat_nomor' => $request->plat_nomor,
			'latitude_driver' => $request->latitude_driver,
			'longitude_driver' => $request->longitude_driver,
		];

        $driver = Driver::where('id_user', $id_user)->first();
		if ($request->profile) {
			$nama_file = "Profile_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Profile/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->profile))) {
				$data ['profile'] = $nama_file;
			}
		}
		if ($request->foto_ktp) {
			$nama_file = "Ktp_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Ktp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
				$data ['foto_ktp'] = $nama_file;
			}
		}
		if ($request->foto_kk) {
			$nama_file = "Kk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Kk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_kk))) {
				$data ['foto_kk'] = $nama_file;
			}
		}

		if ($request->foto_sim) {
			$nama_file = "Sim_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Sim/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_sim))) {
				$data ['foto_sim'] = $nama_file;
			}
		}

		if ($request->foto_stnk) {
			$nama_file = "Stnk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Stnk/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_stnk))) {
				$data ['foto_stnk'] = $nama_file;
			}
		}
		
		if ($request->foto_motor) {
			$nama_file = "Stnk_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Driver/Motor/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_motor))) {
				$data ['foto_motor'] = $nama_file;
			}
		}

		if ($driver->update($data) && User::create($data_user)) {
			return response()->json([
				"message" => "success"
			], Response::HTTP_CREATED);
		} else {
			return response()->json([
				"message" => "failed",
			], Response::HTTP_BAD_REQUEST);
		}
    }

    public function profile($id_user)
    {
        $user = User::where('id', $id_user)->where('role', 'driver')->first();
        $driver = Driver::where('id_user', $user->id)->first();
        $driver['nama'] = $user->nama;
        $driver['role'] = $user->role;
        $driver['email'] = $user->email;
        $driver['no_telp'] = $user->no_telp;
		
        return response()->json([
            'driver' => [$driver]
        ]);
    }

    public function get_posting_driver($id_user)
    {
        $user = Driver::where('id_user', $id_user)->first();
        $posting = Posting::where('id_driver', $user->id)->get();

        return response()->json([
            'posting_driver' => $posting
		], Response::HTTP_OK);
    }

    public function driver_posting(Request $request, $id_user)
    {
        $driver = Driver::where('id_user', $id_user)->first();
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
            return response()->json([
                "message" => "success"
            ], Response::HTTP_CREATED);
        } else {
			return response()->json([
                "message" => "failed",
            ], Response::HTTP_BAD_REQUEST);
        }

    }

	public function driver_delete_posting($id)
	{
		$posting_driver = Posting::findOrFail($id);
		if ($posting_driver->foto_posting) {
			File::delete('Images/Driver/Posting/Normal/'.$posting_driver->foto_posting);
			File::delete('Images/Driver/Posting/Thumbnail/'.$posting_driver->foto_posting);
		}

		if ($posting_driver->delete()) {
			$out = [
				"message" => "delete-menu_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "delete-menu_failed",
				"code"   => 404,
			];
		}
		return response()->json($out, $out['code']);

	}

}
