<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class Department extends Model {
    protected $table = 'departments';

    /* match route parameter to this value instead of 'id' */
    public function getRouteKeyName() {
        return 'code';
    }

    public function students() {
        return $this->hasMany(Student::class, 'department_id');
    }
}
