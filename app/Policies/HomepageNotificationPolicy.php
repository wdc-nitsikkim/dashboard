<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\CustomHelper;

class HomepageNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'office', 'ecell', 'tnp', 'hod', 'faculty', 'staff'];
    protected $create_roles = ['admin', 'office'];
    protected $update_roles = ['admin', 'office'];
    protected $delete_roles = ['admin'];

    public function __construct()
    {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    public function view(User $user)
    {
        return $user->isPermissionValid($this->view_roles, $this->permission['read']);
    }

    public function create(User $user)
    {
        return $user->isPermissionValid($this->create_roles, $this->permission['create']);
    }

    public function update(User $user)
    {
        return $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }

    public function delete(User $user)
    {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete']);
    }
}
