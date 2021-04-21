<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Order;
use App\Posting;
use DB;
use App\Haversine;



class DriverApiController extends Controller
{
    
    public function index()
    {
        
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        if ($request->foto_ktp) {
            $nama_file = "Ktp_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Ktp/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_ktp))) 
            {
                $data = ['foto_ktp' => $nama_file];
            } 
        }
        if ($request->foto_kk) {
            $nama_file = "Kk_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Kk/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_kk))) 
            {
                $data = ['foto_kk' => $nama_file];
            } 
        }
        if ($request->foto_sim) {
            $nama_file = "Sim_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Sim/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_sim))) 
            {
                $data = ['foto_sim' => $nama_file];
            } 
        }
        if ($request->foto_stnk) {
            $nama_file = "Stnk_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Stnk/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_stnk))) 
            {
                $data = ['foto_stnk' => $nama_file];
            } 
        }
        if ($request->foto_motor) {
            $nama_file = "Motor_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Motor/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_motor))) 
            {
                $data = ['foto_motor' => $nama_file];
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

        if ($driver->update($data)) {
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


   
    public function driver_get_order()
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


        for ($i=0; $i < $length ; $i++) {
            if ($driver[$i]->id_kecamatan1 == $lapak->id_kecamatan1 || $driver[$i]->id_kecamatan2 == $lapak->id_kecamatan1) {
        
        $show_order = DB::table('order')
        ->join('lapak', 'order.id_lapak', '=', 'lapak.id')
        ->join('customer', 'order.id_customer', '=', 'customer.id')
        ->select('order.*', 'lapak.latitude_lap', 'lapak.longitude_lap', 'lapak.id_kecamatan1', 'lapak.id_kecamatan2', 'customer.latitude_cus', 'customer.longitude_cus')
        ->where('id_driver', null)
        ->orderBy('id','DESC')
        ->first();

                $tes[] = $driver[$i];
            }
        }

        $lat_lapak = $show_order->latitude_lap;
        $long_lapak = $show_order->longitude_lap;

        $hasil = array();
        foreach ($tes as $value) {


            $jarak = round($hitung->distance($value->latitude_driver, $value->longitude_driver, $lat_lapak, $long_lapak, "K"), 1);
            $hasil[] =['orderan' =>$show_order,'KM' => $jarak,'id'=>$value->id_user] ;
        }
       
        $c = collect($hasil);
        $sort = $c->SortBy('KM');
        return $sort->values()->all();
        
        
        //    return response()->json(['driver' => $tes, 'order' => $show_order, 'jarak' => $hasil], 200, );
    }
        

    public function driver_terima_order(){
        


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
        if ($request->foto_posting) {
            $nama_file = "Posting_".time()."jpeg";
            $tujuan_upload = public_path() . '/Driver/Posting/';
            if (file_put_contents($tujuan_upload . $nama_file , base64_decode($request->foto_posting))) 
            {
                $data = ['foto_posting' => $nama_file];
            } 
        }

        $data = [
            'judul_posting' => $request->judul_posting,
            'deskripsi_posting' => $request->deskripsi_posting,
            'harga' => $request->harga,
            'status' => $request->status,
            'durasi' => $request->durasi,
            'id_driver' => $id,
        ];

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
