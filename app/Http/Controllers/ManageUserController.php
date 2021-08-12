<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Department;
use App\Models\UserRolePermission;
use App\Models\UserAccessDepartment;

class ManageUserController extends Controller {
    public function __construct() {
        $this->timestamp = CustomHelper::dateToUtc(now());
    }

    public function manage($id) {
        $user = User::with([
            'allowedDepartments.department:id,code,name',
            'roles.permissions'
        ])->withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        $departments = Department::select('id', 'name')->whereNotIn('id',
            $user->allowedDepartments->pluck('department_id')->toArray()
        )->get();
        $allRoles = collect(CustomHelper::getRoles());
        $roles = $allRoles->diff($user->roles->pluck('role'));

        return view('users.manage', [
            'user' => $user,
            'roles' => $roles,
            'departments' => $departments
        ]);
    }

    public function savePermissions(Request $request, $id) {
        $user = User::with('roles.permissions')->withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        $data = $request->validate([
            'role' => 'nullable | array'
        ]);

        $currentRoleIds = $user->roles->pluck('id')->toArray();
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
                            'created_at' => $this->timestamp
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

    public function grantRole(Request $request, $id) {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        $request->validate([
            'role' => 'required | in:' . implode(',', CustomHelper::getRoles())
        ]);

        try {
            UserRole::create([
                'user_id' => $user->id,
                'role' => $request->role
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add role!'
            ]);
        }

        $msg = [
            'status' => 'success',
            'message' => 'Role added'
        ];

        return $request->role == 'admin'
            ? redirect()->route('users.account', $user->id)->with($msg)
            : back()->with($msg);
    }

    public function grantDepartmentAccess(Request $request, $id) {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('manage', [User::class, $user]);

        $request->validate([
            'department_id' => ['required', Rule::exists('departments', 'id')]
        ]);

        try {
            UserAccessDepartment::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'created_at' => $this->timestamp
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to give access to department!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Access granted'
        ]);
    }

    public function revokeRole($role_id) {
        $role = UserRole::findOrFail($role_id);
        $user = User::withTrashed()->findOrFail($role->user_id);

        $this->authorize('manage', [User::class, $user]);

        try {
            $role->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to revoke user role!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Role revoked'
        ]);
    }

    public function revokeDepartmentAccess($user_id, $dept_id) {
        $user = User::withTrashed()->findOrFail($user_id);

        $this->authorize('manage', [User::class, $user]);

        try {
            UserAccessDepartment::where([
                'user_id' => $user->id,
                'department_id' => $dept_id
            ])->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to revoke department access!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Department access revoked'
        ]);
    }
}
