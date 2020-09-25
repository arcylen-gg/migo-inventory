<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use DB;

class Uniqueonshop implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $table;
    public $shop_id;

    public function __construct($table, $shop_id)
    {
        $this->table = $table;
        $this->shop_id = $shop_id;
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
        $check_exist = DB::table($this->table)->where("shop_id", $this->shop_id)->where($attribute, $value)->first();
        
        if($check_exist)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute you entered was already used.';
    }
}
