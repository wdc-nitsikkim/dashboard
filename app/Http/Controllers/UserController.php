<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\User;

class UserController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function show() {

    }

    public function profile($id) {
        // $this->authorize();

        $user = User::with([
            'allowedDepartments.department:id,code,name',
            'roles.permissions',
            'profileLink'
        ])->withTrashed()->findOrFail($id);

        return view('user.profile', [
            'user' => $user
        ]);
    }

    public function test() {
        return 'Test';
    }
}
