<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('documents.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Documents') }}</a>
            <span class="mx-2">/</span>
            <span>{{ $documents->name }}</span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $documents->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Creation Date:   {{ $documents -> change_date -> format('M d, Y H:i')}}
                </p>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Last Update:   {{ $documents->change_date -> format('M d, Y H:i')}} 
                </p>
            </div>
            <div class="flex space-x-2">
                @can('edit documents')
                    <a href="{{ route('documents.edit', $documents -> id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('edit documents')
                    <form action="{{ route('documents.destroy', $documents -> id) }}" method="POST"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}')">
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

    <div class="grid grid-cols-1 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    {{ __('Document versions') }} ({{ $documents -> count }})
                </h2>
                @if ($documents -> count > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('File Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Change Date') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Comment') }}
                        </th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Download') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                
                
                <div class="space-y-3">    
                {{-- @foreach ($documents->versions as $version) --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $documents->filename }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $documents->change_date -> format('M d, Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $documents->comment ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ $document->file_url ?? '#' }}" download
                                class="inline-flex items-center justify-center p-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600"
                                title="Download">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                            </td>

                        </tr>
                    {{-- @endforeach --}} 
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
