<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            <a href="{{ route('salaries.index') }}"
                class="hover:text-gray-900 dark:hover:text-gray-200">{{ __('Salary Logs') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('Edit') }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Salary Log') }}</h1>
    </div>

    <div class="max-w-5xl" x-data="salaryForm()">
        <form action="{{ route('salaries.update', $salaryLog) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="space-y-6">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Employee') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="user_id" id="user_id" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $salaryLog->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} {{ $user->surname }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="period_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Period From') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="period_from" id="period_from"
                                    value="{{ $salaryLog->period_from->format('Y-m-d') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            </div>

                            <div>
                                <label for="period_until" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Period Until') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="period_until" id="period_until"
                                    value="{{ $salaryLog->period_until->format('Y-m-d') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Salary Components') }}</h3>
                                <div class="space-y-2">
                                    <template x-for="(component, index) in components" :key="index">
                                        <div class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700 rounded">
                                            <input type="hidden" :name="'components[' + index + '][id]'" :value="component.id">
                                            <input type="text" :name="'components[' + index + '][name]'" x-model="component.name" class="flex-grow px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800" placeholder="Component Name">
                                            <input type="number" :name="'components[' + index + '][amount]'" x-model.number="component.amount" class="w-32 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800" placeholder="Amount" step="0.01">
                                            <button @click.prevent="removeComponent(index)" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button @click.prevent="addComponent" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">{{ __('Add Component') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Summary') }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Gross Salary') }}</label>
                                <p class="text-2xl font-semibold" x-text="formatCurrency(grossSalary)"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Net Salary') }}</label>
                                <p class="text-2xl font-semibold text-green-600" x-text="formatCurrency(netSalary)"></p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">{{ __('Update Salary Log') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function salaryForm() {
            return {
                components: @json($salaryComponents).map(c => ({ id: c.id, name: c.name, amount: c.sum })),
                grossSalary: 0,
                netSalary: 0,

                init() {
                    this.calculateTotals();
                    this.$watch('components', () => this.calculateTotals(), { deep: true });
                },

                addComponent() {
                    this.components.push({ name: '', amount: 0 });
                },

                removeComponent(index) {
                    this.components.splice(index, 1);
                },

                calculateTotals() {
                    this.grossSalary = this.components.filter(c => c.amount > 0).reduce((sum, c) => sum + (parseFloat(c.amount) || 0), 0);
                    this.netSalary = this.components.reduce((sum, c) => sum + (parseFloat(c.amount) || 0), 0);
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
                }
            }
        }
    </script>
</x-layouts.app>
