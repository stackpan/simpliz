<x-layouts.manager>
    <x-slot name="breadcrumbs">
        <li>{{ __('User') }}</li>
    </x-slot>

    <article class="space-y-4">
        <section class="flex justify-end">
            <a href="{{ route('manager.user.create') }}" class="btn btn-sm">
                <x-heroicon-m-user-plus class="w-5" />
                <span>{{ __('Create') }}</span>
            </a>
        </section>
        <section>
            <div class="overflow-x-auto">
                <table class="table table-xs table-zebra">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->gender->name }}</td>
                                <td>
                                    <div class="join">
                                        <a href="{{ route('manager.user.edit', $user) }}"
                                            class="btn btn-xs btn-square btn-ghost join-item">
                                            <x-heroicon-s-pencil-square class="w-5" />
                                        </a>
                                        <form action="{{ route('manager.user.delete', $user->id) }}" method="post">
                                            @method('DELETE')
                                            @csrf

                                            <button type="submit" class="btn btn-xs btn-square btn-ghost join-item">
                                                <x-heroicon-s-trash class="w-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </article>

</x-layouts.manager>
