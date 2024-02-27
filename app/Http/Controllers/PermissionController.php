<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $role = new Permission();
        $role->name = Str::slug($request->name, '_');
        if ($role->save()) {
            return response()->json(['msg' => 'Permission added successfully!', 'status' => 200]);
        } else {
            return response()->json(['error' => 'Permission not added: ', 'status' => 500], 500);
        }
    }
    public function update(Request $request)
    {
        $role = Role::find($request->id);

        if (!$role) {
            return response()->json(['msg' => 'Role not found!', 'status' => 'error']);
        }
        if ($request->isAssign == 'true') {
            // Extract the existing permissions and the new permission
            $existingPermissions = explode(',', $role->permissions);
            $newPermission = $request->name;

            // Remove the old permission if it exists
            $existingPermissions = array_diff($existingPermissions, [$newPermission]);

            // Add the new permission
            $existingPermissions[] = $newPermission;

            // Update the role's permissions
            $role->permissions = implode(',', $existingPermissions);

            if ($role->save()) {
                return response()->json(['msg' => 'Permission add successfully!', 'status' => 'success']);
            } else {
                return response()->json(['msg' => 'Permission not add.', 'status' => 'failed']);
            }
        } else {
            // Extract the existing permissions and the permission to remove
            $existingPermissions = explode(',', $role->permissions);
            $permissionToRemove = $request->name;
            // Remove the permission from the array
            $existingPermissions = array_diff($existingPermissions, [$permissionToRemove]);

            // Update the role's permissions
            $role->permissions = implode(',', $existingPermissions);

            if ($role->save()) {
                return response()->json(['msg' => 'Permission removed successfully!', 'status' => 'success']);
            } else {
                return response()->json(['msg' => 'Permission not removed.', 'status' => 'failed']);
            }
        }
    }
    public function delete(Request $request)
    {
        $permission = Permission::find($request->id);
        if ($permission) {
            if ($permission->delete()) {
                return response()->json(['msg' => 'Permission Deleted successfully!', 'status' => 'success']);
            }
        } else {
            return response()->json(['msg' => 'Permission not Deleted.', 'status' => 'failed']);
        }
        die;
    }
}
