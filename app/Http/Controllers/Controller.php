<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use App\Lapak;
use App\JadwalLapak;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct()
    {
        
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $tgl = Carbon::now();
        
        $lapak = Lapak::all();
        
        $jam_sekarang = date_format($tgl, 'H:i:s');
        
        $hari_sekarang = Carbon::now()->isoFormat('dddd');
        
        foreach($lapak as $lapaks => $get_profil){
        
            if($get_profil->status_tombol == 1){           //jika status tombol Buka
            
                $jadwal_lapak = JadwalLapak::where('id_lapak', $get_profil->id)
                        ->where('hari', $hari_sekarang)
                        ->first();
                
                if($jadwal_lapak['24_jam'] == 1){          // Jika Jadwal lapak pada hari ini 24 jam buka
                    $get_profil->update([
                            'status' => 1
                        ]);
                } elseif($jadwal_lapak['status_buka'] == 1){     // atau jika jadwal lapak buka dari jam yg sudah ditentukan
                    
                    $jadwal_buka = JadwalLapak::where('id_lapak', $get_profil->id) 
                        ->where('jam_buka', '<', $jam_sekarang)
                        ->where('jam_tutup', '>', $jam_sekarang)
                        ->where('hari', $hari_sekarang)
                        ->first();                                  //cek apakah jadwal buka lapak sudah berlaku
                    
                    if($jadwal_buka){              // Jika jadwal lapak masih belaku
                        $get_profil->update([
                                'status' => 1
                            ]);
                    } else{
                        $get_profil->update([
                                'status' => 0
                            ]);
                    }
                } elseif($jadwal_lapak['status_buka'] == 0){    // atau Jika jadwal lapak hari ini tutup
                    $get_profil->update([
                        'status' => 0
                    ]);
                }
            } 
        }
    }
}
