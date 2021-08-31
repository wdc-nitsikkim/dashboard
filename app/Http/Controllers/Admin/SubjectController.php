<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Department;
use App\Models\SubjectType;
use App\Models\RegisteredSubject;

class SubjectController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 20;

    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    public function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function handleRedirect() {
        $redirectRoute = route('admin.subjects.handleRedirect');
        $response = CustomHelper::sessionCheckAndRedirect($redirectRoute,
            ['selectedDepartment', 'selectedBatch']);

        if (is_bool($response)) {
            return redirect()->route('admin.subjects.show', [
                'dept' => session($this->sessionKeys['selectedDepartment']),
                'batch' => session($this->sessionKeys['selectedBatch']),
            ]);
        }
        return $response;
    }

    public function show(Department $dept, Batch $batch, Semester $semester = null, Course $course = null) {
        $courses        = Course::all();
        $semesters      = Semester::all();

        $route = 'admin.subjects.show';
        $semester = $semester ?? $semesters->where('id', CustomHelper::getSemesterFromYear($batch->start_year))->first();

        $subjects = RegisteredSubject::where([
            'batch_id' => $batch->id,
            'semester_id' => $semester->id,
            'department_id' => $dept->id
        ])->when($course ?? false, function ($query) use ($course) {
            $query->whereHas('batch', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            });
        })->orderByDesc('credit')->paginate($this->paginate);

        return view('admin.subjects.show', [
            'subjects' => $subjects,
            'baseRoute' => $route,
            'courses' => $courses,
            'semesters' => $semesters,
            'currentBatch' => $batch,
            'currentCourse' => $course,
            'currentSemester' => $semester,
            'currentDepartment' => $dept,
            'pagination' => $subjects->links('vendor.pagination.default')
        ]);
    }

    public function select() {
        $dept = false;
        $batch = false;
        if (session()->has($this->sessionKeys['selectedDepartment'])) {
            $dept = session($this->sessionKeys['selectedDepartment']);
        }
        if (session()->has($this->sessionKeys['selectedBatch'])) {
            $batch = session($this->sessionKeys['selectedBatch']);
        }

        $preferred = Auth::user()->allowedSubjects()->with('registeredSubject')->get();
        $subjects = RegisteredSubject::when($dept, function ($query) use ($dept) {
            $query->where('department_id', $dept->id);
        })->when($batch, function ($query) use ($batch) {
            $query->where('batch_id', $batch->id);
        })->get();

        return view('admin.subjects.select', [
            'preferred' => $preferred,
            'subjects' => $subjects
        ]);
    }

    public function saveInSession(Request $request, RegisteredSubject $subject) {
        session([$this->sessionKeys['selectedSubject'] => $subject]);
        $redirectRoute = $request->input('redirect');

        return $redirectRoute ? redirect($redirectRoute)
            : redirect()->route('root.home');
    }

    public function add(Department $dept) {
        $this->authorize('create', [Subject::class, $dept]);

        $subjectTypes = SubjectType::all();

        return view('admin.subjects.add', [
            'department' => $dept,
            'subjectTypes' => $subjectTypes
        ]);
    }

    public function saveNew(Request $request, Department $dept) {
        /* use for AJAX calls only, this function returns JSON responses */

        $this->authorize('create', [Subject::class, $dept]);

        $data = $request->validate([
            'name' => 'required | array | min:1',
            'name.*' => 'required | min:3',
            'subject_type_id' => 'required | array | min:1',
            'subject_type_id.*' => ['required', Rule::exists('subject_types', 'id')],
            'code' => 'required | array | min:1',
            'code.*' => 'required | string | size:2'
        ]);

        $countName = count($data['name']);
        $countType = count($data['subject_type_id']);
        $countCode = count($data['code']);
        if (($countName != $countType) || ($countType != $countCode)) {
            return abort(400);
        }

        try {
            $subjectsArr = [];
            $timestamp = now();

            for ($i = 0; $i < $countName; $i++) {
                $student = [
                    'name' => $data['name'][$i],
                    'subject_type_id' => $data['subject_type_id'][$i],
                    'code' => $data['code'][$i],
                    'department_id' => $dept->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp
                ];
                $subjectsArr[] = $student;
            }

            Subject::insert($subjectsArr);
            Log::notice('Subjects bulk inserted!', [Auth::user(), $dept]);
        } catch (\Exception $e) {
            Log::debug('Failed to bulk insert subjects!', [Auth::user(), $dept]);
            return abort(500);
        }

        session()->flash('status', 'success');
        session()->flash('message', $countName . ' subjects added!');

        return response()->json([
            'redirect' => route('admin.department.home', $dept)
        ], 201);
    }

    public function test() {
        return 'Test';
    }
}
