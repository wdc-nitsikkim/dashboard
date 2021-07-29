<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class Batch extends Model {
    protected $table = 'batches';

    /* match route parameter to this value instead of 'id' */
    public function getRouteKeyName() {
        return 'batch';
    }

    public function students() {
        return $this->hasMany(Student::class, 'batch_id');
    }
}
