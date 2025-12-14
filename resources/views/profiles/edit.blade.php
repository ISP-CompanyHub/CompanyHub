@php use App\Enums\UserStatus; @endphp
<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('profiles.index') }}"
               class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Employee') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('profiles.show', $employee->id) }}"
               class="hover:text-gray-900 dark:hover:text-gray-200">{{ $employee->name }} {{ $employee->surname }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit Employee Profile') }}
        </h1>
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

    <form action="{{ route('profiles.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {{ __('Employee Information') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('First Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="surname"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Surname') }}</label>
                    <input type="text" id="surname" name="surname" value="{{ old('surname', $employee->surname) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="job_title"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Job Title') }}</label>
                    <input type="text" id="job_title" name="job_title"
                           value="{{ old('job_title', $employee->job_title) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="department_id"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Department') }}</label>
                    <select id="department_id" name="department_id"
                            class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id', $employee->department->id ?? '') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="phone_number"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone Number') }}</label>
                    <input type="text" id="phone_number" name="phone_number"
                           value="{{ old('phone_number', $employee->phone_number) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Send email notification') }}</label>
                    <input type="checkbox" id="send_email" name="send_email"
                           class="mt-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('send_email') ? 'checked' : '' }} value="{{old('send_email', '1') ? '1' : '0' }}">
                </div>

                <div>
                    <label for="status"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User Status') }}</label>
                    <select id="status" name="status"
                            class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @foreach(UserStatus::cases() as $status)
                            <option value="{{ $status->value }}"
                                {{ old('status', $employee->status->value) == $status->value ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="address"
                           class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $employee->address) }}"
                           class="p-2 mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('profiles.show', $employee->id) }}"
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
