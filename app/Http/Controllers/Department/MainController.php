<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Department;

class MainController extends Controller {
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
        $preferred = Department::whereIn('id',
            Auth::user()->allowedDepartments->pluck('department_id')->toArray())->get();

        $departments = Department::all();
        return view('department.select', [
            'preferred' => $preferred,
            'departments' => $departments
        ]);
    }

    public function saveInSession($dept) {
        session([$this->session_keys['selectedDepartment'] => $dept]);
        return redirect()->route('department.home', $dept);
    }

    public function home($dept) {
        /* temp. -> additional (policy + model) required */
        /* new page model required */
        $advanced_access = false;

        return view('department.home', [
            'department' => $dept,
            'advanced_access' => $advanced_access
        ]);
    }

    public function test() {
        return 'Test';
    }
}
