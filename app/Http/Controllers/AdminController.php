<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Driver;
use App\Lapak;
use App\User;
use App\Customer;
use App\Kecamatan;
use App\Order;
use App\Slideshow;

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
        $no_telp = User::where('no_telp', $request->no_telp)->where('role', 'driver')->first();
        
        $cekemail = User::where('email', $request->email)->where('role', 'driver')->first();
        if ($cekemail) {
            return redirect()->back()->with('error', 'Email pengguna sudah digunakan');
        }

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
            'password' => bcrypt("driveraer"),
            'role' => 'driver',
            'otp' => rand(100000, 999999),
            'status' => '0',
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
            'id_user' => $lastid,
            'latitude_driver' => $request->latitude,
            'longitude_driver' => $request->longitude,
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
        
        return redirect()->route('driver')->with('success', 'Data Driver '. $request->nama .' berhasil ditambahkan. Silahkan login dengan password = driveraer');
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
        $driver = Driver::findOrFail($id);
        $user = User::where('id', $driver->id_user)->first();
        $no_telp_user = User::where('no_telp', $user->no_telp)->where('role', 'driver')->first();
        $no_telp = User::where('no_telp', $request->no_telp)->where('role', 'driver')->first();

        if ($request->no_telp == $no_telp_user->no_telp || $no_telp == null) {

            $data_user = [
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => bcrypt($request->password),
            ];

            $data_driver = [
                'alamat' => $request->alamat,
                'jenis_motor' => $request->jenis_motor,
                'plat_nomor' => $request->plat_nomor,
                'warna_motor' => $request->warna_motor,
                'id_kecamatan1' => $request->id_kecamatan1,
                'id_kecamatan2' => $request->id_kecamatan2,
            ]; 

            if ($file = $request->file('foto_profile')) {
                if ($driver->foto_profile) {
                    File::delete('Images/Driver/Profile/'.$driver->foto_profile);
                }
                $nama_file = "Profile_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Profile/', $nama_file);  
                $data_driver['foto_profile'] = $nama_file;
            }
            if ($file = $request->file('foto_ktp')) {
                if ($driver->foto_ktp) {
                    File::delete(public_path('Images/Driver/Ktp/'.$driver->foto_ktp));
                }
                $nama_file = "Ktp_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Ktp/', $nama_file);  
                $data_driver['foto_ktp'] = $nama_file;
            }
            if ($file = $request->file('foto_kk')) {
                if ($driver->foto_kk) {
                    File::delete(public_path('Images/Driver/Kk/'.$driver->foto_kk));
                }
                $nama_file = "Kk_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Kk/', $nama_file);  
                $data_driver['foto_kk'] = $nama_file;
            }
            if ($file = $request->file('foto_sim')) {
                if ($driver->foto_sim) {
                    File::delete(public_path('Images/Driver/Sim/'.$driver->foto_sim));
                }
                $nama_file = "Sim_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Sim/', $nama_file);  
                $data_driver['foto_sim'] = $nama_file;
            }
            if ($file = $request->file('foto_stnk')) {
                if ($driver->foto_stnk) {
                    File::delete(public_path('Images/Driver/Stnk/'.$driver->foto_stnk));
                }
                $nama_file = "Stnk_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Stnk/', $nama_file);  
                $data_driver['foto_stnk'] = $nama_file;
            }
            if ($file = $request->file('foto_motor')) {
                if ($driver->foto_motor) {
                    File::delete(public_path('Images/Driver/Ktp/'.$driver->foto_motor));
                }
                $nama_file = "Motor_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Driver/Motor/', $nama_file);  
                $data_driver['foto_motor'] = $nama_file;
            }

            $user->update($data_user);
            $driver->update($data_driver);
            
            return back()->with('success', 'Data Driver '. $request->nama .' berhasil diupdate');

        } elseif ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
    }

    public function lapak_index(Request $request)
    {
        $data = Lapak::all();
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();

        return view('admin.lapak.index', compact('data', 'kecamatan'));
    }
    
    public function lapak_create(Request $request)
    {
        $no_telp = User::where('no_telp', $request->no_telp)->where('role', 'lapak')->first();
        
        $cekemail = User::where('email', $request->email)->where('role', 'lapak')->first();
        if ($cekemail) {
            return redirect()->back()->with('error', 'Email pengguna sudah digunakan');
        }

        if ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
        $this->validate($request, [
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_usaha' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_umkm' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'foto_npwp' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $user = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt('lapakaer'),
            'role' => 'lapak',
            'status' => '0',
        ];

        $lastid = User::create($user)->id;
        
        $data = [
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'jenis_usaha' => $request->jenis_usaha,
            'nomor_rekening' => $request->nomor_rekening,
            'keterangan' => $request->keterangan,
            'id_provinsi' => '35',
            'id_kabupaten' => '3510',
            'id_kecamatan1' => $request->id_kecamatan1,
            'id_kecamatan2' => $request->id_kecamatan2,
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
        if ($file = $request->file('foto_npwp')) {
            $nama_file = "Npwp_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Lapak/Npwp/', $nama_file);  
            $data['foto_npwp'] = $nama_file;
        }

        Lapak::create($data);
        
        return redirect()->route('lapak')->with('success', 'Data Lapak '. $request->nama_usaha .' berhasil ditambahkan. Silahkan login dengan password = lapakaer');
    }

    public function lapak_detail(Request $request, $id)
    {
        $data = Lapak::findOrFail($id);
        $user = User::findOrFail($data->id_user);
        $kecamatan = Kecamatan::where('city_id', 3510)->orderBy('name', 'ASC')->get();

        return view('admin.lapak.detail', compact('data', 'user', 'kecamatan'));
    }

    public function lapak_update(Request $request, $id)
    {
        $lapak = Lapak::findOrFail($id);
        $user = User::where('id', $lapak->id_user)->first();
        $no_telp_user = User::where('no_telp', $user->no_telp)->where('role', 'lapak')->first();
        $no_telp = User::where('no_telp', $request->no_telp)->where('role', 'lapak')->first();

        if ($request->no_telp == $no_telp_user->no_telp || $no_telp == null) {

            $data_user = [
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => bcrypt($request->password),
            ];

            $data_lapak = [
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'alamat' => $request->alamat,
                'nomor_rekening' => $request->nomor_rekening,
                'jenis_usaha' => $request->jenis_usaha,
                'keterangan' => $request->keterangan,
                'id_kecamatan1' => $request->id_kecamatan1,
                'id_kecamatan2' => $request->id_kecamatan2,
            ]; 

            if ($file = $request->file('foto_profile')) {
                if ($lapak->foto_profile) {
                    File::delete('Images/Lapak/Profile/'.$lapak->foto_profile);
                }
                $nama_file = "Profile_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Lapak/Profile/', $nama_file);  
                $data_lapak['foto_profile'] = $nama_file;
            }
            if ($file = $request->file('foto_ktp')) {
                if ($lapak->foto_ktp) {
                    File::delete(public_path('Images/Lapak/Ktp/'.$lapak->foto_ktp));
                }
                $nama_file = "Ktp_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Lapak/Ktp/', $nama_file);  
                $data_lapak['foto_ktp'] = $nama_file;
            }
            if ($file = $request->file('foto_umkm')) {
                if ($lapak->foto_umkm) {
                    File::delete(public_path('Images/Lapak/Umkm/'.$lapak->foto_umkm));
                }
                $nama_file = "Umkm_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Lapak/Umkm/', $nama_file);  
                $data_lapak['foto_umkm'] = $nama_file;
            }
            if ($file = $request->file('foto_npwp')) {
                if ($lapak->foto_npwp) {
                    File::delete(public_path('Images/Lapak/Npwp/'.$lapak->foto_npwp));
                }
                $nama_file = "Npwp_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Lapak/Npwp/', $nama_file);  
                $data_lapak['foto_npwp'] = $nama_file;
            }
            if ($file = $request->file('foto_usaha')) {
                if ($lapak->foto_usaha) {
                    File::delete(public_path('Images/Lapak/Usaha/'.$lapak->foto_usaha));
                }
                $nama_file = "Usaha_".time(). ".jpeg";
                $file->move(public_path() . '/Images/Lapak/Usaha/', $nama_file);  
                $data_lapak['foto_usaha'] = $nama_file;
            }

            $user->update($data_user);
            $lapak->update($data_lapak);
            
            return back()->with('success', 'Data Lapak '. $request->nama .' berhasil diupdate');

        } elseif ($no_telp) {
            return redirect()->back()->with('error', 'No telepon pengguna sudah digunakan');
        }
    }

    public function lapak_update_status(Request $request, $id_user)
    {
        $user = User::where('id', $id_user)->first();
        
        $data_user = [
            'status' => "1"
        ];

        $user->update($data_user);
        
        return back()->with('success', 'Data Lapak '. $request->nama .' berhasil approved');

    }
    
    public function customer_index(Request $request)
    {
        $data = Customer::all();

        return view('admin.customer.index', compact('data'));
    }

    public function promosi_index(Request $request)
    {
        $data = Slideshow::all();

        return view('admin.promosi.index', compact('data'));
    }
    
    public function promosi_create(Request $request)
    {
        $this->validate($request, [
            'foto_slideshow' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $data = [
            'judul_slideshow' => $request->judul_slideshow,
            'deskripsi_slideshow' => $request->deskripsi_slideshow,
            'link' => $request->link,
            'menu' => $request->menu,
            'kategori' => $request->kategori,
            'status' => '1',
        ]; 

        if ($file = $request->file('foto_slideshow')) {
            $nama_file = "Slideshow_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Slideshow/', $nama_file);  
            $data['foto_slideshow'] = $nama_file;
        }

        Slideshow::create($data);
        
        return redirect()->route('promosi')->with('success', 'Data Promosi '. $request->judul_slideshow .' berhasil ditambahkan.');
    }
}
