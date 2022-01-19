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
        // $tims = Tim::with(['divisi','organisasi'])->get();
        // return response()->json(['status' => true, 'message' => "Berhasil mengambil data tim", 'data' => $tims]);
        //
        try{
            if($request->user()->hasRole('SuperAdmin')){
                $tims = Tim::with(['divisi','organisasi'])->get();
                return response()->json(['status' => true, 'message' => "Berhasil mengambil data tim", 'data' => $tims]);
            }else{
                $tims = Tim::with(['divisi','organisasi'])->where('id_organisasi', $request->user()->id_organisasi)->get();
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
        $status_keanggotaan_organisasi = in_array($request->id_organisasi, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
        $status_keanggotaan_divisi = $request->id_divisi == null ? false : in_array($request->id_divisi, $request->user()->keanggotaan_by_role('AdminDivisi'));
        if(($status_keanggotaan_organisasi) || ($status_keanggotaan_divisi) || ($request->user()->hasRole(['SuperAdmin']))){
            $request->validate([
                'nama_tim' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'id_divisi' => ['nullable', 'string'],
                'alamat_tim' => ['nullable', 'string'],
                'keterangan_tim' => ['nullable', 'string'],
                'logo_tim' => ['nullable'],
            ]);
            try{
                // $nama_tim = strtoupper($requet->nama_tim);
                $nama_tim = $request->nama_tim;
                $id_tim = Uuid::uuid4()->toString();
                if($request->hasfile('logo_tim_new')){
                    $file = $request->file('logo_tim_new');
                    $name = '['.$id_tim.'] '.$nama_tim.'.'.$file->extension();
                    $file->move(public_path().'/logo_tim/', $name);
                    $logo_tim[] = $name;
                }

                // $tags = json_decode($request->tags_inovation);
                Tim::create([
                    'id_tim' => $id_tim,
                    'id_organisasi' => $request->id_organisasi ,
                    'id_divisi' => $request->id_divisi == null ? null : $request->id_divisi,
                    'nama_tim' => $nama_tim ,
                    'alamat_tim' => $request->alamat_tim == null ? null : $request->alamat_tim,
                    'keterangan_tim' => $request->keterangan_tim == null ? null : $request->keterangan_tim,
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
    public function update(Request $request, Tim $tim = null)
    {
        //
        $status_keanggotaan_organisasi = in_array($request->id_organisasi, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
        $status_keanggotaan_tim = in_array($request->id_tim, $request->user()->keanggotaan_by_role('AdminTim'));
        $status_keanggotaan_divisi = $request->id_divisi == null ? false : in_array($request->id_divisi, $request->user()->keanggotaan_by_role('AdminDivisi'));
        if(($status_keanggotaan_tim) || ($status_keanggotaan_organisasi) || ($status_keanggotaan_divisi) || ($request->user()->hasRole(['SuperAdmin']))){
            error_log('otw validasi');
            $request->validate([
                'nama_tim' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'id_divisi' => ['nullable', 'string'],
                'alamat_tim' => ['nullable', 'string'],
                'keterangan_tim' => ['nullable', 'string'],
                'logo_tim_new' => ['nullable'],
            ]);
            $logo_tim_name = '';
            error_log('otw input');
            try{
                $id_tim = $request->id_tim;
                // $nama_tim = strtoupper($request->nama_tim);
                $nama_tim = $request->nama_tim;
                error_log('otw cek logo');
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
                error_log('otw bikin data');
                $data_tim = [
                    'nama_tim' => $nama_tim ,
                    'id_organisasi' => $request->id_organisasi ,
                    'id_divisi' => $request->id_divisi == null ? null : $request->id_divisi,
                    'alamat_tim' => $request->alamat_tim == null ? null : $request->alamat_tim,
                    'keterangan_tim' => $request->keterangan_tim == null ? null : $request->keterangan_tim,
                    'updated_by' => $request->user()->email ,
                    'logo_tim'=> $logo_tim_name,
                    'updated_at' => $current_date_time,
                ];
                error_log('otw submit data');
                Tim::where('id_tim', $id_tim)->update($data_tim);
            }catch (Exception $e) {
                error_log('error dung');
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
        $status_keanggotaan_organisasi = in_array($request->id_organisasi, $request->user()->organisasis());
        $status_keanggotaan_divisi = $request->id_divisi == null ? false : in_array($request->id_divisi, $request->user()->divisis());
        $status_rbac_organisasi = $request->user()->hasRole(['AdminOrganisasi']);
        $status_rbac_divisi = $request->user()->hasRole(['AdminDivisi']);
        if(($status_keanggotaan_organisasi && $status_rbac_organisasi) || ($status_keanggotaan_divisi && $status_rbac_divisi) || ($request->user()->hasRole(['SuperAdmin']))){
            $id_tim = $request->id_tim;
            try{
                Tim::where('id_tim', $id_tim)->delete();
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Sukses menghapus profil tim']);
    }
}
