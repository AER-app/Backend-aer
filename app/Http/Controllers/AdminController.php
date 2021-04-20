<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Driver;
use App\Lapak;
use App\User;
use App\Customer;
use App\Kecamatan;
use App\Order;

class AdminController extends Controller
{
    public function index()
    {
        $total_driver = Driver::all()->count();
        $total_lapak = Lapak::all()->count();
        $total_customer = Customer::all()->count();
        $total_order = Order::all()->count();

        return view('admin.dashboard.index', compact('total_driver', 'total_lapak', 'total_customer', 'total_order'));
    }

    public function login()
    {
        return view('admin.login');
    }

    public function post_login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $login = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($login)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function driver_index(Request $request)
    {
        $data = Driver::all();
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();
        return view('admin.driver.index', compact('data', 'kecamatan'));
    }

    public function driver_create(Request $request)
    {
        $no_telp = User::where('no_telp', $request->no_telp)->first();
        if ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
        $this->validate($request, [
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_stnk' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_motor' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $user = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt('driveraer'),
            'role' => 'driver',
            'status' => 'nonaktif',
        ];

        $lastid = User::create($user)->id;
        
        $data = [
            'alamat' => $request->alamat,
            'jenis_motor' => $request->jenis_motor,
            'plat_nomor' => $request->plat_nomor,
            'warna_motor' => $request->warna_motor,
            'id_provinsi' => '35',
            'id_kabupaten' => '3510',
            'id_kecamatan1' => $request->id_kecamatan1,
            'id_kecamatan2' => $request->id_kecamatan2,
            'id_user' => $lastid
        ]; 

        if ($file = $request->file('foto_ktp')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Ktp/', $nama_file);  
            $data['foto_ktp'] = $nama_file;
        }
        if ($file = $request->file('foto_kk')) {
            $nama_file = "Kk_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Kk/', $nama_file);  
            $data['foto_kk'] = $nama_file;
        }
        if ($file = $request->file('foto_sim')) {
            $nama_file = "Sim_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Sim/', $nama_file);  
            $data['foto_sim'] = $nama_file;
        }
        if ($file = $request->file('foto_stnk')) {
            $nama_file = "Stnk_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Stnk/', $nama_file);  
            $data['foto_stnk'] = $nama_file;
        }
        if ($file = $request->file('foto_motor')) {
            $nama_file = "Motor_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Motor/', $nama_file);  
            $data['foto_motor'] = $nama_file;
        }

        Driver::create($data);
        
        return redirect()->route('driver')->with('success', 'Data Driver '. $request->nama .' berhasil ditambahkan. dengan password = driveraer');
    }

    public function driver_detail(Request $request, $id)
    {
        $data = Driver::findOrFail($id);
        $user = User::findOrFail($data->id_user);
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();

        return view('admin.driver.detail', compact('data', 'user', 'kecamatan'));
    }
    
    public function driver_update(Request $request, $id)
    {
        $no_telp = User::where('no_telp', $request->no_telp)->first();
        if ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
        $this->validate($request, [
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_stnk' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_motor' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $data_user = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
        ];

        $driver = Driver::findOrFail($id);
        $user = User::where('id', $driver->user_id)->first();
        
        $data_driver = [
            'alamat' => $request->alamat,
            'jenis_motor' => $request->jenis_motor,
            'plat_nomor' => $request->plat_nomor,
            'warna_motor' => $request->warna_motor,
            'id_kecamatan1' => $request->id_kecamatan1,
            'id_kecamatan2' => $request->id_kecamatan2,
        ]; 

        if ($file = $request->file('foto_profile')) {
            $nama_file = "Profile_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Profile/', $nama_file);  
            $data_driver['foto_profile'] = $nama_file;
        }
        if ($file = $request->file('foto_ktp')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Ktp/', $nama_file);  
            $data_driver['foto_ktp'] = $nama_file;
        }
        if ($file = $request->file('foto_kk')) {
            $nama_file = "Kk_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Kk/', $nama_file);  
            $data_driver['foto_kk'] = $nama_file;
        }
        if ($file = $request->file('foto_sim')) {
            $nama_file = "Sim_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Sim/', $nama_file);  
            $data_driver['foto_sim'] = $nama_file;
        }
        if ($file = $request->file('foto_stnk')) {
            $nama_file = "Stnk_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Stnk/', $nama_file);  
            $data_driver['foto_stnk'] = $nama_file;
        }
        if ($file = $request->file('foto_motor')) {
            $nama_file = "Motor_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Motor/', $nama_file);  
            $data_driver['foto_motor'] = $nama_file;
        }

        $user->update($data_user);
        $driver->update($data_driver);
        
        return back()->with('success', 'Data Driver '. $request->nama .' berhasil diupdate');
    }

    public function lapak_index(Request $request)
    {
        $data = Lapak::all();
        return view('admin.lapak.index', compact('data'));
    }
    
    public function lapak_create(Request $request)
    {
        $no_telp = User::where('no_telp', $request->no_telp)->first();
        if ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
        $this->validate($request, [
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_usaha' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_umkm' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $user = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt('lapakaer'),
            'role' => 'lapak',
            'status' => 'nonaktif',
        ];

        $lastid = User::create($user)->id;
        
        $data = [
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'jenis_usaha' => $request->jenis_usaha,
            'keterangan' => $request->keterangan,
            'id_user' => $lastid
        ]; 

        if ($file = $request->file('foto_ktp')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Lapak/Ktp/', $nama_file);  
            $data['foto_ktp'] = $nama_file;
        }
        if ($file = $request->file('foto_usaha')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Lapak/Usaha/', $nama_file);  
            $data['foto_usaha'] = $nama_file;
        }
        if ($file = $request->file('foto_umkm')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Lapak/Umkm/', $nama_file);  
            $data['foto_umkm'] = $nama_file;
        }

        Lapak::create($data);
        
        return redirect()->route('lapak')->with('success', 'Data Lapak '. $request->nama_usaha .' berhasil ditambahkan. dengan password = lapakaer');
    }
    
    public function customer_index(Request $request)
    {
        $data = Customer::all();

        return view('admin.customer.index', compact('data'));
    }
}
