<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CustomHelper;

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
}
