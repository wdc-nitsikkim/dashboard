<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Department;

class MainController extends Controller {
    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function index() {
        if (session()->has($this->sessionKeys['selectedDepartment'])) {
            return redirect()->route('department.home', session($this->sessionKeys['selectedDepartment']));
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

    public function saveInSession(Request $request, Department $dept) {
        session([$this->sessionKeys['selectedDepartment'] => $dept]);
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $dept)
            : redirect()->route('department.home', $dept);
    }

    public function home(Department $dept) {
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
