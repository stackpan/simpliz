<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-2">
            <!-- Email Address -->
            <div class="form-control">
                <x-input.label for="email" :text="__('Email')"/>
                <x-input.text id="email" type="email" name="email" :value="old('email', $request->email)" required
                              autofocus autocomplete="username"/>
                <x-input.error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="form-control">
                <x-input.label for="password" :text="__('Password')"/>
                <x-input.text id="password" type="password" name="password" required autocomplete="new-password"/>
                <x-input.error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Confirm Password -->
            <div class="form-control">
                <x-input.label for="password_confirmation" :text="__('Confirm Password')"/>
                <x-input.text id="password_confirmation"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password"/>
                <x-input.error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>
        </div>

        <div class="flex items-center justify-end mt-12">
            <x-button.primary type="submit" class="btn-block">{{ __('Reset Password') }}</x-button.primary>
        </div>
    </form>
</x-guest-layout>
