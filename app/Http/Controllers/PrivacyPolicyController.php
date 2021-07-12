<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function web()
    {
        $data = PrivacyPolicy::all();
        $data1 = PrivacyPolicy::find(1);
         
        $create = date_format($data1->updated_at, 'd M Y H:i:s');
        return view('privacy_policy', compact('data', 'create'));
    }
    
    public function index()
    {
        $data = PrivacyPolicy::all();
        return view('admin.privacy_policy.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'isi' => $request->isi   
        ];
        
        PrivacyPolicy::create($data);
        
        return back()->with('success', 'Data berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $privacy = PrivacyPolicy::find($id);
        
        $privacy->update(['isi' => $request->isi]);
        
        return back()->with('success', 'Data berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $privacy = PrivacyPolicy::find($id);
        
        $privacy->delete();
        
        return back()->with('success', 'Data berhasil dihapus');
    }
}
