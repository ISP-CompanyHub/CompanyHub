<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('job-offers.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Job Offers') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $jobOffer->offer_number }}</span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Job Offer') }}
                    #{{ $jobOffer->offer_number }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    For {{ $jobOffer->candidate->name }} - {{ $jobOffer->candidate->jobPosting->title }}
                </p>
            </div>
            <div class="flex space-x-2">
                @if ($jobOffer->status === 'draft')
                    @can('edit job offers')
                        <a href="{{ route('job-offers.edit', $jobOffer) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Edit') }}
                        </a>
                    @endcan
                @endif
                @can('delete job offers')
                    <form action="{{ route('job-offers.destroy', $jobOffer) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this job offer?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            {{ __('Delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Offer Details') }}</h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Candidate') }}</dt>
                        <dd class="mt-1">
                            <a href="{{ route('candidates.show', $jobOffer->candidate) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 font-medium">
                                {{ $jobOffer->candidate->name }}
                            </a>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jobOffer->candidate->email }}</p>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Job Posting') }}</dt>
                        <dd class="mt-1">
                            <a href="{{ route('job-postings.show', $jobOffer->candidate->jobPosting) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                {{ $jobOffer->candidate->jobPosting->title }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Salary Offered') }}
                        </dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">
                            ${{ number_format($jobOffer->salary) }}
                        </dd>
                    </div>

                    @if ($jobOffer->start_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Start Date') }}
                            </dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">
                                {{ $jobOffer->start_date->format('F d, Y') }}
                            </dd>
                        </div>
                    @endif

                    @if ($jobOffer->benefits)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                {{ __('Benefits & Perks') }}</dt>
                            <dd
                                class="mt-1 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $jobOffer->benefits }}
                            </dd>
                        </div>
                    @endif

                    @if ($jobOffer->additional_terms)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                {{ __('Additional Terms') }}</dt>
                            <dd
                                class="mt-1 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $jobOffer->additional_terms }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Status') }}</h2>

                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Offer Status') }}</dt>
                        <dd class="mt-1">
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $jobOffer->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                {{ $jobOffer->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                {{ $jobOffer->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $jobOffer->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                {{ ucfirst($jobOffer->status) }}
                            </span>
                        </dd>
                    </div>

                    @if ($jobOffer->sent_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Sent At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $jobOffer->sent_at->format('M d, Y H:i') }}
                            </dd>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $jobOffer->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Actions') }}</h2>

                <div class="space-y-3">
                    <a href="{{ route('job-offers.preview', $jobOffer) }}" target="_blank"
                        class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center px-4 py-2 rounded-lg">
                        {{ __('Preview PDF') }}
                    </a>

                    <a href="{{ route('job-offers.download', $jobOffer) }}"
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg">
                        {{ __('Download PDF') }}
                    </a>

                    @if ($jobOffer->status === 'draft')
                        <form action="{{ route('job-offers.send', $jobOffer) }}" method="POST"
                            onsubmit="return confirm('{{ __('Are you sure you want to send this job offer? The candidate will receive it via email.') }}')">
                            @csrf
                            <button type="submit"
                                class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                {{ __('Send Offer via Email') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
