<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('CompanyHub Structure') }}</h1>

        <form action="{{ route('company-structure.pdf') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                {{ __('Download PDF') }}
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 org-chart overflow-x-auto">
        @if($departments->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">{{ __('No departments found.') }}</p>
        @else
            <ul class="min-w-max">
                @foreach($departments as $department)
                    <li>
                        <div class="node node-department">
                            <span class="font-semibold">{{ $department->name }}</span>
                            @if($department->lead)
                                <span class="block text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Lead') }}: {{ strtoupper($department->lead->name[0]) }}. {{ ucfirst($department->lead->surname) }}
                                </span>
                            @endif
                        </div>
                        @if($department->employees->isNotEmpty())
                            <ul>
                                @foreach($department->employees as $employee)
                                    <li>
                                        {{-- Employee Node --}}
                                        <div class="node node-employee">
                                            {{ $employee->name }} {{ $employee->surname }}
                                            <span class="block text-gray-500 dark:text-gray-400">
                                                {{ $employee->job_title ?? '—' }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layouts.app>
