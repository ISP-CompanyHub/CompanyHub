<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('departments.index') }}"
               class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Departments') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $department->name }}</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit Department') }}
        </h1>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Single card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {{ __('Department Information') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                {{-- Lead --}}
                <div>
                    <label for="department_lead_id" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department Lead') }}</label>
                    <select id="department_lead_id" name="department_lead_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">{{ __('No Lead Assigned') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('department_lead_id', $department->department_lead_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->surname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('description', $department->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end mt-6">
            <a href="{{ route('departments.index') }}"
               class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-lg">
                {{ __('Cancel') }}
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                {{ __('Save Changes') }}
            </button>
        </div>
    </form>
</x-layouts.app>
