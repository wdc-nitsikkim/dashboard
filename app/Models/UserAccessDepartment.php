<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Department;

class UserAccessDepartment extends Model {
    protected $table = 'user_access_departments';

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }
}
