<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HomepageNotification as Noti;

class HomepageNotificationController extends Controller {
    private $noti_paginate = 5;

    public function show(Request $request) {
        if (!empty($request->input('filter_by')) && !empty($request->input('value'))) {
            $notifications = Noti::where($request->input('filter_by'), $request->input('value'))
                ->orderBy('created_at', 'desc');
        } else {
            $notifications = Noti::orderBy('created_at', 'desc');
        }
        $notifications = $notifications->paginate($this->noti_paginate);

        return \view('homepage.notifications-view')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }
}
