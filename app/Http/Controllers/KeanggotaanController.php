<?php

namespace App\Http\Controllers;

use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

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
        if($request->user()->hasRole(['SuperAdmin'])){
            $status_keanggotaan = true;
        }else{
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
        }
        if(($request->user() != null && $status_keanggotaan)){
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
                if($id_user == $request->user()->id){
                    $role_keanggotaan[] = 'Admin'.ucfirst($jenis_keanggotaan);
                }
<<<<<<< HEAD
                $str_penulis = "Penulis";
                $str_penyunting = "Penyunting";
                $str_supervisor = "Supervisor";

                $str_artikel = "Artikel";
                $str_inovasi = "Inovasi";
                $permision_user = [];
                
                foreach($role_keanggotaan as $value) {
                    # code...


                    //artikel penulis
                    if(strpos($value,$str_penulis) !==false){

                        if(strpos($value,$str_artikel) !==false){

                            // $user = User::find($id_user);
                            //automatically add all of permisiion.
                            // $user->syncPermissions($list_permission);
                            array_push($permision_user,"articles.create","articles.read","articles.delete","articles.restore");

                        }elseif(strpos($value,$str_inovasi) !==false){
                            // $user = User::find($id_user);
                            //automatically add all of permisiion.
                            // $user->syncPermissions($list_permission);

                            array_push($permision_user,"inovations.create","inovations.read","inovations.delete","inovations.restore");
                        }

                    }elseif(strpos($value,$str_penyunting) !==false){

                        if(strpos($value,$str_artikel) !==false){
                            // $list_permission = ["articles.read","articles.update"];
                            // $user = User::find($id_user);
                            // //automatically add all of permisiion.
                            // $user->syncPermissions($list_permission);
                            array_push($permision_user,"articles.read","articles.update");


                        }elseif(strpos($value,$str_inovasi) !==false){
                            // $list_permission = ["inovations.read","inovations.update"];
                            // $user = User::find($id_user);
                            // //automatically add all of permisiion.
                            // $user->syncPermissions($list_permission);
                            array_push($permision_user,"inovations.read","inovations.update");

                        }

                    }elseif(strpos($value,$str_supervisor) !==false ){

                        // $list_permission = ["articles.read","articles.verification","articles.publish","articles.unpublish","inovations.read","inovations.verification","inovations.publish","inovations.unpublish"];
                        // $user = User::find($id_user);
                        //     //automatically add all of permisiion.
                        // $user->syncPermissions($list_permission);

                        array_push($permision_user,"articles.read","articles.verification","articles.publish","articles.unpublish","inovations.read","inovations.verification","inovations.publish","inovations.unpublish");

                    }



=======
                foreach($role_keanggotaan as $value) {
                    # code...

>>>>>>> ca7b705b3048e7965e784e1afb7ba06ba30e178d
                    $data_keanggotaan[] = [
                        'id_user' => $id_user,
                        'id_keanggotaan' => $id_keanggotaan,
                        'jenis_keanggotaan' => $jenis_keanggotaan,
                        'role_keanggotaan' => $value,
                        'permissions_keanggotaan' => $permissions_keanggotaan
                    ];
                }
<<<<<<< HEAD
                $permision_user = array_unique($permision_user);

                $user = User::find($id_user);
                //automatically add all of permisiion.
                $user->syncPermissions($permision_user);

=======
>>>>>>> ca7b705b3048e7965e784e1afb7ba06ba30e178d
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
                if($id_user != $request->user()->id){
                    Keanggotaan::where($data)->delete();
                }
            }catch (Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        return response()->json(['status' => true, 'message' => 'Berhasil menghapus keanggotaan']);
    }
}
