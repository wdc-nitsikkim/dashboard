<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

use App\CustomHelper;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordReset as ResetEmail;

class ForgotPasswordController extends Controller {
    public function __construct() {
        $this->middleware('guest');
    }

    public function sendEmail(Request $request) {
        $request->validate([
            'email' => 'required | email'
        ]);

        $user = User::select('id', 'name', 'email')->where('email', $request->email)->first();
        if ($user == null) {
            return back()->with([
                'status' => 'fail',
                'message' => 'User does not exist!'
            ]);
        }

        $email = urlencode($user->email);
        $token = CustomHelper::getRandomStr(CustomHelper::RESET_PWD_TOKEN_LEN);

        $link = route('auth.resetPassword', [
            'email' => $email,
            'token' => urlencode($token)
        ]);

        try {
            PasswordReset::updateOrCreate([
                'email' => $user->email
            ], [
                'token' => $token,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::debug('Failed to save password reset info.', [$e->getMessage(), $user]);
            return back()->with([
                'status' => 'fail',
                'message' => 'An unknown error occurred!'
            ])->withInput();
        }

        /**
         * Register a function to send email. It is executed after the response is sent
         * as queues cannot be used because of unavailability of CRON jobs on
         * actual server (https://nitsikkim.ac.in)
         */
        app()->terminating(function () use ($user, $link) {
            Notification::route('mail', $user->email)
                ->notify(new ResetEmail($user->name, $link));
            Log::info('Password reset link sent to ' . $user->email);
        });

        return redirect()->route('login')->with([
            'status' => 'info',
            'message' => 'Password reset link has been sent'
        ]);
    }
}
