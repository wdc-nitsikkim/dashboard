<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CustomHelper;

class RootController extends Controller
{
    public function clearSession()
    {
        $sessionKeys = CustomHelper::getSessionConstants();
        foreach ($sessionKeys as $val) {
            session()->forget($val);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Session cleared'
        ]);
    }

    public function userRedirect()
    {
        if (CustomHelper::isStudentOnly()) {
            return redirect()->route('student.index');
        }
        return redirect()->route('admin.home');
    }

    public function test()
    {
        return 'Test';
    }
}
