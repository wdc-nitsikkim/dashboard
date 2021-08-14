<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Profile;
use App\Models\Position;

class PositionController extends Controller {
    public function show() {
        $this->authorize('view', Position::class);

        $positions = Position::with(['profile:id,name'])->get();

        return view('admin.office.positions', [
            'positions' => $positions
        ]);
    }

    public function assign(Request $request) {
        $this->authorize('create', Position::class);

        $data = $request->validate([
            'position' => 'required | string | min:5',
            'email' => 'required | email',
            'mobile' => 'required | numeric | digits:10',
            'profile_id' => 'required | numeric'
        ]);

        try {
            Position::create($data);
            Log::notice('PoR assigned', [Auth::user(), $data]);
        } catch (\Exception $e) {
            Log::debug('Failed to assign PoR', [Auth::user(), $e->getMessage(), $data]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to assign PoR'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Position assigned'
        ]);
    }

    public function remove($id) {
        $this->authorize('update', Position::class);

        try {
            Position::where('id', $id)->delete();
            Log::notice('PoR removed', [Auth::user(), $id]);
        } catch (\Exception $e) {
            Log::debug('Failed to remove PoR', [Auth::user(), $e->getMessage(), $id]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to remove PoR'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Position vacacted'
        ]);
    }
}
