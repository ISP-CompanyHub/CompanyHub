<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Vacation & Holidays') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('All vacation requests and official holidays') }}</p>
        </div>

        <div class="flex items-center space-x-2">
            @can('create vacation requests')
                <a href="{{ route('vacation.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Create Request') }}
                </a>
            @endcan

            {{-- Leave Balance button: opens the separate Leave Balance page --}}
            @can('view leave balances')
                <a href="{{ route('vacation.leave_balance') }}"
                   class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c1.657 0 3 .895 3 2v4M12 8c-1.657 0-3 .895-3 2v4M5 12h14" />
                    </svg>
                    {{ __('Leave Balance') }}
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('User') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Submission Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Start') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('End') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type / Title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Comments') }}</th>
                </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($vacationRequests as $item)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
{{--                                SVARBU - KAI REIKIA USER, NAUDOTI belongsToUser()->first()->..... --}}
                                {{ $item->belongsToUser()->first()->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->model }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->submission_date ? $item->submission_date->format('Y-m-d H:i') : '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->vacation_start ? $item->vacation_start->format('Y-m-d') : '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->vacation_end ? $item->vacation_end->format('Y-m-d') : '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->type ?? '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($item->model === 'holiday')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('Holiday') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $item->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $item->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($item->status ?? 'unknown') }}
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ \Illuminate\Support\Str::limit($item->comments, 80, '...') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <p class="mt-4 text-sm">{{ __('No vacation requests or holidays found.') }}</p>
                            @can('create vacation requests')
                                <a href="{{ route('vacation.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-900">
                                    {{ __('Create a request') }}
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($vacationRequests) && method_exists($vacationRequests, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $vacationRequests->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
