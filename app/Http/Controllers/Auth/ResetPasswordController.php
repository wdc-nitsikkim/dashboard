<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\CustomHelper;
use App\Models\User;
use App\Models\PasswordReset;

class ResetPasswordController extends Controller {
    public function __construct() {
        $this->middleware('guest');
    }

    public function show($email, $token) {
        $dbToken = $this->validateLink($email, $token);

        if ($this->isLinkExpired($dbToken)) {
            abort(410);
        }

        return view('auth.resetPassword', [
            'userInfo' => $dbToken->user->name . ', ' . $email
        ]);
    }

    public function reset(Request $request, $email, $token) {
        $request->validate([
            'password' => 'required | min:6 | confirmed'
        ]);

        $dbToken = $this->validateLink($email, $token);

        if ($this->isLinkExpired($dbToken)) {
            abort(410);
        }

        try {
            User::where('id', $dbToken->user->id)
                ->update([
                    'password' => Hash::make($request->password)
                ]);
            $dbToken->delete();
        } catch (\Exception $e) {
            Log::debug('Failed to reset password', [$e->getMessage(), $user, $dbToken]);
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update password! Please try again'
            ]);
        }

        return redirect()->route('login')->with([
            'status' => 'success',
            'message' => 'Password updated succesfully. Login to continue'
        ]);
    }

    private function validateLink($email, $token) {
        if (strlen($token) != CustomHelper::RESET_PWD_TOKEN_LEN) {
            abort(404);
        }

        return PasswordReset::with('user:id,email,name')->where([
            'email' => $email,
            'token' => $token
        ])->firstOrFail();
    }

    private function isLinkExpired($tokenRow) {
        $validSeconds = 3600;

        $tokenCreationTime = strtotime($tokenRow->created_at);
        $current = strtotime(now());

        return ($current - $tokenCreationTime) > $validSeconds;
    }
}
