<x-guest-layout>
    <div class="mb-4 text-sm text-primary-content">
        <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-control">
            <x-input.label for="email" :text="__('Email')"/>
            <x-input.text id="email" type="email" name="email" :value="old('email')" required
                          autofocus/>
            <x-input.error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-8">
            <x-button.primary class="btn-block">{{ __('Email Password Reset Link') }}</x-button.primary>
        </div>
    </form>
</x-guest-layout>
