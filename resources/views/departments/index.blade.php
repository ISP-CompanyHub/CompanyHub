<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <span>{{ __('Departments') }}</span>
        </div>

        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ __('Departments') }}
            </h1>
            @can('create departments')
                <a href="{{ route('departments.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Add Department') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Search bar --}}
    <form method="GET" action="{{ route('departments.index') }}" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('Search departments...') }}"
               class="w-full md:w-1/3 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2">
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Name') }}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Description') }}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Lead') }}
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Actions') }}
                </th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($departments as $department)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $department->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                        {{ $department->description ?: '—' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                        {{ $department->lead?->name ?: __('No Lead Assigned') }}
                    </td>
                    @can('edit departments')
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('departments.edit', $department->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                {{ __('Edit') }}
                            </a>
                        </td>
                    @endcan
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No departments found.') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</x-layouts.app>
