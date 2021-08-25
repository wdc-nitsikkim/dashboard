<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\User;

class RootController extends Controller {
    public function clearSession() {
        $sessionKeys = CustomHelper::getSessionConstants();
        foreach ($sessionKeys as $val) {
            session()->forget($val);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Session cleared'
        ]);
    }

    public function userRedirect() {
        $user = Auth::user();
        if ($user->hasRole('student') && $user->roles->count() == 1) {
            /* redirect to student account as user has 1 role only, i.e., student */
            return 'Student redirect';
            // return redirect()->route();
        }
        return redirect()->route('admin.home');
    }
}
