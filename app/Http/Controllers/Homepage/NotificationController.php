<?php

namespace App\Http\Controllers\Homepage;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Models\HomepageNotification as Noti;

class NotificationController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 5;

    public function show(Request $request) {
        $this->authorize('view', Noti::class);

        $notifications = Noti::orderBy('created_at', 'desc')->paginate($this->paginate);

        return \view('homepage.notifications.show')->with([
            'notifications' => $notifications->toArray(),
            'pagination' => $notifications->links('vendor.pagination.default')
        ]);
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
            $fileName = CustomHelper::formatFileName($file->getClientOriginalName());
            $extension = $file->extension();
            $fileName .= '.' . $extension;
            $storagePath = $this->getStoragePath($request->input('type'));
            $path = $file->storeAs($storagePath, $fileName, 'public');
            $link = asset(Storage::url($path));
        } else {
            $link = $request->input('link');
        }

        try {
            $notification = new Noti;
            $notification->display_text = $request->input('display_text');
            $notification->type = $request->input('type');
            $notification->link = $link;
            $notification->save();
        } catch (\Exception $e) {
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

    public function showTrashed() {
        $this->authorize('view', Noti::class);

        $notifications = Noti::onlyTrashed()->paginate($this->paginate);

        return \view('homepage.notifications.show')->with([
            'notifications'=> $notifications->toArray(),
            'pagination'=> $notifications->links('vendor.pagination.default')]
        );
    }

    public function editPage(Noti $notification) {
        $this->authorize('update', Noti::class);

        return \view('homepage.notifications.edit', [
            'data'=> $notification
        ]);
    }

    public function update(Request $request, Noti $notification) {
        $this->authorize('update', Noti::class);

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
            return back()->withErrors($validator->errors());
        } else if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $fileName = CustomHelper::formatFileName($file->getClientOriginalName());
            $extension = $file->extension();
            $fileName .= '.' . $extension;
            $storagePath = $this->getStoragePath($request->input('type'));
            $path = $file->storeAs($storagePath, $fileName, 'public');
            $link = asset(Storage::url($path));
        } else {
            $link = $request->input('link');
        }

        try {
            $notification->display_text = $request->input('display_text');
            $notification->type = $request->input('type');
            $notification->link = $link;
            $notification->save();
        } catch (\Exception $e) {
            return back()->with([
                'status'=> 'fail',
                'message'=> 'Failed to update!'
            ]);
        }

        return back()->with([
            'status'=> 'success',
            'message'=> 'Notification updated!'
        ]);
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

    /**
     * Returns file storage path for this class
     *
     * @param string $type
     * @return string
     */
    private function getStoragePath($type) {
        return 'homepage/files/' . $type;
    }

    /**
     * Checks whether link & file both are missing from input
     *
     * @return bool
     */
    private function checkLinkAndFileBothMissing() {
        $file_status = CustomHelper::checkFileInput('attachment');
        $link_status = isset($_POST['link']) && !empty($_POST['link']);
        return ($file_status == false && $link_status == false);
    }

    public function test() {
        return 'Test';
    }
}
