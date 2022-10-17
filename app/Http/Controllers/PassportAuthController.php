<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role'      => 'required|integer',
                'name' => 'required|max:200',
                'username'  => 'required|max:100',
                'password'  => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Validation failed');
            }

            $user = User::create([
                'role_id'   => $request->role,
                'nama_user' => $request->name,
                'username'  => $request->username,
                'password'  => Hash::make($request->password)
            ]);

            $token = $user->createToken('LaravelAuthApp')->accessToken;

            return response()->json(['token' => $token], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username'  => 'required|max:100',
                'password'  => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Validation failed');
            }

            $user = $request->username;
            $pass = $request->password;

            if (!auth()->attempt(['username' => $user, 'password' => $pass, 'active' => 1])) {
                throw new \Exception("Unauthorised");
            }

            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;

            return response()->json(['token' => $token], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
