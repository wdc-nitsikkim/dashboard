<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Hod;
use App\Models\Department;

class HodController extends Controller {
    public function show() {
        $this->authorize('view', Hod::class);

        $hods = Hod::with(['department:id,name', 'profile:id,name'])->get();
        $excludeDepts = $hods->pluck('department_id')->toArray();
        $departments = Department::select('id', 'name')
            ->whereNotIn('id', $excludeDepts)->get();

        return view('admin.office.hod', [
            'hods' => $hods,
            'departments' => $departments
        ]);
    }

    public function assign(Request $request) {
        $this->authorize('update', Hod::class);

        $data = $request->validate([
            'department_id' => 'required | numeric',
            'profile_id' => 'required | numeric'
        ]);

        try {
            Hod::create($data);
            Log::notice('Profile assigned as HoD', [Auth::user(), $data]);
        } catch (\Exception $e) {
            Log::debug('Failed to assign profile as HoD', [Auth::user(), $e->getMessage(), $data]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to assign as HoD'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Profile assigned as HoD'
        ]);
    }

    public function remove($dept_id) {
        $this->authorize('update', Hod::class);

        try {
            Hod::where('department_id', $dept_id)->delete();
            Log::notice('HoD removed', [Auth::user(), $dept_id]);
        } catch (\Exception $e) {
            Log::debug('Failed to remove HoD', [Auth::user(), $e->getMessage(), $dept_id]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to remove HoD'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'HoD position vacacted'
        ]);
    }
}
