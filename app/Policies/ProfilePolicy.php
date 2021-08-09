<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\CustomHelper;
use App\Models\Profile;
use App\Models\UserProfileLink;

class ProfilePolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'office', 'ecell', 'tnp', 'hod', 'faculty', 'staff'];
    protected $create_roles = ['admin'];
    protected $update_roles = ['admin'];
    protected $delete_roles = ['admin'];

    public function __construct() {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     */
    public function view(User $user) {
        return $user->isPermissionValid($this->view_roles, $this->permission['read']);
    }

    /**
     * Whether user is authorized to create profiles
     * Users with roles 'hod', 'faculty', 'staff' can create a single profile only
     *
     * @param App\Models\User $user
     * @return boolean
     */
    public function create(User $user) {
        $isAllowed = false;
        if ($user->hasRole('hod', 'faculty', 'staff')) {
            $isAllowed = is_null($user->profileLink);
        }
        if ($user->isPermissionValid($this->create_roles, $this->permission['create'])) {
            $isAllowed = true;
        }
        return $isAllowed;
    }

    /**
     * Whether user is authorized to update profiles
     * Users with roles 'hod', 'faculty', 'staff' can only update thier own profiles
     *
     * @param App\Models\User $user
     * @param int $profile_id
     * @return boolean
     */
    public function update(User $user, $profile_id) {
        $isAllowed = false;
        if ($user->hasRole('hod', 'faculty', 'staff') && $user->hasProfile()) {
            $isAllowed = $user->profileLink->profile_id === $profile_id;
        } else if ($user->hasProfile()) {
            $isAllowed = $user->profileLink->profile_id === $profile_id;
        }
        if ($user->isPermissionValid($this->update_roles, $this->permission['update'])) {
            $isAllowed = true;
        }

        return $isAllowed;
    }

    public function delete(User $user, $profile_id) {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete']);
    }

    public function chooseType(User $user) {
        return $user->hasRole('admin');
    }

    public function customizeLinkOption(User $user) {
        return $user->hasRole('admin') &&
            $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }

    public function updateDepartment(User $user) {
        return $user->hasRole('admin') &&
            $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }
}
