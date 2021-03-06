<?php

namespace App\Traits;

use File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\CustomHelper;

/**
 * Class contaning pre-defined functions to store files at proper locations
 */
trait StoreFiles
{
    protected $notificationBasePath = 'files/homepage';
    protected $profileImageBasePath = 'images/profiles';
    protected $userImageBasePath = 'images/users';
    protected $studentsFileBasePath = 'students';
    protected $backupDir = 'backup';
    protected $privateDir = 'private';

    /**
     * Handles homepage notification attachments (always saved as public files)
     *
     * @param Illuminate\Http\UploadedFile $file  File to be saved
     * @param string $type  (optional)
     * @param boolean $absolute  (optional) Whether to return absolute path
     * @return string  Path of saved file
     */
    public function storeNotification($file, $type = 'others', bool $absolute = true)
    {
        $fileName = CustomHelper::formatFileName($file->getClientOriginalName());
        $fileName .= '.' . $file->extension();
        $storagePath = $this->notificationBasePath . '/' . $type;
        $path = $file->storeAs($storagePath, $fileName, 'public');

        return $absolute ? asset(Storage::url($path)) : Storage::url($path);
    }

    /**
     * Handles uploading of profile images
     *
     * @param Illuminate\Http\UploadedFile $image  Image to be saved
     * @param int $profile_id
     * @return string  Path of saved image
     */
    public function storeProfileImage($image, $profile_id)
    {
        $fileName = $profile_id . '.' . $image->extension();
        $storagePath = $this->profileImageBasePath;
        $path = $image->storeAs($storagePath, $fileName, 'public');

        return $path;
    }

    /**
     * Handles uploading of user images
     *
     * @param Illuminate\Http\UploadedFile $image  Image to be saved
     * @param int $user_id
     * @return string  Path of saved image
     */
    public function storeUserImage($image, $user_id)
    {
        $fileName = $user_id . '.' . $image->extension();
        $storagePath = $this->userImageBasePath;
        $path = $image->storeAs($storagePath, $fileName, 'public');

        return $path;
    }

    /**
     * Handles uploading of students_info files (always stored as private files)
     *
     * @param Illuminate\Http\UploadedFile $file  File to be saved
     * @param string $type
     * @param \App\Models\Student $student  **with nested relations
     * @return string  relative path of stored file
     */
    public function storeStudentFile($file, $type, $student)
    {
        $types = [ 'image', 'sign', 'resume' ];
        if (! in_array($type, $types)) {
            $type = 'file';
        }

        $fileName = $student->roll_number . '-' . $type . '.' . $file->extension();
        $storagePath = $this->privateDir . '/' . $this->studentsFileBasePath . '/'
            . $student->batch->code . '/' . $student->department->code . '/'
            . $student->roll_number;
        $path = $file->storeAs($storagePath, $fileName, 'local');

        return $path;
    }

    /**
     * Remove unused profile image
     *
     * @param $path  Public path of image to be removed
     * @return boolean
     */
    public function removeUploadedImage($path)
    {
        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove unused files
     *
     * @param $path  Private path of file to be removed
     * @return boolean
     */
    public function removePrivateFile($path)
    {
        try {
            return Storage::disk('local')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Permanently delete a directory
     *
     * @param string $path  Relative to 'local' disk
     */
    public function removeDirectory($path)
    {
        try {
            return Storage::disk('local')->deleteDirectory($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create backup directory if not exists
     */
    public function createBackupDir()
    {
        if (! File::isDirectory(config('filesystems.disks.local.root')
            . '/' . $this->backupDir)) {
            Storage::disk('local')->makeDirectory($this->backupDir);
        }
    }

    /**
     * Move file from absolute path to local disk
     *
     * @param string $absolutePath  Absolute path offile to be moved
     * @param string $fileName  Name of file in the local disk
     * @param boolean $remove  (optional) Whether to delete original file if error occurs
     */
    public function moveFileToLocalDisk($absolutePath, $fileName, $remove = true)
    {
        $relativePath = $this->backupDir . '/' . $fileName;
        $dest = config('filesystems.disks.local.root') . '/'
            . $relativePath;

        try {
            File::move($absolutePath, $dest);
            return $dest;
        } catch (\Exception $e) {
            $remove ? File::delete($absolutePath) : false;
            return false;
        }
    }
}
