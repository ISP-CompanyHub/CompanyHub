<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('interviews.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Interviews') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Schedule Interview') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Schedule New Interview') }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('interviews.store') }}" method="POST">
                @csrf

                <x-form-errors />

                <div class="space-y-6">
                    <div>
                        <label for="candidate_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Candidate') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="candidate_id" id="candidate_id" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">{{ __('Select a candidate') }}</option>
                            @foreach ($candidates as $candidate)
                                <option value="{{ $candidate->id }}"
                                    {{ old('candidate_id', $selectedCandidateId) == $candidate->id ? 'selected' : '' }}>
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
                            value="{{ old('scheduled_at') }}" required
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
                            <option value="in-person" {{ old('mode') == 'in-person' ? 'selected' : '' }}>
                                {{ __('In-Person') }}
                            </option>
                            <option value="video" {{ old('mode') == 'video' ? 'selected' : '' }}>
                                {{ __('Video Call') }}
                            </option>
                            <option value="phone" {{ old('mode') == 'phone' ? 'selected' : '' }}>
                                {{ __('Phone Call') }}
                            </option>
                            <option value="remote" {{ old('mode') == 'remote' ? 'selected' : '' }}>
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
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
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
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('interviews.index') }}"
                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('Schedule Interview') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
