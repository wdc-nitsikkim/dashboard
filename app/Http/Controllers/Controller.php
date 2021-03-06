<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $site_settings = null;

    public function __construct()
    {
        /**
         * Custom app configuration
         * Making the `site_settings` database accessible to all controllers
         */
        $this->site_settings = App::make('site_settings');
    }
}
