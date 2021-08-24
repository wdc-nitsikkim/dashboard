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
                'redirect' => 'admin.subjects.handleRedirect'
            ]);
        }
        return redirect()->route('admin.subjects.show', [
            'dept' => session($this->sessionKeys['selectedDepartment'])
        ]);
    }

    public function show(Department $dept, Semester $semester = null) {
        $subjects = Subject::where('department_id', $dept->id);
        $semester ? $subjects->where('semester_id', $semester->id) : false;

        $subjects = $subjects->paginate($this->paginate);
        $subjects->setPath(route('admin.subjects.show', [
            'dept' => $dept->code,
            'semester' => $semester ? $semester->id : null
        ]));

        $departments = Department::select('id', 'code', 'name')->get();
        $semesters = Semester::all();

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
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $subject)
            : redirect()->route('admin.subjects.show');
    }

    public function test() {
        return 'Test';
    }
}
