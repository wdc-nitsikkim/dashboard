<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\CustomHelper;

class UserPolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin'];
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

    public function view_account(User $user, $id) {
        $isAllowed = false;
        if ($user->id === $id) {
            $isAllowed = true;
        }
        if ($user->isPermissionValid($this->view_roles, $this->permission['read'])) {
            $isAllowed = true;
        }
        return $isAllowed;
    }

    public function update(User $user, User $curr) {
        $isAllowed = false;

        if ($user->id === $curr->id) {
            $isAllowed = true;
        }
        if ($user->isPermissionValid($this->update_roles, $this->permission['update'])
            && !$curr->hasRole('root', 'admin')) {

            $isAllowed = true;
        }

        return $isAllowed;
    }

    public function manage(User $user, User $curr) {
        return $user->isPermissionValid($this->update_roles, $this->permission['update'])
            && !$curr->hasRole('root', 'admin');
    }

    public function delete(User $user, User $curr) {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete'])
            && !$curr->hasRole('root', 'admin');
    }

    public function manage_site(User $user) {
        return false;
    }
}
