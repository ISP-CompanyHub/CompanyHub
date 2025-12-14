<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('interviews.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Interviews') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Interview Details') }}</span>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        {{ __('This feature was added beyond the original project scope.') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Interview Details') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ $interview->scheduled_at->format('l, F d, Y \a\t H:i') }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('edit interviews')
                    <a href="{{ route('interviews.edit', $interview) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete interviews')
                    <form action="{{ route('interviews.destroy', $interview) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this interview?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    {{ __('Interview Information') }}</h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Candidate') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">
                            <a href="{{ route('candidates.show', $interview->candidate) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 font-medium">
                                {{ $interview->candidate->name }}
                            </a>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $interview->candidate->email }}</p>
                            @if ($interview->candidate->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $interview->candidate->phone }}
                                </p>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Job Posting') }}</dt>
                        <dd class="mt-1">
                            <a href="{{ route('job-postings.show', $interview->candidate->jobPosting) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                {{ $interview->candidate->jobPosting->title }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date & Time') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $interview->scheduled_at->format('l, F d, Y') }}
                            <br>
                            {{ $interview->scheduled_at->format('H:i') }}
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                ({{ $interview->scheduled_at->diffForHumans() }})
                            </span>
                        </dd>
                    </div>

                    @if ($interview->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $interview->location }}</dd>
                        </div>
                    @endif

                    @if ($interview->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Notes') }}
                            </dt>
                            <dd
                                class="mt-1 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $interview->notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Status') }}</h2>

                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Interview Status') }}
                        </dt>
                        <dd class="mt-1">
                            @if ($interview->scheduled_at > now())
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ __('Upcoming') }}
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ __('Past') }}
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Candidate Status') }}
                        </dt>
                        <dd class="mt-1">
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $interview->candidate->status === 'interview_scheduled' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
                                {{ $interview->candidate->status === 'interviewed' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' : '' }}
                                {{ $interview->candidate->status === 'offer_sent' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $interview->candidate->status)) }}
                            </span>
                        </dd>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $interview->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
