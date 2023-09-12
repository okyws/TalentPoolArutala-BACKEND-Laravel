<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\API\v1\Controller;

class PermissionController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role_or_permission:admin']);
  }

  public function assignPermissionToRole(Request $request)
  {
    try {
      $request->validate([
        'role_id' => 'required|exists:roles,id',
        'permission_id' => 'required|exists:permissions,id',
      ]);

      $role = Role::findOrFail($request->input('role_id'));
      $permission = Permission::findOrFail($request->input('permission_id'));

      $role->givePermissionTo($permission);

      return response()->json(['message' => 'Permission assigned to role successfully'], 200);
    } catch (ValidationException $e) {
      return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function assignPermissionToUser(Request $request)
  {
    try {
      $request->validate([
        'user_id' => 'required|exists:users,id',
        'permission_id' => 'required|exists:permissions,id',
      ]);

      $user = User::findOrFail($request->input('user_id'));
      $permission = Permission::findOrFail($request->input('permission_id'));

      $user->givePermissionTo($permission);

      return response()->json(['message' => 'Permission assigned to user successfully'], 200);
    } catch (ValidationException $e) {
      return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
