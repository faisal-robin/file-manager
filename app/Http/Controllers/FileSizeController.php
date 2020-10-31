<?php

namespace App\Http\Controllers;

use App\Models\FileSize;
use Illuminate\Http\Request;

class FileSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['file_size_list'] = FileSize::all();
        return view('file_size.index', $data);
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
            'file_size' => 'required',
        ]);

        $file_size = new FileSize;
        $file_size->file_size = $request->file_size;
        //echo "<pre>";print_r($client_source);die;
        $file_size->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\file_size  $file_size
     * @return \Illuminate\Http\Response
     */
    public function show(FileSize $file_size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\file_size  $file_size
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['file_size_info'] = FileSize::find($id);

        return view('file_size.edit_file_size', $data);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\file_size  $file_size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file_size' => 'required',
        ]);

        $file_size = FileSize::find($id);
        $file_size->file_size = $request->file_size;
        $file_size->save();

        //return redirect('file_size');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\file_size  $file_size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file_size = FileSize::find($id);
        $file_size->delete();
        return redirect('file-size');
    }
}
