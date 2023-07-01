<x-dashboard-layout>
    <x-slot name="breadcrumb">
        <ol>
            <li><a href="{{ route('dashboard.admin') }}">{{ __('Admin Management') }}</a></li>
            <li>{{ __('Admin Editor') }}</li>
        </ol>
    </x-slot>

    <form action="{{ route('admins.store') }}" method="post" class="mx-8 py-8 flex flex-col min-h-full justify-between">
    @csrf
        <div class="flex flex-col gap-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <x-input-select name="gender" id="gender" class="block mt-1 w-full" :options="App\Enums\Gender::cases()" required autofocus autocomplete="gender"/>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <div class="flex w-full gap-4">
                <!-- Password -->
                <div class="flex-1">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Password Confirmation -->
                <div class="flex-1">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </div>

        <div>
            <x-button-primary type="submit" class="mr-4">{{ __('Save') }}</x-button-primary>
            <a href="{{ route('dashboard.admin') }}">
                <x-button-secondary type="button">{{ __('Cancel') }}</x-button-secondary>
            </a>
        </div>
    </form>
</x-dashboard-layout>