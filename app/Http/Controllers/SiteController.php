<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

use Ifsnop\Mysqldump as IMysqldump;

use App\CustomHelper;
use App\Traits\StoreFiles;

class SiteController extends Controller {
    use StoreFiles;

    public function __construct() {
        $this->middleware('can:manage_site,App\Models\User');
    }

    public function dbBackupCreate() {
        $host = config('database.connections.mysql.host');
        $name = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');

        $fileName = 'db-backup-' . time() . '.gz';
        $this->createBackupDir();
        $tempFile = config('filesystems.disks.public.root') . '/' . $fileName;

        try {
            $dump = new IMysqldump\Mysqldump("mysql:host=$host;dbname=$name", $user, $pass, [
                'compress' => IMysqldump\Mysqldump::GZIP
            ]);
            $dump->start($tempFile);
            $url = $this->moveFileToLocalDisk($tempFile, $fileName);

            if ($url) {
                return response()->download($url, $fileName);
            }
            throw new \Exception('Failed to move file!');
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function removeBackupDir() {
        $status = $this->removeDirectory($this->backupDir);
        if ($status) {
            return back()->with([
                'status' => 'success',
                'message' => 'Backup directory removed!'
            ]);
        }
        return back()->with([
            'status' => 'fail',
            'message' => 'An unknown error occurred'
        ]);
    }

    public function siteSettings() {
        return view('manageSite');
    }

    public function executeArtisanCommand(Request $request, $command) {
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
