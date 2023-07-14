<h1 class="text-4xl font-bold mb-6">{{ $quiz->name }}</h1>
<p class="text-gray-600 mb-6">{{ $quiz->description }}</p>
<div class="flex flex-row justify-between">
    <div>
        <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.clipboard-document-list class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ $quiz->questions_count }} {{ __('Questions') }}</span></p>
        <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.clock class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ $quiz->duration }} {{ __('Minutes') }}</span></p>
        @isset($username)
        <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.user class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ $username }}</span></p>
        @endisset
    </div>
    @isset($score)
    <p class="my-1 text-2xl md:text-4xl font-bold text-gray-600 justify-self-end"><x-icon.check-circle class="inline-block -mb-1 align-text-top w-9 md:w-14 h-9 md:h-14" /><span class="align-middle">{{ $score }}%</span></p>
    @endisset
</div>
