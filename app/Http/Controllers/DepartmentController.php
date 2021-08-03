<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Department;

class DepartmentController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 10;

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
        $advancedAccess = false;

        return view('department.home', [
            'department' => $dept,
            'advancedAccess' => $advancedAccess
        ]);
    }

    public function show(Request $request) {
        $this->authorize('view', Department::class);

        $departments = Department::withTrashed()->paginate($this->paginate);

        return view('department.show', [
            'departments' => $departments->toArray(),
            'pagination' => $departments->links('vendor.pagination.default')
        ]);
    }

    public function add() {
        $this->authorize('create', Department::class);

        return view('department.add');
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Department::class);

        $request->validate([
            'code' => 'required | min:2 | max:4',
            'name' => 'required | min:5 | max:50'
        ]);

        try {
            Department::create($request->all());
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add department!'
            ])->withInput();
        }

        return redirect()->route('department.show')->with([
            'status' => 'success',
            'message' => 'Department added!'
        ]);
    }

    public function edit($id) {
        $this->authorize('update', Department::class);

        $department = Department::findOrFail($id);

        return view('department.edit', [
            'department' => $department
        ]);
    }

    public function update(Request $request, $id) {
        $this->authorize('update', Department::class);

        $department = Department::findOrFail($id);

        $request->validate([
            'code' => 'required | min:2 | max:4',
            'name' => 'required | max:50'
        ]);

        try {
            $department->update($request->all());
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update department!'
            ])->withInput();
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Department updated!'
        ]);
    }

    public function softDelete($id) {
        $this->authorize('update', Department::class);

        $department = Department::findOrFail($id);
        try {
            $department->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to delete!'
            ]);
        }

        /**
         * removing selected department from session to prevent 404 page
         * from showing up (if removed department was the selected department)
         */
        session()->forget($this->sessionKeys['selectedDepartment']);

        return back()->with([
            'status' => 'success',
            'message' => 'Moved to trash!'
        ]);
    }

    public function restore($id) {
        $this->authorize('update', Department::class);

        $department = Department::onlyTrashed()->findOrFail($id);
        try {
            $department->restore();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to restore!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Restored successfully!'
        ]);
    }

    public function delete($id) {
        $this->authorize('delete', Department::class);

        $department = Department::onlyTrashed()->findOrFail($id);
        try {
            $department->forceDelete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to delete!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Deleted permanently!'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
