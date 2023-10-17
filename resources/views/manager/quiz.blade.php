<x-layouts.manager>
    <x-slot name="breadcrumbs">
        <li>Quiz</li>
    </x-slot>

    <article class="space-y-4">
        <section class="flex justify-end">
            <a href="{{ route('manager.quizzes.create') }}" class="btn btn-sm">
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
                            <th>{{ __('Users') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $quiz->name }}</td>
                                <td>{{ $quiz->users_count }}</td>
                                <td>{{ $quiz->duration }}</td>
                                <td>
                                    <div class="join">
                                        <a
                                            class="btn btn-xs btn-square btn-ghost join-item">
                                            <x-heroicon-s-pencil-square class="w-5" />
                                        </a>
                                        <form method="post">
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
</x-layouts.manager>

