<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-4">
            <!-- Email Address -->
            <div class="form-control">
                <x-input.label for="email" :text="__('Email')"/>
                <x-input.text id="email" type="email" name="email" :value="old('email')" required autofocus
                              autocomplete="username"/>
                <x-input.error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="form-control">
                <x-input.label for="password" :text="__('Password')"/>
                <x-input.text id="password"
                              type="password"
                              name="password"
                              required autocomplete="current-password"/>
                <x-input.error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Remember Me -->
            <div class="form-control">
                <x-input.label for="remember_me" :text="__('Remember me')" class="cursor-pointer sm:flex-row-reverse sm:justify-end gap-2">
                    <x-input.checkbox id="remember_me" name="remember"/>
                </x-input.label>
            </div>
        </div>

        <div class="flex flex-col items-center mt-8">
            <x-button.primary type="submit" class="btn-block">{{ __('Log in') }}</x-button.primary>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    <x-button.link type="button">{{ __('Forgot your password?') }}</x-button.link>
                </a>
            @endif
        </div>
    </form>

    <x-slot name="footer">
        <a href="{{ route('register') }}">
            <x-button.link type="button">{{ __('Register') }}</x-button.link>
        </a>
    </x-slot>
</x-guest-layout>
