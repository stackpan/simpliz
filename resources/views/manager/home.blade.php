<x-layouts.manager>
    <x-slot name="breadcrumbs">
        <li>{{ __('Home') }}</li>
    </x-slot>
    <article class="space-y-2">
        <h2 class="font-bold text-2xl">{{ __('Stats') }}</h2>
        <section class="stats shadow bg-base-200">
            <article class="stat">
                <div class="stat-figure text-secondary">
                    <x-heroicon-s-users class="h-8 w-8" />
                </div>
                <div class="stat-title">{{ __('Users') }}</div>
                <div class="stat-value">{{ $counts['user'] }}</div>
            </article>
            <article class="stat">
                <div class="stat-figure text-secondary">
                    <x-heroicon-s-document-text class="h-8 w-8" />
                </div>
                <div class="stat-title">{{ __('Quizzes') }}</div>
                <div class="stat-value">{{ $counts['quiz'] }}</div>
            </article>
            <article class="stat">
                <div class="stat-figure text-secondary">
                    <x-heroicon-s-clipboard-document-list class="h-8 w-8" />
                </div>
                <div class="stat-title">{{ __('Results') }}</div>
                <div class="stat-value">{{ $counts['result'] }}</div>
            </article>
        </section>
    </article>
    <article class="space-y-2 mt-8">
        <h2 class="font-bold text-2xl">{{ __('Activities') }}</h2>
        <div class="overflow-x-auto">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>{{ __('Timestamp') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Activity') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($activites as $activity)
                    <tr>
                        <td>{{ $activity->created_at }}</td>
                        <td>{{ $activity->user->name }}</td>
                        <td>{{ $activity->body }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </article>
</x-layouts.manager>
