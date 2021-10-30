<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CustomHelper;
use App\Models\Student;
use App\Models\Semester;
use App\Models\ResultType;
use App\Models\StudentInfo;
use App\Models\RegisteredSubject;

class ResultController extends Controller
{
    public function show(Student $student_by_roll_number, Semester $semester)
    {
        $student = $student_by_roll_number;
        $this->authorize('view_result', [StudentInfo::class, $student]);

        $student->load(['batch.course', 'department', 'result']);
        $resultTypes = ResultType::all();
        $semesters = Semester::all();
        $subjects = RegisteredSubject::where([
                'batch_id' => $student->batch->id,
                'semester_id' => $semester->id,
                'department_id' => $student->department->id,
            ])->get();

        return view('student.result', [
            'student' => $student,
            'subjects' => $subjects,
            'semesters' => $semesters,
            'resultTypes' => $resultTypes,
            'currentSemester' => $semester
        ]);
    }
}
