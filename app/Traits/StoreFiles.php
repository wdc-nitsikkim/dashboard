<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;

/**
 * Class contaning pre-defined functions to store files at proper locations
 */
trait StoreFiles {
    protected $notificationBasePath = 'files/homepage/';

    /**
     * Handles homepage notification attachments (always saved as public files)
     *
     * @param Illuminate\Http\UploadedFile $file  File to be saved
     * @param string $type  (optional)
     * @param boolean $absolute  (optional) Whether to return absolute path
     * @return string  File path of saved file
     */
    public function storeNotification($file, $type = 'others', bool $absolute = true) {
        $file = request()->file('attachment');
        $fileName = CustomHelper::formatFileName($file->getClientOriginalName());
        $fileName .= '.' . $file->extension();
        $storagePath = $this->notificationBasePath . $type;
        $path = $file->storeAs($storagePath, $fileName, 'public');

        return $absolute ? asset(Storage::url($path)) : Storage::url($path);
    }
}
