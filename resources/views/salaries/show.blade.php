<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('salaries.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Salary Logs') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $salaryLog->user->name }} {{ $salaryLog->user->surname }} - {{ $salaryLog->calculation_date }}</span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    {{ __('Salary Log for') }} {{ $salaryLog->user->name }} {{ $salaryLog->user->surname }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Calculated on') }} {{ $salaryLog->calculation_date }}
                </p>
            </div>
            <div class="flex space-x-2">
                @can('edit salaries')
                    <a href="{{ route('salaries.edit', $salaryLog) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete salaries')
                    <form action="{{ route('salaries.destroy', $salaryLog) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this salary log?') }}')">
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
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Salary Components') }}
                </h2>
                <div class="space-y-3">
                    @foreach ($salaryLog->salaryComponents as $component)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $component->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($component->type) }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                ${{ number_format($component->pivot->amount, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Summary') }}</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Gross Salary') }}</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ${{ number_format($salaryLog->gross_salary, 2) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Net Salary') }}</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ${{ number_format($salaryLog->net_salary, 2) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $salaryLog->created_at->format('M d, Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.app>
