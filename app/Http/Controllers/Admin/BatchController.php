<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;
use App\Models\Course;

class BatchController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 5;

    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
        $this->courses = Course::all();
        $this->btechCode = $this->courses->where('code', 'btech')->first()->id;
        $this->mtechCode = $this->courses->where('code', 'mtech')->first()->id;
    }

    public function select() {
        $batches = Batch::all();
        $btechBatches = $batches->where('course_id', $this->btechCode)->sortByDesc('id');
        $mtechBatches = $batches->where('course_id', $this->mtechCode)->sortByDesc('id');

        return view('admin.batch.select', [
            'btechBatches' => $btechBatches,
            'mtechBatches' => $mtechBatches
        ]);
    }

    public function saveInSession(Request $request, Batch $batch) {
        session([$this->sessionKeys['selectedBatch'] => $batch]);
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $batch)
            : redirect()->route('admin.batch.show');
    }

    public function show(Request $request) {
        $this->authorize('view', Batch::class);

        $btechPage = $request->btech;
        $mtechPage = $request->mtech;

        $btechBatches = Batch::withTrashed()->where('course_id', $this->btechCode)
            ->orderByDesc('id')->paginate($this->paginate, ['*'], 'btech', $btechPage);
        $mtechBatches = Batch::withTrashed()->where('course_id', $this->mtechCode)
            ->orderByDesc('id')->paginate($this->paginate, ['*'], 'mtech', $mtechPage);

        $btechBatches->setPageName('btech');
        $btechBatches->appends(['mtech' => $mtechPage]);
        $mtechBatches->setPageName('mtech');
        $mtechBatches->appends(['btech' => $btechPage]);

        return view('admin.batch.show', [
            'btechBatches' => $btechBatches->toArray(),
            'btechPagination' => $btechBatches->links('vendor.pagination.default'),
            'mtechBatches' => $mtechBatches->toArray(),
            'mtechPagination' => $mtechBatches->links('vendor.pagination.default')
        ]);
    }

    public function add() {
        $this->authorize('create', Batch::class);

        return view('admin.batch.add', [
            'courses' => $this->courses
        ]);
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Batch::class);

        $data = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'code' => ['required', 'max:5', Rule::unique('batches', 'code')],
            'start_year' => 'required | numeric | min:2010',
            'name' => 'required | min:3'
        ]);

        try {
            Batch::create($data);
            Log::notice('New batch added.', [Auth::user(), $data]);
        } catch (\Exception $e) {
            Log::debug("Batch addition failed!", [Auth::user(), $e->getMessage(), $data]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add batch!'
            ])->withInput();
        }

        return redirect()->route('admin.batch.show')->with([
            'status' => 'success',
            'message' => 'Batch added!'
        ]);
    }

    public function edit($id) {
        $this->authorize('update', Batch::class);

        $batch = Batch::findOrFail($id);
        return view('admin.batch.edit', [
            'batch' => $batch,
            'courses' => $this->courses
        ]);
    }

    public function update(Request $request, $id) {
        $this->authorize('update', Batch::class);

        $batch = Batch::findOrFail($id);

        $data = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'code' => ['required', 'max:5', Rule::unique('batches', 'code')->ignore($batch->id)],
            'start_year' => 'required | numeric | min:2010',
            'name' => 'required | min:3'
        ]);

        try {
            $batch->update($data);
            Log::info('Batch updated.', [Auth::user(), $data]);
        } catch (\Exception $e) {
            Log::debug('Batch update failed!', [Auth::user(), $e->getMessage(), $data]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update batch!'
            ])->withInput();
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Batch updated!'
        ]);
    }

    public function softDelete($id) {
        $this->authorize('update', Batch::class);

        $batch = Batch::findOrFail($id);
        try {
            $batch->delete();
            Log::notice('Batch soft deleted.', [Auth::user(), $batch]);
        } catch (\Exception $e) {
            Log::debug('Batch soft deletion failed!.', [Auth::user(), $e->getMessage(), $batch]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to delete!'
            ]);
        }

        /**
         * removing selected batch from session to prevent 404 page
         * from showing up (if removed batch was the selected batch)
         */
        session()->forget($this->sessionKeys['selectedBatch']);

        return back()->with([
            'status' => 'success',
            'message' => 'Moved to trash!'
        ]);
    }

    public function restore($id) {
        $this->authorize('update', Batch::class);

        $batch = Batch::onlyTrashed()->findOrFail($id);
        try {
            $batch->restore();
            Log::info('Batch restored.', [Auth::user(), $batch]);
        } catch (\Exception $e) {
            Log::debugo('Batch restoration failed!', [Auth::user(), $e->getMessage(), $batch]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to restore!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Restored successfully!'
        ]);
    }

    public function delete($id) {
        $this->authorize('delete', Batch::class);

        $batch = Batch::onlyTrashed()->findOrFail($id);
        try {
            $batch->forceDelete();
            Log::alert('Batch deleted.', [Auth::user(), $batch]);
        } catch (\Exception $e) {
            Log::debug('Batch deletion failed!', [Auth::user(), $e->getMessage(), $batch]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to delete!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Deleted permanently!'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
