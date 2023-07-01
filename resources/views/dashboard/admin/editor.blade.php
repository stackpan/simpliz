<x-dashboard-layout>
    <x-slot name="breadcrumb">
        <ol>
            <li><a href="{{ route('dashboard.admin') }}">{{ __('Admin Management') }}</a></li>
            <li>{{ __('Admin Editor') }}</li>
        </ol>
    </x-slot>

    <form 
        @isset($user)
        action="{{ route('admins.update', $user) }}"
        @else
        action="{{ route('admins.store') }}" 
        @endisset
        method="post" class="mx-8 py-8 flex flex-col min-h-full justify-between">
    @csrf
    @isset($user)
        @method('PUT')
        <input type="hidden" name="id" value="{{ $user->id }}" />
    @endisset

        <div class="flex flex-col gap-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                @isset($user)
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$user->name" required autofocus autocomplete="username" />
                @else
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" />
                @endisset
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                @isset($user)
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$user->email" required autofocus autocomplete="username" />
                @else
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                @endisset
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                @isset($user)
                <x-input-select name="gender" id="gender" class="block mt-1 w-full" :options="App\Enums\Gender::cases()" :selected="$user->gender" required autofocus autocomplete="gender"/>
                @else
                <x-input-select name="gender" id="gender" class="block mt-1 w-full" :options="App\Enums\Gender::cases()" required autofocus autocomplete="gender"/>
                @endisset
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            @if(!isset($user))
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
            @endif

            @if (session('status'))
            <div>
                {{ session('status') }}
            </div>
        @endif
        </div>

        <div>
            <x-button-primary type="submit" class="mr-4">{{ __('Save') }}</x-button-primary>
            <a href="{{ route('dashboard.admin') }}">
                <x-button-secondary type="button">{{ __('Cancel') }}</x-button-secondary>
            </a>
        </div>
    </form>
</x-dashboard-layout>