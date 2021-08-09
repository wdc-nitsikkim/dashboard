<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

use App\CustomHelper;
use App\Models\User;
use App\Models\UserRole;

class RegisterController extends Controller {
    public function __contruct() {
        $this->middleware('guest');
    }

    protected $allowedRoles = ['office', 'hod', 'faculty', 'staff'];

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

        try {
            $user = new User($data);
            $user->deleted_at = date('Y-m-d H:i:s', time());
            $user->save();
            $user_id = $user->id;
            UserRole::create([
                'user_id' => $user_id,
                'role' => $data['role']
            ]);
        } catch (\Exception $e) {
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
