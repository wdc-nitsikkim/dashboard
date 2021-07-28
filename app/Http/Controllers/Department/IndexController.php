<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Department;

class IndexController extends Controller {
    private $session_keys = null;

    function __construct() {
        $this->session_keys = CustomHelper::get_session_constants();
    }

    public function index() {
        if (session()->has($this->session_keys['selectedDepartment'])) {
            return redirect()->route('department.home', session($this->session_keys['selectedDepartment']));
        }
        return redirect()->route('department.select');
    }

    public function select() {
        $preferred = [];
        if (Auth::user()->role != 'admin') {
            $preferred = Department::whereIn('id',
            Auth::user()->departments->pluck('department_id')->toArray())->get();
        }

        $departments = Department::all();
        return view('department.select', [
            'admin' => Auth::user()->role == 'admin',
            'preferred' => $preferred,
            'departments' => $departments
        ]);
    }

    public function saveInSession($code) {
        session([$this->session_keys['selectedDepartment'] => $code]);
        return redirect()->route('department.home', $code);
    }

    public function home($code) {
        $this->authorize('view', Department::class);

        return view('department.home', [
            'department'=> $code
        ]);
    }

    public function test() {
       return 'Test';
    }
}
