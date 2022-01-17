<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;

class OrganisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //pengecekan user ini hanya bisa diluar try catch
        $list_role = $request->user()->getRoleNames();
        // error_log(gettype($list_role));
        $status_user = $list_role->contains('SuperAdmin');
        // error_log($status_user);
        try{
            if($status_user){
                // error_log('SuperAdmin');
                $organizations = Organisasi::all();
                return response()->json(['status' => true, 'message' => "Berhasil ambil data organisasi", 'data' => $organizations]);
            }else{
                // error_log('Common');
                $organizations = Organisasi::where('id_organisasi', $request->user()->id_organisasi)->get();
                return response()->json(['status' => true, 'message' => "Berhasil ambil data organisasi", 'data' => $organizations]);
            }
        }catch (Exception $e) {
            // error_log('Error');
            // error_log($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        // error_log('LongPass');
        return response()->json(['status' => false, 'message' => "Data kosong", 'data' => []]);
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
        $logo_organisasi = [];
        if(($request->user()->hasPermissionTo('users.create')) || $request->user()->hasRole(['SuperAdmin'])){
            $request->validate([
                'nama_organisasi' => ['required', 'string'],
                'alamat_organisasi' => ['required', 'string'],
                'keterangan_organisasi' => ['nullable', 'string'],
                'logo_organisasi_new' => ['nullable'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            try{
                $nama_organisasi = strtoupper($request->nama_organisasi);
                $id_organisasi = Uuid::uuid4()->toString();
                if($request->hasfile('logo_organisasi_new')){
                    $file = $request->file('logo_organisasi_new');
                    $name = '['.$id_organisasi.'] '.$nama_organisasi.'.'.$file->extension();
                    $file->move(public_path().'/logo_organisasi/', $name);
                    $logo_organisasi[] = $name;
                }

                // $tags = json_decode($request->tags_inovation);
                Organisasi::create([
                    'id_organisasi' => $id_organisasi,
                    'nama_organisasi' => $nama_organisasi ,
                    'alamat_organisasi' => $request->alamat_organisasi ,
                    'keterangan_organisasi' => $request->keterangan_organisasi,
                    'created_by' => $request->user()->email ,
                    'updated_by' => $request->user()->email ,
                    'logo_organisasi'=> count($logo_organisasi) > 0 ? implode(",",$logo_organisasi) : null,
                ]);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menambahkan profil organisasi']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organisasi  $organisasi
     * @return \Illuminate\Http\Response
     */
    public function show(Organisasi $organisasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organisasi  $organisasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Organisasi $organisasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organisasi  $organisasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organisasi $organisasi = null)
    {
        //
        // error_log($request->user()->getRoleNames());
        if((($request->user()->hasPermissionTo('users.update')) && ($request->user()->id_organisasi == $request->id_organisasi)) || ($request->user()->hasRole(['SuperAdmin']))){
            $request->validate([
                'nama_organisasi' => ['required', 'string'],
                'alamat_organisasi' => ['required', 'string'],
                'keterangan_organisasi' => ['nullable', 'string'],
                'logo_organisasi_new' => ['nullable'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $logo_organisasi_name = '';
            try{
                $id_organisasi = $request->id_organisasi;
                $nama_organisasi = strtoupper($request->nama_organisasi);
                // $gambar_pembuka_inovation = $request->file('ikon_inovation_new');
                if($request->hasfile('logo_organisasi_new')){
                    $mask = public_path().'/logo_organisasi/'.'['.$id_organisasi.'] '.'*.*';
                    array_map('unlink', glob($mask));
                    $file = $request->file('logo_organisasi_new');
                    $name = '['.$id_organisasi.'] '.$nama_organisasi.'.'.$file->extension();
                    $file->move(public_path().'/logo_organisasi/', $name);
                    $logo_organisasi_name = $name;
                }else{
                    $logo_organisasi_name = $request->logo_organisasi;
                }

                $current_date_time = Carbon::now()->toDateTimeString(); 
                $data_organisasi = [
                    'nama_organisasi' => $nama_organisasi , 
                    'alamat_organisasi' => $request->alamat_organisasi ,
                    'keterangan_organisasi' => $request->keterangan_organisasi,
                    'updated_by' => $request->user()->email ,
                    'logo_organisasi'=> $logo_organisasi_name,
                    'updated_at' => $current_date_time,
                ];
                Organisasi::where('id_organisasi', $id_organisasi)->update($data_organisasi);
            }catch (Exception $e) {
                error_log($e->getMessage());
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil memutakhirkan data organisasi']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organisasi  $organisasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            $id_organisasi = $request->id_organisasi;
            try{
                Organisasi::where('id_organisasi', $id_organisasi)->delete();
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['msg' => 'Sukses menghapus profil organisasi']);
    }
}
