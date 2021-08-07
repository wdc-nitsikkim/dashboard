<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;

/**
 * Class contaning pre-defined functions to store files at proper locations
 */
trait StoreFiles {
    protected $notificationBasePath = 'files/homepage';
    protected $profileImageBasePath = 'images/profiles';

    /**
     * Handles homepage notification attachments (always saved as public files)
     *
     * @param Illuminate\Http\UploadedFile $file  File to be saved
     * @param string $type  (optional)
     * @param boolean $absolute  (optional) Whether to return absolute path
     * @return string  Path of saved file
     */
    public function storeNotification($file, $type = 'others', bool $absolute = true) {
        $fileName = CustomHelper::formatFileName($file->getClientOriginalName());
        $fileName .= '.' . $file->extension();
        $storagePath = $this->notificationBasePath . '/' . $type;
        $path = $file->storeAs($storagePath, $fileName, 'public');

        return $absolute ? asset(Storage::url($path)) : Storage::url($path);
    }

    /**
     * Handles uploading of profile images
     *
     * @param Illuminate\Http\UploadedFile $image  Image to be uploaded
     * @param int $profile_id
     * @return string  Path of saved image
     */
    public function storeProfileImage($image, $profile_id) {
        $fileName = $profile_id . '.' . $image->extension();
        $storagePath = $this->profileImageBasePath;
        $path = $image->storeAs($storagePath, $fileName, 'public');

        return $path;
    }

    /**
     * Remove unused profile image
     *
     * @param $path  Public path of image to be removed
     * @return void
     */
    public function removeProfileImage($path) {
        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}
