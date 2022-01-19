<?php

namespace App\Http\Controllers;

use App\Models\Keanggotaan;
use Illuminate\Http\Request;

class KeanggotaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $jenis_keanggotaan = $request->jenis_keanggotaan;
        $status_keanggotaan = false;
        switch ($jenis_keanggotaan) {
            case "organisasi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
                break;
            case "divisi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminDivisi'));
                break;
            case "tim":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminTim'));
                break;
            default:
                $status_keanggotaan = false;
        }
        if(($request->user() != null && $status_keanggotaan) || ($request->user()->hasRole(['SuperAdmin']))){
            $request->validate([
                'id_user' => ['required', 'integer'],
                'id_keanggotaan' => ['required', 'string'],
                'jenis_keanggotaan' => ['required', 'string'],
                'id_organisasi' => ['required', 'string']
            ]);
            $keanggotaans = $request->user()->keanggotaans();
            $status_keanggotaan = false;
            foreach($keanggotaans as $keanggotaan){
                error_log($keanggotaan['id_keanggotaan']);
                if($keanggotaan['id_keanggotaan'] == $request->id_keanggotaan || $keanggotaan['id_keanggotaan'] == $request->id_organisasi){
                    $status_keanggotaan = true;
                    error_log('ada yang sama');
                }
            }
            $status_admin = $request->user()->hasRole('SuperAdmin');
            try{
                if($status_admin || $status_keanggotaan){
                    Keanggotaan::create([
                        'id_user' => $request->id_user ,
                        'id_keanggotaan' => $request->id_keanggotaan,
                        'jenis_keanggotaan' => $request->jenis_keanggotaan,
                        'created_by' => $request->user()->email ,
                        'updated_by' => $request->user()->email ,
                    ]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
                }
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menambahkan keanggotaan pengguna']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keanggotaan  $keanggotaan
     * @return \Illuminate\Http\Response
     */
    public function show(Keanggotaan $keanggotaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Keanggotaan  $keanggotaan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Keanggotaan $keanggotaan = null)
    {
        //
        $id_user = $request->id_user;
        $id_keanggotaan = $request->id_keanggotaan;
        $jenis_keanggotaan = $request->jenis_keanggotaan;
        $data = [];
        $status_keanggotaan = false;
        switch ($jenis_keanggotaan) {
            case "organisasi":
                $status_keanggotaan = in_array($id_keanggotaan, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
                break;
            case "divisi":
                $status_keanggotaan = in_array($id_keanggotaan, $request->user()->keanggotaan_by_role('AdminDivisi'));
                break;
            case "tim":
                $status_keanggotaan = in_array($id_keanggotaan, $request->user()->keanggotaan_by_role('AdminTim'));
                break;
            default:
                $status_keanggotaan = false;
        }
        if(($request->user() != null && $status_keanggotaan) || ($request->user()->hasRole(['SuperAdmin']))){
            try{
                $conditions = [
                    'id_user' => $id_user,
                    'id_keanggotaan' => $id_keanggotaan,
                    'jenis_keanggotaan' => $jenis_keanggotaan
                ];
                $data = Keanggotaan::where($conditions)->get();
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil mengambil data role keanggotaan', 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Keanggotaan  $keanggotaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keanggotaan $keanggotaan = null)
    {
        //
        $jenis_keanggotaan = $request->jenis_keanggotaan;
        $status_keanggotaan = false;
        switch ($jenis_keanggotaan) {
            case "organisasi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
                break;
            case "divisi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminDivisi'));
                break;
            case "tim":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminTim'));
                break;
            default:
                $status_keanggotaan = false;
        }
        if(($request->user() != null && $status_keanggotaan) || ($request->user()->hasRole(['SuperAdmin']))){
            $request->validate([
                'id_user' => ['required', 'integer'],
                'id_keanggotaan' => ['required', 'string'],
                'jenis_keanggotaan' => ['required', 'string'],
            ]);
            try{
                $id_user = $request->id_user;
                $id_keanggotaan = $request->id_keanggotaan;
                $jenis_keanggotaan = $request->jenis_keanggotaan;
                $role_keanggotaan = $request->role_keanggotaan;
                $permissions_keanggotaan = $request->permissions_keanggotaan == null ? null : $request->permissions_keanggotaan;
                $data = [
                    'id_user' => $id_user,
                    'id_keanggotaan' => $id_keanggotaan,
                    'jenis_keanggotaan' => $jenis_keanggotaan
                ];
                Keanggotaan::where($data)->delete();
                $data_keanggotaan = [];
                // error_log($role_keanggotaan);
                $role_keanggotaan = array_filter(explode(',',$role_keanggotaan));
                foreach($role_keanggotaan as $value) {
                    # code...

                    $data_keanggotaan[] = [
                        'id_user' => $id_user,
                        'id_keanggotaan' => $id_keanggotaan,
                        'jenis_keanggotaan' => $jenis_keanggotaan,
                        'role_keanggotaan' => $value,
                        'permissions_keanggotaan' => $permissions_keanggotaan
                    ];
                }
                Keanggotaan::insert($data_keanggotaan);
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil mengupdate keanggotaan']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keanggotaan  $keanggotaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Keanggotaan $keanggotaan = null)
    {
        //
        $jenis_keanggotaan = $request->jenis_keanggotaan;
        $status_keanggotaan = false;
        switch ($jenis_keanggotaan) {
            case "organisasi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminOrganisasi'));
                break;
            case "divisi":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminDivisi'));
                break;
            case "tim":
                $status_keanggotaan = in_array($request->id_keanggotaan, $request->user()->keanggotaan_by_role('AdminTim'));
                break;
            default:
                $status_keanggotaan = false;
        }
        if(($request->user() != null && $status_keanggotaan) || ($request->user()->hasRole(['SuperAdmin']))){
            $request->validate([
                'id_user' => ['required', 'integer'],
                'id_keanggotaan' => ['required', 'string'],
                'jenis_keanggotaan' => ['required', 'string'],
            ]);

            // $keanggotaans = $request->user()->keanggotaans();
            // $status_keanggotaan = false;
            // foreach($keanggotaans as $keanggotaan){
            //     if($keanggotaan['id_keanggotaan'] == $request->id_keanggotaan){
            //         $status_keanggotaan = true;
            //     }
            // }
            try{
                $id_user = $request->id_user;
                $id_keanggotaan = $request->id_keanggotaan;
                $jenis_keanggotaan = $request->jenis_keanggotaan;
                $data = [
                    'id_user' => $id_user,
                    'id_keanggotaan' => $id_keanggotaan,
                    'jenis_keanggotaan' => $jenis_keanggotaan
                ];
                // if(($status_keanggotaan == true) || ($request->user()->hasRole('SuperAdmin'))){
                //     Keanggotaan::where($data)->delete();
                // }else{
                //     return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
                // }
                Keanggotaan::where($data)->delete();
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus keanggotaan']);
    }
}
