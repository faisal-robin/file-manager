<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Auth;
use App\Models\FileManager;
use App\Models\File;
use App\Models\FileSize;
use App\Models\Extension;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $data['all_folder'] = FileManager::where("user_id", $user_id)->where("parent_id", 0)->get();
        $data['all_files'] = File::where("user_id", $user_id)->get();
        return view('file_manager.index',$data);
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
            'folder_name' => 'required',
        ]);
        
        if ($request->parent_folder != '') {
         $parent_directory =  $this->parent_directory($request->parent_id, '');          
          $folder_path = $parent_directory.$request->folder_name;
          Storage::makeDirectory($folder_path);
        }else{
           Storage::makeDirectory($request->folder_name); 
        }
        
        $file_manager = new FileManager;
        $file_manager->folder_name = $request->folder_name;
        $file_manager->user_id = Auth::id();
        $file_manager->parent_id = $request->parent_id;
        // echo "<pre>";print_r($request->folder_name);die;
        $file_manager->save();
    }

    public function parent_directory($parent_id, $list){
        $flag = 0;
        $user_id = Auth::user()->id;
        $is_child_available = FileManager::where("user_id", $user_id)
        ->where("id", $parent_id)
        ->first();
        
        // $list = '';
        // echo $is_child_available->parent_id;
        $list .= $is_child_available->folder_name."/";
           
        
        if($is_child_available->parent_id != 0) {
            $this->parent_directory($is_child_available->parent_id, $list);
        }

        return $list;
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        $data['parent_id'] = $id;

        $data['parent_folder_details'] = FileManager::where("user_id", $user_id)
        ->where("id", $id)
        ->first();

        $data['folder_details'] = FileManager::where("user_id", $user_id)
        ->where("parent_id", $id)
        ->get();
        
        // echo "<pre>";print_r($data['parent_folder_details']);die;
        return view('file_manager.folder_details',$data);
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
        Storage::move($request->old_folder_name, $request->folder_name);
        $file_manager = FileManager::find($id);
        $file_manager->folder_name = $request->folder_name;
        $file_manager->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }


    public function file_upload(Request $request){
        
        $ext = array();
        $all_extension = Extension::all();
        
        foreach ($all_extension as $key => $value) {
            $ext[] = $value->extension_name;
        }
        
        $list = implode(',', $ext); 
        $file_size = FileSize::first();
        $request->validate([
            'file_name' => 'required|mimes:'.$list.'|max:'.$file_size->file_size,
        ]);

        // echo "<pre>";print_r($request->file('file_name'));die;
        $fileName = '';
        if ($files = $request->file('file_name')) {
             $destinationPath = 'storage/app';
             $fileName =  $files->getClientOriginalName();
             $files->move($destinationPath, $fileName);
        }

        $file_upload = new File;
        $file_upload->file_name = $fileName;
        $file_upload->user_id = Auth::id();
        $file_upload->folder_id = 0;
        // echo "<pre>";print_r($request->folder_name);die;
        $file_upload->save();
    }

    public function file_delete($id){
        $file = File::find($id);

        if ($file->file_name) {
            $exists = Storage::get($file->file_name);

            if ($exists) {
                Storage::delete($file->file_name);
            }
        }
        
        $file->delete();
        return redirect('file-manager');
    }

    public function file_download($file_name) {
        $file_path = storage_path('app/'.$file_name);
        return response()->download($file_path);
    }

    public function folder_delete($id){
        $file_manager = FileManager::find($id);

        if ($file_manager->folder_name) {
            Storage::deleteDirectory($file_manager->folder_name);
        }
        
        $file_manager->delete();
        return redirect('file-manager');
    }
}
