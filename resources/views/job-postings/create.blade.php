<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('job-postings.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Job Postings') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Create') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Job Posting') }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <x-form-errors :errors="$errors" />

        <form action="{{ route('job-postings.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Job Title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description" id="description" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Location') }}
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Employment Type') }}
                        </label>
                        <select name="employment_type" id="employment_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <option value="">{{ __('Select type') }}</option>
                            <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>
                                {{ __('Full-time') }}</option>
                            <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>
                                {{ __('Part-time') }}</option>
                            <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>
                                {{ __('Contract') }}</option>
                            <option value="Internship" {{ old('employment_type') == 'Internship' ? 'selected' : '' }}>
                                {{ __('Internship') }}</option>
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Minimum Salary') }}
                        </label>
                        <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min') }}"
                            step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error('salary_min')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Maximum Salary') }}
                        </label>
                        <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max') }}"
                            step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error('salary_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span
                            class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Active (visible to candidates)') }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('job-postings.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Create Job Posting') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
