<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Holidays') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Official company / national holidays') }}</p>
        </div>

        <div class="flex items-center space-x-2">
            @can('create holidays')
                <button id="open-holiday-btn"
                        type="button"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Create Holiday') }}
                </button>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('ID') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($holidays as $holiday)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $holiday->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $holiday->holiday_date ? (is_string($holiday->holiday_date) ? \Carbon\Carbon::parse($holiday->holiday_date)->format('Y-m-d') : $holiday->holiday_date->format('Y-m-d')) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $holiday->title ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($holiday->type ?? '-') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No holidays found.') }}
                            @can('create holidays')
                                <div class="mt-2">
                                    <button id="open-holiday-btn-empty" class="text-blue-600 hover:text-blue-900">{{ __('Create a holiday') }}</button>
                                </div>
                            @endcan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $holidays->links() }}
        </div>
    </div>

    <!-- Create Holiday Modal (plain JS, no Alpine) -->
    <div id="holiday-modal" class="hidden fixed inset-0 z-40 flex items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="holiday-modal-title">
        <!-- Backdrop -->
        <div id="holiday-backdrop" class="absolute inset-0 bg-black/50"></div>

        <!-- Modal panel -->
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full z-50 overflow-hidden">
            <form action="{{ route('holidays.store') }}" method="POST" class="p-6">
                @csrf

                <div class="flex items-start justify-between">
                    <h2 id="holiday-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Create Holiday') }}
                    </h2>

                    <button type="button" class="close-holiday-modal text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" aria-label="Close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="holiday_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }}</label>
                        <input id="holiday_date" name="holiday_date" type="date" value="{{ old('holiday_date') }}" required
                               class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100" />
                        @error('holiday_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required
                               class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100" />
                        @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                        <select id="type" name="type" required
                                class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                            <option value="national" {{ old('type') === 'national' ? 'selected' : '' }}>{{ __('National') }}</option>
                            <option value="observance" {{ old('type') === 'observance' ? 'selected' : '' }}>{{ __('Observance') }}</option>
                            <option value="company" {{ old('type') === 'company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                        </select>
                        @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <button type="button" class="close-holiday-modal px-4 py-2 rounded border border-gray-300 text-gray-700 dark:text-gray-200">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        {{ __('Create Holiday') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Plain-JS modal controls (no Alpine). Auto-opens if there are validation errors. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var openBtn = document.getElementById('open-holiday-btn');
            var openBtnEmpty = document.getElementById('open-holiday-btn-empty');
            var modal = document.getElementById('holiday-modal');
            var backdrop = document.getElementById('holiday-backdrop');
            var closeButtons = document.querySelectorAll('.close-holiday-modal');

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                // prevent background scroll
                document.documentElement.classList.add('overflow-hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                document.documentElement.classList.remove('overflow-hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (openBtn) {
                openBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal();
                });
            }
            if (openBtnEmpty) {
                openBtnEmpty.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    closeModal();
                });
            }

            closeButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeModal();
                });
            });

            // Auto-open modal on validation errors (server-side)
            @if ($errors->any())
            openModal();
            @endif
        });
    </script>
</x-layouts.app>
