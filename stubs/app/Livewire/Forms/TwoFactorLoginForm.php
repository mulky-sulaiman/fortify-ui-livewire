<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Attributes\Validate;
// use Illuminate\Contracts\Auth\StatefulGuard;
// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Exceptions\HttpResponseException;
// use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Livewire\Form;

class TwoFactorLoginForm extends Form
{
    #[Validate('nullable|string')]
    public string $code = '';

    #[Validate('nullable|string')]
    public string $recovery_code = '';

    /**
     * The user attempting the two factor challenge.
     *
     * @var mixed
     */
    protected $challengedUser;

    /**
     * Indicates if the user wished to be remembered after login.
     *
     * @var bool
     */
    protected $remember;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Determine if the request has a valid two factor code.
     *
     * @return bool
     */
    public function hasValidCode()
    {
        return $this->code && tap(app(TwoFactorAuthenticationProvider::class)->verify(
            decrypt($this->challengedUser()->two_factor_secret), $this->code
        ), function ($result) {
            if ($result) {
                session()->forget('login.id');
            }
        });
    }

    /**
     * Get the valid recovery code if one exists on the request.
     *
     * @return string|null
     */
    public function validRecoveryCode()
    {
        if (! $this->recovery_code) {
            return;
        }

        return tap(collect($this->challengedUser()->recoveryCodes())->first(function ($code) {
            return hash_equals($code, $this->recovery_code) ? $code : null;
        }), function ($code) {
            if ($code) {
                session()->forget('login.id');
            }
        });
    }

    /**
     * Determine if there is a challenged user in the current session.
     *
     * @return bool
     */
    public function hasChallengedUser()
    {
        // $this->validate();

        if ($this->challengedUser) {
            return true;
        }

        // $model = app(StatefulGuard::class)->getProvider()->getModel();
        $model = User::class;

        return session()->has('login.id') &&
            $model::find(session()->get('login.id'));
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @return mixed
     */
    public function challengedUser()
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        // $model = app(StatefulGuard::class)->getProvider()->getModel();
        $model = User::class;

        if (! session()->has('login.id') || ! $user = $model::find(session()->get('login.id'))) {
            // throw new HttpResponseException(
            //     app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            // );
            throw ValidationException::withMessages([
                'code' => __('No valid session found.'),
            ]);
        }

        return $this->challengedUser = $user;
    }

    /**
     * Determine if the user wanted to be remembered after login.
     *
     * @return bool
     */
    public function remember()
    {
        if (! $this->remember) {
            $this->remember = session()->pull('login.remember', false);
        }

        return $this->remember;
    }
}
