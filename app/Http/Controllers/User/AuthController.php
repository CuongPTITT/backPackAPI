<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
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
        try {
            $token = Auth::guard('api')->attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed'
                ]);
            }

            $user = Auth::guard('api')->user();

            $user->authorisation = $this->respondWithToken($token)->getData();

            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e
            ]);
        }
    }

    public function logout()
    {
        try {

            Auth::guard('api')->logout();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
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

    public function update_post(Request $request)
    {
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);

        $fields = ['title', 'description', 'status'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $post->$field = $request->input($field);
            }
        }

        if ($request->hasFile('image')) {
            $post->image = $request->file('image');
        }

        $post->save();

        return response()->json([
            'status' => 200,
            'message' => 'success',
        ]);
    }
}
