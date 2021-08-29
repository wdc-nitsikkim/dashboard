<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\CustomHelper;
use App\Models\Student;
use App\Models\Semester;
use App\Models\ResultType;
use App\Models\DepartmentSubjectsTaught as Sub;

class ResultController extends Controller {
    public function show(Student $student_by_roll_number, Semester $semester) {
        $student = $student_by_roll_number;
        $this->authorize('view', [StudentInfo::class, $student]);

        $student->load(['batch.course', 'department', 'result']);
        $resultTypes = ResultType::all();
        $semesters = Semester::all();
        $subjects = Sub::with('subject')
            ->where('department_id', $student->department->id)
            ->whereHas('subject', function ($query) use ($student, $semester) {
                $query->where([
                    'course_id' => $student->batch->course->id,
                    'semester_id' => $semester->id
                ]);
            })->get();

        return view('student.result', [
            'student' => $student,
            'subjects' => $subjects,
            'semesters' => $semesters,
            'resultTypes' => $resultTypes,
            'currentSemester' => $semester
        ]);
    }
}
