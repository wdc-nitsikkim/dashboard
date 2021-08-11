<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\CustomHelper;
use App\Models\User;
use App\Traits\StoreFiles;

class UserController extends Controller {
    use StoreFiles;

    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 9;

    public function __construct() {
        $this->middleware('auth');
    }

    public function show() {

    }

    public function profile(int $id) {
        $this->authorize('view_account', [User::class, $id]);

        $user = User::with([
            'allowedDepartments.department:id,code,name',
            'roles.permissions',
            'profileLink'
        ])->withTrashed()->findOrFail($id);

        $canManage = Auth::user()->can('manage', [User::class, $id]);
        $canUpdate = Auth::user()->can('update', [User::class, $id]);
        $canDelete = Auth::user()->can('delete', [User::class, $id]);

        return view('user.profile', [
            'user' => $user,
            'canManage' => $canManage,
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete
        ]);
    }

    public function update(Request $request, int $id) {
        $user = User::findOrFail($id);
        $this->authorize('update', [User::class, $id]);

        $data = $request->validate([
            'name' => 'required | min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['required', 'numeric', 'digits:10',
                Rule::unique('users', 'mobile')->ignore($user->id)
            ],
            'remove_profile_image' => 'filled | in:on',
            'profile_image' => 'filled | image | max:800'
        ]);

        try {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->mobile = $data['mobile'];

            if (isset($data['remove_profile_image'])) {
                $this->removeUploadedImage($user->image);
                $user->image = null;
            } else if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                $this->removeUploadedImage($user->image);
                $user->image = $this->storeUserImage($request->file('profile_image'), $user->id);
            }
            $user->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update account details!'
            ])->withInput();
        }

        return back()->with([
            'status' =>'success',
            'message' => 'Account info. updated'
        ]);
    }

    public function changePassword(Request $request, int $id) {
        $user = User::findOrFail($id);
        $this->authorize('update', [User::class, $id]);

        $request->validate([
            'password' => 'required',
            'new_password' => 'required | min:6 | confirmed'
        ]);

        if (! \Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Current password is incorrect!'
            ]);
        }

        try {
            $user->password = \Hash::make($request->new_password);
            $user->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'An unknown error occurred!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Password updated!'
        ])->withInput();
    }

    public function softDelete(int $id) {
        $user = User::findOrFail($id);
        $this->authorize('manage', [User::class, $id]);

        try {
            $user->delete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Account suspension failed!'
            ]);
        }

        if ($user->id === Auth::id()) {
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with([
                'status' => 'info',
                'message' => 'Account suspended'
            ]);
        }
        return back()->with([
            'status' => 'success',
            'message' => 'Account suspended'
        ]);
    }

    public function restore(int $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorize('manage', [User::class, $id]);

        try {
            $user->restore();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Account restoration failed!'
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Account restored'
        ]);
    }

    public function delete(int $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', [User::class, $id]);

        try {
            $user->forceDelete();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Account deletion failed!'
            ]);
        }

        return redirect()->route('root.default')->with([
            'status' => 'success',
            'message' => 'Account deleted'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
