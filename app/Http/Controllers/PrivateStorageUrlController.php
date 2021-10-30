<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;

class PrivateStorageUrlController extends Controller
{
    /**
    * Stores session keys received from \CustomHelper::getSessionConstants()
    *
    * @var null|array
    */
    private $sessionKeys = null;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    /**
     * Obtain files from private storage (authorized access)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Facades\Storage::response|HttpException
     */
    public function get(Request $request)
    {
        $path = $request->segments();
        /* remove the first segment to get original file path as array */
        array_shift($path);
        /* join array using '/' separator to get original path as string */
        $path = join('/', $path);

        if (! Storage::exists($path)) {
            abort(404);
        }

        $authUrls = session($this->sessionKeys['privateUrls'], null);
        $expireTime = session($this->sessionKeys['privateUrlsExpire'], null);
        if ($expireTime < time()) {
            abort(410);
        }
        if (! in_array($path, $authUrls)) {
            abort(403);
        }

        return Storage::response($path);
    }
}
