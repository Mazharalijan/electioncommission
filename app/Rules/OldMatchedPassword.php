<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class OldMatchedPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the provided value matches the hashed old password in the database
        return Hash::check($value, auth()->user()->password);
    }

    public function message()
    {
        return 'The provided :attribute does not match your current password.';
    }
}
