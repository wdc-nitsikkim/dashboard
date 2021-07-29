<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Department;
use App\Models\Batch;

class Student extends Model {
    use softDeletes;

    protected $table = 'students';
    protected $dates = ['deleted_at'];

    /**
     * Defines many-to-one relationship
     */
    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function batch() {
        return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }
}
