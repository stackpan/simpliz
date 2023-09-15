<x-guest-layout>
    <div class="mb-4 text-sm text-primary-content">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-control">
            <x-input.label for="password" :text="__('Password')"/>
            <x-input.text id="password" class="mt-2"
                          type="password"
                          name="password"
                          required autocomplete="current-password"/>
            <x-input.error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-8">
            <x-button.primary>{{ __('Confirm') }}</x-button.primary>
        </div>
    </form>
</x-guest-layout>
