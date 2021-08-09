<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Google_Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\User;

class LoginController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function defaultLogin(Request $request) {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required',
            'remember' => 'nullable'
        ]);

        $remember = $request->remember ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            return redirect()->route('root.default')->with([
                'status' => 'success',
                'message' => 'Logged in'
            ]);
        }
        return back()->with([
            'status' => 'fail',
            'message' => 'Invalid credentials!'
        ])->withInput($request->except('password'));
    }

    public function withGoogle(Request $request) {
        $cookieVal = $_COOKIE['g_csrf_token'] ?? '';
        $validator = Validator::make($request->post(),[
            'g_csrf_token' => ['required', 'string', 'in:' . $cookieVal],
            'credential' => 'required | string'
        ]);

        if ($validator->fails()) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Double submit cookie verification failed!'
            ]);
        }

        $validIssuers = ['https://accounts.google.com', 'accounts.google.com'];
        $validDomains = ['nitsikkim.ac.in'];
        $clientId = config('app.g_signin_client_id');
        $gClient = new Google_Client([ 'client_id' => $clientId ]);

        try {
            $payload = $gClient->verifyIdToken($request->post('credential'));
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

        if (!$payload) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Auth token invalid!'
            ]);
        }

        $tokenIssuer = $payload['iss'] ?? '';
        $tokenHdClaim = $payload['hd'] ?? '';

        if (!in_array($tokenIssuer, $validIssuers)) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Auth server did not respond correctly!'
            ]);
        }
        if (!in_array($tokenHdClaim, $validDomains)) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Organization error!'
            ]);
        }
        if (empty($payload['email']) || empty($payload['name'])) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Unable to access user profile'
            ]);
        }

        $email = $payload['email'];
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return $this->redirectRegister($payload);
        }

        Auth::loginUsingId($user->id);
        return redirect()->route('root.default')->with([
            'status' => 'success',
            'message' => 'Signed in with Google'
        ]);
    }

    public function redirectRegister($data = null) {
        return redirect()->route('register')->withInput([
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? ''
        ])->with([
            'status' => 'info',
            'message' => 'No account found'
        ]);
    }

    public function logout() {
        Auth::logout();
        session()->flush();
        return redirect('/home');
    }

    public function test() {
        return 'Test';
    }
}
