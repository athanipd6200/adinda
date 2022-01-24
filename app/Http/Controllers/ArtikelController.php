<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Exception;
// use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;


class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(String $id_artikel = null, int $jumlah_konten_artikel = null)
    {
        //
        if($id_artikel == null){
            $artikels = Artikel::select('id_artikel', 'created_by', 'edited_by', 'penyunting_artikel','teks_pembuka_artikel', 'gambar_pembuka_artikel', 'penulis_artikel', 'tanggal_artikel', 'jenis_artikel', 'tags_artikel', 'judul_artikel')->where('status_tampilan_artikel', true)->where('status_verifikasi_artikel', true)->orderBy('created_at', 'desc')->limit(6)->get();
            return response($artikels, 200);
        }elseif(is_numeric($id_artikel)){
            error_log($id_artikel);
            $artikels = Artikel::select('id_artikel', 'created_by', 'edited_by', 'penyunting_artikel','teks_pembuka_artikel', 'gambar_pembuka_artikel', 'penulis_artikel', 'tanggal_artikel', 'jenis_artikel', 'tags_artikel', 'judul_artikel')->where('status_tampilan_artikel', true)->where('status_verifikasi_artikel', true)->orderBy('created_at', 'desc')->limit($id_artikel)->get();
            return response($artikels, 200);
        }elseif($jumlah_konten_artikel != null && $id_artikel != null){
            error_log($id_artikel);
            $artikel = Artikel::where('id_artikel', $id_artikel)->orWhere('jenis_artikel', $id_artikel)->first();
            $artikels = Artikel::select('id_artikel', 'created_by', 'edited_by', 'penyunting_artikel', 'teks_pembuka_artikel', 'gambar_pembuka_artikel', 'penulis_artikel', 'tanggal_artikel', 'jenis_artikel', 'tags_artikel', 'judul_artikel')->where('status_tampilan_artikel', true)->where('status_verifikasi_artikel', true)->inRandomOrder()->limit($jumlah_konten_artikel)->get();
            return response(['artikel' => $artikel, 'artikels' => $artikels], 200);
        }else{
            error_log($id_artikel);
            $artikel = Artikel::where('id_artikel', $id_artikel)->orWhere('jenis_artikel', $id_artikel)->first();
            // $artikels = Artikel::orderBy('created_at', 'desc')->take($jumlah_konten_artikel)->get();
            return response($artikel,  200);
        }
    }

    public function pagination(Request $request)
    {
        //
        if($request->pencarian != null){
            $column1 = 'judul_artikel';
            $column2 = 'teks_pembuka_artikel';
            $artikels = Artikel::whereRaw('UPPER('.$column1.') LIKE ?', ['%' . strtoupper($request->pencarian) . '%'])->orWhereRaw('UPPER('.$column2.') LIKE ?', ['%' . strtoupper($request->pencarian) . '%'])->orderBy('created_at', 'desc')->where('status_tampilan_artikel', true)->get();
            return $artikels;
        }else{
            $artikels = Artikel::orderBy('created_at', 'desc')->where('status_tampilan_artikel', true)->where('status_verifikasi_artikel', true);
            return $artikels->paginate(10);
        }
    }

    public function verification(Request $request)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            try{
                $id_artikel = $request->id_artikel;

                $data = [
                    'status_verifikasi_artikel' => ($request->status_verifikasi_artikel === 'true'),
                    'verificated_by' => $request->user()->email,
                    'updated_at' => Carbon::now(),
                ];
                Artikel::where('id_artikel', $id_artikel)->update($data);
            }catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage()]);
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
        $data_foto = [];
        if($request->user()->hasRole('SuperAdmin')){
            $request->validate([
                'judul_artikel' => ['required', 'string'],
                'id_keanggotaan' => ['required', 'string'],
            ]);
            try{
                // $gambar_pembuka_artikel = $request->file('gambar_pembuka_artikel');
                $data_lampiran_artikel = [];
                $lampiran_artikel_filename = json_decode($request->lampiran_artikel_filename);
                $id_artikel = Uuid::uuid4()->toString();
                $id_keanggotaan = $request->id_keanggotaan;
                // $tags = explode(",",$request->tags_artikel);
                // $tags = $request->tags_artikel;
                $tags = json_decode($request->tags_artikel);

                // setelah membuat row di database untuk konten artikel, memasukkan file gambar dan lampiran
                if($request->hasfile('gambar_pembuka_artikel')){
                    $file = $request->file('gambar_pembuka_artikel');
                    $name = time().'_'.Uuid::uuid4()->toString().'.'.$file->extension();
                    $file->move(public_path().'/gambar_pembuka_artikel/', $name);
                    $data_foto[] = $name;
                }
                if($request->hasfile('lampiran_artikel')){
                    foreach($request->file('lampiran_artikel') as $key=>$file){
                        error_log(serialize(pathinfo($file)));
                        $name = '['.$id_artikel.'] '.$lampiran_artikel_filename[$key];
                        $file->move(public_path().'/lampiran_artikel/', $name);  
                        $data_lampiran_artikel[] = $name;  
                    }
                }

                Artikel::create([
                    'id_keanggotaan' => $id_keanggotaan ,
                    'id_artikel' => $id_artikel,
                    'jenis_artikel' => $request->jenis_artikel , 
                    'judul_artikel' => $request->judul_artikel ,
                    'teks_pembuka_artikel' => $request->teks_pembuka_artikel,
                    // 'gambar_pembuka_artikel' => $request->gambar_pembuka_artikel,
                    'teks_isi_artikel' => $request->teks_isi_artikel,
                    'tanggal_artikel' => $request->tanggal_artikel,
                    'status_tampilan_artikel'=> ($request->status_tampilan_artikel === 'true'),
                    'status_verifikasi_artikel'=> 0,
                    'penulis_artikel' => $request->penulis_artikel ,
                    'created_by' => $request->user()->email,
                    'updated_by' => $request->user()->email,
                    'tags_artikel'=> count($tags) > 0 ? implode(",",$tags) : null, 
                    'gambar_pembuka_artikel'=> count($data_foto) > 0 ? implode(",",$data_foto) : null,
                    'lampiran_artikel'=> count($data_lampiran_artikel) > 0 ? implode(",",$data_lampiran_artikel) : null,
                    'geolocation_artikel'=> $request->geolocation_artikel ,
                ]);
            }catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }

        return response()->json(['msg' => 'Register success', 'notes' => $data_foto]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function show(Artikel $artikel)
    {
        //
        $artikels = Artikel::orderBy('created_at', 'desc')->get();
        return response($artikels, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function edit(Artikel $artikel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artikel $artikel)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            $request->validate([
                'judul_artikel' => ['required', 'string'],
                'teks_isi_artikel' => ['required'],
                // 'kelurahan_aset' => ['required', 'string'],
                // 'kecamatan_aset' => ['required', 'string'],
                // 'foto_aset.*'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $gambar_pembuka_artikel_nama = '';
            try{
                $id_artikel = $request->id_artikel;

                // $gambar_pembuka_artikel = $request->file('gambar_pembuka_artikel_new');
                if($request->hasfile('gambar_pembuka_artikel_new')){
                    $file = $request->file('gambar_pembuka_artikel_new');
                    $name = time().'_'.Uuid::uuid4()->toString().'.'.$file->extension();
                    $file->move(public_path().'/gambar_pembuka_artikel/', $name);
                    $gambar_pembuka_artikel_nama = $name;
                }else{
                    $gambar_pembuka_artikel_nama = $request->gambar_pembuka_artikel;
                }

                $data_lampiran_artikel_new = [];
                $lampiran_artikel_filename = json_decode($request->lampiran_artikel_filename);

                if($request->hasfile('lampiran_artikel_new')){
                    foreach($request->file('lampiran_artikel_new') as $key=>$file){
                        // error_log(serialize(pathinfo($file)));
                        $name = '['.$id_artikel.'] '.$lampiran_artikel_filename[$key];
                        $file->move(public_path().'/lampiran_artikel/', $name);  
                        $data_lampiran_artikel_new[] = $name;  
                    }
                }
                $tags = json_decode($request->tags_artikel);
                $lampiran_artikel_deleted = json_decode($request->lampiran_artikel_deleted);
                $lampiran_artikel_temps = json_decode($request->lampiran_artikel_temps);
                $lampiran_artikel = array_merge($lampiran_artikel_temps, $data_lampiran_artikel_new);

                $data_artikel = [
                    'id_keanggotaan' => $request->id_keanggotaan , 
                    'jenis_artikel' => $request->jenis_artikel , 
                    'judul_artikel' => $request->judul_artikel ,
                    'teks_pembuka_artikel' => $request->teks_pembuka_artikel,
                    'gambar_pembuka_artikel' => $request->gambar_pembuka_artikel,
                    'teks_isi_artikel' => $request->teks_isi_artikel,
                    'tanggal_artikel' => $request->tanggal_artikel,
                    'status_tampilan_artikel'=> ($request->status_tampilan_artikel === 'true'),
                    'penyunting_artikel' => $request->penyunting_artikel ,
                    'updated_by' => $request->user()->email,
                    'tags_artikel'=> count($tags) > 0 ? implode(",",$tags) : null, 
                    'gambar_pembuka_artikel'=> $gambar_pembuka_artikel_nama,
                    'lampiran_artikel'=> count($lampiran_artikel) > 0 ? implode(",",$lampiran_artikel) : null,
                    'geolocation_artikel'=> $request->geolocation_artikel ,
                    'updated_at' => Carbon::now(),
                ];
                Artikel::where('id_artikel', $id_artikel)->update($data_artikel);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil update artikel']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if($request->user()->hasRole('SuperAdmin')){
            $id_artikel = $request->id_artikel;
            $id_entri = $request->id_entri;
            error_log($request->id_artikel);
            error_log($request->id_entri);
            try{
                // Artikel::where('id_artikel', $id_artikel)->delete();
                Artikel::destroy($id_entri);
                // error_log('berhasil hapus');
            }catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['msg' => 'Sukses menghapus aset']);
    }
}
