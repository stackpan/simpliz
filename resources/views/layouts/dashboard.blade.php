<x-app-layout>
    <div class="flex">
        <div class="basis-1/4">
            <div class="bg-gray-400 h-screen">
                @if(auth()->user()->role == App\Enums\Role::SuperAdmin->value)
                <div class="pt-6">
                    <x-sidebar-menu :route="'dashboard.admin'" :content="'Admin'" :active="request()->routeIs('dashboard.admin')" />
                </div>
                @else
                <div class="pt-6">
                    <x-sidebar-menu :route="'dashboard.home'" :content="'Home'" :active="request()->routeIs('dashboard.home')" />
                </div>
                <div class="pt-2">
                    <x-sidebar-menu :route="'dashboard.user'" :content="'User'" :active="request()->routeIs('dashboard.user')" />
                </div>
                <div class="pt-2">
                    <x-sidebar-menu :route="'dashboard.quiz'" :content="'Quiz'" :active="request()->routeIs('dashboard.quiz')" />
                </div>
                <div class="pt-2">
                    <x-sidebar-menu :route="'dashboard.result'" :content="'result'" :active="request()->routeIs('dashboard.result')" />
                </div>
                @endif
            </div>
        </div>
        <div class="basis-3/4">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>