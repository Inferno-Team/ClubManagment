<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $values;
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */


     // values =  [ [name => mohammed] , [email => e@gmail.com] , [password => 123123] ]
    public function passes($attribute, $value)
    {
        $this->values = $value;
        return in_array('name', array_keys($this->values))
            && in_array('email', array_keys($this->values))
            && in_array('password', array_keys($this->values));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $msg = [];
        if (!in_array('name', array_keys($this->values)))
            array_push($msg, "manager name required");
        if (!in_array('email', array_keys($this->values)))
            array_push($msg, "manager email required");
        if (!in_array('password', array_keys($this->values)))
            array_push($msg, "manager password required");
        return $msg;
    }
}
