<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-4">
            <!-- Name -->
            <div class="form-control">
                <x-input.label for="name" :text="__('Name')"/>
                <x-input.text id="name" type="text" name="name" :value="old('name')" required
                              autofocus autocomplete="name"/>
                <x-input.error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <!-- Email Address -->
            <div class="form-control">
                <x-input.label for="email" :text="__('Email')"/>
                <x-input.text id="email" type="email" name="email" :value="old('email')" required
                              autocomplete="username"/>
                <x-input.error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Gender -->
            <div class="form-control">
                <x-input.label for="gender" :text="__('Gender')"/>
                <x-input.select name="gender" id="gender" :options="App\Enums\UserGender::cases()"
                                required autocomplete="gender"/>
                <x-input.error :messages="$errors->get('gender')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="form-control">
                <x-input.label for="password" :text="__('Password')"/>
                <x-input.text id="password"
                              type="password"
                              name="password"
                              required autocomplete="new-password"/>
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

        <div class="flex items-center justify-end mt-8">
            <a href="{{ route('login') }}">
                <x-button.link type="button">{{ __('Already registered?') }}</x-button.link>
            </a>

            <x-button.primary type="submit">{{ __('Register') }}</x-button.primary>
        </div>
    </form>
</x-guest-layout>
