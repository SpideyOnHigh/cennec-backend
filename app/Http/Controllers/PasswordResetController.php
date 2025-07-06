<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;

        $isValidToken = $this->isTokenValid($email, $token);
        if (!$isValidToken) {
            return view('auth.reset-password', [
                'token' => $token,
                'email' => $email,
                'request' => $request,
                'error' => 'The password reset link you clicked has expired. This is to ensure the security of your account. To reset your password now, please click "Request a New Link" below.'
            ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
            'request' => $request,
            'error' => null
        ]);
    }

    private function isTokenValid($email, $token)
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if ($record) {
            $isTokenValid = password_verify($token, $record->token);
            $expiresAt = Carbon::now()->subMinutes(config('auth.passwords.users.expire'));
            return $isTokenValid && Carbon::parse($record->created_at)->greaterThan($expiresAt);
        }

        return false;
    }


    public function reset(Request $request)
    {
        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = bcrypt($password);
            $user->setRememberToken(Str::random(60));
            $user->save();
        });

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __('Your password has been reset!'));
        }

        return back()->withErrors(['email' => __($response)]);
    }
}
