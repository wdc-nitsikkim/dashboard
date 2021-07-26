<?php

namespace App\Http\Controllers\Homepage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HomepageNotification as Noti;

class NotificationController extends Controller {
    private $noti_paginate = 5;

    public function show(Request $request) {
        if (!empty($request->input('filter_by')) && !empty($request->input('value'))) {
            $notifications = Noti::where($request->input('filter_by'), $request->input('value'))
                ->orderBy('created_at', 'desc');
        } else {
            $notifications = Noti::orderBy('created_at', 'desc');
        }
        $notifications = $notifications->paginate($this->noti_paginate);

        return \view('homepage.show-notifications')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }

    public function showBin(Request $request) {
        $notifications = Noti::onlyTrashed()->paginate($this->noti_paginate);

        return \view('homepage.show-notifications')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }
}
