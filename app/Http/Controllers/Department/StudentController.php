<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Department;

class StudentController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 15;

    public function index($dept) {
        $this->authorize('view', [Student::class, $dept]);

        $btechBatches = Batch::where('type', 'b')->orderByDesc('id')->get();
        $mtechBatches = Batch::where('type', 'm')->orderByDesc('id')->get();

        return view('department.students.select-batch', [
            'department' => $dept,
            'btechBatches' => $btechBatches,
            'mtechBatches' => $mtechBatches
        ]);
    }

    public function show($dept, $batch) {
        $this->authorize('view', [Student::class, $dept]);

        echo $dept;
        echo $batch;
    }

    public function test() {
        return 'Test';
    }
}
