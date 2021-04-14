<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Driver;
use App\Lapak;
use App\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
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

    public function driver_index(Request $request)
    {
        $data = Driver::all();
        return view('admin.driver.index', compact('data'));
    }
    public function driver_create(Request $request)
    {
        $no_telp = User::where('no_telp', $request->no_telp)->first();
        if ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
        $this->validate($request, [
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_motor' => 'required',
            'plat_nomor' => 'required',
            'warna_motor' => 'required',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg',
            'foto_stnk' => 'required|image|mimes:jpeg,png,jpg',
            'foto_motor' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $user = [
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt('driveraer'),
            'role' => 'driver',
            'status' => 'nonaktif',
        ];

        $lastid = User::create($user)->id;
        
        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_motor' => $request->jenis_motor,
            'plat_nomor' => $request->plat_nomor,
            'warna_motor' => $request->warna_motor,
            'id_user' => $lastid
        ]; 

        if ($file = $request->file('foto_ktp')) {
            $nama_file = "Ktp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Driver/Ktp/', $nama_file);  
            $data['foto_ktp'] = $nama_file;
        }
        if ($file = $request->file('foto_kk')) {
            $nama_file = "Kk_".time(). ".jpeg";
            $file->save(public_path() . '/Images/Driver/Kk/', $nama_file);  
            $data['foto_kk'] = $nama_file;
        }
        if ($file = $request->file('foto_sim')) {
            $nama_file = "Sim_".time(). ".jpeg";
            $file->save(public_path() . '/Images/Driver/Sim/', $nama_file);  
            $data['foto_sim'] = $nama_file;
        }
        if ($file = $request->file('foto_stnk')) {
            $nama_file = "Stnk_".time(). ".jpeg";
            $file->save(public_path() . '/Images/Driver/Stnk/', $nama_file);  
            $data['foto_stnk'] = $nama_file;
        }
        if ($file = $request->file('foto_motor')) {
            $nama_file = "Motor_".time(). ".jpeg";
            $file->save(public_path() . '/Images/Driver/Motor/', $nama_file);  
            $data['foto_motor'] = $nama_file;
        }

        Driver::create($data);
        
        return redirect()->route('driver')->with('success', 'Data Driver '. $request->nama .' berhasil ditambahkan. dengan password = driveraer');
    }

    public function lapak_index(Request $request)
    {
        $data = Lapak::all();
        return view('admin.lapak.index', compact('data'));
    }
    public function lapak_create(Request $request)
    {
        # code...
    }
    
    public function customer_index(Request $request)
    {
        return view('admin.customer.index');
    }
}
