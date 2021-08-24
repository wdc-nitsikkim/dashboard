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
use App\Models\Semester;
use App\Models\Department;
use App\Models\ResultType;
use App\Models\DepartmentSubjectsTaught as Sub;

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
        $redirectRoute = route('admin.results.handleRedirect');
        $response = CustomHelper::sessionCheckAndRedirect($redirectRoute,
            ['selectedDepartment', 'selectedBatch', 'selectedSubject']);

        if (is_bool($response)) {
            return redirect()->route('admin.results.show', [
                'dept' => session($this->sessionKeys['selectedDepartment']),
                'batch' => session($this->sessionKeys['selectedBatch']),
                'subject' => session($this->sessionKeys['selectedSubject'])
            ]);
        }
        return $response;
    }

    public function show(Department $dept, Batch $batch, Subject $subject,
        ResultType $result_type = null) {
        $this->authorize('view', [Result::class, $subject]);

        $resultTypes = ResultType::all();
        $result_type = $result_type ?? $resultTypes->first();
        $students = Student::withTrashed()->where([
                'batch_id' => $batch->id,
                'department_id' => $dept->id
            ])->with(['result' => function ($query) use ($result_type, $subject) {
                $query->where([
                    'result_type_id' => $result_type->id,
                    'subject_id' => $subject->id
                ]);
            }])->orderBy('roll_number')->paginate($this->paginate);

        $canUpdate = Auth::user()->can('update', [Result::class, $subject]);

        return view('admin.results.show', [
            'batch' => $batch,
            'subject' => $subject,
            'students' => $students,
            'department' => $dept,
            'canUpdate' => $canUpdate,
            'resultTypes' => $resultTypes,
            'currentResultType' => $result_type,
            'pagination' => $students->links('vendor.pagination.default')
        ]);
    }

    public function semWiseHandleRedirect() {
        $redirectRoute = route('admin.results.semWiseHandleRedirect');
        $response = CustomHelper::sessionCheckAndRedirect($redirectRoute, ['selectedDepartment', 'selectedBatch']);
        if (is_bool($response)) {
            return redirect()->route('admin.results.showSemWise', [
                'dept' => session($this->sessionKeys['selectedDepartment']),
                'batch' => session($this->sessionKeys['selectedBatch'])
            ]);
        }
        return $response;
    }

    public function showSemWise(Department $dept, Batch $batch,
        ResultType $result_type = null, Semester $semester = null) {

        $this->authorize('view_sem_wise', Result::class);

        $resultTypes = ResultType::all();
        $result_type = $result_type ?? $resultTypes->first();
        $semesters = Semester::all();
        $semester = $semester ?? $semesters->first();
        $subjects = Sub::with('subject')->where('department_id', $dept->id)
            ->whereHas('subject', function ($query) use ($batch, $semester) {
                $query->where([
                    'course_id' => $batch->course->id,
                    'semester_id' => $semester->id
                ]);
            })->get();
        $students = Student::withTrashed()->where([
                'batch_id' => $batch->id,
                'department_id' => $dept->id
            ])->with(['result' => function ($query) use ($result_type) {
                $query->where([
                    'result_type_id' => $result_type->id
                ]);
            }])->orderBy('roll_number')->get();

        return view('admin.results.showSemWise', [
            'batch' => $batch,
            'department' => $dept,
            'subjects' => $subjects,
            'students' => $students,
            'semesters' => $semesters,
            'resultTypes' => $resultTypes,
            'currentSemester' => $semester,
            'currentResultType' => $result_type
        ]);
    }

    public function save(Request $request, Department $dept, Batch $batch,
        Subject $subject, ResultType $result_type) {

        /* function primarily for use with AJAX requests */

        $this->authorize('update', [Result::class, $subject]);

        $data = $request->validate([
            'result' => 'array',
            'result.*' => 'nullable | numeric | between:0,' . $result_type->max_marks
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
                    'subject_id' => $subject->id,
                    'result_type_id' => $result_type->id
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
