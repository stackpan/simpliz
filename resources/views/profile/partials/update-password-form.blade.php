<section>
    <header>
        <h2 class="text-lg font-medium text-primary-content/90">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-primary-content/80">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input.label for="current_password" :text="__('Current Password')" />
            <x-input.text id="current_password" name="current_password" type="password" class="w-full" autocomplete="current-password" />
            <x-input.error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input.label for="password" :text="__('New Password')" />
            <x-input.text id="password" name="password" type="password" class="w-full" autocomplete="new-password" />
            <x-input.error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input.label for="password_confirmation" :text="__('Confirm Password')" />
            <x-input.text id="password_confirmation" name="password_confirmation" type="password" class="w-full" autocomplete="new-password" />
            <x-input.error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-button.primary>{{ __('Save') }}</x-button.primary>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
