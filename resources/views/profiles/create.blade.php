<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('profiles.index') }}"
               class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Employees') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Create Profile') }}</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Employee Profile') }}</h1>
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

    <form action="{{ route('profiles.store') }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('First Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="surname" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Surname') }}</label>
                    <input type="text" name="surname" id="surname" value="{{ old('surname') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="job_title" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Job Title') }}</label>
                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department') }}</label>
                    <select name="department_id" id="department_id"
                            class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">{{ __('Select Department') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="personal_id" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Personal ID') }}</label>
                    <input type="text" name="personal_id" id="email" value="{{ old('personal_id') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date of Birth') }}</label>
                    <input type="date" name="date_of_birth" id="email" value="{{ old('date_of_birth') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Password') }}</label>
                    <input type="password" name="password" id="password" value="{{ old('password') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Confirm password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('profiles.index') }}"
               class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-lg">
                {{ __('Cancel') }}
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                {{ __('Create Profile') }}
            </button>
        </div>
    </form>
</x-layouts.app>
