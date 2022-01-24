<?php

use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
// use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/test', function (Request $request) {
//     // $user = User::find(1);
//     // $article_create = Permission::create(['name' => 'articles.create', 'guard_name' => 'web']);
//     // $article_read = Permission::create(['name' => 'articles.read', 'guard_name' => 'web']);
//     // $article_update = Permission::create(['name' => 'articles.update', 'guard_name' => 'web']);
//     // $article_delete = Permission::create(['name' => 'articles.delete', 'guard_name' => 'web']);
//     // $article_publish = Permission::create(['name' => 'articles.publish', 'guard_name' => 'web']);
//     // $article_unpublish = Permission::create(['name' => 'articles.unpublish', 'guard_name' => 'web']);
//     // $article_restore = Permission::create(['name' => 'articles.restore', 'guard_name' => 'web']);
//     // $article_verification = Permission::create(['name' => 'articles.verification', 'guard_name' => 'web']);

//     // $users_create = Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
//     // $user_read = Permission::create(['name' => 'users.read', 'guard_name' => 'web']);
//     // $user_update = Permission::create(['name' => 'users.update', 'guard_name' => 'web']);
//     // $user_delete = Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);
//     // $user_ban = Permission::create(['name' => 'users.ban', 'guard_name' => 'web']);
//     // $user_unban = Permission::create(['name' => 'users.unban', 'guard_name' => 'web']);
//     // $user_restore = Permission::create(['name' => 'users.restore', 'guard_name' => 'web']);
//     // $user_verification = Permission::create(['name' => 'users.verification', 'guard_name' => 'web']);

//     // $inovation_create = Permission::create(['name' => 'inovations.create', 'guard_name' => 'web']);
//     // $inovation_read = Permission::create(['name' => 'inovations.read', 'guard_name' => 'web']);
//     // $inovation_update = Permission::create(['name' => 'inovations.update', 'guard_name' => 'web']);
//     // $inovation_delete = Permission::create(['name' => 'inovations.delete', 'guard_name' => 'web']);
//     // $inovation_publish = Permission::create(['name' => 'inovations.publish', 'guard_name' => 'web']);
//     // $inovation_unpublish = Permission::create(['name' => 'inovations.unpublish', 'guard_name' => 'web']);
//     // $inovation_restore = Permission::create(['name' => 'inovations.restore', 'guard_name' => 'web']);
//     // $inovation_verification = Permission::create(['name' => 'inovations.verification', 'guard_name' => 'web']);
//     $permissions = [];

//     // $permissions = ['inovations.create', 'inovations.read', 'inovations.update', 'inovations.delete', 'inovations.publish', 'inovations.unpublish', 'inovations.restore', 'inovations.verification', 'articles.create', 'articles.read', 'articles.update', 'articles.delete', 'articles.publish', 'articles.unpublish', 'articles.restore', 'articles.verification', 'users.create', 'users.update', 'users.read', 'users.delete', 'users.ban', 'users.unban', 'users.restore', 'users.verification'];
//     // $user->syncPermissions($permissions);

//     // $permissions = Permission::all();
//     return response($permissions, 200);

// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    try {
        //code...
        $user = $request->user();
    } catch (Exception $e) {
        //throw $th;
        return response()->json(['status' => false, 'message' => $e->getMessage(), 'user' => []]);
    }
    return response()->json(['status' => true, 'message' => 'User ada', 'user' => $user]);
});

Route::middleware('auth:sanctum')->get('/user-permission', function (Request $request) {
    $user = $request->user();
    $permissions_temp = $request->user()->getAllPermissions();
    $roles_temp = $request->user()->getRoleNames();
    $permissions = [];
    $roles = [];
    foreach ($permissions_temp as $key => $value) {
        $permissions[] = $value->name;
    };
    foreach ($roles_temp as $key => $value) {
        $roles[] = $value;
    };
    return response()->json(['status' => true, 'user' => $user, 'permissions' => $permissions, 'roles' => $roles]);
});

Route::middleware('auth:sanctum')->get('/user-memberships/{role?}/{detail?}', function (Request $request, String $role = null, bool $detail = null) {
    if($role == null){
        $role = 'all';
    }
    if($detail == null){
        $detail = false;
    }
    $data = $request->user()->keanggotaan_by_role($role, $detail);
    return response()->json(['status' => true, 'data' => $data, 'message' => 'Berhasil ambil data keanggotaan']);
});

// router untuk mengambil gambar infografis
Route::get('infografis/{filename?}', function ($filename = null)
{
    if($filename == null){
        $dirs = [];
        $dir_path = public_path() . '/infografis';
        // $dir = new DirectoryIterator($dir_path);
        $dir = array_diff(scandir($dir_path), array('.', '..')); 
        foreach ($dir as $fileinfo) {
            $dirs[] = $fileinfo;
        }
        return response()->json(['status' => true, 'files' => $dirs]);
    }
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/infografis/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/infografis/error.jpg';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// routing API logo organisasi
Route::get('logo_organisasi/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/logo_organisasi/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/logo_organisasi/error.png';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
// routing API logo divisi
Route::get('logo_divisi/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/logo_divisi/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/logo_divisi/error.png';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
// routing API logo tim
Route::get('logo_tim/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/logo_tim/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/logo_tim/error.png';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// router untuk mengambil other
Route::get('other/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/other/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/infografis/error.pdf';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// untuk ambil gambar depan artikel
Route::get('gambar_pembuka_artikel/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/gambar_pembuka_artikel/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/gambar_pembuka_artikel/error.jpg';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// untuk akses file bukti pembelian aset
Route::get('lampiran_artikel/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/lampiran_artikel/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/lampiran_artikel/error.pdf';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// untuk akses gambar ikon
Route::get('ikon_inovation/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/ikon_inovation/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/ikon_inovation/error.pdf';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// untuk akses gambar pendukung inovation
Route::get('gambar_inovation/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/gambar_inovation/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/gambar_inovation/error.pdf';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//registrasi dan otorisasi
Route::post('/register', [App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');

// router untuk berita
Route::post('/insert-artikel', [App\Http\Controllers\ArtikelController::class, 'store'])->middleware('auth:sanctum');
Route::get('/get-artikels', [App\Http\Controllers\ArtikelController::class, 'show'])->middleware('auth:sanctum');
Route::get('/artikels', [App\Http\Controllers\ArtikelController::class, 'pagination']);
Route::get('/ambil-artikel/{id_artikel?}/{jumlah_konten_artikel?}', [App\Http\Controllers\ArtikelController::class, 'index']);
Route::post('/hapus-artikel', [App\Http\Controllers\ArtikelController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-artikel', [App\Http\Controllers\ArtikelController::class, 'update'])->middleware('auth:sanctum');
Route::post('/verification-artikel', [App\Http\Controllers\ArtikelController::class, 'verification'])->middleware('auth:sanctum');

// router untuk user
Route::middleware('auth:sanctum')->get('/users/{search?}', function (Request $request, String $search = null) {
    $users = [];
    if($request->user() != null && $request->user()->hasPermissionTo('users.read')){
        if($search != null && $request->keanggotaan_pengguna == 'tambah_anggota' && $request->id_keanggotaan != null && $request->jenis_keanggotaan != null){
            error_log('pencarian user untuk tambah anggota di manajemen organisasi');
            $id_keanggotaan = $request->id_keanggotaan;
            $jenis_keanggotaan = $request->jenis_keanggotaan;
            $keanggotaan = User::with(['keanggotaans'])->where('users.name', 'like', '%'.$search.'%')->orWhere('users.email', 'like', '%'.$search.'%')->get();
            return response($keanggotaan, 200);
        }elseif($request->id_keanggotaan != null && $request->jenis_keanggotaan != null){
            error_log('ambil user dari suatu keanggotaan di manajemen organisasi');
            $id_keanggotaan = $request->id_keanggotaan;
            $jenis_keanggotaan = $request->jenis_keanggotaan;
            $keanggotaan = Keanggotaan::select(['id_user', 'id_keanggotaan', 'jenis_keanggotaan', 'id', 'name', 'email', 'users.updated_at', 'users.created_at'])->groupBy(['id_user', 'id_keanggotaan', 'jenis_keanggotaan', 'id', 'name', 'email', 'users.updated_at', 'users.created_at'])->where('id_keanggotaan', $id_keanggotaan)->where('jenis_keanggotaan', $jenis_keanggotaan)->join('users', 'users.id', '=', 'keanggotaans.id_user')->get();
            // $keanggotaan = DB::table('keanggotaans')->where('id_keanggotaan', $id_keanggotaan)->where('jenis_keanggotaan', $jenis_keanggotaan)->select(['id_user', 'id_keanggotaan', 'jenis_keanggotaan', 'id', 'name', 'email', 'users.updated_at', 'users.created_at'])->distinct()->join('users', 'users.id', '=', 'keanggotaans.id_user')->get();
            return response($keanggotaan, 200);
        }elseif($search == null){
            error_log('all user');
            $users = User::all();
            return response($users, 200);
        }else{
            error_log('pencarian tanpa syarat');
            $users = User::where('name', 'like', '%'.$search.'%')->get();
            return response($users, 200);
        }
    }else{
        return response($users, 200);
    }
});
Route::get('/get-permissions/{id?}', [App\Http\Controllers\UserController::class, 'permission'])->middleware('auth:sanctum');
Route::post('/add-permissions/{id?}', [App\Http\Controllers\UserController::class, 'addPermission'])->middleware('auth:sanctum');
Route::post('/change-info/', [App\Http\Controllers\UserController::class, 'changeInfo'])->middleware('auth:sanctum');
Route::post('/change-password/', [App\Http\Controllers\UserController::class, 'changePassword'])->middleware('auth:sanctum');

// infografis
Route::get('/pagination-infografis', [App\Http\Controllers\InfografisController::class, 'pagination']);
Route::post('/insert-infografis', [App\Http\Controllers\InfografisController::class, 'store'])->middleware('auth:sanctum');
Route::get('/show-infografis', [App\Http\Controllers\InfografisController::class, 'show'])->middleware('auth:sanctum');
Route::post('/update-tampilan-infografis', [App\Http\Controllers\InfografisController::class, 'updateTampilan'])->middleware('auth:sanctum');
Route::post('/delete-infografis', [App\Http\Controllers\InfografisController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-infografis', [App\Http\Controllers\InfografisController::class, 'update'])->middleware('auth:sanctum');
Route::get('/check-infografis', [App\Http\Controllers\InfografisController::class, 'index']);


// router untuk inovation
Route::post('/insert-inovation', [App\Http\Controllers\InovationController::class, 'store'])->middleware('auth:sanctum');
Route::get('/get-inovations', [App\Http\Controllers\InovationController::class, 'show'])->middleware('auth:sanctum');
Route::get('/inovations', [App\Http\Controllers\InovationController::class, 'pagination']);
Route::get('/ambil-inovation/{id_inovation?}/{jumlah_konten_inovation?}', [App\Http\Controllers\InovationController::class, 'index']);
Route::post('/delete-inovation', [App\Http\Controllers\InovationController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-inovation', [App\Http\Controllers\InovationController::class, 'update'])->middleware('auth:sanctum');
Route::post('/verification-inovation', [App\Http\Controllers\InovationController::class, 'verification'])->middleware('auth:sanctum');

// router untuk organisasi
Route::post('/create-organisasi', [App\Http\Controllers\OrganisasiController::class, 'store'])->middleware('auth:sanctum');
Route::get('/read-organisasi', [App\Http\Controllers\OrganisasiController::class, 'index'])->middleware('auth:sanctum');
Route::post('/delete-organisasi', [App\Http\Controllers\OrganisasiController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-organisasi', [App\Http\Controllers\OrganisasiController::class, 'update'])->middleware('auth:sanctum');
Route::get('/take-organisasi/{id_organisasi?}', [App\Http\Controllers\OrganisasiController::class, 'show'])->middleware('auth:sanctum');

// router untuk divisi
Route::post('/create-divisi', [App\Http\Controllers\DivisiController::class, 'store'])->middleware('auth:sanctum');
Route::get('/read-divisi/{id_organisasi?}', [App\Http\Controllers\DivisiController::class, 'index'])->middleware('auth:sanctum');
Route::post('/delete-divisi', [App\Http\Controllers\DivisiController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-divisi', [App\Http\Controllers\DivisiController::class, 'update'])->middleware('auth:sanctum');
Route::get('/take-divisi/{id_divisi?}', [App\Http\Controllers\DivisiController::class, 'show'])->middleware('auth:sanctum');

// router untuk tim
Route::post('/create-tim', [App\Http\Controllers\TimController::class, 'store'])->middleware('auth:sanctum');
Route::get('/read-tim', [App\Http\Controllers\TimController::class, 'index'])->middleware('auth:sanctum');
Route::post('/delete-tim', [App\Http\Controllers\TimController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-tim', [App\Http\Controllers\TimController::class, 'update'])->middleware('auth:sanctum');
Route::get('/take-tim/{id_tim?}', [App\Http\Controllers\TimController::class, 'show'])->middleware('auth:sanctum');

// route untuk keanggotaan
Route::post('/create-keanggotaan', [App\Http\Controllers\KeanggotaanController::class, 'store'])->middleware('auth:sanctum');
Route::get('/read-keanggotaan', [App\Http\Controllers\KeanggotaanController::class, 'index'])->middleware('auth:sanctum');
Route::post('/delete-keanggotaan', [App\Http\Controllers\KeanggotaanController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/update-keanggotaan', [App\Http\Controllers\KeanggotaanController::class, 'update'])->middleware('auth:sanctum');
Route::get('/take-keanggotaan', [App\Http\Controllers\KeanggotaanController::class, 'edit'])->middleware('auth:sanctum');

// router error
Route::get('*', function ()
{
    return response()->json(['status' => false, 'message' => 'null']);
});