<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AccountPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || mb_strlen($value) < 12 || strlen($value) > 72 || ! preg_match('/\pL/u', $value) || ! preg_match('/\pN/u', $value)) {
            $fail('Use at least 12 characters with a letter and a number, and no more than 72 bytes.');
        }
    }
}
