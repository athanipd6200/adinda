<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Auth::routes();

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    try {
        //code...
        $user = $request->user();
    } catch (Exception $e) {
        //throw $th;
        return response()->json(['status' => false, 'message' => $e->getMessage(), 'user' => []]);
    }
    return response()->json(['status' => true, 'message' => 'User ada', 'user' => $user]);
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/create-aset', [App\Http\Controllers\AsetController::class, 'create'])->name('create-aset');
Route::get('userfiles/files/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $public_path = public_path();
    $path = $public_path . '/userfiles/files/' . $filename;

    if (!File::exists($path)) {
        $path = $public_path . '/userfiles/files/error.jpg';
        // abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
