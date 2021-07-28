<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use Auth;
use App\Models\User;
use App\CustomHelper;
use App\Models\Department;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /* TODO: to be made dynamic */
    protected $view_roles = ['admin', 'office', 'hod', 'ecell', 'faculty'];
    protected $create_roles = ['admin'];
    protected $update_roles = ['admin', 'office'];
    protected $delete_roles = ['admin'];

    public function __construct() {
        $this->permission = CustomHelper::get_permission_constants();
        $this->user_permissions = Auth::user()->permissions->pluck('permission')->toArray();
    }

    public function view(User $user) {
        return \in_array($user->role, $this->view_roles)
            && \in_array($this->permission['read'], $this->user_permissions);
    }

    public function create(User $user) {
        return \in_array($user->role, $this->create_roles)
            && \in_array($this->permission['write'], $this->user_permissions);
    }

    public function update(User $user) {
        return \in_array($user->role, $this->update_roles)
            && \in_array($this->permission['write'], $this->user_permissions);
    }

    public function delete(User $user) {
        return \in_array($user->role, $this->delete_roles)
            && \in_array($this->permission['delete'], $this->user_permissions);
    }
}
