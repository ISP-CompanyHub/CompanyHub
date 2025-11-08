<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Leave Balance') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Generate leave balance for a date/time range') }}</p>
        </div>

        <a href="{{ route('vacation.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
            <svg class="w-5 h-5 mr-2" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Back to Requests') }}
        </a>
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

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('vacation.leave_balance.generate') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="vacation_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start (date & time)') }}</label>
                    <input id="vacation_start" name="vacation_start" type="datetime-local"
                           value="{{ old('vacation_start') }}"
                           required
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100" />
                    @error('vacation_start') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="vacation_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End (date & time)') }}</label>
                    <input id="vacation_end" name="vacation_end" type="datetime-local"
                           value="{{ old('vacation_end') }}"
                           required
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100" />
                    @error('vacation_end') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 flex items-center space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        {{ __('Generate Leave Balance') }}
                    </button>

                    <label class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="generate_pdf" value="1" class="rounded mr-2">
                        {{ __('Generate PDF') }}
                    </label>
                </div>
            </div>
        </form>

        {{-- Optional: show calculation result if controller flashed it --}}
        @if (session('leave_payload'))
            @php $payload = session('leave_payload'); @endphp
            <div class="mt-6 border-t pt-4">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">{{ __('Result') }}</h3>
                <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    <div><strong>{{ __('User') }}:</strong> {{ $payload['user']->name }} ({{ $payload['user']->email }})</div>
                    <div><strong>{{ __('Start') }}:</strong> {{ $payload['vacation_start']->format('Y-m-d H:i') }}</div>
                    <div><strong>{{ __('End') }}:</strong> {{ $payload['vacation_end']->format('Y-m-d H:i') }}</div>
                    <div class="mt-2 text-xs text-gray-500">{{ __('If you checked Generate PDF, the PDF was returned as a download.') }}</div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
