<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\CustomHelper;
use App\Models\Student;
use App\Models\Department;

class StudentPolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'office', 'hod', 'ecell', 'faculty', 'tnp'];
    protected $create_roles = ['admin', 'hod'];
    protected $update_roles = ['admin', 'hod'];
    protected $delete_roles = ['admin'];

    public function __construct() {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     * @param App\Models\Department $department
     */
    public function view(User $user, Department $department) {
        return $user->isPermissionValid($this->view_roles, $this->permission['read'])
            && $user->hasDepartmentAccess($department->id);
    }

    public function create(User $user, Department $department) {
        return $user->isPermissionValid($this->create_roles, $this->permission['create'])
            && $user->hasDepartmentAccess($department->id);
    }

    public function update(User $user, Department $department) {
        return $user->isPermissionValid($this->update_roles, $this->permission['update'])
            && $user->hasDepartmentAccess($department->id);
    }

    public function delete(User $user, Department $department) {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete'])
            && $user->hasDepartmentAccess($department->id);
    }
}
