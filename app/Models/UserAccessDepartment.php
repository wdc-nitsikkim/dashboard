<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessDepartment extends Model {
    protected $table = 'user_access_departments';

    public function user() {
        return $this->belongsTo(\App\Models\User::class)->withDefault();
    }

    public function department() {
        return $this->belongsTo(\App\Models\Department::class, 'department_id')->withDefault();
    }
}