<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('documents.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Documents') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('documents.show', $document -> id) }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ $document->name }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Document') }}</h1>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('documents.update', $document -> id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-errors />

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Document Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('title',$document->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Comment') }}
                    </label>
                    <textarea name="comment" id="comment" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ old('comment', $document-> comment) }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <p>Current file: {{ $document -> filename }} </p>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('File') }}  <span class="text-red-500">*</span>
                    </label>
                    
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.txt" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 file:mr-4 file:py-2 file:px-4">
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="convert" value="1" id="convert"
                            {{ old('convert', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span
                            class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Convert to PDF') }}</span>
                    </label>
                </div>


                   

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('documents.index', $document -> id) }}"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        {{ __('Update Document') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
