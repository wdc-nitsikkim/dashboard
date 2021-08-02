<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Batch;

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
    }

    public function select() {
        $btechBatches = Batch::where('type', 'b')->orderByDesc('id')->get();
        $mtechBatches = Batch::where('type', 'm')->orderByDesc('id')->get();

        return view('batch.select', [
            'btechBatches' => $btechBatches,
            'mtechBatches' => $mtechBatches
        ]);
    }

    public function saveInSession(Request $request, Batch $batch) {
        session([$this->sessionKeys['selectedBatch'] => $batch]);
        $redirectRouteName = $request->input('redirect');

        return Route::has($redirectRouteName)
            ? redirect()->route($redirectRouteName, $batch)
            : redirect()->route('batch.show');
    }

    public function show(Request $request) {
        $btechPage = $request->btech;
        $mtechPage = $request->mtech;

        $btechBatches = Batch::where('type', 'b')->orderByDesc('id')
            ->paginate($this->paginate, ['*'], 'btech', $btechPage);
        $mtechBatches = Batch::where('type', 'm')->orderByDesc('id')
            ->paginate($this->paginate, ['*'], 'mtech', $mtechPage);

        $btechBatches->setPageName('btech');
        $btechBatches->appends(['mtech' => $mtechPage]);
        $mtechBatches->setPageName('mtech');
        $mtechBatches->appends(['btech' => $btechPage]);

        return view('batch.show', [
            'btechBatches' => $btechBatches->toArray(),
            'btechPagination' => $btechBatches->links('vendor.pagination.default'),
            'mtechBatches' => $mtechBatches->toArray(),
            'mtechPagination' => $mtechBatches->links('vendor.pagination.default')
        ]);
    }

    public function test() {
        return 'Test';
    }
}
