<!-- Consumer Ledger View -->
<div class="space-y-6" x-data="{ selectedConsumer: null, transactions: [] }">
    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="flex gap-4">
            <input type="hidden" name="view" value="consumers">
            <div class="flex-1">
                <input type="text" name="consumer_search" value="{{ request('consumer_search') }}"
                    placeholder="Search by account number or name..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit"
                class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition-colors">
                Search
            </button>
            <a href="?view=consumers"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Clear
            </a>
        </form>
    </div>

    <!-- Consumer List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-white">Consumer Ledger</h2>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($consumerLedger['consumers'] as $consumer)
                        @php
                            $transactions = $consumerLedger['details'][$consumer->consumer_id] ?? [];
                            $currentBalance = count($transactions) > 0 ? end($transactions)['balance'] : 0;
                            $totalBills = array_sum(array_column($transactions, 'amount'));
                            $totalPayments = array_sum(array_column($transactions, 'payment'));
                        @endphp

                        <!-- Consumer Row (Clickable) -->
                        <button @click="selectedConsumer = {{ json_encode([
                    'account_number' => $consumer->account_number,
                    'name' => $consumer->first_name . ' ' . $consumer->last_name,
                    'type' => ucfirst($consumer->connection_type ?? 'N/A'),
                    'balance' => $currentBalance,
                    'total_bills' => $totalBills,
                    'total_payments' => $totalPayments,
                    'transaction_count' => count($transactions)
                ]) }}; transactions = {{ json_encode($transactions) }}"
                            class="w-full px-6 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($consumer->account_number, -3) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $consumer->account_number }} - {{ $consumer->first_name }}
                                                {{ $consumer->last_name }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Type: <span
                                                    class="font-medium">{{ ucfirst($consumer->connection_type ?? 'N/A') }}</span>
                                                | Transactions: <span class="font-medium">{{ count($transactions) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-6">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Balance</p>
                                        <p
                                            class="text-2xl font-bold @if($currentBalance > 0) text-red-600 dark:text-red-400 @else text-green-600 dark:text-green-400 @endif">
                                            ₱{{ number_format($currentBalance, 2) }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </button>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="h-16 w-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No consumers found</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or check back later.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div x-show="selectedConsumer !== null" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="selectedConsumer !== null" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="selectedConsumer = null"
                class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="selectedConsumer !== null" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white"
                                x-text="selectedConsumer ? selectedConsumer.account_number + ' - ' + selectedConsumer.name : ''">
                            </h3>
                            <p class="text-sm text-cyan-100 mt-1">
                                <span x-text="selectedConsumer ? 'Type: ' + selectedConsumer.type : ''"></span>
                                <span x-show="selectedConsumer" class="mx-2">|</span>
                                <span
                                    x-text="selectedConsumer ? 'Total Transactions: ' + selectedConsumer.transaction_count : ''"></span>
                            </p>
                        </div>
                        <button @click="selectedConsumer = null"
                            class="text-white hover:text-gray-200 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                            <p class="text-sm text-green-600 dark:text-green-400 font-medium">Total Bills</p>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300"
                                x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.total_bills).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₱0.00'">
                            </p>
                        </div>
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Payments</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300"
                                x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.total_payments).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₱0.00'">
                            </p>
                        </div>
                        <div class="rounded-lg p-4 border"
                            :class="selectedConsumer && selectedConsumer.balance > 0 ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'">
                            <p class="text-sm font-medium"
                                :class="selectedConsumer && selectedConsumer.balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'">
                                Current Balance</p>
                            <p class="text-2xl font-bold"
                                :class="selectedConsumer && selectedConsumer.balance > 0 ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300'"
                                x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₱0.00'">
                            </p>
                        </div>
                    </div>

                    <!-- Transaction Table -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full" x-show="transactions.length > 0">
                            <thead class="bg-gray-100 dark:bg-gray-800 border-b-2 border-gray-300 dark:border-gray-600">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-300 dark:border-gray-600">
                                        Month</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-300 dark:border-gray-600">
                                        Bill</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-300 dark:border-gray-600">
                                        Payment</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-300 dark:border-gray-600">
                                        Balance</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900/30 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="(transaction, index) in transactions" :key="index">
                                    <tr :class="index % 2 == 0 ? 'bg-gray-50/50 dark:bg-gray-800/20' : ''"
                                        class="hover:bg-blue-50 dark:hover:bg-blue-900/10">
                                        <!-- Month -->
                                        <td
                                            class="px-4 py-3 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                                            <span x-show="transaction.type == 'Bill'"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400"
                                                x-text="transaction.billing_month"></span>
                                            <span x-show="transaction.type == 'Payment'"
                                                class="text-sm text-gray-400 italic">Payment Received</span>
                                        </td>
                                        <!-- Bill -->
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center border-r border-gray-200 dark:border-gray-700">
                                            <span x-show="transaction.type == 'Bill'"
                                                class="text-base font-bold text-green-600 dark:text-green-400"
                                                x-text="'₱' + parseFloat(transaction.amount).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                            <span x-show="transaction.type != 'Bill'"
                                                class="text-gray-300 dark:text-gray-600">-</span>
                                        </td>
                                        <!-- Payment -->
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center border-r border-gray-200 dark:border-gray-700">
                                            <span x-show="transaction.type == 'Payment'"
                                                class="text-base font-bold text-blue-600 dark:text-blue-400"
                                                x-text="'₱' + parseFloat(transaction.payment).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                            <span x-show="transaction.type != 'Payment'"
                                                class="text-gray-300 dark:text-gray-600">-</span>
                                        </td>
                                        <!-- Balance -->
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center border-r border-gray-200 dark:border-gray-700">
                                            <span class="text-base font-bold"
                                                :class="transaction.balance > 0 ? 'text-red-600 dark:text-red-400' : (transaction.balance == 0 ? 'text-gray-600 dark:text-gray-400' : 'text-green-600 dark:text-green-400')"
                                                x-text="'₱' + parseFloat(transaction.balance).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                        </td>
                                        <!-- Date -->
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="new Date(transaction.date).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})">
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="new Date(transaction.date).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})">
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Totals Row -->
                                <tr class="bg-gray-100 dark:bg-gray-800 font-bold border-t-2 border-gray-300 dark:border-gray-600"
                                    x-show="transactions.length > 0">
                                    <td class="px-4 py-3 text-right border-r border-gray-300 dark:border-gray-600">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">TOTALS:</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-300 dark:border-gray-600">
                                        <span class="text-base text-green-700 dark:text-green-400"
                                            x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.total_bills).toLocaleString('en-US', {minimumFractionDigits: 2}) : ''"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-300 dark:border-gray-600">
                                        <span class="text-base text-blue-700 dark:text-blue-400"
                                            x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.total_payments).toLocaleString('en-US', {minimumFractionDigits: 2}) : ''"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-300 dark:border-gray-600"
                                        colspan="2">
                                        <span class="text-base"
                                            :class="selectedConsumer && selectedConsumer.balance > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-400'"
                                            x-text="selectedConsumer ? '₱' + parseFloat(selectedConsumer.balance).toLocaleString('en-US', {minimumFractionDigits: 2}) : ''"></span>
                                        <span class="text-xs ml-2 text-gray-600 dark:text-gray-400"
                                            x-text="selectedConsumer && selectedConsumer.balance > 0 ? '(Outstanding)' : (selectedConsumer && selectedConsumer.balance == 0 ? '(Paid in Full)' : '(Credit)')"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div x-show="transactions.length == 0" class="p-8 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">No transaction history.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex justify-end">
                    <button @click="selectedConsumer = null"
                        class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>