<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class Department extends Model {
    protected $table = 'departments';

    /**
     * Match route parameter of this model to specified string
     * instead of default 'id'
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'code';
    }

    /**
     * Defines one-to-many relationship
     */
    public function students() {
        return $this->hasMany(Student::class, 'department_id');
    }
}
