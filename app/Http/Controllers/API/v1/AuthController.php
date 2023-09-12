<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
  }

  public function login(Request $request)
  {
    try {
      $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
      ]);

      $credentials = $request->only('email', 'password');
      $token = Auth::attempt($credentials);

      if (!$token) {
        return response()->json([
          'message' => 'Unauthorized',
        ], 401);
      }

      $user = Auth::user();
      return response()->json([
        'user' => $user,
        'authorization' => [
          'token' => $token,
          'type' => 'bearer',
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'An error occurred while logging in.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ], 400);
    }

    try {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      return response()->json([
        'message' => 'User created successfully',
        'user' => $user
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'An error occurred while registering the user.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function logout()
  {
    try {
      Auth::logout();

      return response()->json([
        'message' => 'Successfully logged out',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'An error occurred while logging out.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function refresh()
  {
    try {
      return response()->json([
        'user' => Auth::user(),
        'authorization' => [
          'token' => Auth::refresh(),
          'type' => 'bearer',
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'An error occurred while refreshing the authentication token.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
