<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class EmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->email_verified_at == null) {
            $msg = [
                'status' => 'info',
                'message' => 'You need to verify your email first'
            ];
            $route = route('users.verifyEmail.view');

            if ($request->ajax()) {
                return response(null, 300)->header('Location', $route);
            }

            return redirect($route)->with($msg);
        }

        return $next($request);
    }
}
