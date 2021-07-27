<?php

namespace App\Policies\Homepage;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\CustomHelper;
use App\Models\HomepageNotification;

class NotificationPolicy {
    use HandlesAuthorization;

    /* TODO: to be made dynamic */
    protected $view_roles = ['admin', 'office', 'hod', 'ecell', 'faculty'];
    protected $create_roles = ['admin', 'office'];
    protected $update_roles = ['admin', 'office'];
    protected $delete_roles = ['admin'];

    public function __construct() {
        $this->permission = CustomHelper::get_permission_constants();
    }

    public function view(User $user) {
        $user_permissions = CustomHelper::array_val_from_rows($user->permissions->toArray(), 'permission');
        return \in_array($user->role, $this->view_roles)
            && \in_array($this->permission['read'], $user_permissions);
    }

    public function create(User $user) {
        $user_permissions = CustomHelper::array_val_from_rows($user->permissions->toArray(), 'permission');
        return \in_array($user->role, $this->create_roles)
            && \in_array($this->permission['write'], $user_permissions);
    }

    public function update(User $user) {
        $user_permissions = CustomHelper::array_val_from_rows($user->permissions->toArray(), 'permission');
        return \in_array($user->role, $this->update_roles)
            && \in_array($this->permission['write'], $user_permissions);
    }

    public function delete(User $user) {
        $user_permissions = CustomHelper::array_val_from_rows($user->permissions->toArray(), 'permission');
        return \in_array($user->role, $this->delete_roles)
            && \in_array($this->permission['delete'], $user_permissions);
    }
}
