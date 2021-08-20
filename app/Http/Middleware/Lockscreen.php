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

        $intendedUrl = url()->previous();
        if (strtolower($request->method()) == 'get') {
            $intendedUrl = url()->current();
        }

        if ($current - $lastVerified > VERIFY_INTERVAL) {
            $route = route('root.confirmPassword', [
                'intended' => $intendedUrl,
                'previous' => url()->previous()
            ]);

            if ($request->ajax()) {
                /**
                 * Used 300 as response code because for other redirects jQuery won't
                 * be able to access the response (browser will auto-redirect on its own).
                 * The redirect url is set using the 'Location' header
                */
                return response(null, 300)->header('Location', $route);
            }
            return redirect($route);
        }

        return $next($request);
    }
}
