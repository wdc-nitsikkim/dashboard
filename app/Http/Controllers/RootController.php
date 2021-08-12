<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

use App\CustomHelper;
use App\Models\User;

class RootController extends Controller {
    public function clearSession() {
        $sessionKeys = CustomHelper::getSessionConstants();
        foreach ($sessionKeys as $val) {
            session()->forget($val);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Session cleared'
        ]);
    }

    public function siteSettings() {
        $this->authorize('manage_site', User::class);

        return view('manageSite');
    }

    public function executeArtisanCommand(Request $request, $command) {
        $this->authorize('manage_site', User::class);

        if ($command == 'storage:link') {
            return $this->linkStorage($request->getHttpHost());
        } else if ($command == 'route:cache') {
            return back()->with([
                'status' => 'fail',
                'message' => 'Command not supported!'
            ]);
        }

        $exitCode = Artisan::call($command);
        $msg = [
            'status' => 'success',
            'message' => 'Command executed with exit code: ' . $exitCode
        ];

        return back()->with($msg);
    }

    private function linkStorage($host) {
        $target = '/home/ntskm85i/domains/nitsikkim.ac.in/laravel-5.5/storage/app/public';
        $shortcut = '/home/ntskm85i/domains/nitsikkim.ac.in/public_html/dashboard-beta/storage';

        if ($host == 'nitsikkim.ac.in') {
            try {
                symlink($target, $shortcut);
            } catch (\Exception $e) {
                return back()->with([
                    'status' => 'fail',
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            Artisan::call('storage:link');
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Symlink created!'
        ]);
    }
}
