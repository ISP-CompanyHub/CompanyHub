<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <span>{{ __('Departments') }}</span>
        </div>

        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ __('Departments') }}
            </h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('departments.edit') }}" class="mb-4">
        <div class="flex items-end space-x-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="{{ __('Search departments...') }}"
                   class="w-full md:w-1/3 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg">
                {{ __('Search') }}
            </button>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            {{-- Table Header --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 grid grid-cols-12 gap-4">
                <div class="col-span-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Name') }}
                </div>
                <div class="col-span-5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Description') }}
                </div>
                <div class="col-span-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Lead') }}
                </div>
                <div class="col-span-1 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('Actions') }}
                </div>
            </div>

            {{-- Table Body --}}
            @forelse ($departments as $department)
                <div x-data="{ expanded: {{ old('_department_id') == $department->id ? 'true' : 'false' }} }">
                    {{-- Row --}}
                    <div class="px-6 py-4 grid grid-cols-12 gap-4 items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                         @click="expanded = !expanded">
                        <div class="col-span-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $department->name }}
                        </div>
                        <div class="col-span-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ Str::limit($department->description, 80) ?: '—' }}
                        </div>
                        <div class="col-span-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ $department->lead?->name ?: __('No Lead Assigned') }}
                        </div>
                        @can('edit departments')
                            <div class="col-span-1 text-right text-sm">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                        @click.stop="expanded = !expanded">
                                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': expanded }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div class="col-span-1"></div>
                        @endcan
                    </div>

                    {{-- Expandable Edit Form --}}
                    @can('edit departments')
                        <div x-show="expanded" x-collapse x-cloak
                             class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600">
                            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_department_id" value="{{ $department->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="name_{{ $department->id }}" class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Department Name') }}
                                        </label>
                                        <input type="text" id="name_{{ $department->id }}" name="name"
                                               value="{{ old('_department_id') == $department->id ? old('name') : $department->name }}"
                                               class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                               required>
                                    </div>

                                    <div>
                                        <label for="department_lead_id_{{ $department->id }}" class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Department Lead') }}
                                        </label>
                                        <select id="department_lead_id_{{ $department->id }}" name="department_lead_id"
                                                class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="">{{ __('No Lead Assigned') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ (old('_department_id') == $department->id ? old('department_lead_id') : $department->department_lead_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} {{ $user->surname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="description_{{ $department->id }}" class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Description') }}
                                        </label>
                                        <textarea id="description_{{ $department->id }}" name="description" rows="3"
                                                  class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('_department_id') == $department->id ? old('description') : $department->description }}</textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" @click="expanded = false"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200 px-4 py-2 rounded-lg text-sm">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endcan
                </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    {{ __('No departments found.') }}
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</x-layouts.app>
