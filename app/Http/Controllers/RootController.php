<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CustomHelper;

class RootController extends Controller {
    public function clearSession() {
        $session_keys = CustomHelper::get_session_constants();
        foreach ($session_keys as $val) {
            session()->forget($val);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Session cleared'
        ]);
    }
}
