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

    public function edit(Student $student_by_roll_number) {
        $student = $student_by_roll_number->load('info');
        $this->authorize('update', [StudentInfo::class, $student, $student->info]);

        $semesters = Semester::all();
        $student->load(['department', 'batch.course']);

        return view('student.info.edit', [
            'info' => $student->info,
            'student' => $student,
            'semesters' => $semesters,
            'selectMenu' => CustomHelper::FORM_SELECTMENU,
            'canEdit' => true
        ]);
    }

    public function update(Request $request, Student $student_by_roll_number) {
        $student = $student_by_roll_number->load('info');
        $this->authorize('update', [StudentInfo::class, $student, $student->info]);

        $rules = new \App\Http\Requests\StoreStudentInfo;
        $updateRules = array_merge($rules->rules(), $rules->updateRules($student));
        dd($updateRules);
        $data = $request->validate($updateRules);
    }

    public function test() {
        return 'Test';
    }
}
