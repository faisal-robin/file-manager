<?php

namespace App\Http\Controllers;

use App\Models\extension;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['extension_list'] = Extension::all();
        return view('extension.index', $data);
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

        $request->validate([
            'extension' => 'required',
        ]);

        $extension = new extension;
        $extension->extension_name = $request->extension;
        //echo "<pre>";print_r($client_source);die;
        $extension->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function show(extension $extension)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['extension_info'] = Extension::find($id);

        return view('extension.edit_extension', $data);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'extension' => 'required',
        ]);

        $extension = Extension::find($id);
        $extension->extension_name = $request->extension;
        $extension->save();

        //return redirect('extension');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $extension = Extension::find($id);
        $extension->delete();
        return redirect('extension');
    }
}
