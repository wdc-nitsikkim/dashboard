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
use App\Traits\StoreFiles;
use App\Http\Requests\StoreStudentInfo;

class InfoController extends Controller {
    use StoreFiles;

    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    public function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function show(Student $student_by_roll_number) {
        $student = $student_by_roll_number;
        $info = StudentInfo::withTrashed()->findOrFail($student->id);

        $this->authorize('view', [StudentInfo::class, $student, $info]);

        $semesters = Semester::all();
        $student->load(['department', 'batch.course']);

        $privatePaths = [
            $info->image,
            $info->signature,
            $info->resume
        ];
        CustomHelper::storePrivatePaths($privatePaths);

        return view('student.info.edit', [
            'info' => $info,
            'student' => $student,
            'semesters' => $semesters,
            'selectMenu' => CustomHelper::FORM_SELECTMENU,
            'canEdit' => false
        ]);
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
        $student = $student_by_roll_number;
        $info = StudentInfo::withTrashed()->findOrFail($student->id);

        $this->authorize('update', [StudentInfo::class, $student, $info]);

        $semesters = Semester::all();
        $student->load(['department', 'batch.course']);

        $privatePaths = [
            $info->image,
            $info->signature,
            $info->resume
        ];
        CustomHelper::storePrivatePaths($privatePaths);

        return view('student.info.edit', [
            'info' => $info,
            'student' => $student,
            'semesters' => $semesters,
            'selectMenu' => CustomHelper::FORM_SELECTMENU,
            'canEdit' => true
        ]);
    }

    public function update(Request $request, Student $student_by_roll_number) {
        $student = $student_by_roll_number;
        $info = StudentInfo::select('student_id')->withTrashed()->findOrFail($student->id);

        $this->authorize('update', [StudentInfo::class, $student, $info]);

        $rules = new StoreStudentInfo;
        $updateRules = array_merge($rules->rules(), $rules->updateRules($student));
        $data = $request->validate($updateRules);

        $student->load(['department', 'batch.course']);

        try {
            $fileData = [];
            if (isset($data['remove_image'])) {
                $this->removePrivateFile($info->image);
                $fileData['image'] = null;
            } else if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->removePrivateFile($info->image);
                $fileData['image'] = $this->storeStudentFile($request->file('image'), 'image', $student);
            }

            if (isset($data['remove_signature'])) {
                $this->removePrivateFile($info->signature);
                $fileData['signature'] = null;
            } else if ($request->hasFile('signature') && $request->file('signature')->isValid()) {
                $this->removePrivateFile($info->signature);
                $fileData['signature'] = $this->storeStudentFile($request->file('signature'), 'sign', $student);
            }

            if (isset($data['remove_resume'])) {
                $this->removePrivateFile($info->resume);
                $fileData['resume'] = null;
            } else if ($request->hasFile('resume') && $request->file('resume')->isValid()) {
                $this->removePrivateFile($info->resume);
                $fileData['resume'] = $this->storeStudentFile($request->file('resume'), 'resume', $student);
            }

            $info->update(array_merge($data, $fileData));
            Log::info('Student info updated.', [Auth::user(), $student]);
        } catch (\Exception $e) {
            Log::debug('Failed to update student information!', [Auth::user(), $e->getMessage(), $student, $data]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update information!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Information updated successfully'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
