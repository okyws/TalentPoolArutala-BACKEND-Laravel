<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function index()
  {
    $users = User::all();

    return response()->json([
      'users' => $users
    ]);
  }

  public function show($id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json([
        'message' => 'User not found'
      ], 404);
    }

    return response()->json([
      'user' => $user
    ]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ], 400);
    }

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
      'message' => 'User created successfully',
      'user' => $user
    ], 201);
  }

  public function update(Request $request, $id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json([
        'message' => 'User not found'
      ], 404);
    }

    $validator = Validator::make($request->all(), [
      'name' => 'string|max:255',
      'email' => 'string|email|max:255|unique:users,email,' . $user->id,
      'password' => 'string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ], 400);
    }

    if ($request->has('name')) {
      $user->name = $request->name;
    }
    if ($request->has('email')) {
      $user->email = $request->email;
    }
    if ($request->has('password')) {
      $user->password = Hash::make($request->password);
    }
    $user->save();

    return response()->json([
      'message' => 'User updated successfully',
      'user' => $user
    ]);
  }

  public function destroy($id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json([
        'message' => 'User not found'
      ], 404);
    }

    $user->delete();

    return response()->json([
      'message' => 'User deleted successfully'
    ]);
  }
}
