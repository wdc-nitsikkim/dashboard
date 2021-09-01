<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Department;
use App\CustomHelper;

class SubjectPolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $create_roles = ['admin', 'office', 'hod'];

    public function __construct() {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     */

    public function view(User $user) {
        return true;
    }

    public function create(User $user, Department $department) {
        return $user->isPermissionValid($this->create_roles, $this->permission['create'])
            && ($user->hasRole('admin') || $user->hasDepartmentAccess($department->id));
    }

    public function update(User $user, $student) {
        return false;
    }

    public function delete(User $user, $student) {
        return false;
    }
}
