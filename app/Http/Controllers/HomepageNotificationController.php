<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HomepageNotification as Noti;

class HomepageNotificationController extends Controller {
    public function show(Request $request) {
        $notifications = Noti::orderBy('created_at', 'desc')->paginate(10);
        return \view('homepage.notifications-view')->with(
            'notifications',  $notifications->toArray()
        );
    }
}
