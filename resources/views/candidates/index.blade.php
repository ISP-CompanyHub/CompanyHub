<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Candidates') }}</h1>
            @can('create candidates')
                <a href="{{ route('candidates.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Add Candidate') }}
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('candidates.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Search') }}
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="{{ __('Name or email...') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>

                <div>
                    <label for="job_posting_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Job Posting') }}
                    </label>
                    <select name="job_posting_id" id="job_posting_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('All Postings') }}</option>
                        @foreach ($jobPostings as $posting)
                            <option value="{{ $posting->id }}"
                                {{ request('job_posting_id') == $posting->id ? 'selected' : '' }}>
                                {{ $posting->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Status') }}
                    </label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>{{ __('New') }}
                        </option>
                        <option value="reviewing" {{ request('status') === 'reviewing' ? 'selected' : '' }}>
                            {{ __('Reviewing') }}</option>
                        <option value="interview_scheduled"
                            {{ request('status') === 'interview_scheduled' ? 'selected' : '' }}>
                            {{ __('Interview Scheduled') }}</option>
                        <option value="interviewed" {{ request('status') === 'interviewed' ? 'selected' : '' }}>
                            {{ __('Interviewed') }}</option>
                        <option value="offer_sent" {{ request('status') === 'offer_sent' ? 'selected' : '' }}>
                            {{ __('Offer Sent') }}</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>
                            {{ __('Accepted') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                            {{ __('Rejected') }}</option>
                        <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>
                            {{ __('Withdrawn') }}</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Filter') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Candidate') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Job Posting') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Applied') }}
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($candidates as $candidate)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $candidate->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $candidate->email }}</p>
                                    @if ($candidate->phone)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $candidate->phone }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('job-postings.show', $candidate->jobPosting) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ $candidate->jobPosting->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $candidate->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('candidates.show', $candidate) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ __('View') }}
                                </a>
                                @can('schedule interviews')
                                    @if (!in_array($candidate->status, ['accepted', 'rejected', 'withdrawn']))
                                        <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400">
                                            {{ __('Schedule') }}
                                        </a>
                                    @endif
                                @endcan
                                @can('edit candidates')
                                    <a href="{{ route('candidates.edit', $candidate) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No candidates found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($candidates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $candidates->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
