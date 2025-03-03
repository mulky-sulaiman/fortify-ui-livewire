<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\TwoFactorLoginForm;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public TwoFactorLoginForm $form;

    public ?bool $recovery = false;

    public string $mode;

    public function mount()
    {
        if (! $this->form->hasChallengedUser()) {
            return $this->redirectRoute('login', navigate: true);
        }
    }

    public function verify()
    {
        $user = $this->form->challengedUser();

        if ($code = $this->form->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $this->form->hasValidCode()) {
            event(new TwoFactorAuthenticationFailed($user));

            [$key, $message] = ! empty($this->form->recovery_code) ? ['form.recovery_code', __('The provided two factor recovery code was invalid.')] : ['form.code', __('The provided two factor authentication code was invalid.')];

            throw ValidationException::withMessages([
                $key => [$message],
            ]);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        auth()->login($user, $this->form->remember());

        session()->regenerate();

        return $this->redirectRoute('dashboard', navigate: true);
    }

    public function toggleRecovery()
    {
        $this->recovery = ! $this->recovery;
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}
