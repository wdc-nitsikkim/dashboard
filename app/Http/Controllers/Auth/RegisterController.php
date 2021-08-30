<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermission;

class RegisterController extends Controller {
    public function __contruct() {
        $this->middleware('guest');
    }

    protected $allowedRoles = ['office', 'hod', 'faculty', 'staff', 'ecell', 'student'];

    public function index(Request $request, $role = null) {
        \Session::keep(['name', 'email']);

        if (in_array($role, $this->allowedRoles)) {
            return view('register', [
                'role' => $role
            ]);
        }
        return view('register', [
            'select' => true,
            'roles' => $this->allowedRoles
        ]);
    }

    public function defaultSignup(Request $request) {
        $data = $request->validate([
            'role' => ['required', 'in:' . implode(',', $this->allowedRoles)],
            'name' => 'required | string | min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'mobile' => ['required', 'numeric', 'digits:10',
                Rule::unique('users', 'mobile')
            ],
            'password' => 'required | min:6 | confirmed'
        ]);

        $data['password'] = \Hash::make($data['password']);
        $permissionConstants = CustomHelper::getPermissionConstants();

        DB::beginTransaction();
        try {
            $user = new User($data);
            $user->deleted_at = now();
            $user->save();
            /* assign role to user */
            $role = UserRole::create([
                'user_id' => $user->id,
                'role' => $data['role']
            ]);
            /* give read permission to the role */
            UserRolePermission::create([
                'role_id' => $role->id,
                'permission' => $permissionConstants['read'],
                'created_at' => now()
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput($request->all())->with([
                'status' => 'fail',
                'message' => 'An error occurred while signing you up!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Account created & role assigned. It will be activated within 24 hours'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
