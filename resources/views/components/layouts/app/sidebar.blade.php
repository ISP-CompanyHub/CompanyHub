<aside class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                        :active="request()->routeIs('dashboard*')">
                    Dashboard
                </x-layouts.sidebar-link>

                <!-- Recruitment -->
                @canany(['view job postings', 'view candidates', 'view interviews', 'view job offers'])
                    <x-layouts.sidebar-two-level-link-parent title="Recruitment" icon="fas-users"
                                                             :active="request()->routeIs(
                                                                'job-postings.*',
                                                                'candidates.*',
                                                                'interviews.*',
                                                                'job-offers.*'
                                                            )">

                        @can('view job postings')
                            <x-layouts.sidebar-two-level-link href="{{ route('job-postings.index') }}"
                                                              icon='fas-briefcase' :active="request()->routeIs('job-postings.*')">
                                Job Postings
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view candidates')
                            <x-layouts.sidebar-two-level-link href="{{ route('candidates.index') }}"
                                                              icon='fas-user-tie' :active="request()->routeIs('candidates.*')">
                                Candidates
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view interviews')
                            <x-layouts.sidebar-two-level-link href="{{ route('interviews.index') }}"
                                                              icon='fas-calendar-check'
                                                              :active="request()->routeIs('interviews.*')">
                                Interviews
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view job offers')
                            <x-layouts.sidebar-two-level-link href="{{ route('job-offers.index') }}"
                                                              icon='fas-file-contract' :active="request()->routeIs('job-offers.*')">
                                Job Offers
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                    </x-layouts.sidebar-two-level-link-parent>
                @endcanany

                @can('view documents')
                    <x-layouts.sidebar-link
                        href="{{ route('documents.index') }}"  icon="fas-file-lines"
                        :active="request()->routeIs('documents.*')">
                        Documents
                    </x-layouts.sidebar-link>
                @endcan

                @canany(['view vacation requests', 'approve vacation requests', 'view holidays', 'view leave balances'])
                    <x-layouts.sidebar-two-level-link-parent
                        title="Vacation"
                        icon="fas-calendar"
                        :active="(
                            request()->routeIs('vacation.*')
                            || request()->routeIs('vacation.approvals*')
                            || request()->routeIs('vacation.balances*')
                            || request()->routeIs('holidays.*')
                        )">

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
                                :active="request()->routeIs('vacation.approvals*')">
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

                @canany(['view employee profile', 'view departments', 'view employees', 'generate company structure'])
                    <x-layouts.sidebar-two-level-link-parent
                        title="Employees & Departments"
                        icon="fas-users-gear" :active="request()->routeIs('profiles.*', 'departments.*', 'company-structure.*')"
                    >
                        @can('view employee profile')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('profiles.show', request()->user()) }}"
                                icon="fas-id-card" :active="request()->routeIs('profiles.show')"
                            >
                                View Profile
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view employees')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('profiles.index') }}"
                                icon="fas-users" :active="request()->routeIs('profiles.index', 'profiles.edit')"
                            >
                                View Employees
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('view departments')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('departments.index') }}"
                                icon="fas-building" :active="request()->routeIs('departments.*')"
                            >
                                View Departments
                            </x-layouts.sidebar-two-level-link>
                        @endcan

                        @can('generate company structure')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('company-structure.index') }}"
                                icon="fas-diagram-project" :active="request()->routeIs('company-structure.*')"
                            >
                                Company Structure
                            </x-layouts.sidebar-two-level-link>
                        @endcan
                    </x-layouts.sidebar-two-level-link-parent>
                @endcanany
				
				@canany(['view salaries', 'generate monthly salary'])
                    <x-layouts.sidebar-two-level-link-parent
                        title="Financials"
                        icon="fas-dollar-sign" :active="request()->routeIs('salaries.*')"
                    >
                        @can('view salaries')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('salaries.index') }}"
                                icon="fas-file-invoice-dollar" :active="request()->routeIs('salaries.index')"
                            >
                                Salaries
                            </x-layouts.sidebar-two-level-link>
                        @endcan
                        @can('generate monthly salary')
                            <x-layouts.sidebar-two-level-link
                                href="{{ route('salaries.monthly') }}"
                                icon="fas-calculator" :active="request()->routeIs('salaries.monthly')"
                            >
                                Monthly Calculation
                            </x-layouts.sidebar-two-level-link>
                        @endcan
                    </x-layouts.sidebar-two-level-link-parent>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
