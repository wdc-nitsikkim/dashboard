<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Subject;
use App\Traits\GlobalAccessors;

class UserAccessSubject extends Model {
    use GlobalAccessors;

    protected $table = 'user_access_subjects';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['user_id', 'subject_id', 'created_at'];

    /**
     * Defines many-to-one relationship
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id')->withDefault();
    }
}
