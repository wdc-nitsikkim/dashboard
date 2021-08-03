<?php

namespace App\Traits;

/**
 * Mutator functions to be applied on common attributes
 */
trait GlobalMutators {
    /**
     * Model mutator
     * Set name
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords($value);
    }

    /**
     * Set email
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value) {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Model mutator
     * Set code
     *
     * @param string $value
     * @return void
     */
    public function setCodeAttribute($value) {
        $this->attributes['code'] = strtolower($value);
    }
}
