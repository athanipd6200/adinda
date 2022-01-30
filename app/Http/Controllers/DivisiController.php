<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Keanggotaan;
use App\Models\Tim;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // dd("hell0");
        try{
            if(($request->user()->hasRole('SuperAdmin')) && ($request->id_organisasi == null)){
                // Masuk sebagai super admin
                $divisis = Divisi::with(['organisasi'])->get();
                return response()->json(['status' => true, 'message' => "Berhasil ambil data divisi", 'data' => $divisis]);
            }elseif($request->id_organisasi != null){
                // ambil data untuk dropdown divisi dari organisasi
                $divisis = Divisi::with(['organisasi'])->where('id_organisasi', $request->id_organisasi)->get();
                return response()->json(['status' => true, 'message' => "Berhasil ambil data divisi", 'data' => $divisis]);
            }else{
                // Masuk sebagai API ngambil divisi
            //    dd("hell01");

                $divisis_ids = $request->user()->divisis();
                // $divisis = Divisi::with(['organisasi'])->whereIn('id_divisi', $divisis_ids)->leftJoin('keanggotaans', 'keanggotaans.id_keanggotaan', '=', 'divisis.id_divisi')->get();
                $divisis = Divisi::with(['organisasi'])->whereIn('id_divisi', $divisis_ids)->leftJoin('keanggotaans', 'keanggotaans.id_keanggotaan', '=', 'divisis.id_divisi')->groupby('id_divisi')->get();
                // $remove_dup_divisis = $divisis->groupby('id_divisi');

                return response()->json(['status' => true, 'message' => "Berhasil ambil data divisi", 'data' => $divisis]);
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
        $logo_divisi = [];
        $status_keanggotaan = in_array($request->id_organisasi, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
        if(($status_keanggotaan) || ($request->user()->hasRole(['SuperAdmin'])) ){
            $request->validate([
                'nama_divisi' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'alamat_divisi' => ['nullable', 'string'],
                'keterangan_divisi' => ['nullable', 'string'],
                'logo_divisi_new' => ['nullable'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            try{
                $nama_divisi = strtoupper($request->nama_divisi);
                $id_divisi = Uuid::uuid4()->toString();
                if($request->hasfile('logo_divisi_new')){
                    $file = $request->file('logo_divisi_new');
                    $name = '['.$id_divisi.'] '.$nama_divisi.'.'.$file->extension();
                    $file->move(public_path().'/logo_divisi/', $name);
                    $logo_divisi[] = $name;
                }

            

                // $tags = json_decode($request->tags_inovation);
                Divisi::create([
                    'id_divisi' => $id_divisi,
                    'id_organisasi' => $request->id_organisasi ,
                    'nama_divisi' => $nama_divisi ,
                    'alamat_divisi' => $request->alamat_divisi == null ? null : $request->alamat_divisi,
                    'keterangan_divisi' => $request->keterangan_divisi == null ? null : $request->keterangan_divisi,
                    'created_by' => $request->user()->email ,
                    'updated_by' => $request->user()->email ,
                    'logo_divisi'=> count($logo_divisi) > 0 ? implode(",",$logo_divisi) : null,
                ]);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menambahkan profil divisi dari suatu organisasi']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function show(Divisi $divisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, String $id_divisi = null)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Divisi $divisi = null)
    {
        $status_keanggotaan_organisasi = in_array($request->id_organisasi, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
        $status_keanggotaan_divisi = in_array($request->id_divisi, $request->user()->keanggotaan_by_role('AdminDivisi'));
        if(($status_keanggotaan_divisi) || ($status_keanggotaan_organisasi) || ($request->user()->hasRole(['SuperAdmin'])) ){
            $request->validate([
                'nama_divisi' => ['required', 'string'],
                'id_organisasi' => ['required', 'string'],
                'alamat_divisi' => ['nullable', 'string'],
                'keterangan_divisi' => ['nullable', 'string'],
                'logo_divisi_new' => ['nullable'],
            ]);
            $logo_divisi_name = '';
            try{
                $id_divisi = $request->id_divisi;
                $id_organisasi = $request->id_organisasi;
                $nama_divisi = strtoupper($request->nama_divisi);
                if($request->hasfile('logo_divisi_new')){
                    $mask = public_path().'/logo_divisi/'.'['.$id_divisi.'] '.'*.*';
                    array_map('unlink', glob($mask));
                    $file = $request->file('logo_divisi_new');
                    $name = '['.$id_divisi.'] '.$nama_divisi.'.'.$file->extension();
                    $file->move(public_path().'/logo_divisi/', $name);
                    $logo_divisi_name = $name;
                }else{
                    $logo_divisi_name = $request->logo_divisi;
                }

                $current_date_time = Carbon::now()->toDateTimeString(); 
                $data_divisi = [
                    'nama_divisi' => $nama_divisi ,
                    'alamat_divisi' => $request->alamat_divisi == null ? null : $request->alamat_divisi,
                    'keterangan_divisi' => $request->keterangan_divisi == null ? null : $request->keterangan_divisi,
                    'updated_by' => $request->user()->email ,
                    'logo_divisi'=> $logo_divisi_name,
                    'updated_at' => $current_date_time,
                ];
                Divisi::where('id_divisi', $id_divisi)->update($data_divisi);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil memutakhirkan data divisi']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Divisi  $divisi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $status_keanggotaan_organisasi = in_array($request->id_organisasi, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
        if(($status_keanggotaan_organisasi) || ($request->user()->hasRole(['SuperAdmin'])) ){
            $id_divisi = $request->id_divisi;
            try{
                Divisi::where('id_divisi', $id_divisi)->delete();
                Tim::where('id_divisi', $id_divisi)->update(['id_divisi' => null]);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Sukses menghapus profil divisi']);
    }
}
