<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('candidates.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Candidates') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $candidate->name }}</span>
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
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $candidate->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Applied {{ $candidate->created_at->diffForHumans() }} for
                    <a href="{{ route('job-postings.show', $candidate->jobPosting) }}"
                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                        {{ $candidate->jobPosting->title }}
                    </a>
                </p>
            </div>
            <div class="flex space-x-2">
                @can('schedule interviews')
                    @if (!$candidate->jobOffer)
                        <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Schedule Interview') }}
                        </a>
                    @endif
                @endcan
                @can('edit candidates')
                    <a href="{{ route('candidates.edit', $candidate) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete candidates')
                    <form action="{{ route('candidates.destroy', $candidate) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this candidate?') }}')">
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
            @if ($candidate->cover_letter)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Cover Letter') }}
                    </h2>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        {{ $candidate->cover_letter }}
                    </div>
                </div>
            @endif

            @if ($candidate->interviews->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        {{ __('Interviews') }} ({{ $candidate->interviews->count() }})
                    </h2>
                    <div class="space-y-3">
                        @foreach ($candidate->interviews as $interview)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $interview->scheduled_at->format('M d, Y H:i') }}
                                    </p>
                                    @if ($interview->location)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $interview->location }}
                                        </p>
                                    @endif
                                    @if ($interview->notes)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $interview->notes }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $interview->scheduled_at > now() ? 'Upcoming' : 'Past' }}
                                    </span>
                                    <a href="{{ route('interviews.show', $interview) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        {{ __('View') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @can('schedule interviews')
                        <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}"
                            class="mt-4 inline-block text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">
                            {{ __('+ Schedule New Interview') }}
                        </a>
                    @endcan
                </div>
            @else
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Interviews') }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        {{ __('No interviews scheduled yet.') }}</p>
                    @can('schedule interviews')
                        <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}"
                            class="mt-2 inline-block text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">
                            {{ __('+ Schedule Interview') }}
                        </a>
                    @endcan
            @endif

            @if ($candidate->jobOffer)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Job Offer') }}</h2>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $candidate->jobOffer->offer_number }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    Salary: ${{ number_format($candidate->jobOffer->salary) }}
                                    @if ($candidate->jobOffer->start_date)
                                        • Start: {{ $candidate->jobOffer->start_date->format('M d, Y') }}
                                    @endif
                                </p>
                                <span
                                    class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $candidate->jobOffer->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                    {{ $candidate->jobOffer->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $candidate->jobOffer->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $candidate->jobOffer->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                    {{ ucfirst($candidate->jobOffer->status) }}
                                </span>
                            </div>
                            <a href="{{ route('job-offers.show', $candidate->jobOffer) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                {{ __('View Offer') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                @can('create job offers')
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Job Offer') }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No job offer sent yet.') }}</p>
                        <a href="{{ route('job-offers.index', ['candidate_id' => $candidate->id]) }}"
                            class="mt-2 inline-block text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">
                            {{ __('+ Create Job Offer') }}
                        </a>
                    </div>
                @endcan
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Contact Information') }}
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <a href="mailto:{{ $candidate->email }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                {{ $candidate->email }}
                            </a>
                        </dd>
                    </div>

                    @if ($candidate->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <a href="tel:{{ $candidate->phone }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ $candidate->phone }}
                                </a>
                            </dd>
                        </div>
                    @endif

                    @if ($candidate->resume_path)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Resume') }}</dt>
                            <dd class="mt-1">
                                <a href="{{ Storage::url($candidate->resume_path) }}" target="_blank"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    {{ __('Download Resume') }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Application Status') }}
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Current Status') }}
                        </dt>
                        <dd class="mt-1">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $candidate->status === 'new' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                {{ $candidate->status === 'reviewing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                {{ $candidate->status === 'interview_scheduled' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
                                {{ $candidate->status === 'interviewed' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' : '' }}
                                {{ $candidate->status === 'offer_sent' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $candidate->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $candidate->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                {{ $candidate->status === 'withdrawn' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $candidate->status)) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Applied') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $candidate->created_at->format('M d, Y H:i') }}</dd>
                    </div>

                    @can('edit candidates')
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <form action="{{ route('candidates.update-status', $candidate) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="status_update"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Update Status') }}
                                </label>
                                <select name="status" id="status_update" onchange="this.form.submit()"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="new" {{ $candidate->status === 'new' ? 'selected' : '' }}>
                                        {{ __('New') }}</option>
                                    <option value="reviewing" {{ $candidate->status === 'reviewing' ? 'selected' : '' }}>
                                        {{ __('Reviewing') }}</option>
                                    <option value="interview_scheduled"
                                        {{ $candidate->status === 'interview_scheduled' ? 'selected' : '' }}>
                                        {{ __('Interview Scheduled') }}</option>
                                    <option value="interviewed"
                                        {{ $candidate->status === 'interviewed' ? 'selected' : '' }}>
                                        {{ __('Interviewed') }}</option>
                                    <option value="offer_sent"
                                        {{ $candidate->status === 'offer_sent' ? 'selected' : '' }}>{{ __('Offer Sent') }}
                                    </option>
                                    <option value="accepted" {{ $candidate->status === 'accepted' ? 'selected' : '' }}>
                                        {{ __('Accepted') }}</option>
                                    <option value="rejected" {{ $candidate->status === 'rejected' ? 'selected' : '' }}>
                                        {{ __('Rejected') }}</option>
                                    <option value="withdrawn" {{ $candidate->status === 'withdrawn' ? 'selected' : '' }}>
                                        {{ __('Withdrawn') }}</option>
                                </select>
                            </form>
                        </div>
                    @endcan
                </dl>
            </div>
        </div>
    </div>
</x-layouts.app>
