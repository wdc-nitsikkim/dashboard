<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
            return redirect()->route('admin.department.home',
                session($this->sessionKeys['selectedDepartment']));
        }
        return redirect()->route('admin.department.select');
    }

    public function select() {
        $departments = Department::all();
        $preferred = $departments->whereIn('id',
            Auth::user()->allowedDepartments->pluck('department_id')->toArray());

        return view('admin.department.select', [
            'preferred' => $preferred,
            'departments' => $departments
        ]);
    }

    public function saveInSession(Request $request, Department $dept) {
        session([$this->sessionKeys['selectedDepartment'] => $dept]);
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $dept)
            : redirect()->route('admin.department.home', $dept);
    }

    public function home(Department $dept) {
        /* temp. -> additional (policy + model) required */
        /* new page model required */
        $advancedAccess = false;

        return view('admin.department.home', [
            'department' => $dept,
            'advancedAccess' => $advancedAccess
        ]);
    }

    public function show(Request $request) {
        $this->authorize('view', Department::class);

        $departments = Department::withTrashed()->paginate($this->paginate);

        return view('admin.department.show', [
            'departments' => $departments->toArray(),
            'pagination' => $departments->links('vendor.pagination.default')
        ]);
    }

    public function add() {
        $this->authorize('create', Department::class);

        return view('admin.department.add');
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Department::class);

        $data = $request->validate([
            'code' => ['required', 'min:2', 'max:4',
                Rule::unique('departments', 'code')
            ],
            'name' => 'required | min:5 | max:50'
        ]);

        try {
            Department::create($data);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add department!'
            ])->withInput();
        }

        return redirect()->route('admin.department.show')->with([
            'status' => 'success',
            'message' => 'Department added!'
        ]);
    }

    public function edit($id) {
        $this->authorize('update', Department::class);

        $department = Department::findOrFail($id);

        return view('admin.department.edit', [
            'department' => $department
        ]);
    }

    public function update(Request $request, $id) {
        $this->authorize('update', Department::class);

        $department = Department::findOrFail($id);

        $data = $request->validate([
            'code' => ['required', 'min:2', 'max:4',
                Rule::unique('departments', 'code')->ignore($department->id)
            ],
            'name' => 'required | max:50'
        ]);

        try {
            $department->update($data);
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
