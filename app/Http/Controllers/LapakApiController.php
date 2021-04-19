<?php

namespace App\Http\Controllers;

use App\Menu;
use App\MenuDetail;
use App\Lapak;
use App\PostingLapak;
use App\User;
use App\PostingLapakDetail;
use App\Kategori;
use Illuminate\Http\Request;
use Image;

class LapakApiController extends Controller
{

	public function lapak_update(Request $request, $id_user)
	{
		$user = User::findOrFail($id_user);
		$lapak = Lapak::where('id_user', $id_user)->first();

		if ($request->foto_usaha) {
			$nama_file = "Usaha_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
				$data['foto_usaha'] = $nama_file;
			}
		}

		if ($request->foto_profile) {
			$nama_file = "Profile_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Profile/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_profile))) {
				$data['foto_profile'] = $nama_file;
			}
		}

		if ($request->foto_ktp) {
			$nama_file = "Ktp_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Ktp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_ktp))) {
				$data['foto_ktp'] = $nama_file;
			}
		}

		if ($request->foto_umkm) {
			$nama_file = "Umkm_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Umkm/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_umkm))) {
				$data['foto_umkm'] = $nama_file;
			}
		}

		if ($request->foto_npwp) {
			$nama_file = "Npwp_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Npwp/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_npwp))) {
				$data['foto_npwp'] = $nama_file;
			}
		}

		$data_user = [
			'nama' => $request->nama,
			'no_telp' => $request->no_telp,
			'email' => $request->email,
			'token' => $request->token,
		];

		$data = [
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

		if ($lapak->update($data) && $user->update($data_user)) {
			$out = [
				"message" => "update-profil_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "update-profil_failed",
				"code"   => 400,
			];
		}

		return response()->json($out, $out['code']);
	}

	//menambahkan postingan pada role lapak
	public function lapak_tambah_posting(Request $request)
	{

		$data = [
			'id_lapak' => $request->id_lapak,
			'nama_menu' => $request->nama_menu,
			'deskripsi_menu' => $request->deskripsi_menu,
			'harga' => $request->harga,
			'status' => $request->status,
			'diskon' => $request->diskon,
		];

		if ($request->foto_posting_lapak) {
			$nama_file = "Lapak_Posting_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Posting/Normal/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_posting_lapak))) {
				$data['foto_posting_lapak'] = $nama_file;
			}

			$img = Image::make($tujuan_upload . $nama_file);
			$img->resize(200, 200)->save(public_path() . '/Images/Lapak/Posting/Thumbnail/' . $nama_file);
		}

		if (PostingLapak::create($data)) {
			$out = [
				"message" => "tambah-posting_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-posting_failed",
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
			$nama_file = "Lapak_Menu_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Menu/Normal/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_menu))) {
				$data['foto_menu'] = $nama_file;
			}

			$img = Image::make($tujuan_upload . $nama_file);
			$img->resize(200, 200)->save(public_path() . '/Images/Lapak/Menu/Thumbnail/' . $nama_file);
		}

		$lastid = Menu::create($data)->id;

		$menu_detail = MenuDetail::create([
			'id_menu' => $lastid,
			'id_kategori' => $request->id_kategori
		]);

		if ($menu_detail) {
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
		$lapak =  Lapak::where('id_user', $id)->first();

		$get_menu = Menu::where('id_lapak', $lapak->id)->get();

		return response()->json([

			'Hasil Menu' => [$get_menu]

		]);
	}

	public function lapak_get_profile($id)
	{
		$user = User::findOrFail($id);
		$get_profil = lapak::where('id_user', $id)->first();
		$get_profil['nama'] = $user->nama;
		$get_profil['alamat'] = $user->alamat;
		$get_profil['email'] = $user->email;
		$get_profil['no_telp'] = $user->no_telp;
		$get_profil['role'] = $user->role;

		return response()->json([
			'Profile' => [$get_profil]
		]);
	}

	//mengambil menu pada role lapak
	public function lapak_get_posting_lapak($id)
	{

		$get_posting_lapak = PostingLapak::where('id_lapak', $id)->get();

		return response()->json([

			'Hasil Menu' => $get_posting_lapak

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
