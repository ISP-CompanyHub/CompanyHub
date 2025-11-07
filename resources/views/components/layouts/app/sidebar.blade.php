<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
       class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                        :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                <!-- Recruitment -->
                @canany(['view job postings', 'view candidates', 'view interviews', 'view job offers'])
                    <x-layouts.sidebar-two-level-link-parent title="Recruitment" icon="fas-users"
                                                             :active="request()->routeIs(
                            'job-postings.*',
                            'candidates.*',
                            'interviews.*',
                            'job-offers.*',
                        )">

                        @can('view job postings')
                            <x-layouts.sidebar-two-level-link href="{{ route('job-postings.index') }}"
                                                              icon='fas-briefcase' :active="request()->routeIs('job-postings.*')">Job
                                Postings</x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view candidates')
                            <x-layouts.sidebar-two-level-link href="{{ route('candidates.index') }}"
                                                              icon='fas-user-tie' :active="request()->routeIs('candidates.*')">Candidates</x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view interviews')
                            <x-layouts.sidebar-two-level-link href="{{ route('interviews.index') }}"
                                                              icon='fas-calendar-check'
                                                              :active="request()->routeIs('interviews.*')">Interviews</x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view job offers')
                            <x-layouts.sidebar-two-level-link href="{{ route('job-offers.index') }}"
                                                              icon='fas-file-contract' :active="request()->routeIs('job-offers.*')">Job
                                Offers</x-layouts.sidebar-two-level-link>
                        @endcan

                    </x-layouts.sidebar-two-level-link-parent>
                @endcanany

                @can('view documents')
                    <x-layouts.sidebar-link
                        href="{{ route('documents.index') }}"  icon="fas-file-lines"
                        :active="request()->routeIs('documents.*')">Documents
                    </x-layouts.sidebar-link>
                @endcan

                {{-- Vacation parent with dropdown: Requests + Approvals --}}
                @canany(['view vacation requests', 'approve vacation requests'])
                    <x-layouts.sidebar-two-level-link-parent
                        title="Vacation"
                        icon="fas-calendar"
                        :active="request()->routeIs('vacation.*')">

                        @can('view vacation requests')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('vacation.index') }}"
                                icon="fas-calendar-days"
                                :active="request()->routeIs('vacation.index')">
                                {{ __('Requests') }}
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('approve vacation requests')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('vacation.approvals') }}"
                                icon="fas-check-circle"
                                :active="request()->routeIs('vacation.approvals')">
                                {{ __('Approvals') }}
                            </x-layouts.sidebar-two-level-link>
                        @endcan
                            @can('view holidays')
                                <x-layouts.sidebar-two-level-link
                                    href="{{ route('holidays.index') }}"
                                    icon="fas-umbrella-beach"
                                    :active="request()->routeIs('holidays.*')">
                                    {{ __('Holidays') }}
                                </x-layouts.sidebar-two-level-link>
                            @endcan

                    </x-layouts.sidebar-two-level-link-parent>
                @endcanany

            </ul>
        </nav>
    </div>
</aside>
