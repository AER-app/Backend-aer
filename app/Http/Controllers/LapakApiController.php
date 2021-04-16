<?php

namespace App\Http\Controllers;


use App\Menu;
use App\Lapak;
use App\PostingLapak;
use Illuminate\Http\Request;
use Image;

class LapakApiController extends Controller
{

	public function lapak_update(Request $request, $id_user)
	{

		$lapak = Lapak::where('id_user', $id_user)->first();

		if ($request->foto_usaha) {
			$nama_file = "Usaha_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
				$data['foto_usaha'] = $nama_file;
			}
		}

		if ($request->foto_profile) {
			$nama_file = "Usaha_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Profile/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
				$data['foto_profile'] = $nama_file;
			}
		}

		if ($request->foto_ktp) {
			$nama_file = "Usaha_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Ktp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
				$data['foto_ktp'] = $nama_file;
			}
		}

		if ($request->foto_umkm) {
			$nama_file = "Usaha_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_umkm))) {
				$data['foto_umkm'] = $nama_file;
			}
		}

		if ($request->foto_npwp) {
			$nama_file = "Usaha_" . time() . "jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_npwp))) {
				$data['foto_npwp'] = $nama_file;
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

		if ($lapak->update($data)) {
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

	public function lapak_tambah_menu(Request $request)
	{

		$data = [
			'id_lapak' => $request->id_lapak,
			'nama_menu' => $request->nama_menu,
			'deskripsi_menu' => $request->deskripsi_menu,
			'harga' => $request->harga,
			'status' => $request->status,
			'diskon' => $request->diskon,
		];

		if ($request->foto_menu) {
			$nama_file = "Driver_Posting_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Menu/Normal/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_menu))) {
				$data['foto_menu'] = $nama_file;
			}

			$img = Image::make($tujuan_upload . $nama_file);
			$img->resize(200, 200)->save(public_path() . '/Images/Lapak/Menu/Thumbnail/' . $nama_file);
		}

		if (Menu::create($data)) {
			$out = [
				"message" => "tambah-menu_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-menu_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}



	public function lapak_get_menu($id)
	{

		$get_menu = Menu::where('id_lapak', $id)->get();

		return response()->json([

			'Hasil Menu' => $get_menu

		]);
	}



	public function lapak_get_profil($id)
	{

		$get_profil = lapak::where('id_user', $id)->get();

		return response()->json([

			'Profil' => $get_profil

		]);
	}

	public function lapak_delete_posting($id)
	{
		$posting_lapak = PostingLapak::findOrFail($id);

		if ($posting_lapak->delete()) {
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
	}
}
