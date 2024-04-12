<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        try {
            if (!$token = auth()->attempt($credentials)) {
                return [
                    'status' => 500,
                    'message' => 'Failed'
                ];
            }

            $user = Auth::guard('api')->user();

            $user->authorisation = $this->respondWithToken($token)->getData();

        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => $e
            ];
        }
        return [
            'status' => 200,
            'message' => 'Success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
    }

    public function logout()
    {

        try {

            Auth::guard('api')->logout();

        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => $e
            ];
        }

        return [
            'status' => 200,
            'message' => 'Success'
        ];
    }

    public function me()
    {
        $user = Auth::guard('api')->user();

        if($user) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'user'=> $user
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'failed',
                'user'=> $user
            ]);
        }
    }

    public function register(Request $request)
    {

        $user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'pass_jwt' => ''
        ]);
    }

    public function loginForm()
    {
        return view('auth/login');
    }

    public function registerForm()
    {
        return view('auth/register');
    }
}
