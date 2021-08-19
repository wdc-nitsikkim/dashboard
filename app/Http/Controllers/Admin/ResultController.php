<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;

class ResultController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 15;

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
        if (! session()->has($this->sessionKeys['selectedDepartment'])) {
            return redirect()->route('admin.department.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        if (! session()->has($this->sessionKeys['selectedBatch'])) {
            return redirect()->route('admin.batch.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        if (! session()->has($this->sessionKeys['selectedSubject'])) {
            return redirect()->route('admin.subjects.select', [
                'redirect' => 'admin.results.handleRedirect'
            ]);
        }

        return redirect()->route('admin.results.show', [
            'dept' => session($this->sessionKeys['selectedDepartment']),
            'batch' => session($this->sessionKeys['selectedBatch']),
            'subject' => session($this->sessionKeys['selectedSubject'])
        ]);
    }

    public function show(Department $dept, Batch $batch, Subject $subject) {
        $this->authorize('view', [Result::class, $subject]);

        $students = Student::withTrashed()->where([
            'batch_id' => $batch->id,
            'department_id' => $dept->id
        ])->with('result')->orderBy('roll_number')->paginate($this->paginate);

        return view('admin.results.show', [
            'batch' => $batch,
            'subject' => $subject,
            'students' => $students,
            'department' => $dept,
            'pagination' => $students->links('vendor.pagination.default')
        ]);
    }

    public function test() {
        return 'Test';
    }
}
