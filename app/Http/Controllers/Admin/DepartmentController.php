<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Profile;
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
            Log::notice('Department updated.', [Auth::user(), $data]);
        } catch (\Exception $e) {
            Log::debug('Department updation failed!', [Auth::user(), $e->getMessage(), $data]);
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
            Log::notice('Department soft deleted.', [Auth::user(), $department]);
        } catch (\Exception $e) {
            Log::debug('Department soft deletion failed!', [Auth::user(), $e->getMessage(), $department]);
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
            Log::info('Department restored.', [Auth::user(), $department]);
        } catch (\Exception $e) {
            Log::debug('Department restoration failed!', [Auth::user(), $e->getMessage(), $department]);
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
            Log::alert('Department deleted!', [Auth::user(), $department]);
        } catch (\Exception $e) {
            Log::debug('Department deletion failed!', [Auth::user(), $e->getMessage(), $department]);
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

    public function orderPeople(Department $dept) {
        $this->authorize('view', Department::class);

        $profiles = Profile::with('hod')->where('department_id', $dept->id)
            ->orderBy('order')->get();

        return view('admin.department.orderPeople', [
            'profiles' => $profiles,
            'department' => $dept
        ]);
    }

    public function saveOrder(Request $request, Department $dept) {
        /* function only meant for AJAX calls */

        $this->authorize('orderPeople', [Department::class, $dept]);

        $data = $request->validate([
            'order' => 'required | json'
        ]);

        try {
            $order = collect(json_decode($data['order']));
        } catch (\Exception $e) {
            Log::debug('Failed to save order!', [Auth::user(), $e->getMessage(), $data, $dept]);
            return abort(400);
        }
        $profiles = Profile::select('id', 'department_id', 'order')
            ->where('department_id', $dept->id)
            ->whereIn('id', $order->pluck('profile_id')
            ->toArray())->get();

        try {
            foreach ($profiles as $profile) {
                $profile->order = $order->where('profile_id', $profile->id)
                    ->first()->order;
                $profile->save();
            }
        } catch (\Exception $e) {
            Log::debug('Failed to save order!', [Auth::user(), $e->getMessage(), $data, $dept]);
            return abort(500);
        }

        session()->flash('status', 'success');
        session()->flash('message', 'Order updated!');

        return response()->json([
            'reload' => true
        ], 200);
    }

    public function test() {
        return 'Test';
    }
}
