<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Interviews') }}</h1>
            @can('schedule interviews')
                <a href="{{ route('interviews.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Schedule Interview') }}
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
            <form method="GET" action="{{ route('interviews.index') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Filter') }}
                    </label>
                    <select name="filter" id="filter"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('All Interviews') }}</option>
                        <option value="upcoming" {{ request('filter') === 'upcoming' ? 'selected' : '' }}>
                            {{ __('Upcoming') }}</option>
                        <option value="past" {{ request('filter') === 'past' ? 'selected' : '' }}>{{ __('Past') }}
                        </option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    {{ __('Filter') }}
                </button>
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
                            {{ __('Scheduled Time') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Location') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($interviews as $interview)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('candidates.show', $interview->candidate) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                            {{ $interview->candidate->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $interview->candidate->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('job-postings.show', $interview->candidate->jobPosting) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ $interview->candidate->jobPosting->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $interview->scheduled_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $interview->scheduled_at->format('H:i') }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $interview->location ?? __('Not specified') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($interview->scheduled_at > now())
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ __('Upcoming') }}
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ __('Past') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('interviews.show', $interview) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ __('View') }}
                                </a>
                                @can('edit interviews')
                                    <a href="{{ route('interviews.edit', $interview) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No interviews found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($interviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $interviews->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
