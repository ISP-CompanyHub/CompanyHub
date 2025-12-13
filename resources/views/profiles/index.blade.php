<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Employee profiles') }}</h1>
            @can('create employee profile')
                <a href="{{ route('profiles.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Create employee profile') }}
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
            <form method="GET" action="{{ route('profiles.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Search') }}
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="{{ __('Name or email...') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>

                <div>
                    <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Job Title') }}
                    </label>
                    <select name="job_title" id="job_title"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('All Job Titles') }}</option>
                        @foreach ($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle }}"
                                {{ request('job_title') == $jobTitle ? 'selected' : '' }}>
                                {{ $jobTitle }}
                            </option>
                        @endforeach
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
                        {{ __('Name') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Surname') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Email') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ __('Department') }}
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
                @forelse ($profiles as $employee)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <p class="text-gray-900 dark:text-gray-100">{{ $employee->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900 dark:text-gray-100">{{ $employee->surname }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900 dark:text-gray-100">{{ $employee->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900 dark:text-gray-100">{{ $employee->department()->first()->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $employee->status->value === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $employee->status->value === 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                {{ ucfirst($employee->status->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('profiles.show', $employee) }}"
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                {{ __('View') }}
                            </a>
                            @if ($employee->status->value === 'active')
                                @can('edit employee profile')
                                    <a href="{{ route('profiles.edit', $employee) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No employees found.') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($profiles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $profiles->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
