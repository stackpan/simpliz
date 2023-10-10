<x-layouts.manager>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('manager.user') }}">{{ __('User') }}</a></li>
        <li>{{ $meta['title'] }}</li>
    </x-slot>

    <article>
        <section>
            <form action="{{ $href['store'] }}" method="post" class="space-y-8">
                @csrf
                @if ($meta['title'] === 'Editor')
                    @method('PUT')
                @endif

                <div class="space-y-4">

                    <!-- Name -->
                    <div class="form-control max-w-md">
                        <x-input.label for="name" :text="__('Name')" />
                        <x-input.text id="name" type="text" name="name" :value="$user->name ?? old('name')" required autofocus
                            autocomplete="name" />
                        <x-input.error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="form-control max-w-md">
                        <x-input.label for="email" :text="__('Email')" />
                        <x-input.text id="email" type="email" name="email" :value="$user->email ?? old('email')" required
                            autocomplete="username" />
                        <x-input.error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Gender -->
                    <div class="form-control max-w-md">
                        <x-input.label for="gender" :text="__('Gender')" />
                        <x-input.select name="gender" id="gender" :options="App\Enums\UserGender::cases()" :value="$user->gender->value ?? old('gender')" required
                            autocomplete="gender" />
                        <x-input.error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div class="max-w-2xl flex gap-6">

                        <!-- Password -->
                        <div class="form-control flex-1">
                            <x-input.label for="password" :text="__('Password')" />
                            @if ($meta['title'] === 'Create')
                                <x-input.text id="password" type="password" name="password" required
                                    autocomplete="new-password" />
                            @else
                                <x-input.text id="password" type="password" name="password"
                                    autocomplete="new-password" />
                            @endif
                            <x-input.error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-control flex-1">
                            <x-input.label for="password_confirmation" :text="__('Confirm Password')" />
                            @if ($meta['title'] === 'Create')
                                <x-input.text id="password_confirmation" type="password" name="password_confirmation"
                                    required autocomplete="new-password" />
                            @else
                                <x-input.text id="password_confirmation" type="password" name="password_confirmation"
                                    autocomplete="new-password" />
                            @endif
                            <x-input.error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                    </div>

                </div>

                <div>
                    <x-button.primary type="submit">{{ __('Save') }}</x-button.primary>
                </div>

            </form>
        </section>
    </article>
</x-layouts.manager>
