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

    public function view_sem_wise(User $user) {
        return $user->hasRole('admin', 'ecell')
            && $user->isPermissionValid($this->update_roles, $this->permission['read']);
    }

    public function update(User $user, Subject $subject) {
        $resultSetting = CustomHelper::getSiteSetting('resultMod');

        /* Check if updating of result is enabled (in database) */
        if ($resultSetting == null || (int)$resultSetting != 1) {
            return false;
        }

        $permission = $user->isPermissionValid($this->update_roles, $this->permission['update']);

        return $permission && ($user->hasRole('admin') || $user->hasSubjectAccess($subject->id));
    }
}
