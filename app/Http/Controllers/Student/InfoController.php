<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\Student;
use App\Models\Semester;
use App\Models\StudentInfo;

class InfoController extends Controller {
    public function __construct() {

    }

    public function add(Student $student_by_roll_number) {
        $student = $student_by_roll_number;
        $this->authorize('create', [StudentInfo::class, $student]);

        $student = $student->load(['department', 'batch.course']);
        $semesters = Semester::all();

        return view('student.info.add', [
            'student' => $student,
            'semesters' => $semesters,
            'selectMenu' => CustomHelper::FORM_SELECTMENU
        ]);
    }

    public function saveNew(Request $request, Student $student_by_roll_number) {
        $student = $student_by_roll_number;
        $this->authorize('create', [StudentInfo::class, $student]);
    }
}
