<?php

namespace App\Http\Controllers\Homepage;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\HomepageNotification as Noti;

class NotificationController extends Controller {
    private $noti_paginate = 5;

    public function show(Request $request) {
        $this->authorize('view', Noti::class);
        if (!empty($request->input('filter_by')) && !empty($request->input('value'))) {
            $notifications = Noti::where($request->input('filter_by'), $request->input('value'))
                ->orderBy('created_at', 'desc');
        } else {
            $notifications = Noti::orderBy('created_at', 'desc');
        }
        $notifications = $notifications->paginate($this->noti_paginate);

        return \view('homepage.notifications.show')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }

    public function add($type = null) {
        $this->authorize('create', Noti::class);
        return \view('homepage.notifications.add', ['type'=> $type]);
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Noti::class);
        /* TODO: make more dynamic.. use constants */

        $validator = Validator::make($request->all(), [
            'display_text'=> 'required | min:10',
            'type'=> 'required',
            'link'=> 'nullable | url',
            'attachment'=> 'filled | mimes:pdf,doc,docx,xls,xlsx | max:5120'
        ]);

        $validator->after(function($validator) {
            if ($this->checkLinkAndFileBothMissing()) {
                $validator->errors()->add('link', 'Link required if attachment is not present!');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $file_name = CustomHelper::format_file_name($file->getClientOriginalName());
            $extension = $file->extension();
            $file_name .= '.' . $extension;
            $path = $file->storeAs('files', $file_name, 'public');
            $link = asset(Storage::url($path));
        } else {
            $link = $request->input('link');
        }

        $notification = new Noti;
        try {
            $notification->display_text = $request->input('display_text');
            $notification->type = $request->input('type');
            $notification->link = $link;
            $notification->save();
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to add notification!'
            ])->withInput();
        }

        return redirect()->route('homepage.notification.show')->with([
            'status'=> 'success',
            'message'=> 'Notification added!'
        ]);
    }

    private function checkLinkAndFileBothMissing() {
        $file_status = CustomHelper::check_file_input('attachment');
        $link_status = isset($_POST['link']) && !empty($_POST['link']);
        return ($file_status == false && $link_status == false);
    }

    public function showTrashed() {
        $this->authorize('view', Noti::class);

        $notifications = Noti::onlyTrashed()->paginate($this->noti_paginate);

        return \view('homepage.notifications.show')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }

    public function editPage(Noti $notification) {
        $this->authorize('update', Noti::class);

        return \response()->json($notification);
    }

    public function updateStatus($id, $status) {
        $this->authorize('update', Noti::class);

        $notification = Noti::withTrashed()->findOrFail($id);
        try {
            $notification->status = ($status == 'enable') ? '1' : '0';
            $notification->save();
        } catch (\Exception $e) {
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to update!'
            ]);
        }

        return back()->with([
            'status'=> 'success',
            'message'=> 'Status updated!'
        ]);
    }

    public function softDelete(Noti $notification) {
        $this->authorize('update', Noti::class);

        try {
            $notification->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to Delete!'
            ]);
        }

        return back()->with([
            'status'=> 'success',
            'message'=> 'Moved to Trash'
        ]);
    }

    public function restore($id) {
        $this->authorize('update', Noti::class);

        $notification = Noti::withTrashed()->findOrFail($id);
        try {
            $notification->restore();
        } catch (\Exception $e) {
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to restore!'
            ]);
        }

        return back()->with([
            'status'=> 'success',
            'message'=> 'Restored successfully'
        ]);
    }

    public function delete($id) {
        $this->authorize('delete', Noti::class);

        $notification = Noti::withTrashed()->findOrFail($id);
        try {
            $notification->forceDelete();
        } catch (\Exception $e) {
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to delete!'
            ]);
        }

        return back()->with([
            'status'=> 'success',
            'message'=> 'Deleted permanently!'
        ]);
    }
}
