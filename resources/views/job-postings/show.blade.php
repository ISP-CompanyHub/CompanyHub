<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('job-postings.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Job Postings') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $jobPosting->title }}</span>
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
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $jobPosting->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Posted {{ $jobPosting->posted_at?->diffForHumans() ?? 'Not published' }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('edit job postings')
                    <a href="{{ route('job-postings.edit', $jobPosting) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete job postings')
                    <form action="{{ route('job-postings.destroy', $jobPosting) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this job posting?') }}')">
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
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Job Description') }}
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    {{ $jobPosting->description ?? __('No description provided.') }}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    {{ __('Recent Candidates') }} ({{ $jobPosting->candidates->count() }})
                </h2>
                @if ($jobPosting->candidates->count() > 0)
                    <div class="space-y-3">
                        @foreach ($jobPosting->candidates as $candidate)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $candidate->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $candidate->email }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $candidate->status === 'new' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                        {{ $candidate->status === 'reviewing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                        {{ $candidate->status === 'interview_scheduled' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
                                        {{ $candidate->status === 'offer_sent' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                                    </span>
                                    <a href="{{ route('candidates.show', $candidate) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        {{ __('View') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('candidates.index', ['job_posting_id' => $jobPosting->id]) }}"
                        class="mt-4 inline-block text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">
                        {{ __('View all candidates →') }}
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No candidates yet.') }}</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Details') }}</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd class="mt-1">
                            @if ($jobPosting->is_active)
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                        </dd>
                    </div>

                    @if ($jobPosting->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $jobPosting->location }}</dd>
                        </div>
                    @endif

                    @if ($jobPosting->employment_type)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Employment Type') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $jobPosting->employment_type }}</dd>
                        </div>
                    @endif

                    @if ($jobPosting->salary_min || $jobPosting->salary_max)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Salary Range') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                ${{ number_format($jobPosting->salary_min ?? 0) }} -
                                ${{ number_format($jobPosting->salary_max ?? 0) }}
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $jobPosting->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.app>
