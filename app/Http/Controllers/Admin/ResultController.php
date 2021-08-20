<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;

class ResultController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 15;

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
        if (! session()->has($this->sessionKeys['selectedDepartment'])) {
            return redirect()->route('admin.department.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        if (! session()->has($this->sessionKeys['selectedBatch'])) {
            return redirect()->route('admin.batch.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        if (! session()->has($this->sessionKeys['selectedSubject'])) {
            return redirect()->route('admin.subjects.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        return redirect()->route('admin.results.show', [
            'dept' => session($this->sessionKeys['selectedDepartment']),
            'batch' => session($this->sessionKeys['selectedBatch']),
            'subject' => session($this->sessionKeys['selectedSubject'])
        ]);
    }

    public function show(Department $dept, Batch $batch, Subject $subject) {
        $this->authorize('view', [Result::class, $subject]);

        $students = Student::withTrashed()->where([
            'batch_id' => $batch->id,
            'department_id' => $dept->id
        ])->with('result')->orderBy('roll_number')->paginate($this->paginate);

        $canUpdate = Auth::user()->can('update', [Result::class, $subject]);

        return view('admin.results.show', [
            'canUpdate' => $canUpdate,
            'batch' => $batch,
            'subject' => $subject,
            'students' => $students,
            'department' => $dept,
            'pagination' => $students->links('vendor.pagination.default')
        ]);
    }

    public function save(Request $request, Department $dept, Batch $batch,
        Subject $subject) {

        /* function primarily for use with AJAX requests */

        $this->authorize('update', [Result::class, $subject]);

        $data = $request->validate([
            'result' => 'array',
            'result.*' => 'nullable | numeric | between:0,100'
        ]);

        /* result array is formed as 'result[student_id] => score' */
        $result = $data['result'];
        $studentIds = array_keys($result);
        $students = Student::select('id')->where([
            'department_id' => $dept->id,
            'batch_id' => $batch->id
        ])->whereIn('id', $studentIds)->get();
        $studentIds = $students->pluck('id')->toArray();  /* filtered students */

        $num = 0;

        try {
            foreach ($studentIds as $studentId) {
                $findResult = [
                    'student_id' => $studentId,
                    'subject_id' => $subject->id
                ];

                if ($result[$studentId] == null) {
                    Result::where($findResult)->delete();
                    continue;
                }

                $num++;
                Result::withTrashed()->updateOrCreate($findResult, [
                    'score' => $result[$studentId]
                ])->restore();
            }
            Log::notice('Result added', [Auth::user(), $dept, $batch, $subject, $data]);
        } catch (\Expception $e) {
            Log::debug('Failed to update result!', [Auth::user(), $dept, $batch, $subject, $data]);
            return abort(503);
        }

        session()->flash('status', 'success');
        session()->flash('message', 'Result of ' . $num . ' student(s) updated!');

        return response()->json([
            'reload' => true
        ], 201);
    }

    public function test() {
        return 'Test';
    }
}
