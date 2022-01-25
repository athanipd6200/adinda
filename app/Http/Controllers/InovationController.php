<?php

namespace App\Http\Controllers;

use App\Models\Inovation;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;

class InovationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(String $id_inovation = null, int $jumlah_konten_inovation = null)
    {
        //
        if($id_inovation == null){
            $inovations = Inovation::select('id_inovation','deskripsi_inovation', 'ikon_inovation', 'kontak_hubung_inovation', 'updated_at', 'satker_asal_inovation', 'tautan_kode_inovation', 'tautan_materi_inovation', 'gambar_inovation')->where('status_verifikasi_inovation', true)->orderBy('updated_at', 'desc')->limit(6)->get();
            return response($inovations, 200);
        }elseif(is_numeric($id_inovation)){
            error_log($id_inovation);
            $inovations = Inovation::select('id_inovation','deskripsi_inovation', 'ikon_inovation', 'kontak_hubung_inovation', 'updated_at', 'satker_asal_inovation', 'tautan_kode_inovation', 'tautan_materi_inovation', 'gambar_inovation')->where('status_verifikasi_inovation', true)->orderBy('updated_at', 'desc')->limit($id_inovation)->get();
            return response($inovations, 200);
        }elseif($jumlah_konten_inovation != null && $id_inovation != null){
            error_log($id_inovation);
            $inovation = Inovation::where('id_inovation', $id_inovation)->first();
            $inovations = Inovation::select('id_inovation','deskripsi_inovation', 'ikon_inovation', 'kontak_hubung_inovation', 'updated_at', 'satker_asal_inovation', 'tautan_kode_inovation', 'tautan_materi_inovation', 'gambar_inovation')->where('status_verifikasi_inovation', true)->inRandomOrder()->limit($jumlah_konten_inovation)->get();
            return response(['inovation' => $inovation, 'inovations' => $inovations], 200);
        }else{
            error_log($id_inovation);
            $inovation = Inovation::where('id_inovation', $id_inovation)->first();
            // $inovations = Inovation::orderBy('created_at', 'desc')->take($jumlah_konten_inovation)->get();
            return response($inovation,  200);
        }
    }

    public function pagination(Request $request)
    {
        //
        if($request->pencarian != null){
            $column1 = 'nama_inovation';
            $column2 = 'deskripsi_inovation';
            $artikels = Inovation::whereRaw('UPPER('.$column1.') LIKE ?', ['%' . strtoupper($request->pencarian) . '%'])->orWhereRaw('UPPER('.$column2.') LIKE ?', ['%' . strtoupper($request->pencarian) . '%'])->orderBy('updated_at', 'desc')->get();
            return $artikels;
        }else{
            $artikels = Inovation::orderBy('updated_at', 'desc');
            return $artikels->paginate(10);
        }
    }

    public function verification(Request $request)
    {
        //
        $id_inovation = $request->id_inovation;
        $id_keanggotaan = $request->id_keanggotaan;
        $supervisor = in_array($id_keanggotaan, $request->user()->keanggotaan_by_roles(['SupervisorOrganisasi', 'SupervisorDivisi', 'SupervisorTim']));
        if($request->user()->hasRole('SuperAdmin') || $supervisor){
            try{
                $id_inovation = $request->id_inovation;
                $status = ($request->status_verifikasi_inovation === 'true');
                if($status){
                    $data = [
                        'status_verifikasi_inovation' => $status,
                        'verified_by' => $request->user()->email,
                        'updated_at' => Carbon::now(),
                    ];
                }else{
                    $data = [
                        'status_verifikasi_inovation' => $status,
                        'verified_by' => null,
                        'updated_at' => Carbon::now(),
                    ];
                }
                Inovation::where('id_inovation', $id_inovation)->update($data);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' =>$e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }

        return response()->json(['status' => true, 'message' => 'Berhasil mengubah status verifikasi']);
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
        $ikon_inovation = [];
        $gambar_inovation = [];
        $id_keanggotaan = $request->id_keanggotaan;
        $penulis = in_array($id_keanggotaan, $request->user()->keanggotaan_by_roles(['PenulisInovasiOrganisasi', 'PenulisInovasiDivisi', 'PenulisInovasiTim']));
        if($request->user()->hasRole('SuperAdmin') || $penulis){
            $request->validate([
                'nama_inovation' => ['required', 'string'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            try{
                $nama_inovation = strtoupper($request->nama_inovation);
                $id_inovation = Uuid::uuid4()->toString();
                if($request->hasfile('ikon_inovation')){
                    $file = $request->file('ikon_inovation');
                    $name = '['.$id_inovation.'] '.$nama_inovation.'.'.$file->extension();
                    $file->move(public_path().'/ikon_inovation/', $name);
                    $ikon_inovation[] = $name;
                }

                $gambar_inovation_filename = $request->gambar_inovation_filename;
                if($request->hasfile('gambar_inovation_file')){
                    foreach($request->file('gambar_inovation_file') as $key=>$file){
                        error_log(serialize(pathinfo($file)));
                        $name = '['.$id_inovation.'] '.$gambar_inovation_filename[$key];
                        $file->move(public_path().'/gambar_inovation/', $name);
                        $gambar_inovation[] = $name;
                    }
                }

                // $tags = json_decode($request->tags_inovation);
                Inovation::create([
                    'id_inovation' => $id_inovation,
                    'id_keanggotaan' => $id_keanggotaan,
                    'nama_inovation' => $nama_inovation ,
                    'satker_asal_inovation' => $request->satker_asal_inovation ,
                    'kontak_hubung_inovation' => $request->kontak_hubung_inovation,
                    'deskripsi_inovation' => $request->deskripsi_inovation,
                    'konten_inovation' => $request->konten_inovation,
                    'tautan_materi_inovation'=> $request->tautan_materi_inovation,
                    'tautan_kode_inovation' => $request->tautan_kode_inovation ,
                    'created_by' => $request->user()->email ,
                    // 'created_by_group' => $request->user()->group == null ? null : $request->user()->group,
                    // 'tags_inovation'=> count($tags) > 0 ? implode(",",$tags) : null, 
                    'ikon_inovation'=> count($ikon_inovation) > 0 ? implode(",",$ikon_inovation) : null,
                    'gambar_inovation'=> count($gambar_inovation) > 0 ? implode(",",$gambar_inovation) : null,
                ]);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menambahkan profil inovasi']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inovation  $inovation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, String $jenis = null)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            $inovations = Inovation::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'inovations.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'inovations.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'inovations.id_keanggotaan')->orderBy('inovations.created_at', 'desc')->get();
            return response($inovations, 200);
        }elseif($jenis == 'supervisor'){
            $id_kegiatans = $request->user()->keanggotaan_by_roles(['SupervisorOrganisasi', 'SupervisorDivisi', 'SupervisorTim']);
            $inovations = Inovation::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'inovations.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'inovations.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'inovations.id_keanggotaan')->whereIn('id_keanggotaan', $id_kegiatans)->orderBy('inovations.created_at', 'desc')->get();
            return response($inovations, 200);
        }elseif($jenis == 'penyunting'){
            $id_kegiatans = $request->user()->keanggotaan_by_roles(['PenyuntingInovasiOrganisasi', 'PenyuntingInovasiDivisi', 'PenyuntingInovasiTim']);
            $inovations = Inovation::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'inovations.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'inovations.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'inovations.id_keanggotaan')->whereIn('id_keanggotaan', $id_kegiatans)->orderBy('inovations.created_at', 'desc')->get();
            return response($inovations, 200);
        }else{
            $inovations = Inovation::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'inovations.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'inovations.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'inovations.id_keanggotaan')->where('inovations.created_by', $request->user()->email)->orderBy('inovations.created_at', 'desc')->get();
            return response($inovations, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inovation  $inovation
     * @return \Illuminate\Http\Response
     */
    public function edit(Inovation $inovation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inovation  $inovation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inovation $inovation)
    {
        //
        $id_inovation = $request->id_inovation;
        $id_keanggotaan = $request->id_keanggotaan;
        $supervisor = in_array($id_keanggotaan, $request->user()->keanggotaan_by_roles(['SupervisorOrganisasi', 'SupervisorDivisi', 'SupervisorTim']));
        if($request->user()->hasRole('SuperAdmin') || $supervisor || ($request->user()->email == $request->created_by)){
            $request->validate([
                'id_inovation' => ['required', 'string'],
                'id_keanggotaan' => ['required', 'string'],
                'nama_inovation' => ['required', 'string'],
                'satker_asal_inovation' => ['required', 'string'],
                'kontak_hubung_inovation' => ['required', 'string'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $ikon_inovation_name = '';
            try{
                $nama_inovation = strtoupper($request->nama_inovation);
                // $gambar_pembuka_inovation = $request->file('ikon_inovation_new');
                if($request->hasfile('ikon_inovation_new')){
                    $mask = public_path().'/ikon_inovation/'.'['.$id_inovation.'] '.'*.*';
                    array_map('unlink', glob($mask));
                    $file = $request->file('ikon_inovation_new');
                    $name = '['.$id_inovation.'] '.$nama_inovation.'.'.$file->extension();
                    $file->move(public_path().'/ikon_inovation/', $name);
                    $ikon_inovation_name = $name;
                }else{
                    $ikon_inovation_name = $request->ikon_inovation;
                }

                $old_images = (explode(",",$request->gambar_inovation));
                // $mask2 = public_path().'/gambar_inovation/'.'['.$id_inovation.']'.'*.*';
                // error_log($mask2);
                // array_map('unlink', glob($mask2));
                $gambar_inovation_name_new = [];
                $gambar_inovation_new_filename = $request->gambar_inovation_new_filename;
                if($request->hasfile('gambar_inovation_new_file')){
                    $nama_file_lama = '';
                    foreach($old_images as $nama_file){
                        $nama_file_lama = public_path().'/gambar_inovation/'.$nama_file;
                        unlink($nama_file_lama);
                    }
                    foreach($request->file('gambar_inovation_new_file') as $key=>$file){
                        // error_log(serialize(pathinfo($file)));
                        $name = '['.$id_inovation.'] '.$gambar_inovation_new_filename[$key];
                        $file->move(public_path().'/gambar_inovation/', $name);  
                        $gambar_inovation_name_new[] = $name;
                    }
                }else{
                    $gambar_inovation_name_new = (explode(",",$request->gambar_inovation));
                }
                // $tags = json_decode($request->tags_inovation);
                $current_date_time = Carbon::now()->toDateTimeString(); 
                $data_inovation = [
                    'nama_inovation' => $nama_inovation , 
                    'id_keanggotaan' => $id_keanggotaan , 
                    'satker_asal_inovation' => $request->satker_asal_inovation ,
                    'kontak_hubung_inovation' => $request->kontak_hubung_inovation,
                    'deskripsi_inovation' => $request->deskripsi_inovation,
                    'konten_inovation' => $request->konten_inovation,
                    'tautan_materi_inovation' => $request->tautan_materi_inovation,
                    'tautan_kode_inovation'=> $request->tautan_kode_inovation,
                    'created_by' => $request->user()->email ,
                    'updated_by' => $request->user()->email ,
                    // 'created_by_group' => $request->user()->group == null ? null : $request->user()->group,
                    // 'tags_inovation'=> count($tags) > 0 ? implode(",",$tags) : null, 
                    'ikon_inovation'=> $ikon_inovation_name,
                    'gambar_inovation'=> count($gambar_inovation_name_new) > 0 ? implode(",",$gambar_inovation_name_new) : null,
                    'updated_at' => $current_date_time,
                ];
                Inovation::where('id_inovation', $id_inovation)->update($data_inovation);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil update inovation']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inovation  $inovation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id_keanggotaan = $request->id_keanggotaan;
        $supervisor = in_array($id_keanggotaan, $request->user()->keanggotaan_by_roles(['SupervisorOrganisasi', 'SupervisorDivisi', 'SupervisorTim']));
        if($request->user()->hasRole('SuperAdmin') || $supervisor || ($request->user()->email == $request->created_by)){
            $id_inovation = $request->id_inovation;
            $id_entri = $request->id_entri;
            // error_log($request->id_inovation);
            // error_log($request->id_entri);
            try{
                Inovation::where('id_inovation', $id_inovation)->delete();
                // Inovation::destroy($id_entri);
                // error_log('berhasil hapus');
            }catch (Exception $e) {
                // return response()->json(['msg' => $e->getMessage()]);
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['msg' => 'Sukses menghapus aset']);
    }
}
