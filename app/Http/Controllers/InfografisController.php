<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\File; 

class InfografisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $infografis = [];
        $infografis = Infografis::whereNotNull('index_infografis')->orderBy('index_infografis', 'asc')->get();
        return response()->json(['status' => true, 'infografis' => $infografis]);
    }

    public function pagination(Request $request){
        $infografis = [];
        try {
            //code...
            $infografis = Infografis::where('tampilan_web', 'true')->orderBy('created_at', 'desc')->paginate(10);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'infografis' => $infografis]);
        }

        return response()->json(['status' => true, 'message' => 'Berhasil mengambil infografis publik', 'infografis' => $infografis]);
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
        //
        // $infografis = $request->file('infografis');
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'infografis'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try{
            $name = '';
            if($request->hasfile('source')){
                $file = $request->file('source');
                $name = time().'_'.Uuid::uuid4()->toString().'.'.$file->extension();
                $file->move(public_path().'/infografis/', $name);
            }else{
                return response()->json(['status' => false,'message' => 'Tidak ada file yang diupload']);
            }

            $tags = json_decode($request->tags_infografis);
            infografis::create([
                'name' => $request->name,
                'description' => $request->description, 
                'source' => $name,
                'inserted_by' => $request->user()->email,
                'tautan_infografis' => $request->tautan_infografis,
                'tampilan_web' => $request->tampilan_web,
                'tags_infografis'=> count($tags) > 0 ? implode(",",$tags) : null,
            ]); 
        }catch (Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
        return response()->json(['status' => true,'message' => 'Berhasil upload file']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try{
            // $infografis = Infografis::all();
            $infografis_selected = Infografis::whereNotNull('index_infografis')->orderBy('index_infografis', 'asc')->get();
            $infografis_non_selected = Infografis::whereNull('index_infografis')->get();
            return response()->json(['status' => true, 'infografis_selected' => $infografis_selected, 'infografis_non_selected' => $infografis_non_selected]);
        }catch (Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $infografis = [];
        try{
            $id_entri = $request->id_entri;
            $name = '';
            if($request->hasfile('source_new')){
                $file = $request->file('source_new');
                $name = time().'_'.Uuid::uuid4()->toString().'.'.$file->extension();
                $file->move(public_path().'/infografis/', $name);
            }else{
                $name = $request->source_old;
            }

            $tags = json_decode($request->tags_infografis);
            $data = [
                'name' => $request->name,
                'description' => $request->description, 
                'source' => $name,
                'inserted_by' => $request->user()->email,
                'tautan_infografis' => $request->tautan_infografis,
                'tampilan_web' => $request->tampilan_web,
                'tags_infografis'=> count($tags) > 0 ? implode(",",$tags) : null,
            ];
            Infografis::where('id_entri', $id_entri)->update($data);

        }catch (Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
        return response()->json(['status' => true, 'message' => "Berhasil update data"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function updateTampilan(Request $request)
    {
        //
        $infografis = [];
        try{
            Infografis::where('id_entri','>',0)->update(['index_infografis' => null]);
            $infografis = json_decode($request->selected);
            foreach ($infografis as $key => $value) {
                // code...
                // error_log($key.' '.$value->id_entri.' '.$value->name);
                Infografis::where('id_entri','=',$value->id_entri)->update(['index_infografis' => $key]);
            }
        }catch (Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
        $infografis_selected = Infografis::whereNotNull('index_infografis')->orderBy('index_infografis', 'asc')->get();
        $infografis_non_selected = Infografis::whereNull('index_infografis')->get();
        return response()->json(['status' => true, 'infografis_selected' => $infografis_selected, 'infografis_non_selected' => $infografis_non_selected]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Infografis $infografis = null)
    {
        //
        try{
            // error_log('kosong');
            $items = Infografis::find($request->id_entri); //Reports is my model
            $file_path = public_path().'/infografis/'.$items->source; // if your file is in some folder in public directory.
            if(File::exists($file_path)){
                File::delete($file_path); //for deleting only file try this
                $items->delete(); //for deleting record and file try both
            }

        }catch (Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
        return response()->json(['status' => true,'message' => 'Berhasil menghapus infografis']);
    }
}
