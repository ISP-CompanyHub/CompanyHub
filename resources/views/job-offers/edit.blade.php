<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('job-offers.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Job Offers') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('job-offers.show', $jobOffer) }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ $jobOffer->offer_number }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Job Offer') }}</h1>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('job-offers.update', $jobOffer) }}" method="POST">
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
                                    {{ old('candidate_id', $jobOffer->candidate_id) == $candidate->id ? 'selected' : '' }}>
                                    {{ $candidate->name }} - {{ $candidate->jobPosting->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('candidate_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Salary Offered ($)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="salary" id="salary" step="0.01"
                            value="{{ old('salary', $jobOffer->salary) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        @error('salary')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Start Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                            value="{{ old('start_date', $jobOffer->start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Offer Expires On') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expires_at" id="expires_at" required
                            value="{{ old('expires_at', $jobOffer->expires_at?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Deadline for the candidate to accept the offer') }}</p>
                    </div>

                    <div>
                        <label for="benefits" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Benefits & Perks') }}
                        </label>
                        <textarea name="benefits" id="benefits" rows="4"
                            placeholder="{{ __('Health insurance, vacation days, remote work, etc.') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('benefits', $jobOffer->benefits) }}</textarea>
                        @error('benefits')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="additional_terms"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Additional Terms') }}
                        </label>
                        <textarea name="additional_terms" id="additional_terms" rows="6"
                            placeholder="{{ __('Contract duration, probation period, special conditions, etc.') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('additional_terms', $jobOffer->additional_terms) }}</textarea>
                        @error('additional_terms')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('job-offers.show', $jobOffer) }}"
                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            {{ __('Update Job Offer') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
