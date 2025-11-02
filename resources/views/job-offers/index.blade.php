<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Job Offers') }}</h1>
            @can('create job offers')
                <a href="{{ route('job-offers.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    {{ __('Create Job Offer') }}
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Offer Number') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Candidate') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Job Posting') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Salary') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Created') }}
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($jobOffers as $jobOffer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-mono text-sm text-gray-900 dark:text-gray-100">
                                {{ $jobOffer->offer_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('candidates.show', $jobOffer->candidate) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                            {{ $jobOffer->candidate->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $jobOffer->candidate->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('job-postings.show', $jobOffer->candidate->jobPosting) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ $jobOffer->candidate->jobPosting->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                ${{ number_format($jobOffer->salary) }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $jobOffer->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                    {{ $jobOffer->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $jobOffer->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $jobOffer->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                    {{ ucfirst($jobOffer->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $jobOffer->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('job-offers.show', $jobOffer) }}"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    {{ __('View') }}
                                </a>
                                @if ($jobOffer->status === 'draft')
                                    @can('edit job offers')
                                        <a href="{{ route('job-offers.edit', $jobOffer) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ __('Edit') }}
                                        </a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No job offers found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jobOffers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $jobOffers->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
