<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;
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

    public function handleRedirect(Department $dept) {
        if (session()->has($this->sessionKeys['selectedBatch'])) {
            return redirect()->route('department.students.show', [
                'dept' => $dept,
                'batch' => session($this->sessionKeys['selectedBatch'])
            ]);
        }
        return redirect()->route('batch.select', ['redirect' => '']);
    }

    public function selectBatch(Department $dept) {
        $this->authorize('view', [Student::class, $dept]);

        $btechBatches = Batch::where('type', 'b')->orderByDesc('id')->get();
        $mtechBatches = Batch::where('type', 'm')->orderByDesc('id')->get();

        return view('department.students.selectBatch', [
            'department' => $dept,
            'btechBatches' => $btechBatches,
            'mtechBatches' => $mtechBatches
        ]);
    }

    public function show(Department $dept, Batch $batch) {
        $this->authorize('view', [Student::class, $dept]);

        $students = $batch->students()->where(
            'department_id', $dept->id
        )->withTrashed()->paginate($this->paginate);

        return view('department.students.show', [
            'batch' => $batch,
            'department' => $dept,
            'students' => $students->toArray(),
            'pagination' => $students->links('vendor.pagination.default')
        ]);
    }

    public function add(Department $dept, Batch $batch) {
        $this->authorize('create', [Student::class, $dept]);

        return view('department.students.add', [
            'batch' => $batch,
            'department' => $dept
        ]);
    }

    public function saveNew(Request $request, Department $dept, Batch $batch) {
        $this->authorize('create', [Student::class, $dept]);

        $request->validate([
            'name' => 'required | string | min:3',
            'roll_number' => 'required',  /* TODO: add regex validation */
            'email' => 'required | email'
        ]);

        try {
            $student = new Student($request->all());
            $student->department_id = $dept->id;
            $student->batch_id = $batch->id;
            $student->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add student!'
            ])->withInput();
        }

        return redirect()->route('department.students.show', [
            'dept' => $dept,
            'batch' => $batch
        ])->with([
            'status' => 'success',
            'message' => 'Student added!'
        ]);
    }

    public function edit(Department $dept, Batch $batch, Student $student) {
        $this->authorize('update', [Student::class, $dept]);

        $departmentList = Department::all();
        return view('department.students.edit', [
            'batch' => $batch,
            'department' => $dept,
            'student' => $student,
            'departmentList' => $departmentList
        ]);
    }

    public function update(Request $request, Department $dept, Batch $batch,
        Student $student) {

        $this->authorize('update', [Student::class, $dept]);

        $validator = $request->validate([
            'name' => 'required | string | min:3',
            'roll_number' => 'required',  /* TODO: add regex validation */
            'email' => 'required | email',
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
        } catch (\Exception $e) {
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
        $this->authorize('update', [Student::class, $dept]);

        try {
            $student->delete();
        } catch (\Exception $e) {
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
        $this->authorize('update', [Student::class, $dept]);

        $student = Student::withTrashed()->findOrFail($student_id);
        try {
            $student->restore();
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

    public function delete(Department $dept, Batch $batch, $student_id) {
        $this->authorize('delete', [Student::class, $dept]);

        $student = Student::onlyTrashed()->findOrFail($student_id);
        try {
            $student->forceDelete();
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
