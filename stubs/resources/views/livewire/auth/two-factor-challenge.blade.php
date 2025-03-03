<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __(
            $recovery
                ? 'Please confirm access to your account by entering one of your emergency recovery codes.'
                : 'Please confirm access to your account by entering the authentication code provided by your authenticator application.',
        ) }}
    </div>

    <form wire:submit.prevent="verify">
        <fieldset wire:loading.attr="disabled" wire:target="verify">
            @if ($recovery)
                <div class="mt-4">
                    <x-input-label for="recovery_code" value="{{ __('Recovery Code') }}" />
                    <x-text-input id="recovery_code" class="block w-full mt-1" wire:model="form.recovery_code"
                        type="text" name="recovery_code" autofocus autocomplete="one-time-code" />
                    <x-text-input-error :messages="$errors->get('form.recovery_code')" class="mt-2" />
                </div>
            @else
                <div class="mt-4">
                    <x-input-label for="code" value="{{ __('Code') }}" />
                    <x-text-input id="code" class="block w-full mt-1" wire:model="form.code" inputmode="numeric"
                        type="text" name="code" autofocus autocomplete="one-time-code" />
                    <x-text-input-error :messages="$errors->get('form.code')" class="mt-2" />
                </div>
            @endif
            <div class="flex items-center justify-end mt-4">
                <button type="button"
                    class="text-sm text-gray-600 underline cursor-pointer dark:text-gray-400 hover:text-gray-900"
                    wire:click.prevent="toggleRecovery">
                    {{ __($recovery ? 'Use an authentication code' : 'Use a recovery code') }}
                </button>
                <x-primary-button class="ms-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </fieldset>
    </form>
</div>
