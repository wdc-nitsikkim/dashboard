<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Profile;
use App\Models\UserProfileLink;
use App\CustomHelper;

class ProfilePolicy
{
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
    protected $special_roles = ['hod', 'faculty', 'staff'];

    public function __construct()
    {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     */
    public function view(User $user)
    {
        return $user->isPermissionValid($this->view_roles, $this->permission['read']);
    }

    /**
     * Whether user is authorized to create profiles
     * Users with roles 'hod', 'faculty', 'staff' can create a single profile only
     *
     * @param App\Models\User $user
     * @return boolean
     */
    public function create(User $user)
    {
        $isAllowed = false;
        if ($user->hasRole('hod', 'faculty', 'staff')) {
            $isAllowed = is_null($user->profileLink)
                && $user->isPermissionValid($this->special_roles, $this->permission['create']);
        }

        return $isAllowed || $user->isPermissionValid($this->create_roles, $this->permission['create']);
    }

    /**
     * Whether user is authorized to update profiles
     * Users with roles 'hod', 'faculty', 'staff' can only update thier own profiles
     *
     * @param App\Models\User $user
     * @param int $profile_id
     * @return boolean
     */
    public function update(User $user, $profile_id)
    {
        $isAllowed = false;
        if ($user->hasRole('hod', 'faculty', 'staff') && $user->hasProfile()) {
            $isAllowed = $user->profileLink->profile_id === $profile_id;
        } elseif ($user->hasProfile()) {
            $isAllowed = $user->profileLink->profile_id === $profile_id;
        }
        $isAllowed = $isAllowed && $user->isPermissionValid($this->special_roles, $this->permission['update']);

        return $isAllowed || $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }

    public function delete(User $user, $profile_id)
    {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete']);
    }

    public function chooseType(User $user)
    {
        return $user->hasRole('admin');
    }

    public function customizeLinkOption(User $user)
    {
        return $user->hasRole('admin') &&
            $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }

    public function updateDepartment(User $user)
    {
        return $user->hasRole('admin') &&
            $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }
}
