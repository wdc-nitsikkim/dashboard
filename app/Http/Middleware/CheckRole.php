<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles) {
        if (!Auth::check()) {
            return \redirect()->route('login');
        }

        if (! \In_array(Auth::user()->role, $roles)) {
            \abort(403, 'Acess Denied!');
        }

        return $next($request);
    }
}
