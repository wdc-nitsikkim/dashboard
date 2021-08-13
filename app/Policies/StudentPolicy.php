<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use App\CustomHelper;

class StudentPolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'office', 'ecell', 'tnp', 'hod', 'faculty', 'staff'];
    protected $create_roles = ['admin', 'hod'];
    protected $update_roles = ['admin', 'hod'];
    protected $delete_roles = ['admin'];

    public function __construct() {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     * @param array $student  Single student model converted to array
     */

    public function view(User $user) {
        return $user->isPermissionValid($this->view_roles, $this->permission['read']);
    }

    public function create(User $user, Department $department) {
        return $user->isPermissionValid($this->create_roles, $this->permission['create'])
            && $user->hasDepartmentAccess($department->id);
    }

    public function update(User $user, $student) {
        return $user->isPermissionValid($this->update_roles, $this->permission['update'])
            && $user->hasDepartmentAccess($student['department_id']);
    }

    public function delete(User $user, $student) {
        return $user->isPermissionValid($this->delete_roles, $this->permission['delete'])
            && $user->hasDepartmentAccess($student['department_id']);
    }

    public function updateDepartment(User $user) {
        return $user->hasRole('admin');
    }
}
