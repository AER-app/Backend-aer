<?php

namespace App\Http\Controllers;

use App\JadwalLapak;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

use App\Menu;
use App\MenuDetail;
use App\Lapak;
use App\PostingLapak;
use App\User;
use App\PostingLapakDetail;
use App\Kategori;
use App\Posting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class LapakApiController extends Controller
{

	public function lapak_get_profile($id)
	{
		$user = User::where('id', $id)->where('role', 'lapak')->first();
		$get_profil = lapak::where('id_user', $id)->first();
		$get_profil['nama'] = $user->nama;
		$get_profil['email'] = $user->email;
		$get_profil['no_telp'] = $user->no_telp;
		$get_profil['role'] = $user->role;

		return response()->json([
			'Profile' => [$get_profil]
		]);
	}

	public function lapak_update(Request $request, $id_user)
	{
		$user = User::findOrFail($id_user);
		$lapak = Lapak::where('id_user', $id_user)->first();
		$data = [];

		if ($request->foto_usaha) {
			$nama_file = "Usaha_" . time() . ".jpeg";
			$tujuan_upload = public_path() . '/Images/Lapak/Usaha/';
			if (file_put_contents($tujuan_upload . $nama_file, base64_decode($request->foto_usaha))) {
				$data['foto_usaha'] = $nama_file;
			}
		}
		
		if ($lapak->update($data)) {
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


	public function lapak_jadwal($id_lapak)
	{
		$jadwal_lapak = JadwalLapak::where('id_lapak', $id_lapak)->get();

		return response()->json([
			'Jadwal Lapak' => $jadwal_lapak
		]);
	}

	public function lapak_tambah_menu(Request $request)
	{
		$str = Str::length($request->foto_menu);
		// Jika file gambar lebih dari 2.15 Mb 
		if ($str >= 2500000) {
			$pesan = "Foto terlalu besar";

			return $pesan;
            // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
		} else {

			$data = [
				'id_lapak' => $request->id_lapak,
				'nama_menu' => $request->nama_menu,
				'deskripsi_menu' => $request->deskripsi_menu,
				'harga' => $request->harga,
				'jenis' => $request->jenis,
				'status' => $request->status,
				'diskon' => $request->diskon,
				'rating' => $request->rating,
			];


			if ($request->foto_menu) {
				$foto_menu = Str::limit($request->foto_menu, 500000);
				$nama_file = "Lapak_Menu_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Lapak/Menu/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_menu))) {
					$data['foto_menu'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path() . '/Images/Lapak/Menu/Thumbnail/' . $nama_file);
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
	}

	public function lapak_get_menu($id)
	{
		$lapak =  Lapak::where('id_user', $id)->first();

		$get_menu = Menu::where('id_lapak', $lapak->id)->get();

		// $get_menu = lapak::withCount('menu')->orderBy('lapak_count', 'DESC')->get();

		return response()->json([

			'Hasil Menu' => $get_menu

		]);
	}

	public function lapak_update_menu(Request $request, $id)
	{
			$menu = Menu::find($id);

			$data = [
				'id_lapak' => $request->id_lapak,
				'nama_menu' => $request->nama_menu,
				'deskripsi_menu' => $request->deskripsi_menu,
				'harga' => $request->harga,
				'jenis' => $request->jenis,
				'status' => $request->status,
				'diskon' => $request->diskon,
				'rating' => $request->rating,
			];

			// $menu_detail = MenuDetail::create([
			// 	'id_menu' => $lastid,
			// 	'id_kategori' => $request->id_kategori
			// ]);

			if ($menu->update($data)) {
				$out = [
					"message" => "update-menu_success",
					"code"    => 201,
				];
			} else {
				$out = [
					"message" => "update-menu_failed",
					"code"   => 404,
				];
			}

			return response()->json($out, $out['code']);
	}

	public function lapak_delete_menu($id)
	{
		$menu = Menu::findOrFail($id);
		if ($menu->foto_menu) {
			File::delete('Images/Lapak/Menu/Normal/' . $menu->foto_menu);
			File::delete('Images/Lapak/Menu/Thumbnail/' . $menu->foto_menu);
		}
		$pld = MenuDetail::where('id_menu', $menu->id)->get();

		$del = $pld->each->delete();
		$del_menu = $menu->delete();

		if ($del && $del_menu) {
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

	public function lapak_get_kategori()
	{
		$jenis = Kategori::all();
		return response()->json([
			'Hasil Menu' => $jenis
		]);
	}

	//menambahkan postingan pada role lapak
	public function lapak_tambah_posting(Request $request)
	{
		$str = Str::length($request->foto_posting_lapak);
		// Jika file gambar lebih dari 2.15 Mb 
		if ($str >= 2500000) {
			$pesan = "Foto terlalu besar";

			return $pesan;
            // return response()->json(['message' => $pesan], Response::HTTP_UNAUTHORIZED);
		} else {

			$data = [
				'id_lapak' => $request->id_lapak,
				'nama_menu' => $request->nama_menu,
				'deskripsi_menu' => $request->deskripsi_menu,
				'harga' => $request->harga,
				'status' => $request->status,
				'diskon' => $request->diskon,
				'rating' => "0",
			];

			if ($request->foto_posting_lapak) {
				$foto_posting_lapak = Str::limit($request->foto_posting_lapak, 500000);
				$nama_file = "Lapak_Posting_" . time() . ".jpeg";
				$tujuan_upload = public_path() . '/Images/Lapak/Posting/Normal/';
				if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_posting_lapak))) {
					$data['foto_posting_lapak'] = $nama_file;
				}

				$img = Image::make($tujuan_upload . $nama_file);
				$img->resize(250, 250)->save(public_path() . '/Images/Lapak/Posting/Thumbnail/' . $nama_file);
			}

			$lastid = PostingLapak::create($data)->id;

			$menu_detail = PostingLapakDetail::create([
				'id_posting_lapak' => $lastid,
				'id_kategori' => $request->id_kategori
			]);

			if ($menu_detail) {
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
	}

	//mengambil menu pada role lapak
	public function lapak_get_posting_lapak($id)
	{

		$get_posting_lapak = PostingLapak::where('id_lapak', $id)->get();

		return response()->json([

			'Hasil Menu' => $get_posting_lapak

		]);
	}

	//edit postingan pada role lapak
	public function lapak_update_posting(Request $request, $id)
	{

		$posting = Posting::find($id);
		$data = [
			'id_lapak' => $request->id_lapak,
			'nama_menu' => $request->nama_menu,
			'deskripsi_menu' => $request->deskripsi_menu,
			'harga' => $request->harga,
			'status' => $request->status,
			'diskon' => $request->diskon,
			'rating' => "0",
		];

		// $menu_detail = PostingLapakDetail::create([
		// 	'id_posting_lapak' => $lastid,
		// 	'id_kategori' => $request->id_kategori
		// ]);

		if ($posting->update($data)) {
			$out = [
				"message" => "update-posting_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "update-posting_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}

	public function lapak_delete_posting($id)
	{
		$posting_lapak = PostingLapak::findOrFail($id);
		if ($posting_lapak->foto_posting_lapak) {
			File::delete('Images/Lapak/Posting/Normal/' . $posting_lapak->foto_posting_lapak);
			File::delete('Images/Lapak/Posting/Thumbnail/' . $posting_lapak->foto_posting_lapak);
		}
		$pld = PostingLapakDetail::where('id_posting_lapak', $posting_lapak->id)->get();

		$del = $pld->each->delete();
		$del_pos = $posting_lapak->delete();

		if ($del && $del_pos) {
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
