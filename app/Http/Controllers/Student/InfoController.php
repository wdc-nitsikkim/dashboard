<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Http\Requests\StoreStudentInfo;
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

    public function saveNew(StoreStudentInfo $request, Student $student_by_roll_number) {
        $student = $student_by_roll_number;
        $this->authorize('create', [StudentInfo::class, $student]);

        try {
            $info = new StudentInfo($request->all());
            $info->student_id = $student->id;
            $info->save();
            Log::info('Student info added.', [Auth::user()]);
        } catch (\Exception $e) {
            Log::debug('Failtd to add student info!', [Auth::user(), $e->getMessage(), $student, $request->all()]);
            return back()->with([
                'status' => 'fail',
                'message' => 'An unknown error occurred!'
            ])->withInput();
        }
        return redirect()->route('student.home', $student->roll_number)->with([
            'status' => 'success',
            'message' => 'Information added'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
