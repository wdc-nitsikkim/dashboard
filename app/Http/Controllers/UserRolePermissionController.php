<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\User;
use App\Models\UserRolePermission;

class UserRolePermissionController extends Controller {
    public function manage($id) {
        $user = User::with([
            'allowedDepartments.department:id,code,name',
            'roles.permissions'
        ])->withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        return view('users.manage', [
            'user' => $user
        ]);
    }

    public function savePermissions(Request $request, $id) {
        $user = User::with('roles.permissions')->withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        $data = $request->validate([
            'role' => 'nullable | array'
        ]);

        $currentRoleIds = $user->roles->pluck('id')->toArray();
        $time = CustomHelper::dateToUtc(now());
        $permissionMap = array_keys(CustomHelper::getInversePermissionMap());
        $insert = [];

        DB::beginTransaction();
        try {
            UserRolePermission::whereIn('role_id', $currentRoleIds)->delete();

            if (! is_null($data['role'] ?? null)) {
                foreach ($data['role'] as $key => $val) {
                    if (! $user->roles->contains('role', $key)) {
                        continue;
                    }
                    $currentRole = $user->roles->where('role', $key)->first();
                    $newPerms = array_keys($val);

                    foreach ($newPerms as $permission) {
                        if (! in_array($permission, $permissionMap)){
                            continue;
                        }
                        $insert[] = [
                            'role_id' => $currentRole->id,
                            'permission' => $permission,
                            'created_at' => $time
                        ];
                    }
                }

                UserRolePermission::insert($insert);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'status' => 'fail',
                'message' => 'Operation failed with 1 or more errors!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Permissions updated'
        ]);
    }
}
