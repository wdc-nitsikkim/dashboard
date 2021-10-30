<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Department;
use App\Traits\GlobalAccessors;

class UserAccessDepartment extends Model
{
    use GlobalAccessors;

    protected $table = 'user_access_departments';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'department_id', 'created_at'];

    /**
     * Defines many-to-one relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }
}
