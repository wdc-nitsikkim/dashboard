<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\Student;
use App\Models\StudentInfo;

class MainController extends Controller {
    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    public function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function home(Student $student_by_roll_number = null) {
        $student = $student_by_roll_number
            ?? Student::withTrashed()->where('email', Auth::user()->email)->first();

        if ($student == null) {
            return view('student.index');
        }

        /* storing student in session variable for easier url generation */
        session([ $this->sessionKeys['userStudent'] => $student ]);

        $user = Auth::user();
        $info = StudentInfo::withTrashed()->select('student_id', 'deleted_at')->find($student->id);
        $canCreate = ($info != null) ? false : $user->can('create', [StudentInfo::class, $student]);
        $canUpdate = ($info == null) ? false : $user->can('update', [StudentInfo::class, $student, $info]);
        $canView = ($info == null) ? false : $user->can('view', [StudentInfo::class, $student, $info]);

        return view('student.home', [
            'student' => $student->load(['department', 'batch.course']),
            'canView' => $canView,
            'canCreate' => $canCreate,
            'canUpdate' => $canUpdate
        ]);
    }

    public function test() {
        return 'Test';
    }
}
