<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Department;
use App\Models\DepartmentSubjectsTaught as Sub;

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

    public function show(Request $request) {
        $courses        = Course::all();
        $semesters      = Semester::all();
        $departments    = Department::select('id', 'code', 'name')->get();

        $dept           = $request->dept ? $departments->where('code', $request->dept)->first() : null;
        $course         = $request->course ? $courses->where('code', $request->course)->first() : null;
        $semester       = $request->semester ? $semesters->where('id', $request->semester)->first() : null;

        $route = 'admin.subjects.show';
        $subjects = Subject::when($dept, function ($query) use ($dept) {
                $query->where('department_id', $dept->id);
            })->when($semester ?? false, function ($query) use ($semester) {
                $query->where('semester_id', $semester->id);
            })->when($course ?? false, function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->paginate($this->paginate);

        $subjects->setPath(route($route, [
            'dept' => $request->dept,
            'course' => $request->course,
            'semester' => $request->semester
        ]));

        return view('admin.subjects.show', [
            'subjects' => $subjects->toArray(),
            'baseRoute' => $route,
            'courses' => $courses,
            'semesters' => $semesters,
            'departments' => $departments,
            'currentCourse' => $course,
            'currentSemester' => $semester,
            'currentDepartment' => $dept,
            'secondaryText' => 'Subjects belonging to department',
            'pagination' => $subjects->links('vendor.pagination.default')
        ]);
    }

    public function showSyllabusWise(Request $request) {
        $courses        = Course::all();
        $semesters      = Semester::all();
        $departments    = Department::select('id', 'code', 'name')->get();

        $dept           = $request->dept ? $departments->where('code', $request->dept)->first() : null;
        $course         = $request->course ? $courses->where('code', $request->course)->first() : null;
        $semester       = $request->semester ? $semesters->where('id', $request->semester)->first() : null;

        $route = 'admin.subjects.showSyllabusWise';
        $subjects = Sub::with('subject')->when($dept, function ($query) use ($dept) {
                $query->where('department_id', $dept->id);
            })->when($semester ?? false, function ($query) use ($semester) {
                $query->whereHas('subject', function ($query) use ($semester) {
                    $query->where('semester_id', $semester->id);
                });
            })->when($course ?? false, function ($query) use ($course) {
                $query->whereHas('subject', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                });
            })->paginate($this->paginate);

        return view('admin.subjects.show', [
            'subjects' => $subjects->toArray(),
            'nestedSubject' => true,
            'baseRoute' => $route,
            'courses' => $courses,
            'semesters' => $semesters,
            'departments' => $departments,
            'currentCourse' => $course,
            'currentSemester' => $semester,
            'currentDepartment' => $dept,
            'secondaryText' => 'Subjects registered in syllabus',
            'pagination' => $subjects->links('vendor.pagination.default')
        ]);
    }

    public function select() {
        $preferred = Auth::user()->allowedSubjects()->with('subject:id,code,name')->get();
        $subjects = Subject::select('id', 'code', 'name', 'semester_id')->get();

        return view('admin.subjects.select', [
            'preferred' => $preferred,
            'subjects' => $subjects
        ]);
    }

    public function saveInSession(Request $request, Subject $subject) {
        session([$this->sessionKeys['selectedSubject'] => $subject]);
        $redirectRoute = $request->input('redirect');

        return $redirectRoute ? redirect($redirectRoute)
            : redirect()->route('admin.subjects.show');
    }

    public function test() {
        return 'Test';
    }
}
