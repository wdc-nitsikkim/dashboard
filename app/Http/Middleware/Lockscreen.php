<?php

namespace App\Http\Middleware;

use Closure;

use App\CustomHelper;

class Lockscreen
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
        define('DEFAULT_REDUCER', 3600);
        define('VERIFY_INTERVAL', DEFAULT_REDUCER / 2);

        $sessionKey = CustomHelper::getSessionConstants()['confirmPassword'];

        $lastVerified = session($sessionKey, time() - DEFAULT_REDUCER);
        $current = time();

        if ($current - $lastVerified > VERIFY_INTERVAL) {
            return redirect()->route('root.confirmPassword', [
                'intended' => url()->previous()
            ]);
        }

        return $next($request);
    }
}
