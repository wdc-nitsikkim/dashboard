<?php

namespace App\Http\Controllers\Admin;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;
use App\Traits\StoreFiles;
use App\Models\HomepageNotification as Noti;

class NotificationController extends Controller {
    use StoreFiles;

    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 10;

    public function show(Request $request, $trashed = null) {
        $this->authorize('view', Noti::class);

        if (is_null($trashed)) {
            $notifications = Noti::orderBy('created_at', 'desc')->paginate($this->paginate);
        } else {
            $notifications = Noti::onlyTrashed()->orderBy('created_at', 'desc')
                ->paginate($this->paginate);
        }

        $user = Auth::user();
        $canUpdate = $user->can('update', Noti::class);
        $canDelete = $user->can('delete', Noti::class);

        return view('admin.homepage.notifications.show')->with([
            'notifications' => $notifications->toArray(),
            'pagination' => $notifications->links('vendor.pagination.default'),
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete
        ]);
    }

    public function searchForm() {
        $this->authorize('view', Noti::class);

        return view('admin.homepage.notifications.search');
    }

    public function search(Request $request) {
        $this->authorize('view', Noti::class);

        $data = $request->validate([
            'display_text' => 'nullable',
            'type' => 'nullable',
            'link' => 'nullable',
            'status' => 'nullable | in:0,1',
            'trash_options' => 'nullable | in:only_trash,only_active',
            'created_at' => 'nullable | date_format:Y-m-d',
            'created_at_compare' => 'nullable | in:after,before'
        ]);

        $search = Noti::withTrashed();
        if (!is_null($data['trash_options'] ?? null)) {
            if ($data['trash_options'] == 'only_trash')
                $search = Noti::onlyTrashed();
            else if ($data['trash_options'] == 'only_active')
                $search = Noti::whereNull('deleted_at');
        }

        $map = [
            'display_text' => 'like',
            'link' => 'like',
            'type' => 'strict',
            'status' => 'strict',
            'created_at' => 'date'
        ];

        $search = CustomHelper::getSearchQuery($search, $data, $map)->paginate($this->paginate);
        $search->appends($data);

        $user = Auth::user();
        $canUpdate = $user->can('update', Noti::class);
        $canDelete = $user->can('delete', Noti::class);

        return view('admin.homepage.notifications.show')->with([
            'notifications' => $search->toArray(),
            'pagination' => $search->links('vendor.pagination.default'),
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete
        ]);
    }

    public function add($type = null) {
        $this->authorize('create', Noti::class);

        return view('admin.homepage.notifications.add', ['type' => $type]);
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Noti::class);

        $validator = Validator::make($request->all(), [
            'display_text' => 'required | min:10',
            'type' => 'required | in:announcement,download,notice,tender',
            'link' => 'nullable | url',
            'attachment' => 'filled | mimes:pdf,doc,docx,xls,xlsx | max:5120'
        ]);

        $validator->after(function ($validator) {
            if ($this->checkLinkAndFileBothMissing()) {
                $validator->errors()->add('link', 'Link required if attachment is not present!');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $link = $this->storeNotification($file, $request->type);
        } else {
            $link = $request->input('link');
        }

        try {
            $notification = new Noti($request->only([
                'display_text', 'type'
            ]));
            $notification->link = $link;
            $notification->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to add notification!'
            ])->withInput();
        }

        return redirect()->route('admin.homepage.notification.show')->with([
            'status' => 'success',
            'message' => 'Notification added!'
        ]);
    }

    public function edit(Noti $notification) {
        $this->authorize('update', Noti::class);

        return view('admin.homepage.notifications.edit', [
            'notification' => $notification
        ]);
    }

    public function update(Request $request, Noti $notification) {
        $this->authorize('update', Noti::class);

        $validator = Validator::make($request->all(), [
            'display_text' => 'required | min:10',
            'type' => 'required | in:announcement,download,notice,tender',
            'link' => 'nullable | url',
            'attachment' => 'filled | mimes:pdf,doc,docx,xls,xlsx | max:5120'
        ]);

        $validator->after(function ($validator) {
            if ($this->checkLinkAndFileBothMissing()) {
                $validator->errors()->add('link', 'Link required if attachment is not present!');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $link = $this->storeNotification($file, $request->type);
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
                'status' => 'fail',
                'message' => 'Failed to update!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Notification updated!'
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
                'status' => 'fail',
                'message' => 'Failed to update!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Status updated!'
        ]);
    }

    public function softDelete(Noti $notification) {
        $this->authorize('update', Noti::class);

        try {
            $notification->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to Delete!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Moved to Trash'
        ]);
    }

    public function restore($id) {
        $this->authorize('update', Noti::class);

        $notification = Noti::onlyTrashed()->findOrFail($id);
        try {
            $notification->restore();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to restore!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Restored successfully'
        ]);
    }

    public function delete($id) {
        $this->authorize('delete', Noti::class);

        $notification = Noti::onlyTrashed()->findOrFail($id);
        try {
            $notification->forceDelete();
        } catch (\Exception $e) {
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

    /**
     * Checks whether link & file both are missing from input
     *
     * @return bool
     */
    private function checkLinkAndFileBothMissing() {
        $file_status = request()->has('attachment')
            && request()->file('attachment')->isValid();
        $link_status = request('link');
        return ($file_status == false && $link_status == false);
    }

    public function test() {
        return 'Test';
    }
}
