<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Features;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->form->validate();

        $authenticated = $this->form->authenticate();

        if (! $authenticated && Features::enabled(Features::twoFactorAuthentication())) {
            return $this->redirectRoute('two-factor.login', navigate: true);
        }

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
