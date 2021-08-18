<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Subject;

class SubjectController extends Controller {
    private $sessionKeys = null;

    function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function show() {

    }

    public function select() {
        $preferred = Auth::user()->allowedSubjects()->with('subject:id,code,name')->get();
        $subjects = Subject::select('id', 'code', 'name', 'semester')->get();

        return view('admin.subjects.select', [
            'preferred' => $preferred,
            'subjects' => $subjects
        ]);
    }

    public function saveInSession(Request $request, Subject $subject) {
        session([$this->sessionKeys['selectedSubject'] => $subject]);
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $subject)
            : redirect()->route('admin.subjects.show');
    }

    public function test() {
        return 'Test';
    }
}
