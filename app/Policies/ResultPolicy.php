<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Subject;
use App\CustomHelper;

class ResultPolicy {
    use HandlesAuthorization;

    /**
     * Valid role list for specified access
     *
     * @var array
     */
    protected $view_roles = ['admin', 'ecell', 'hod', 'faculty', 'staff'];
    protected $update_roles = ['admin', 'hod', 'faculty', 'staff'];

    public function __construct() {
        $this->permission = CustomHelper::getPermissionConstants();
    }

    public function view(User $user, Subject $subject) {
        $permission = $user->isPermissionValid($this->update_roles, $this->permission['read']);

        return $permission && ($user->hasRole('admin', 'ecell') || $user->hasSubjectAccess($subject->id));
    }

    public function update(User $user, Subject $subject) {
        $permission = $user->isPermissionValid($this->update_roles, $this->permission['update']);

        return $permission && ($user->hasRole('admin') || $user->hasSubjectAccess($subject->id));
    }
}
