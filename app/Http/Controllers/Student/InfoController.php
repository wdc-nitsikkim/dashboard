<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\Student;
use App\Models\Semester;
use App\Models\StudentInfo;
use App\Http\Requests\StoreStudentInfo;

class InfoController extends Controller {
    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    public function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
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

        $privatePaths = [
            $student->info->image,
            $student->info->signature,
            $student->info->resume
        ];
        CustomHelper::storePrivatePaths($privatePaths);

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

        // dd($request->all());
        $rules = new \App\Http\Requests\StoreStudentInfo;
        $updateRules = array_merge($rules->rules(), $rules->updateRules($student));
        $data = $request->validate($updateRules);

        dd($data);
    }

    public function test() {
        return 'Test';
    }
}
