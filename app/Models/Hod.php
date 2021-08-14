<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Profile;
use App\Models\Department;

class Hod extends Model {
    protected $table = 'hods';
    public $timestamps = false;

    /**
     * Attributes that are mass-assignable
     */
    protected $fillable = ['department_id', 'profile_id'];

    /**
     * One-to-one relationship on departments table
     */
    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    /**
     * One-to-one relationship on profiles table
     */
    public function profile() {
        return $this->belongsTo(Profile::class, 'profile_id')->withDefault();
    }
}
