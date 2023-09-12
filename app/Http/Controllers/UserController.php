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
    $this->middleware(['auth:api', 'role:admin']);
  }

  public function index()
  {
    try {
      $users = User::all();

      return response()->json([
        'users' => $users
      ]);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving users'], 500);
    }
  }

  public function show($id)
  {
    try {
      $user = User::findOrFail($id);

      return response()->json([
        'user' => $user
      ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'User not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving user'], 500);
    }
  }

  public function store(Request $request)
  {
    try {
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
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error creating user'], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $user = User::findOrFail($id);

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
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'User not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error updating user'], 500);
    }
  }

  public function destroy($id)
  {
    try {
      $user = User::findOrFail($id);
      $user->delete();

      return response()->json([
        'message' => 'User deleted successfully'
      ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'User not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error deleting user'], 500);
    }
  }
}
