<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        $validator = Validator::make($input, [
            'password' => $this->passwordRules(),
        ], [
            'password.regex' => 'The password must include at least 8 characters long, one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);
        $validator->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
