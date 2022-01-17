<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    //fungsi register dari api
    public function register(Request $request){

    	// validasi isian
    	$request->validate([
    		'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            // 'roles'=> ['required', 'string'],
    	]);

    	User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'username' => $request->username,
    		'password' => Hash::make($request->password),
    		// 'roles' => $request->roles,
    	]);

    	return response()->json(['msg' => 'Register success']);

    }

    // fungsi untuk login dari API sanctum
    public function login(Request $request){
    	$data = $request->validate([
	        'email' => 'required|email',
	        'password' => 'required',
	        'device_name' => 'required',
	    ]);

		try{
			$user = User::where('email', $request->email)->first();

			if (!$user || !Hash::check($request->password, $user->password)) {
				// throw ValidationException::withMessage([
				// 	'email' => ['The provided credentials are incorrect'],
				// ]);
				return response()->json(['status' => false, 'message' => 'Email dan password berbeda'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
			}

			$callData = [ 'status' => true, 'token' => $user->createToken($request->device_name)->plainTextToken];
			return response()->json($callData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    
		}catch(Exception $e){
			return response()->json(['status' => false, 'message' =>$e->getMessage()], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
		}
	}

    //fungsi untuk logout via API
    public function logout(Request $request){
		try {
			//code...
			$request->user()->currentAccessToken()->delete();
		} catch (Exception $e) {
			//throw $th;
			return response()->json(['status' => false, 'message' =>$e->getMessage()], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
		}
    	
    	return response()->json(['status' => true, 'message' =>'Logout berhasil'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
		
    }
}
