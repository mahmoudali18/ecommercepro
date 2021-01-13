<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductQty implements Rule
{
    private $manage_stock;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($manage_stock)
    {
        $this->manage_stock = $manage_stock;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if($this -> manage_stock == 1 &&  $value == null )
            return false;  //that is means the validation is failed
        else
            return true;  //that is means the validation is success
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The qty must have value when mange stock equal 1';
    }
}
