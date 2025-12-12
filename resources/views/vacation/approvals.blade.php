<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Approvals') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Pending and approved vacation requests') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-100">{{ __('Waiting for approval') }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Submission Date') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Start') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('End') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Comments') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Action') }}</th>
                    </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($pending as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $p->belongsToUser()->first()->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $p->submission_date ? $p->submission_date->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $p->vacation_start ? $p->vacation_start->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $p->vacation_end ? $p->vacation_end->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $p->type ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($p->comments, 80) }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                @can('approve vacation requests')
                                    <div class="flex justify-end space-x-2">
                                        <form action="{{ route('vacation.approve', $p) }}" method="POST" onsubmit="return confirm('{{ __('Approve this request?') }}')">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                {{ __('Approve') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('vacation.reject', $p) }}" method="POST" onsubmit="return confirm('{{ __('Reject this request?') }}')">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">{{ __('No actions') }}</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No pending requests.') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $pending->links() }}
            </div>
        </div>

        <!-- Approved requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-100">{{ __('Approved') }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Submission Date') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Start') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('End') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Comments') }}</th>
                    </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($approved as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $a->belongsToUser()->first()->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $a->submission_date ? $a->submission_date->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $a->vacation_start ? $a->vacation_start->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $a->vacation_end ? $a->vacation_end->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $a->type ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($a->comments, 80) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No approved requests.') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $approved->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
