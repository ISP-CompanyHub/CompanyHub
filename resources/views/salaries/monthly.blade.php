<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Monthly Salary Calculation') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Generate a monthly salary calculation for an employee.') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <form action="{{ route('salaries.monthly') }}" method="GET">
            <div class="flex items-center gap-4">
                <div class="flex-grow">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Employee') }}</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">{{ __('Select Employee') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow">
                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Month') }}</label>
                    <input type="month" name="month" id="month" value="{{ request('month', date('Y-m')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                </div>
                <div class="pt-5">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">{{ __('Generate') }}</button>
                </div>
            </div>
        </form>
    </div>

    @if ($reportData)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ __('Salary Details for') }} {{ $reportData['selectedUser']->name }} {{ $reportData['selectedUser']->surname }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Employee Information') }}</h3>
                    <dl>
                        <div class="flex justify-between py-2 border-b">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $reportData['selectedUser']->name }} {{ $reportData['selectedUser']->surname }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department') }}</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $reportData['selectedUser']->department->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Job Title') }}</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $reportData['selectedUser']->job_title }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Salary Summary') }}</h3>
                    <dl>
                        <div class="flex justify-between py-2 border-b">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Gross Salary') }}</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($reportData['grossSalary'], 2) }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Net Salary') }}</dt>
                            <dd class="text-sm font-semibold text-green-600 dark:text-green-400">${{ number_format($reportData['netSalary'], 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Salary Components') }}</h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($reportData['groupedComponents'] as $component)
                        <li class="flex justify-between py-2">
                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $component->name }}</span>
                            <span class="text-sm font-medium @if($component->pivot->amount < 0) text-red-600 @else text-green-600 @endif">
                                ${{ number_format($component->pivot->amount, 2) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6 text-right">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">{{ __('Send Email') }}</button>
            </div>
        </div>
    @elseif(request()->has('user_id') && request('user_id'))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
            <p class="text-gray-500 dark:text-gray-400">{{ __('No salary log found for the selected employee and month.') }}</p>
        </div>
    @endif
</x-layouts.app>
