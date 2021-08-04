<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserProfileLink;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Profile extends Model {
    protected $table = 'profiles';

    /**
     * Defines one-to-one relationship
     */
    public function userLink() {
        return $this->hasOne(UserProfileLink::class, 'user_id');
    }
}
