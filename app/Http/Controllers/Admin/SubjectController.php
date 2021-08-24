<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Department;

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

    function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function show(Request $request) {
        $departments = Department::select('id', 'code', 'name')->get();
        $semesters = Semester::all();

        $dept = $request->dept ? $departments->where('code', $request->dept)->first() : null;
        $semester = $request->semester ? $semesters->where('id', $request->semester)->first() : null;

        $subjects = Subject::when($dept, function ($query) use ($dept) {
            $query->where('department_id', $dept->id);
        })->when($semester ?? false, function ($query) use ($semester) {
            $query->where('semester_id', $semester->id);
        });

        $subjects = $subjects->paginate($this->paginate);
        $subjects->setPath(route('admin.subjects.show', [
            'dept' => $request->dept,
            'semester' => $request->semester
        ]));

        return view('admin.subjects.show', [
            'subjects' => $subjects->toArray(),
            'semesters' => $semesters,
            'departments' => $departments,
            'currentSemester' => $semester,
            'currentDepartment' => $dept,
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
