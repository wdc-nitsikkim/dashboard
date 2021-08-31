<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Department;
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
            $query->whereHas('subject', function ($query) use ($course) {
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

    public function test() {
        return 'Test';
    }
}
