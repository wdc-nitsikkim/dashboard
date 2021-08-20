<?php

namespace App\Http\Controllers\Admin;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Department;

class StudentController extends Controller {
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

    public function handleRedirect() {
        if (!session()->has($this->sessionKeys['selectedDepartment'])) {
            return redirect()->route('admin.department.select', [
                'redirect' => 'admin.students.handleRedirect'
            ]);
        }

        if (!session()->has($this->sessionKeys['selectedBatch'])) {
            return redirect()->route('admin.batch.select', [
                'redirect' => 'admin.students.handleRedirect'
            ]);
        }

        return redirect()->route('admin.students.show', [
            'dept' => session($this->sessionKeys['selectedDepartment']),
            'batch' => session($this->sessionKeys['selectedBatch'])
        ]);
    }

    public function show(Department $dept, Batch $batch) {
        $this->authorize('view', Student::class);

        $students = $batch->students()->where('department_id', $dept->id)
            ->withTrashed()->paginate($this->paginate);

        return view('admin.students.show', [
            'batch' => $batch,
            'department' => $dept,
            'students' => $students->toArray(),
            'pagination' => $students->links('vendor.pagination.default')
        ]);
    }

    public function searchForm() {
        $this->authorize('view', Student::class);

        $departments = Department::select('id', 'name')->get();
        $batches = Batch::select('id', 'type', 'name')->get();
        $batches->transform(function ($batch) {
            return [
                'id' => $batch->id,
                'name' => ($batch->type == 'b' ? 'B.Tech' : 'M.Tech') . ', ' . $batch->name
            ];
        });

        return view('admin.students.search', [
            'departments' => $departments,
            'batches' => $batches
        ]);
    }

    public function search(Request $request) {
        $this->authorize('view', Student::class);

        $data = $request->validate([
            'name' => 'nullable',
            'department_id' => 'nullable',
            'batch_id' => 'nullable',
            'trash_options' => 'nullable | in:only_trash,only_active',
            'created_at' => 'nullable | date_format:Y-m-d',
            'created_at_compare' => 'nullable | in:after,before'
        ]);

        $search = Student::withTrashed();
        if (!is_null($data['trash_options'] ?? null)) {
            if ($data['trash_options'] == 'only_trash')
                $search = Student::onlyTrashed();
            else if ($data['trash_options'] == 'only_active')
                $search = Student::whereNull('deleted_at');
        }

        $map = [
            'name' => 'like',
            'department_id' => 'strict',
            'batch_id' => 'strict',
            'created_at' => 'date'
        ];

        $search->with(['department:id,code,name', 'batch:id,code,type,start_year']);
        $search = CustomHelper::getSearchQuery($search, $data, $map)->paginate($this->paginate);
        $search->appends($data);

        return view('admin.students.searchResults')->with([
            'students' => $search->toArray(),
            'pagination' => $search->links('vendor.pagination.default')
        ]);
    }

    public function add(Department $dept, Batch $batch) {
        $this->authorize('create', [Student::class, $dept]);

        return view('admin.students.add', [
            'batch' => $batch,
            'department' => $dept
        ]);
    }

    public function bulkInsert(Department $dept, Batch $batch) {
        $this->authorize('create', [Student::class, $dept]);

        return view('admin.students.bulkInsert', [
            'batch' => $batch,
            'department' => $dept
        ]);
    }

    public function bulkInsertSave(Request $request, Department $dept, Batch $batch) {
        /* use for AJAX calls only, this function returns JSON responses */

        $this->authorize('create', [Student::class, $dept]);

        $data = $request->validate([
            'name' => 'required | array | min:1',
            'name.*' => 'required | min:3',
            'roll_number' => 'required | array | min:1',
            'roll_number.*' => ['required', 'distinct', Rule::unique('students', 'roll_number')],
            'email' => 'required | array | min:1',
            'email.*' => ['required', 'distinct', 'email', Rule::unique('students', 'email')]
        ]);

        $countName = count($data['name']);
        $countRoll = count($data['roll_number']);
        $countEmail = count($data['email']);
        if (($countName != $countRoll) || ($countRoll != $countEmail)) {
            return abort(400);
        }

        try {
            for ($i = 0; $i < $countName; $i++) {
                $student = Student::create([
                    'name' => $data['name'][$i],
                    'roll_number' => $data['roll_number'][$i],
                    'email' => $data['email'][$i],
                    'department_id' => $dept->id,
                    'batch_id' => $batch->id
                ]);
            }
        } catch (\Exception $e) {
            Log::debug('Failed to bulk insert students!', [Auth::user(), $dept, $batch]);
            return abort(500);
        }

        session()->flash('status', 'success');
        session()->flash('message', $countName . ' students added!');

        return response()->json([
            'redirect' => route('admin.students.show', [
                'dept' => $dept->code,
                'batch' => $batch->code
            ])
        ], 201);
    }

    public function saveNew(Request $request, Department $dept, Batch $batch) {
        $this->authorize('create', [Student::class, $dept]);

        $data = $request->validate([
            'name' => 'required | string | min:3',
            'roll_number' => ['required', 'alpha_num', Rule::unique('students', 'roll_number')],
            'email' => ['required', 'email', Rule::unique('students', 'email')]
        ]);

        try {
            $student = new Student($data);
            $student->department_id = $dept->id;
            $student->batch_id = $batch->id;
            $student->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add student!'
            ])->withInput();
        }

        return redirect()->route('admin.students.show', [
            'dept' => $dept,
            'batch' => $batch
        ])->with([
            'status' => 'success',
            'message' => 'Student added!'
        ]);
    }

    public function edit(Department $dept, Batch $batch, Student $student) {
        $this->authorize('update', [Student::class, $student]);

        $departmentList = Department::select('id', 'name')->get();
        return view('admin.students.edit', [
            'batch' => $batch,
            'department' => $dept,
            'student' => $student,
            'departmentList' => $departmentList
        ]);
    }

    public function update(Request $request, Department $dept, Batch $batch,
        Student $student) {

        $this->authorize('update', [Student::class, $student]);

        $data = $request->validate([
            'name' => 'required | string | min:3',
            'roll_number' => ['required', 'alpha_num',
                Rule::unique('students', 'roll_number')->ignore($student->id)
            ],
            'email' => ['required', 'email',
                Rule::unique('students', 'email')->ignore($student->id)
            ],
            'department' => 'required | numeric'
        ]);

        try {
            $student->name = $request->input('name');
            $student->roll_number = $request->input('roll_number');
            $student->email = $request->input('email');

            if (Auth::user()->can('updateDepartment', Student::class)) {
                $student->department_id = $request->input('department');
            }

            $student->save();
            Log::info('Student updated.', [Auth::user(), $student]);
        } catch (\Exception $e) {
            Log::debug('Student updation failed!', [Auth::user(), $student]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Student updated'
        ]);
    }

    public function softDelete(Department $dept, Batch $batch, Student $student) {
        $this->authorize('update', [Student::class, $student]);

        try {
            $student->delete();
            Log::notice('Student soft deleted!', [Auth::user(), $student]);
        } catch (\Exception $e) {
            Log::debug('Student updated.', [Auth::user(), $e->getMessage(), $student]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to delete!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Moved to trash!'
        ]);
    }

    public function restore(Department $dept, Batch $batch, $student_id) {
        $student = Student::onlyTrashed()->findOrFail($student_id);
        $this->authorize('update', [Student::class, $student]);

        try {
            $student->restore();
            Log::info('Student restored.', [Auth::user(), $student]);
        } catch (\Exception $e) {
            Log::debug('Student restoration failed!', [Auth::user(), $e->getMessage(), $student]);
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

    public function delete(Department $dept, Batch $batch, $student_id) {
        $student = Student::onlyTrashed()->findOrFail($student_id);
        $this->authorize('delete', [Student::class, $student]);

        try {
            $student->forceDelete();
            Log::alert('Student deleted!.', [Auth::user(), $student]);
        } catch (\Exception $e) {
            Log::debug('Student deletion failed!', [Auth::user(), $e->getMessage(), $student]);
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
