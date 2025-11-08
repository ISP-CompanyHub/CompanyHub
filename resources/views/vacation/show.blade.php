<x-layouts.app>
    @php
        $vac = $vacation ?? $vacationRequest ?? null;
    @endphp

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Vacation Request Details') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View the full details of a vacation request') }}</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('vacation.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        @if (! $vac)
            <div class="text-sm text-red-600">{{ __('No vacation request provided to the view.') }}</div>
        @else
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Number') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vac->id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Submission Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $vac->submission_date ? $vac->submission_date->format('Y-m-d H:i') : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Start Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $vac->vacation_start ? $vac->vacation_start->format('Y-m-d') : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('End Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $vac->vacation_end ? $vac->vacation_end->format('Y-m-d') : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Type') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $vac->type ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $vac->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $vac->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $vac->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ ucfirst($vac->status ?? 'unknown') }}
                        </span>
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('Comments') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">
                        {{ $vac->comments ?? '-' }}
                    </dd>
                </div>
            </dl>
        @endif
    </div>
</x-layouts.app>
