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

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('vacation.leave_balance.generate') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="vacation_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start (date & time)') }}</label>
                    <input id="vacation_start" name="vacation_start" type="datetime-local"
                           value="{{ old('vacation_start', isset($results) ? $results['start']->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100" />
                    @error('vacation_start') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="vacation_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End (date & time)') }}</label>
                    <input id="vacation_end" name="vacation_end" type="datetime-local"
                           value="{{ old('vacation_end', isset($results) ? $results['end']->format('Y-m-d\TH:i') : '') }}"
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

        {{-- Results Section --}}
        @if (isset($results))
            <div class="mt-8 border-t dark:border-gray-700 pt-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">{{ __('Balance Report') }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ __('Note: Only "Vacation" type leaves are deducted from the earned balance.') }}</p>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                        <div class="text-sm text-blue-600 dark:text-blue-400 font-medium uppercase">{{ __('Earned (Accrued)') }}</div>
                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $results['accrued_days'] }} <span class="text-sm font-normal text-gray-500">days</span></div>
                        <div class="text-xs text-gray-500 mt-1">Based on {{ $results['start']->diffInMonths($results['end']) }} months work</div>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-100 dark:border-orange-800">
                        <div class="text-sm text-orange-600 dark:text-orange-400 font-medium uppercase">{{ __('Vacation Taken') }}</div>
                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $results['taken_days'] }} <span class="text-sm font-normal text-gray-500">days</span></div>
                        <div class="text-xs text-gray-500 mt-1">Deducted from balance</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium uppercase">{{ __('Net Balance') }}</div>
                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $results['net_balance'] }} <span class="text-sm font-normal text-gray-500">days</span></div>
                        <div class="text-xs text-gray-500 mt-1">Earned - Taken</div>
                    </div>
                </div>

                {{-- Vacation History Table --}}
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Full Leave History in Period') }}</h4>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Start Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('End Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Days') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($results['vacations'] as $vacation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ match(strtolower($vacation->type)) {
                                            'vacation' => 'bg-indigo-100 text-indigo-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'sick' => 'bg-red-100 text-red-800',
                                            'unpaid' => 'bg-gray-100 text-gray-800',
                                            'education' => 'bg-purple-100 text-purple-800',
                                            'remote' => 'bg-blue-100 text-blue-800',
                                            'emergency' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        } }}">
                                        {{ ucfirst($vacation->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($vacation->vacation_start)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($vacation->vacation_end)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($vacation->vacation_start)->diffInDays(\Carbon\Carbon::parse($vacation->vacation_end)) + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($vacation->status) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No leave records found in this period.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    @if(isset($pdf_download_content) && $pdf_download_content)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create a virtual link to trigger the download
                const link = document.createElement('a');
                link.href = "data:application/pdf;base64,{{ $pdf_download_content }}";
                link.download = "leave_balance_report_{{ now()->format('Ymd') }}.pdf";

                // Append, click, and remove
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        </script>
    @endif
</x-layouts.app>
