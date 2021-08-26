<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\StudentInfo;

class MainController extends Controller {
    public function home(Student $student_by_roll_number = null) {
        $student = $student_by_roll_number
            ?? Student::withTrashed()->where('email', Auth::user()->email)->first();

        $this->authorize('view', [StudentInfo::class, $student]);

        return $student == null
            ? view('student.index')
            : view('student.home', [
                'student' => $student->load(['department', 'batch.course', 'info'])
            ]);
    }

    public function test() {
        return 'Test';
    }
}
