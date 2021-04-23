<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\CustomerOffline;
use App\Lapak;
use App\Menu;
use App\Haversine;
use DB;
use App\Driver;
use App\Order;
use App\Jastip;
use App\OrderDetail;
use App\OrderDetailOffline;
use App\OrderOffline;

use Illuminate\Http\Request;

class OrderApiController extends Controller
{

	//proses tamboh orderan 
	public function order_tambah_order(Request $request)
	{

		$id_menu = $request->id_menu;
		$id_jastip = $request->id_jastip;
		$no_telp = $request->no_telp;
		$note = $request->note;
		$jarak = $request->jarak;
		$harga = $request->harga;


		$data = ([
			'kode_order' => $request->kode_order,
			'id_customer' => $request->id_customer,
			'id_lapak' => $request->id_lapak,
			'ongkir' => $request->ongkir,
			'total_harga' => $request->total_harga,
			'longitude' => $request->longitude,
			'latitude' => $request->latitude,
			'status_order' => 'waiting',
		]);

		$lastid = Order::create($data)->id;

		$order_detail = OrderDetail::create([
			'id_order' => $lastid,
			'id_menu' => $id_menu,
			'id_jastip' => $id_jastip,
			'no_telp' => $no_telp,
			'note' => $note,
			'jarak' => $jarak,
			'harga' => $harga

		]);



		if ($lastid && $order_detail) {
			$out = [
				"message" => "tambah-order_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-order_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}


	//proses tambah jastip dari orderan yang muncul
	public function order_tambah_jastip(Request $request)
	{

		$id_order = $request->id_order;
		$id_menu = $request->id_menu;
		$no_telp = $request->no_telp;
		$note = $request->note;
		$jarak = $request->jarak;
		$harga = $request->harga;


		$data = ([
			'id_order' => $request->id_order,
			'id_driver' => $request->id_driver,
			'id_customer' => $request->id_customer,
			'kode_jastip' => $request->kode_jastip,
			'status_jastip' => $request->status_jastip,
		]);

		$lastid = Jastip::create($data)->id;

		$order_detail = OrderDetail::create([
			'id_order' => $id_order,
			'id_menu' => $id_menu,
			'id_jastip' => $lastid,
			'no_telp' => $no_telp,
			'note' => $note,
			'jarak' => $jarak,
			'harga' => $harga

		]);



		if ($lastid && $order_detail) {
			$out = [
				"message" => "tambah-jastip_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-jastip_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}



	//proses tambah jastip dari orderan yang muncul
	public function order_tambah_order_customer_offline(Request $request)
	{


		$id_menu = $request->id_menu;
		$no_telp = $request->no_telp;
		$note = $request->note;
		$jarak = $request->jarak;
		$harga = $request->harga;


		$data = ([
			'nama' => $request->nama,
			'alamat' => $request->alamat,
			'no_telp' => $request->no_telp,
			'longitude' => $request->longitude,
			'latitude' => $request->latitude,
		]);

		$lastid = CustomerOffline::create($data)->id;


		$data2 = ([
			'kode_order_offline' => $request->kode_order_offline,
			'id_customer_offline' => $lastid,
			'id_driver' => $request->id_driver,
			'id_lapak' => $request->id_lapak,
			'ongkir' => $request->ongkir,
			'total_harga' => $request->total_harga,
			'status_order_offline' => $request->status_order_offline,
		]);

		$lastid2 = OrderOffline::create($data2)->id;


		$order_detail_offline = OrderDetailOffline::create([
			'id_order_offline' => $lastid2,
			'id_menu' => $id_menu,
			'no_telp' => $no_telp,
			'note' => $note,
			'jarak' => $jarak,
			'harga' => $harga,

		]);



		if ($lastid && $lastid2 && $order_detail_offline) {
			$out = [
				"message" => "tambah-offline_success",
				"code"    => 201,
			];
		} else {
			$out = [
				"message" => "tambah-jastip_failed",
				"code"   => 404,
			];
		}

		return response()->json($out, $out['code']);
	}

	public function order_driver_get_order()
	{
		$driver = Driver::all();
		$hitung = new Haversine();

		$lapak = DB::table('order')
			->join('lapak', 'order.id_lapak', '=', 'lapak.id')
			->where('id_driver', null)
			->select('lapak.id_kecamatan1')->first();
		// dd($lapak);
		$length = count($driver);
		$tes = null;
		$show_order = null;


		for ($i = 0; $i < $length; $i++) {
			if ($driver[$i]->id_kecamatan1 == $lapak->id_kecamatan1 || $driver[$i]->id_kecamatan2 == $lapak->id_kecamatan1) {

				$show_order = DB::table('order')
					->join('lapak', 'order.id_lapak', '=', 'lapak.id')
					->join('customer', 'order.id_customer', '=', 'customer.id')
					->select('order.*', 'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.id_kecamatan1', 'lapak.id_kecamatan2', 'customer.latitude_cus', 'customer.longitude_cus')
					->where('id_driver', null)
					->orderBy('id', 'DESC')
					->first();

				$tes[] = $driver[$i];
			}
		}

		$lat_lapak = $show_order->latitude_lap;
		$long_lapak = $show_order->longitude_lap;

		$hasil = array();
		foreach ($tes as $value) {

			$jarak = round($hitung->distance($value->latitude_driver, $value->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
			$hasil[] = ['orderan' => $show_order, 'KM' => $jarak, 'id' => $value->id_user];
		}

		$c = collect($hasil);
		$sort = $c->SortBy('KM');
		return $sort->values()->all();


		//    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
	}
}
