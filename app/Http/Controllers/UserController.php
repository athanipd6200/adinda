<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    //

    public function index()
    {
        //
        return response();
        
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        return response()->json(['msg' => 'Sukses menghapus aset']);
    }

    public function permission(Request $request, String $id = null){
        // $owner = User::find($request->user()->id);
        // error_log($owner);
        // error_log($request->user()->getAllPermissions());
        $permissions = [];
        $user_permissions = [];
        if($request->user()->hasPermissionTo('users.read')){
            try{
                $user = User::find($id);
                // $user->givePermissionTo(['articles.read', 'articles.update', 'articles.delete']);
                $user_permissions_temp = $user->getAllPermissions();
                $permissions_temp = Permission::where('name', 'like', 'users%')->get();
                // $permissions_temp = Permission::all();
                // dd($permissions_temp);
                foreach ($permissions_temp as $key => $value) {
                    // code...
                    $permissions[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'created_at' => $value->created_at
                    ];
                };
                foreach ($user_permissions_temp as $key => $value) {
                    // code...
                    $user_permissions[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'created_at' => $value->created_at
                    ];
                };
            }catch(Exception $e){
                error_log($e->getMessage());
                return response()->json(['status' => false, 'available' => $permissions, 'taken' => $user_permissions]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Tidak bisa diakses (Forbidden)']);
        }
        
        return response()->json(['status' => true, 'available' => $permissions, 'taken' => $user_permissions]);
    }

    public function addPermission(Request $request, String $id = null){
        $permissions = json_decode($request->permissions);
        // dd($permissions);
        // dd($request);
        // error_log($request->permissions);
        // error_log($request->user()->getAllPermissions());
        if($request->user()->hasPermissionTo('users.update')){
            try {
                // error_log('masuk ke try');
                $user = User::find($id);
                //automatically add all of permisiion.
                $user->syncPermissions($permissions);
            }catch(Exception $e){
                error_log($e);
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            } 
        }else{
            return response()->json(['status' => false, 'message' => "Tidak bisa diakses (Forbidden)"]);
        }


        return response()->json(['status' => true, 'message' => "Berhasil masuk"]);
    }

    public function changeInfo(Request $request){
        try{
            $id = $request->user()->id;
            $name = $request->name;
            $user_change = User::find($id);
            $user_change->name = $name;
            $user_change->save();
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage]);
        }
        return response()->json(['status' => true, 'message' => "Berhasil mengupdate data"]);
    }

    public function changePassword(Request $request, String $id = null){
        try{
            if (!$request->user() || !Hash::check($request->old, $request->user()->password)) {
                return response()->json(['status' => false, 'message' => "Password lama berbeda"]);
            }elseif($request->new != $request->check){
                return response()->json(['status' => false, 'message' => "Konfirmasi password berbeda"]);
            }else{
                $password = Hash::make($request->new);
                $user_change = User::find($request->user()->id);
                $user_change->password = $password;
                $user_change->save();
                $user_change->refresh();
            }
            // $password = Hash::make($request->new);
            // $user_change = User::find($request->user()->id);
            // $user_change->password = $password;
            // $user_change->save();
            // $user_change->refresh();
            error_log($request->user()->password);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage]);
        }
        return response()->json(['status' => true, 'message' => "Berhasil mengganti password"]);
    }
}
