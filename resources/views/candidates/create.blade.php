<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('candidates.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Candidates') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Add Candidate') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Add New Candidate') }}</h1>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <x-form-errors :errors="$errors" />

            <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="job_posting_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Job Posting') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="job_posting_id" id="job_posting_id" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('job_posting_id') border-red-500 @enderror">
                            <option value="">{{ __('Select a job posting') }}</option>
                            @foreach ($jobPostings as $posting)
                                <option value="{{ $posting->id }}"
                                    {{ old('job_posting_id') == $posting->id ? 'selected' : '' }}>
                                    {{ $posting->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_posting_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Full Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Phone') }}
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cover_letter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Cover Letter') }}
                        </label>
                        <textarea name="cover_letter" id="cover_letter" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('cover_letter') border-red-500 @enderror">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="resume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Resume (PDF)') }}
                        </label>
                        <input type="file" name="resume" id="resume" accept=".pdf"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-200 @error('resume') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Optional. PDF format only.') }}
                        </p>
                        @error('resume')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Status') }}
                        </label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('status') border-red-500 @enderror">
                            <option value="new" {{ old('status') === 'new' ? 'selected' : '' }}>{{ __('New') }}
                            </option>
                            <option value="reviewing" {{ old('status') === 'reviewing' ? 'selected' : '' }}>
                                {{ __('Reviewing') }}</option>
                            <option value="interview_scheduled"
                                {{ old('status') === 'interview_scheduled' ? 'selected' : '' }}>
                                {{ __('Interview Scheduled') }}</option>
                            <option value="interviewed" {{ old('status') === 'interviewed' ? 'selected' : '' }}>
                                {{ __('Interviewed') }}</option>
                            <option value="offer_sent" {{ old('status') === 'offer_sent' ? 'selected' : '' }}>
                                {{ __('Offer Sent') }}</option>
                            <option value="accepted" {{ old('status') === 'accepted' ? 'selected' : '' }}>
                                {{ __('Accepted') }}</option>
                            <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>
                                {{ __('Rejected') }}</option>
                            <option value="withdrawn" {{ old('status') === 'withdrawn' ? 'selected' : '' }}>
                                {{ __('Withdrawn') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('candidates.index') }}"
                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('Add Candidate') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
