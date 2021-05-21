<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Driver;
use App\Order;
use App\Posting;
use Image;
use App\Lapak;
use App\Menu;
use App\Customer;
use DB;
use Carbon\Carbon;

class DriverApiController extends Controller
{
    public function index()
    {
    }

    public function update(Request $request, $id_user)
    {
        $data_user = [
            'nama' => $request->nama,
        ];

        $data = [
            'alamat' => $request->alamat,
//          'jenis_motor' => $request->jenis_motor,
//          'warna_motor' => $request->warna_motor,
//          'plat_nomor' => $request->plat_nomor,
            'latitude_driver' => $request->latitude_driver,
            'longitude_driver' => $request->longitude_driver,
        ];

        $driver = Driver::where('id_user', $id_user)->first();
        $user = User::find($id_user);
        
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

        if ($driver->update($data) && $user->update($data_user)) {
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
        if($posting == []){
            foreach ($posting as $key => $value) {
                $nama = User::find($user->id_user);
                $posting_driver[] = [
                    "nama" => $nama->nama,
                    "id" => $value->id,
                    "judul_posting" => $value->judul_posting,
                    "deskripsi_posting" => $value->deskripsi_posting,
                    "foto_posting" => $value->foto_posting,
                    "harga" => $value->harga,
                    "status" => $value->status,
                    "durasi" => $value->durasi,
                    "id_driver" => $value->id_driver,
                    "longitude_posting" => $value->longitude_posting,
                    "latitude_posting" => $value->latitude_posting,
                ];
            }
        } else {
            $posting_driver = [];
        }
        
            return response()->json([
                'posting_driver' => $posting_driver
            ], Response::HTTP_OK);
    }

    public function driver_posting(Request $request, $id_user)
    {
        $str = Str::length($request->foto_posting);
        // Jika file gambar lebih dari 1.15 Mb 
        if ($str >= 2500000) {
            $pesan = "Foto terlalu besar";

            return $pesan;
        } else {

            $driver = Driver::where('id_user', $id_user)->first();
            
            setlocale(LC_TIME, 'nl_NL.utf8');
            Carbon::setLocale('id');
    
            $tgl = Carbon::now();
            $batas_durasi = $tgl->addMinutes($request->durasi);
            $data = [
                'judul_posting' => $request->judul_posting,
                'deskripsi_posting' => $request->deskripsi_posting,
                'harga' => $request->harga,
                'status' => $request->status,
                'durasi' => $request->durasi,
                'batas_durasi' => $batas_durasi,
                'latitude_posting' => $request->latitude_posting,
                'longitude_posting' => $request->longitude_posting,
                'id_driver' => $driver->id,
            ];

            if ($request->foto_posting) {
                $foto_posting = Str::limit($request->foto_posting, 500000);
                $nama_file = "Driver_Posting_" . time() . ".jpeg";
                $tujuan_upload = public_path() . '/Images/Driver/Posting/Normal/';
                if (file_put_contents($tujuan_upload . $nama_file, base64_decode($foto_posting))) {
                    $data ['foto_posting'] = $nama_file;
                }

                $img = Image::make($tujuan_upload . $nama_file);
                $img->resize(250, 250)->save(public_path().'/Images/Driver/Posting/Thumbnail/'.$nama_file);

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
    
    public function driver_lihat_order($id_order)
    {
        $order = Order::where('id_driver', $id_order)->get();
        $data = [];
        foreach ($order as $order => $val) {
            $data[] = $val;
            
            $lapak = Lapak::findOrFail($val->id_lapak);
            $customer = Customer::findOrFail($val->id_customer);

            $val['nama_usaha'] = $lapak->nama_usaha;
            $val['no_telp_lapak'] = $lapak->user->no_telp;
            $val['foto_profile_lapak'] = $lapak->foto_profile;
            $val['nama_customer'] = $customer->user->nama;
            $val['no_telp_customer'] = $customer->user->no_telp;
            $val['foto_profile_customer'] = $customer->foto_profile;

            $order_detail = Menu::leftJoin('order_detail', function ($join) {
                $join->on('menu.id', '=', 'order_detail.id_menu');
            })
                ->select('menu.nama_menu', 'order_detail.*')
                ->where('id_order', $val->id)
                ->get();
            // $val['no_telp_customer_2'] = $order_detail['no_telp'];

            foreach ((array)$order_detail as $key => $value) {

                // $menu = Menu::find($value->id_menu);
                $val['detail_orderan'] = $value;
            }
        }

        return response()->json([
            'Hasil' => $data
        ]);
    }

}