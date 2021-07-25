<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Driver;
use Image;
use Auth;

class AuthController extends Controller
{
    
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
            
            if(auth()->user()->role == 'admin'){
                return redirect()->route('admin.dashboard');
            }
            elseif(auth()->user()->role == 'admin_order_offline'){
                return redirect()->route('admin_order_offline.dashboard');
            }
            
        }

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
