<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;

class TimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        try{
            if($request->user()->hasRole('SuperAdmin')){
                $tims = Tim::all();
                return response()->json(['status' => true, 'message' => "Berhasil mengambil data tim", 'data' => $tims]);
            }else{
                $tims = Tim::where('id_organisasi', $request->user()->id_organisasi)->get();
                return response()->json(['status' => true, 'message' => "Berhasil mengambil data tim", 'data' => $tims]);
            }
        }catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
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
        $logo_tim = [];
        if(($request->user()->hasPermissionTo('users.create'))){
            $request->validate([
                'nama_tim' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'id_divisi' => ['nullable', 'string'],
                'alamat_tim' => ['nullable', 'string'],
                'keterangan_tim' => ['nullable', 'string'],
                'logo_tim' => ['nullable'],
            ]);
            try{
                $nama_tim = strtoupper($request->nama_tim);
                $id_tim = Uuid::uuid4()->toString();
                if($request->hasfile('logo_tim')){
                    $file = $request->file('logo_tim');
                    $name = '['.$id_tim.'] '.$nama_tim.'.'.$file->extension();
                    $file->move(public_path().'/logo_tim/', $name);
                    $logo_tim[] = $name;
                }

                // $tags = json_decode($request->tags_inovation);
                Tim::create([
                    'id_tim' => $id_tim,
                    'id_organisasi' => $request->id_organisasi ,
                    'id_divisi' => $request->id_divisi ,
                    'nama_tim' => $nama_tim ,
                    'alamat_tim' => $request->alamat_tim ,
                    'keterangan_tim' => $request->keterangan_tim,
                    'created_by' => $request->user()->email ,
                    'updated_by' => $request->user()->email ,
                    'logo_tim'=> count($logo_tim) > 0 ? implode(",",$logo_tim) : null,
                ]);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menambahkan profil tim dari suatu divisi organisasi']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tim  $tim
     * @return \Illuminate\Http\Response
     */
    public function show(Tim $tim)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tim  $tim
     * @return \Illuminate\Http\Response
     */
    public function edit(Tim $tim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tim  $tim
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tim $tim)
    {
        //
        if(($request->user()->hasPermissionTo('users.update')) && ($request->user()->id_organisasi == $request->id_organisasi)){
            $request->validate([
                'nama_tim' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'id_divisi' => ['nullable', 'string'],
                'alamat_tim' => ['nullable', 'string'],
                'keterangan_tim' => ['nullable', 'string'],
                'logo_tim_new' => ['nullable'],
            ]);
            $logo_tim_name = '';
            try{
                $id_tim = $request->id_tim;
                $nama_tim = strtoupper($request->nama_tim);
                if($request->hasfile('logo_tim_new')){
                    $mask = public_path().'/logo_tim/'.'['.$id_tim.'] '.'*.*';
                    array_map('unlink', glob($mask));
                    $file = $request->file('logo_tim_new');
                    $name = '['.$id_tim.'] '.$nama_tim.'.'.$file->extension();
                    $file->move(public_path().'/logo_tim/', $name);
                    $logo_tim_name = $name;
                }else{
                    $logo_tim_name = $request->logo_tim;
                }

                $current_date_time = Carbon::now()->toDateTimeString(); 
                $data_tim = [
                    'nama_tim' => $nama_tim ,
                    'id_organisasi' => $request->id_organisasi ,
                    'id_divisi' => $request->id_divisi ,
                    'alamat_tim' => $request->alamat_tim ,
                    'keterangan_tim' => $request->keterangan_tim,
                    'updated_by' => $request->user()->email ,
                    'logo_tim'=> $logo_tim_name,
                    'updated_at' => $current_date_time,
                ];
                Tim::where('id_tim', $id_tim)->update($data_tim);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil memutakhirkan data tim']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tim  $tim
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            $id_tim = $request->id_tim;
            try{
                Tim::where('id_tim', $id_tim)->delete();
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['msg' => 'Sukses menghapus profil tim']);
    }
}
