<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    //

    public function index(){

    	return view('admin.index');
    }
}
