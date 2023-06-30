<x-dashboard-layout>
    @php
    $users = App\Models\User::select('id', 'name', 'email')
        ->where('role', App\Enums\Role::Admin->value)
        ->get()
        ->toArray();
    @endphp

    <header>
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-bold text-3xl text-gray-500 leading-tight border-b-2 border-b-gray-300">
                {{ __('Admin Management') }}
            </h2>
        </div>
    </header>

    <div class="py-6">
        <div class="text-gray-900 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <form action="{{ route('admins.create') }}" method="get">
                    @csrf
                    <x-button :type="'submit'" :content="'Add'" />
                </form>
            </div>
                <x-table class="table-auto w-full" :headers="['Name', 'Email']" :values="$users" :action="'admins'" />
        </div>
    </div>
</x-dashboard-layout>