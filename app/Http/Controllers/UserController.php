<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\CustomHelper;
use App\Models\User;
use App\Models\Student;
use App\Traits\StoreFiles;
use App\Traits\SendEmails;
use App\Notifications\VerifyEmail;

class UserController extends Controller {
    use SendEmails, StoreFiles;

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
        $this->authorize('view', User::class);

        $users = User::with('roles')->paginate($this->paginate);

        return view('users.show', [
            'users' => $users,
            'pagination' => $users->links('vendor.pagination.default')
        ]);
    }

    public function searchForm() {
        $this->authorize('view', User::class);

        $roles = CustomHelper::getRoles();
        return view('users.search', [
            'roles' => $roles
        ]);
    }

    public function search(Request $request) {
        $this->authorize('view', User::class);

        $data = $request->validate([
            'name' => 'nullable',
            'role' => ['nullable', 'in:' . implode(',', CustomHelper::getRoles())],
            'trash_options' => 'nullable | in:only_trash,only_active',
            'created_at' => 'nullable | date_format:Y-m-d',
            'created_at_compare' => 'nullable | in:after,before'
        ]);

        $search = User::withTrashed();
        if (!is_null($data['trash_options'] ?? null)) {
            if ($data['trash_options'] == 'only_trash')
                $search = User::onlyTrashed();
            else if ($data['trash_options'] == 'only_active')
                $search = User::whereNull('deleted_at');
        }

        $map = [
            'name' => 'like',
            'created_at' => 'date'
        ];

        $search->with('roles');
        $search = CustomHelper::getSearchQuery($search, $data, $map);
        if (!is_null($data['role'] ?? null)) {
            $search->whereHas('roles', function ($query) use ($data) {
                $query->where('role', $data['role']);
            });
        }

        $search = $search->paginate($this->paginate);
        $search->appends($data);

        return view('users.show', [
            'users' => $search,
            'pagination' => $search->links('vendor.pagination.default')
        ]);
    }

    public function profile(int $id) {
        $this->authorize('view_account', [User::class, $id]);

        $user = User::with([
            'allowedDepartments.department:id,code,name',
            'allowedSubjects.subject:id,code,name',
            'roles.permissions',
            'profileLink'
        ])->withTrashed()->findOrFail($id);
        $student = Student::select('id', 'roll_number')->where('email', $user->email)->first();

        $canManage = Auth::user()->can('manage', [User::class, $user]);
        $canUpdate = Auth::user()->can('update', [User::class, $user]);
        $canDelete = Auth::user()->can('delete', [User::class, $user]);

        return view('users.account', [
            'user' => $user,
            'student' => $student,
            'canManage' => $canManage,
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete
        ]);
    }

    public function update(Request $request, int $id) {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('update', [User::class, $user]);

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
            if ($user->email != $data['email']) {
                $user->email_verified_at = null;
            }

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
            Log::debug('Failed to update account details', [Auth::user(), $e->getMessage(), $user]);
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
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('update', [User::class, $user]);

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
            Log::debug('Failed to update password!', [Auth::user(), $e->getMessage(), $user]);
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
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('manage', [User::class, $user]);

        try {
            $user->delete();
            Log::notice('User soft deleted!', [Auth::user(), $user]);
        } catch (\Exception $e) {
            Log::debug('User soft deletion failed!', [Auth::user(), $e->getMessage(), $user]);
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
        $this->authorize('manage', [User::class, $user]);

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
        $this->authorize('delete', [User::class, $user]);

        try {
            $user->forceDelete();
            Log::alert('User deleted!', [Auth::user(), $user]);
        } catch (\Exception $e) {
            Log::debug('User deletion failed!', [Auth::user(), $user]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Account deletion failed!'
            ]);
        }

        return redirect()->route('root.home')->with([
            'status' => 'success',
            'message' => 'Account deleted'
        ]);
    }

    public function sendVerificationEmail() {
        $user = User::findOrFail(Auth::id());

        if ($user->email_verified_at != null) {
            return back()->with([
                'status' => 'info',
                'message' => 'This email address is already verified!'
            ]);
        }

        $token = CustomHelper::getRandomStr(CustomHelper::RESET_PWD_TOKEN_LEN);

        $link = route('users.verifyEmail.confirm', [
            'token' => urlencode($token)
        ]);

        try {
            $user->email_token = $token;
            $user->save();
        } catch (\Exception $e) {
            Log::debug('Failed to save email verification info.', [$e->getMessage(), $user]);
            return back()->with([
                'status' => 'fail',
                'message' => 'An unknown error occurred!'
            ]);
        }

        /**
         * Register a function to send email. It is executed after the response is sent
         * as queues cannot be used because of unavailability of CRON jobs on
         * actual server (https://nitsikkim.ac.in)
         */
        // app()->terminating(function () use ($user, $link) {
        //     Notification::route('mail', $user->email)
        //         ->notify(new VerifyEmail($user->name, $link));
        //     Log::info('Email verification link sent to ' . $user->email);
        // });

        if ($this->sendEmailVerification($user->email, $user->name, $link)) {
            return back()->with([
                'status' => 'success',
                'message' => 'Verification link sent to email'
            ]);
        } else {
            return back()->with([
                'status' => 'fail',
                'message' => 'Mail server did not respond!'
            ]);
        }
    }

    public function confirmEmail(Request $request, $token) {
        if (strlen($token) != CustomHelper::RESET_PWD_TOKEN_LEN) {
            abort(404);
        }

        $user = User::where([
            'email' => Auth::user()->email,
            'email_token' => $token
        ])->firstOrFail();

        try {
            $user->email_verified_at = now();
            $user->email_token = null;
            $user->save();
        } catch (\Exception $e) {
            Log::debug('Failed to verify email', [Auth::user(), $token]);
            return redirect()->route('users.verifyEmail.view')->with([
                'status' => 'fail',
                'message' => 'Failed to verify email!'
            ]);
        }

        return redirect()->route('users.account', $user->id)->with([
            'status' => 'success',
            'message' => 'Email address verified!'
        ]);
    }

    public function test() {
        return 'Test';
    }
}
