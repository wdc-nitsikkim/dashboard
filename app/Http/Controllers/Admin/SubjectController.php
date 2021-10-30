<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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

class SubjectController extends Controller
{
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

    public function __construct()
    {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function handleRedirect()
    {
        $redirectRoute = route('admin.subjects.handleRedirect');
        $response = CustomHelper::sessionCheckAndRedirect(
            $redirectRoute,
            ['selectedDepartment', 'selectedBatch']
        );

        if (is_bool($response)) {
            return redirect()->route('admin.subjects.show', [
                'dept' => session($this->sessionKeys['selectedDepartment']),
                'batch' => session($this->sessionKeys['selectedBatch']),
            ]);
        }
        return $response;
    }

    public function show(Department $dept, Batch $batch, Semester $semester = null, Course $course = null)
    {
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

    public function select()
    {
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

    public function saveInSession(Request $request, RegisteredSubject $subject)
    {
        session([$this->sessionKeys['selectedSubject'] => $subject]);
        $redirectRoute = $request->input('redirect');

        return $redirectRoute ? redirect($redirectRoute)
            : redirect()->route('root.home');
    }

    public function add(Department $dept)
    {
        $this->authorize('create', [Subject::class, $dept]);

        $subjectTypes = SubjectType::all();

        return view('admin.subjects.add', [
            'department' => $dept,
            'subjectTypes' => $subjectTypes
        ]);
    }

    public function saveNew(Request $request, Department $dept)
    {
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
                $subject = [
                    'name' => $data['name'][$i],
                    'subject_type_id' => $data['subject_type_id'][$i],
                    'code' => $data['code'][$i],
                    'department_id' => $dept->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp
                ];
                $subjectsArr[] = $subject;
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

    public function addReg(Department $dept, Batch $batch = null)
    {
        $this->authorize('create', [Subject::class, $dept]);

        $batches = Batch::limit(10)->orderByDesc('id')->get();
        $departments = Department::all();
        $semesters = Semester::all();
        $batch = $batch ?? (session()->has($this->sessionKeys['selectedBatch'])
                ? session($this->sessionKeys['selectedBatch'])
                : $batches->last());

        return view('admin.subjects.addReg', [
            'batch' => $batch,
            'batches' => $batches,
            'semesters' => $semesters,
            'department' => $dept,
            'departments' => $departments
        ]);
    }

    public function saveNewReg(Request $request, Department $dept, Batch $batch)
    {
        /* use for AJAX calls only, this function returns JSON responses */

        $this->authorize('create', [Subject::class, $dept]);

        $data = $request->validate([
            'semester_id' => 'required | array | min:1',
            'semester_id.*' => ['required', Rule::exists('semesters', 'id')],
            'credit' => 'required | array | min:1',
            'credit.*' => ['required', 'numeric', 'between:1,6'],
            'subject_id' => 'required | array | min:1',
            'subject_id.*' => ['required', Rule::exists('subjects', 'id')]
        ]);

        $countSems = count($data['semester_id']);
        $countCredit = count($data['credit']);
        $countSubIds = count($data['subject_id']);
        $skipped = 0;
        if (($countSems != $countCredit) || ($countCredit != $countSubIds)) {
            return abort(400);
        }

        $dbSubjects = RegisteredSubject::where([
            'department_id' => $dept->id,
            'batch_id' => $batch->id
        ])->get();

        DB::beginTransaction();
        try {
            $subjectsArr = [];
            $timestamp = now();

            for ($i = 0; $i < $countSems; $i++) {
                /* custom composite unique key validation */
                $tmp = $dbSubjects->where('semester_id', $data['semester_id'][$i])
                    ->where('subject_id', $data['subject_id'][$i])->first();

                if ($tmp != null) {
                    $skipped++;
                    continue;
                }

                $student = [
                    'department_id' => $dept->id,
                    'batch_id' => $batch->id,
                    'semester_id' => $data['semester_id'][$i],
                    'credit' => $data['credit'][$i],
                    'subject_id' => $data['subject_id'][$i],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp
                ];
                $subjectsArr[] = $student;
            }

            /* Insert after performing final unique check */
            RegisteredSubject::insert(collect($subjectsArr)->unique()->toArray());
            DB::commit();
            Log::notice('Registered subjects bulk inserted!', [Auth::user(), $dept, $batch]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug('Failed to bulk insert registered subjects!', [Auth::user(), $e->getMessage(), $dept, $batch]);
            return abort(500);
        }

        session()->flash('status', 'success');
        session()->flash('message', ($countSems - $skipped) . ' subject(s) added & '
            . $skipped . ' subject(s) skipped!');

        return response()->json([
            'redirect' => route('admin.department.home', $dept)
        ], 201);
    }

    public function test()
    {
        return 'Test';
    }
}
