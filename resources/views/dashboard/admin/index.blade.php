<x-dashboard-layout>
    @php
    $users = App\Models\User::select('id', 'name', 'email')
        ->where('role', App\Enums\Role::Admin->value)
        ->get()
        ->toArray();
    @endphp

    <x-slot name="breadcrumb">
        <ol>
            <li>{{ __('Admin Management') }}</li>
        </ol>
    </x-slot>
 
    <div class="py-6">
        <div class="text-gray-900 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admins.create') }}">
                    <x-button-primary type="button">{{ __('Add') }}</x-button-primary>
                </a>
            </div>
                <x-table class="table-auto w-full" :headers="['Name', 'Email']" :values="$users" :action="'admins'" />
        </div>
    </div>
</x-dashboard-layout>