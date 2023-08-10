<x-app-layout>
    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-center text-2xl font-bold">{{ __('Your quiz time has ended') }}</h1>
        <p class="text-center py-4 text-md">{{ __('Complete and go to result') }}</p>
        <div class="flex flex-col md:flex-row justify-center">
            <form action="{{ route('quiz_sessions.complete', $quizSessionId) }}" method="post">
                @csrf
                @method('delete')
                <x-button.primary class="w-full my-12">{{ __('Complete') }}</x-button.primary>
            </form>
        </div>
    </div>
</x-app-layout>