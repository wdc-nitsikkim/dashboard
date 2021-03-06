<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentInfo;
use App\CustomHelper;

class StudentInfoPolicy
{
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'office', 'tnp'];
    protected $create_roles = ['admin', 'office'];
    protected $update_roles = ['admin'];
    protected $delete_roles = ['admin'];

    public function __construct()
    {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    /**
     * @param App\Models\User $user
     * @param App\Models\Student $student
     */

    public function view(User $user, Student $student, StudentInfo $info = null)
    {
        $isAllowed = false;

        if ($info == null) {
            return false;
        }
        if ($info->deleted_at != null && ! $user->hasRole('admin', 'student')) {
            return false;
        }
        if ($user->hasRole('student') && ($student->email ?? false) === $user->email) {
            $isAllowed = $user->isPermissionValid(['student'], $this->permission['read']);
        }

        return $isAllowed || $user->isPermissionValid($this->view_roles, $this->permission['read']);
    }

    public function view_result(User $user, Student $student)
    {
        $isAllowed = false;

        if ($user->hasRole('student') && ($student->email ?? false) === $user->email) {
            $isAllowed = $user->isPermissionValid(['student'], $this->permission['read']);
        }

        return $isAllowed || $user->isPermissionValid(['admin'], $this->permission['read']);
    }

    public function create(User $user, Student $student)
    {
        $isAllowed = false;
        if ($user->hasRole('student') && $user->email == $student->email) {
            $isAllowed = ($student->info == null)
                && $user->isPermissionValid(['student'], $this->permission['create']);
        }

        return $isAllowed || $user->isPermissionValid($this->create_roles, $this->permission['create']);
    }

    public function update(User $user, Student $student, StudentInfo $info = null)
    {
        $isAllowed = false;

        if ($info == null) {
            return false;
        }
        if ($user->hasRole('student') && $user->email == $student->email) {
            $isAllowed = ($student->id == $info->student_id)
                && $user->isPermissionValid(['student'], $this->permission['update']);
        }

        return $isAllowed || $user->isPermissionValid($this->update_roles, $this->permission['update']);
    }

    public function delete(User $user, Student $student, StudentInfo $info)
    {
        $isAllowed = false;
        if ($user->hasRole('student') && $user->email == $student->email) {
            $isAllowed = ($student->id == $info->student_id)
                && $user->isPermissionValid(['student'], $this->permission['delete']);
        }

        return $isAllowed || $user->isPermissionValid($this->delete_roles, $this->permission['delete']);
    }
}
