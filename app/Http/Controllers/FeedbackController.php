<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Feedback;

class FeedbackController extends Controller {
    public function saveNew(Request $request) {
        $data = $request->validate([
            'rating' => 'nullable | numeric | between:1,5',
            'feedback' => 'required | string'
        ]);

        $data['user_id'] = Auth::id();

        try {
            Feedback::create($data);
        } catch (\Exception $e) {
            Log::debug('Failed to save feedback', [Auth::user(), $e->getMessage(), $data]);
            return abort(500);
        }

        return response()->json([
            'success' => true
        ], 201);
    }
}
