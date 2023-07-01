<x-app-layout>
    <div class="basis-1/4">
        <div class="bg-gray-400 min-h-full">
            @if(auth()->user()->role == App\Enums\Role::SuperAdmin->value)
            <div class="pt-6">
                <x-sidebar-menu :route="'dashboard.admin'" :content="'Admin'" :active="request()->routeIs(['dashboard.admin', 'admins.create'])" />
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
    <div class="basis-3/4 flex flex-col">
        @isset($breadcrumb)
        <header>
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 font-bold text-3xl text-gray-500 leading-tight">
                <div class="border-b-2 border-b-gray-300 [&>ol]:flex [&>ol>li]:pl-4 [&>ol>li]:before:content-['/'] [&>ol>li]:before:pr-4 [&>ol>li:first-child]:before:content-none [&>ol>li:first-child]:pl-0 [&>ol>li>a]:underline [&>ol>li>a]:text-gray-600">
                    {{ $breadcrumb }}
                </div>
            </div>
        </header>
        @endisset

        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>