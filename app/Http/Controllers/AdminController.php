<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Bantuan;
use App\Driver;
use App\Lapak;
use App\User;
use App\Customer;
use App\JadwalLapak;
use App\Kategori;
use App\Kecamatan;
use App\Menu;
use App\Notif;
use App\MenuDetail;
use App\Order;
use App\OrderDetail;
use App\OrderPosting;
use App\Jastip;
use App\JastipDetail;
use App\Posting;
use App\HistoryCariDriver;
use App\PromoOngkir;
use App\Haversine;
use App\Slideshow;
use App\Testimoni;
use App\BroadcastNotif;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function driver_index(Request $request)
    {
        $data = Driver::where('status_driver', '!=', 'bermasalah')->orderBy('id', 'DESC')->get();
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
                // 'password' => bcrypt($request->password),
            ];

            $data_driver = [
                'alamat' => $request->alamat,
                'jenis_motor' => $request->jenis_motor,
                'plat_nomor' => $request->plat_nomor,
                'warna_motor' => $request->warna_motor,
                'id_kecamatan1' => $request->id_kecamatan1,
                'id_kecamatan2' => $request->id_kecamatan2,
                'status_driver' => $request->status_driver,
                'status_order_driver' => $request->status_order_driver,
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
            return back()->with('error', 'No telepon pengguna sudah digunakan');
        }
    }

    public function driver_delete($id)
    {
        $driver = Driver::find($id);
        $user = User::find($driver->id_user);
        // if ($driver->foto_profile) {
		// 	File::delete('Images/Driver/Profile/' . $driver->foto_profile);
		// }
        // if ($driver->foto_ktp) {
		// 	File::delete('Images/Driver/Ktp/' . $driver->foto_ktp);
		// }
        // if ($driver->foto_kk) {
		// 	File::delete('Images/Driver/Kk/' . $driver->foto_kk);
		// }
        // if ($driver->foto_sim) {
		// 	File::delete('Images/Driver/Sim/' . $driver->foto_sim);
		// }
        // if ($driver->foto_stnk) {
		// 	File::delete('Images/Driver/Stnk/' . $driver->foto_stnk);
		// }
        // if ($driver->foto_motor) {
		// 	File::delete('Images/Driver/Motor/' . $driver->foto_motor);
		// }

        $driver->update([
            'id_user' => null,
            'status_driver' => 'bermasalah'
        ]);

        if ($user->delete()) {
            return back()->with('success', 'Data Driver berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
        return back()->with('error', 'Data gagal dihapus');
    }
    
    public function driver_tambah_saldo(Request $request,   $id){
        $driver = Driver::find($id);
        $data = [
                'saldo' => $driver->saldo + $request->saldo,
            ];
        $driver->update($data);
        
        return redirect()->route('driver')->with('success', 'Berhasil Menambahkan Saldo Driver'. $driver->user->nama);
    }

    public function driver_posting_index()
    {
        $data = Posting::orderBy('id', 'DESC')->get();
        
        return view('admin.driver.posting.index', compact('data'));
    }

    public function driver_posting_delete($id)
    {
        $posting = Posting::find($id);
        if ($posting->foto_posting) {
			File::delete('Images/Driver/Posting/' . $posting->foto_posting);
		}

        if ($posting->delete()) {
            return back()->with('success', 'Data Posting Driver berhasil dihapus');
        }
    }

    public function lapak_index(Request $request)
    {
        $data = Lapak::orderBy('id', 'DESC')->get();
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
            'nama_pemilik_usaha' => $request->nama_pemilik_usaha,
            'alamat' => $request->alamat,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
            'keterangan' => $request->keterangan,
            'latitude_lap' => $request->latitude_lap,
            'longitude_lap' => $request->longitude_lap,
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

        $lapak = Lapak::create($data)->id;

        $hari = (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']);
                foreach($hari as $h){
                $data_jadwal = [ 
                    'hari' => $h,
                    'id_lapak' => $lapak,
                    'status_buka' => 0
                ];
                
                JadwalLapak::create($data_jadwal);
                    
            }
        
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
                // 'password' => bcrypt($request->password),
            ];

            $data_lapak = [
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'nama_pemilik_usaha' => $request->nama_pemilik_usaha,
                'alamat' => $request->alamat,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
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

    public function lapak_delete($id)
    {
        $lapak = lapak::find($id);
        $user = User::find($lapak->id_user);
        if ($lapak->foto_profile) {
			File::delete('Images/Lapak/Profile/' . $lapak->foto_profile);
		}
        if ($lapak->foto_ktp) {
			File::delete('Images/Lapak/Ktp/' . $lapak->foto_ktp);
		}
        if ($lapak->foto_umkm) {
			File::delete('Images/Lapak/Kk/' . $lapak->foto_umkm);
		}
        if ($lapak->foto_usaha) {
			File::delete('Images/Lapak/Sim/' . $lapak->foto_usaha);
		}
        if ($lapak->foto_npwp) {
			File::delete('Images/Lapak/Stnk/' . $lapak->foto_npwp);
		}
        if ($lapak->foto_motor) {
			File::delete('Images/Lapak/Motor/' . $lapak->foto_motor);
		}

        $jadwal_lapak = JadwalLapak::where('id_lapak', $lapak->id)->get();
        if ($jadwal_lapak) {
            $jadwal_lapak->delete();
        }

        if ($lapak->delete() && $user->delete()) {
            return back()->with('success', 'Data Driver berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
        return back()->with('error', 'Data gagal dihapus');
    }

    public function lapak_menu_index()
    {
        $data = Menu::where('status', 'tersedia')->orderBy('id', 'DESC')->get();
        
        foreach($data as $value => $v){
            $kategori = MenuDetail::where('id_menu', $v->id)->get();
            $v['kategori'] = $kategori;
        }
        return view('admin.lapak.menu.index', compact('data'));
    }

    public function lapak_menu_delete($id)
    {
        $menu = Menu::find($id);
        if ($menu->foto_menu) {
			File::delete('Images/Lapak/Menu/' . $menu->foto_menu);
		}

        if ($menu->delete()) {
            return back()->with('success', 'Data Menu Lapak berhasil dihapus');
        }
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

    public function promosi_update(Request $request, $id)
    {

        $promosi = Slideshow::find($id);
        $data = [
            'judul_slideshow' => $request->judul_slideshow,
            'deskripsi_slideshow' => $request->deskripsi_slideshow,
            'link' => $request->link,
            'menu' => $request->menu,
            'kategori' => $request->kategori,
            'status' => $request->status,
        ]; 

        if ($file = $request->file('foto_slideshow')) {
            $nama_file = "Slideshow_".time(). ".jpeg";
            $file->move(public_path() . '/Images/Slideshow/', $nama_file);  
            $data['foto_slideshow'] = $nama_file;
        }

        $promosi->update($data);
        
        return redirect()->route('promosi')->with('success', 'Data Promosi '. $request->judul_slideshow .' berhasil ditambahkan.');
    }

    public function promosi_delete($id)
    {
        $promosi = Slideshow::find($id);
        if ($promosi->foto_slideshow) {
			File::delete('Images/Slideshow/' . $promosi->foto_slideshow);
		}

        if ($promosi->delete()) {
            return back()->with('success', 'Data Promosi berhasil dihapus');
        }
    }

    public function android_bantuan(Request $request)
    {
        $data = Bantuan::all();

        return view('admin.bantuan.android', compact('data'));
    }

    public function bantuan_index(Request $request)
    {
        $data = Bantuan::all();

        return view('admin.bantuan.index', compact('data'));
    }
    
    public function bantuan_create(Request $request)
    {
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]; 

        Bantuan::create($data);
        
        return redirect()->route('bantuan')->with('success', 'Data bantuan '. $request->judul .' berhasil ditambahkan.');
    }

    public function bantuan_update(Request $request, $id)
    {
        $bantuan = Bantuan::find($id);
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
        ];

        $bantuan->update($data);
        
        return redirect()->route('bantuan')->with('success', 'Data bantuan'. $request->judul .' berhasil ditambahkan.');
    }

    public function bantuan_delete($id)
    {
        $bantuan = Bantuan::find($id);

        if ($bantuan->delete()) {
            return back()->with('success', 'Data Promosi berhasil dihapus');
        }
    }

    public function kategori_menu_index(Request $request)
    {
        $data = Kategori::all();

        return view('admin.lapak.kategori_menu.index', compact('data'));
    }
    
    public function kategori_menu_create(Request $request)
    {
        $data = [
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis,
        ]; 

        Kategori::create($data);
        
        return redirect()->route('kategori_menu')->with('success', 'Data Kategori '. $request->nama_kategori .' berhasil ditambahkan.');
    }

    public function kategori_menu_delete($id)
    {
        $kategori = Kategori::find($id);

        if ($kategori->delete()) {
            return back()->with('success', 'Data Kategori Menu berhasil dihapus');
        }
    }

    public function order_index()
    {
        $data = Order::orderBy('id', 'DESC')->get();
        return view('admin.order.index', compact('data'));
    }
    
    public function order_detail(Request $request, $id)
    {
        $data = Order::findOrFail($id);

        $order_detail = OrderDetail::where('id_order', $data->id)->get();
        
        $jastip = Jastip::where('id_order', $data->id)->get();

        $data['order_detail'] = $order_detail;
        $data['jastip'] = $jastip;
        foreach($jastip as $val => $v){
            
            $detail_jastip = JastipDetail::where('id_jastip', $v->id)->get();
            
            $v['jastip_detail'] = $detail_jastip;
        }
        
        // return $data;
        return view('admin.order.detail', compact('data'));
    }
    
    public function order_delete($id)
    {
        $order = Order::find($id);
        $order_detail = OrderDetail::where('id_order',$id)->get();
        
        $jastip = Jastip::where('id_order', $id)->get();
        foreach($jastip as $jas => $v){
            $jastip_detail = JastipDetail::where('id_jastip',$v->id)->get();
            $jastip_detail->each->delete();
        }
        
		$del_jastip = $jastip->each->delete();
        $del_or = $order_detail->each->delete();
		$del_order = $order->delete();

        if ($del_order) {
            return back()->with('success', 'Data Order berhasil dihapus');
        }
    }
    
    public function order_jastip_delete($id)
    {
        $jastip = Jastip::find($id);
        
        $jastip_detail = JastipDetail::where('id_jastip',$jastip->id)->get();
        
        $order = Order::find($jastip->id_order);
        
        $order->update([
                'jumlah_jastip' => $order->jumlah_jastip - 1
            ]);
            
        $jastip_detail->each->delete();
        
		$del_jastip = $jastip->delete();

        if ($del_jastip) {
            return back()->with('success', 'Data Jastip berhasil dihapus');
        }
    }
    
    public function order_posting_index()
    {
        $data = OrderPosting::orderBy('id', 'DESC')->get();
        return view('admin.order_posting.index', compact('data'));
    }
    
    public function order_posting_delete($id)
    {
        $order = OrderPosting::find($id);
        
		$del_order = $order->delete();

        if ($del_order) {
            return back()->with('success', 'Data Order Posting berhasil dihapus');
        }
    }
    
    public function tes_orderan()
    {
        $order = Order::orderBy('id', 'DESC')->get();
        
        $history_cari_driver = [];
        $datanotif = [];
        
        foreach($order as $value => $v){
            
            $his = HistoryCariDriver::select([
                  // This aggregates the data and makes available a 'count' attribute
                  DB::raw('count(id) as `count`'), 
                  // This throws away the timestamp portion of the date
                  DB::raw('id_order as order_id')
                // Group these records according to that day
                ])->groupBy('order_id')
                ->orderBy('order_id', 'Desc')
                // And restrict these results to only those created in the last week
                // ->where('created_at', '>=', Carbon\Carbon::now()->subWeeks(1))
                ->get();
        }
        
        foreach($his as $value => $v){
            // $history_cari_driver = HistoryCariDriver::where('id_order', $v['order_id'])->get();
            $history_cari_driver = DB::table('history_cari_driver')
                ->join('users', 'history_cari_driver.id_user_driver', '=', 'users.id')
                ->select('users.nama', 'history_cari_driver.*')
                ->where('history_cari_driver.id_order' , $v['order_id'])
                ->get();
                
            $datanotif[] = [
                'id_order' => $v['order_id'],
                'notif_driver' => $history_cari_driver
            ];
            
        }
        
        return view('android_lihatorder', compact('order', 'datanotif'));
    }
    
    public function testimoni_form()
    {
        return view('admin.testimoni.form_masukan');
    }
    
    public function testimoni_admin()
    {
        $data = Testimoni::all();

        return view('admin.testimoni.index', compact('data'));
    }
    
    public function testimoni_create(Request $request)
    {
        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'isi' => $request->isi,
        ]; 

        Testimoni::create($data);
        
        return back()->with('success', 'Masukan anda telah kami terima. Terimakasih');
    }
    
    public function testimoni_delete($id)
    {
        $testimoni = Testimoni::find($id);
        
        $testimoni->delete();
        
        return back()->with('success', 'Berhasil hapus data');
    }
    
    public function promo_ongkir(){
        
        $data = PromoOngkir::orderBy('id', 'DESC')->get();
        return view('admin.promo_ongkir.index', compact('data'));
    }
    
    
    public function promo_ongkir_create(Request $request)
    {
        
        $data = [
            'jenis_promo' => $request->jenis_promo,
            'persen_promo' => $request->persen_promo,
            // 'durasi' => $request->durasi,
            'batas_durasi' => $request->batas_durasi,
        ]; 

        PromoOngkir::create($data);
        
        return back()->with('success', 'Data Promo berhasil ditambahkan.');
    }

    public function promo_ongkir_update(Request $request, $id)
    {
        $bantuan = PromoOngkir::find($id);
        $data = [
            'jenis_promo' => $request->jenis_promo,
            'persen_promo' => $request->persen_promo,
            // 'durasi' => $request->durasi,
            'batas_durasi' => $request->batas_durasi,
        ];

        $bantuan->update($data);
        
        return back()->with('success', 'Data Promo berhasil ditambahkan.');
    }

    public function promo_ongkir_delete($id)
    {
        $bantuan = PromoOngkir::find($id);

        if ($bantuan->delete()) {
            return back()->with('success', 'Data Promo berhasil dihapus');
        }
    }
    
    
    
    public function broadcast_notif(){
        
        $data = BroadcastNotif::orderBy('id', 'DESC')->get();
        return view('admin.broadcast_notif.index', compact('data'));
    }
    
    
     public function broadcast_notif_create(Request $request)
    {
        
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            // 'durasi' => $request->durasi,
            'role' => $request->role,
        ]; 
        
        $notif = new Notif();
        $token = User::where('role', $request->role)->whereNotNull('token')->pluck('token');
        $nama = User::where('role', $request->role)->whereNotNull('token')->pluck('nama');
        
        // $nama = User::where('role', $request->role)->pluck('nama');
       
        
        if($request->role == 'customer'){
            
          	$notif->sendCustomer($token, $nama, $request->isi, $request->judul, "aadriver");
            
        } elseif($request->role == 'driver'){
            
          	$notif->sendDriver($token, 1, "driver", $request->isi, $request->judul,"ada");
            
        } elseif($request->role == 'lapak'){
            
          	$notif->sendLapak($token, $nama,  $request->isi,  $request->judul, "ada");
            
        }
        
        //return $token;
        
        BroadcastNotif::create($data);
        
        return back()->with('success', 'Data Broadcast berhasil ditambahkan.');
    }
    
    
     public function broadcast_notif_update(Request $request, $id)
    {
        $bantuan = BroadcastNotif::find($id);
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            // 'durasi' => $request->durasi,
            'role' => $request->role,
        ];
        
        //return  $request->role;
        
          $notif = new Notif();
        $token = User::where('role', $request->role)->whereNotNull('token')->pluck('token');
        $nama = User::where('role', $request->role)->whereNotNull('token')->pluck('nama');
        
        // $nama = User::where('role', $request->role)->pluck('nama');
        
        if($request->role == 'customer'){
            
          	$notif->sendCustomer($token, $nama, $request->isi, $request->judul, "aadriver");
            
        } elseif($request->role == 'driver'){
            
          	$notif->sendDriver($token, 1, "driver", $request->isi, $request->judul,"ada");
            
        } elseif($request->role == 'lapak'){
            
          	$notif->sendLapak($token, $nama,  $request->isi,  $request->judul, "ada");
            
        }
        
        //return $token;

        $bantuan->update($data);
        
        return back()->with('success', 'Data Promo berhasil Diedit.');
    }
    
    
    public function broadcast_notif_delete($id)
    {
        $bantuan = BroadcastNotif::find($id);

        if ($bantuan->delete()) {
            return back()->with('success', 'Data Broadcast berhasil dihapus');
        }
    }
    

}
