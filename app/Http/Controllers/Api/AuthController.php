<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if (!empty($request->admin_key) && $request->admin_key == env('REGISTER_ADMIN_KEY')) {
                $token = $user->createToken('TipsOdds', [User::WRITE_API])->plainTextToken;
                $message = 'Admin user created successfully';
            } else {
                $token = $user->createToken('TipsOdds', [User::READ_API])->plainTextToken;
                $message = 'User created successfully';
            }

            return response()->json([
                'status' => true,
                'message' => $message,
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
               'status' => false,
               'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                   'status' => false,
                   'message' => 'Invalid credentials',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
               'status' => true,
               'message' => 'User logged in successfully',
               'token' => $user->createToken('TipsOdds')->plainTextToken,
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
        }
    }

}
