<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use App\User;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $data = $request->all();

		try {
			$credentials['username'] = $data['username'];
			$credentials['password'] = $data['password'];

			$token = Auth::attempt($credentials);
			if (!$token) {
				return response()->json(['error' => 'credential wrong'], 404);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'something_went_wrong'], 500);
		}
		return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
			'user' => Auth::user()
		]);
    }

    public function register(Request $request)
    {
        try {
            // check duplicate username
			$usernameDuplicate = User::where('username', $request->username)->first();
			if ($usernameDuplicate) {
				return response()->json(['error' => ['field' => 'username', 'msg' => 'Username already exist.']], 409);
            }

			$user = new User();
			$user->username = $request->username;
			$user->password = bcrypt($request->password);
            $user->user_type = $request->user_type;
			$user->save();

			$credentials = request(['username', 'password']);
			if (!$token = Auth::attempt($credentials)) {
				return response()->json(['error' => 'Unauthorized'], 401);
			}
			return $this->respondWithToken($token);

		} catch (\Throwable $e) {
			//
			Log::error('Register error : ' . $e->getMessage());
			return response()->json(['error' => 'Internal server error'], 500);
		}
    }

    public function createUser(Request $request)
    {
        try {
            // check duplicate username
			$usernameDuplicate = User::where('username', $request->username)->first();
			if ($usernameDuplicate) {
				return response()->json(['error' => ['field' => 'username', 'msg' => 'Username already exist.']], 409);
            }

			$user = new User();
			$user->username = $request->username;
			$user->password = bcrypt($request->password);
            $user->user_type = $request->user_type;
			$user->save();

            $credentials = request(['username', 'password']);
            return response()->json(['user' => $credentials], 201);

		} catch (\Throwable $e) {
			//
			Log::error('Register error : ' . $e->getMessage());
			return response()->json(['error' => 'Internal server error'], 500);
		}

    }

}
