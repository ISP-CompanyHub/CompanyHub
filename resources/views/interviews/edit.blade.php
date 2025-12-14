<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('interviews.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Interviews') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('interviews.show', $interview) }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Interview Details') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
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

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Interview') }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('interviews.update', $interview) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-errors />

                <div class="space-y-6">
                    <div>
                        <label for="candidate_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Candidate') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="candidate_id" id="candidate_id" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            @foreach ($candidates as $candidate)
                                <option value="{{ $candidate->id }}"
                                    {{ old('candidate_id', $interview->candidate_id) == $candidate->id ? 'selected' : '' }}>
                                    {{ $candidate->name }} - {{ $candidate->jobPosting->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('candidate_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="scheduled_at"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Date & Time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                            value="{{ old('scheduled_at', $interview->scheduled_at->format('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Interview Mode') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="mode" id="mode" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">{{ __('Select interview mode') }}</option>
                            <option value="in-person"
                                {{ old('mode', $interview->mode) == 'in-person' ? 'selected' : '' }}>
                                {{ __('In-Person') }}
                            </option>
                            <option value="video" {{ old('mode', $interview->mode) == 'video' ? 'selected' : '' }}>
                                {{ __('Video Call') }}
                            </option>
                            <option value="phone" {{ old('mode', $interview->mode) == 'phone' ? 'selected' : '' }}>
                                {{ __('Phone Call') }}
                            </option>
                            <option value="remote" {{ old('mode', $interview->mode) == 'remote' ? 'selected' : '' }}>
                                {{ __('Remote') }}
                            </option>
                        </select>
                        @error('mode')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Location') }}
                        </label>
                        <input type="text" name="location" id="location"
                            value="{{ old('location', $interview->location) }}"
                            placeholder="{{ __('e.g., Office Room 301, Zoom Meeting, etc.') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        @error('location')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Notes') }}
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                            placeholder="{{ __('Interview topics, preparation notes, etc.') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('notes', $interview->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('interviews.show', $interview) }}"
                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('Update Interview') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
