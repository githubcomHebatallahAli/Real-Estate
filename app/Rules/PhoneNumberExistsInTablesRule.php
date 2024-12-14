<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberExistsInTablesRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('users')->where('phoNum', $value)->exists() ||
        DB::table('admins')->where('phoNum', $value)->exists() ||
        DB::table('owners')->where('phoNum', $value)->exists() ||
        DB::table('brokers')->where('phoNum', $value)->exists();

if ($exists) {
  $fail('The phone number is already registered in another account.');
}
    }
}
