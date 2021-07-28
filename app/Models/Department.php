<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {
    protected $table = 'departments';

    /* match route parameter to this value instead of 'id' */
    public function getRouteKeyName() {
        return 'code';
    }
}
