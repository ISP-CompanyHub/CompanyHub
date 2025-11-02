<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('job-postings.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Job Postings') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('job-postings.show', $jobPosting) }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ $jobPosting->title }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Job Posting') }}</h1>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('job-postings.update', $jobPosting) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-errors />

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Job Title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            value="{{ old('title', $jobPosting->title) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" id="description" rows="8"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('description') border-red-500 @enderror">{{ old('description', $jobPosting->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="location"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Location') }}
                            </label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $jobPosting->location) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="employment_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Employment Type') }}
                            </label>
                            <input type="text" name="employment_type" id="employment_type"
                                value="{{ old('employment_type', $jobPosting->employment_type) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('employment_type') border-red-500 @enderror"
                                placeholder="{{ __('e.g., Full-time, Part-time, Contract') }}">
                            @error('employment_type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="salary_min"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Minimum Salary ($)') }}
                            </label>
                            <input type="number" name="salary_min" id="salary_min" step="0.01"
                                value="{{ old('salary_min', $jobPosting->salary_min) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('salary_min') border-red-500 @enderror">
                            @error('salary_min')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salary_max"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Maximum Salary ($)') }}
                            </label>
                            <input type="number" name="salary_max" id="salary_max" step="0.01"
                                value="{{ old('salary_max', $jobPosting->salary_max) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('salary_max') border-red-500 @enderror">
                            @error('salary_max')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $jobPosting->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Active (visible to candidates)') }}
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('job-postings.show', $jobPosting) }}"
                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('Update Job Posting') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
