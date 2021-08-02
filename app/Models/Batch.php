<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Student;

class Batch extends Model {
    use softDeletes;

    protected $table = 'batches';

    /**
     * Match route parameter of this model to specified string
     * instead of default 'id'
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'batch';
    }

    /**
     * Defines one-to-many relationship
     */
    public function students() {
        return $this->hasMany(Student::class, 'batch_id');
    }
}
