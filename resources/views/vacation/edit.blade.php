<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Vacation Request') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update an existing vacation request') }}</p>
        </div>

        <a href="{{ route('vacation.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Back to list') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('vacation.update', $vacationRequest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Submission Date') }}</label>
                    <input type="text" disabled
                           value="{{ $vacationRequest->submission_date ? $vacationRequest->submission_date->format('Y-m-d H:i') : '-' }}"
                           class="mt-1 w-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm text-gray-700 dark:text-gray-100"/>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                    <input id="type" name="type" type="text" value="{{ old('type', $vacationRequest->type) }}"
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100"/>
                </div>

                <div>
                    <label for="vacation_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                    <input id="vacation_start" name="vacation_start" type="date" value="{{ old('vacation_start', $vacationRequest->vacation_start ? $vacationRequest->vacation_start->format('Y-m-d') : '') }}"
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100"/>
                </div>

                <div>
                    <label for="vacation_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                    <input id="vacation_end" name="vacation_end" type="date" value="{{ old('vacation_end', $vacationRequest->vacation_end ? $vacationRequest->vacation_end->format('Y-m-d') : '') }}"
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100"/>
                </div>

                <div class="md:col-span-2">
                    <label for="comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Comments') }}</label>
                    <textarea id="comments" name="comments" rows="5"
                              class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100">{{ old('comments', $vacationRequest->comments) }}</textarea>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>

                    @can('edit vacation requests')
                        <select id="status" name="status" class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                            <option value="pending" {{ old('status', $vacationRequest->status) === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="approved" {{ old('status', $vacationRequest->status) === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                            <option value="rejected" {{ old('status', $vacationRequest->status) === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    @else
                        <input type="text" disabled value="{{ ucfirst($vacationRequest->status ?? 'unknown') }}"
                               class="mt-1 w-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm text-gray-700 dark:text-gray-100"/>
                    @endcan
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    {{ __('Update Request') }}
                </button>

                <a href="{{ route('vacation.show', $vacationRequest->id) }}" class="text-gray-700 dark:text-gray-200 hover:underline">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
