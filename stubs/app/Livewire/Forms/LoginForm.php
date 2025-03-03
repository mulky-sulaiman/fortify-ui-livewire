<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\Features;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->email)
            ->first();

        if ($user) {
            if (Hash::check($this->password, $user->password)) {
                if (Features::enabled(Features::twoFactorAuthentication())) {
                    if ($user->hasEnabledTwoFactorAuthentication()) {
                        session()->put('login.id', $user->id);
                        session()->put('login.remember', $this->remember);
                        TwoFactorAuthenticationChallenged::dispatch($user);

                        return false;
                    } else {
                        Auth::login($user, $this->remember);
                        RateLimiter::clear($this->throttleKey());
                    }
                } else {
                    Auth::login($user, $this->remember);
                    RateLimiter::clear($this->throttleKey());
                }

            } else {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'form.email' => trans('auth.failed'),
                ]);
            }
        } else {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
