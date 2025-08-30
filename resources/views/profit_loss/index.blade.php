<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit & Loss Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- jQuery + Select2 + Alpine.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body, html { font-family: Lato !important}
        body { font-family: Lato; background-color: #f9fafb; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 10px; }
    </style>
</head>
<body class="min-h-screen p-6" x-data="{ selectedAccount: null, docs: [] }">

<div class="max-w-7xlx mx-autox">

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">

        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-4">
            <h2 class="text-3xl font-bold text-gray-800">📊 Profit & Loss</h2>
            <div class="flex gap-2">
                <a href="{{ route('profit_loss.exportExcel', request()->all()) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">⬇️ Export Excel</a>
                <a href="{{ route('profit_loss.exportPdf', request()->all()) }}"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">⬇️ Export PDF</a>
                <a href="{{ route('profit_loss.index') }}"
                   class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">❌ Clear</a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('profit_loss.index') }}" class="flex flex-wrap gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium">From</label>
                <input type="date" name="date_from" value="{{ $date_from }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium">To</label>
                <input type="date" name="date_to" value="{{ $date_to }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-[260px]">
                <label class="block text-sm font-medium">Account (optional)</label>
                <select id="accountSelect" name="account_id" class="w-full border rounded-lg">
                    <option value="">All Accounts</option>
                    @foreach($classes as $class)
                        <optgroup label="{{ $class->name_en }}">
                            @foreach($class->accounts as $acc)
                                <option value="{{ $acc->id }}" {{ ($account_id == $acc->id) ? 'selected' : '' }}>
                                    {{ $acc->code }} - {{ $acc->name_en }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700">🔍 Filter</button>
            </div>
        </form>

        @php
            $classMap = [
                '4' => 'Revenue',
                '6' => 'Other Income',
                '5' => 'Expenses',
                '7' => 'Other Expenses',
            ];
        @endphp

        <!-- Report with slide-in right-side doc panel -->
        <div class="flex gap-4 relative">
            <!-- Left: P&L tables -->
            <div :class="selectedAccount ? 'w-2/3 transition-all duration-500' : 'w-full transition-all duration-500'">
                @foreach (['4','6','5','7'] as $code)
                    @if(isset($report['classes'][$code]) && !empty($report['classes'][$code]['accounts']))
                        @php $groupTotal = $report['classes'][$code]['subtotal']; @endphp

                        <div x-data="{ open: true }" class="mb-6 border rounded-lg">
                            <h3 @click="open = !open"
                                class="cursor-pointer text-lg font-semibold text-gray-700 bg-gray-100 px-4 py-2 flex justify-between items-center">
                                <span>{{ $classes->where('code', $code)->first()->name_en ?? ($classMap[$code] ?? $code) }}</span>
                                <span x-text="open ? '▼' : '▶'"></span>
                            </h3>

                            <div x-show="open" class="overflow-x-auto rounded-b-lg border-t border-gray-200">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 text-gray-700">
                                        <tr>
                                            <th class="border px-2 py-1 text-left">Account</th>
                                            <th class="border px-2 py-1 text-right">Amount</th>
                                            <th class="border px-2 py-1 text-right">% of Group</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($report['classes'][$code]['accounts'] as $a)
                                            <tr @click="$dispatch('account-selected', {{ $a['id'] }})"
                                                class="hover:bg-gray-50 cursor-pointer">
                                                <td class="border px-2 py-1">{{ $a['code'] }} - {{ $a['name'] }}</td>
                                                <td class="border px-2 py-1 text-right">{{ number_format($a['amount'], 2) }}</td>
                                                <td class="border px-2 py-1 text-right">
                                                    {{ $groupTotal != 0 ? number_format(($a['amount'] / $groupTotal) * 100, 2) : '0.00' }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-green-50 font-medium">
                                            <td class="border px-2 py-1 text-right">Subtotal – {{ $classMap[$code] }}</td>
                                            <td class="border px-2 py-1 text-right">{{ number_format($groupTotal, 2) }}</td>
                                            <td class="border px-2 py-1 text-right">100.00%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            &nbsp;
            <!-- Right: Account Docs Panel -->
            <div style="border: 2px solid lightskyblue;" class="absolute right-0 top-0 h-full w-1/3 bg-gray-50 border-l border-gray-200 p-4"
                 x-show="selectedAccount"
                 x-transition:enter="transition transform duration-500"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition transform duration-500"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 x-data
                 @account-selected.window="
                     selectedAccount = $event.detail;
                     docs = [];
                     fetch('/system/profit_loss/account_docs/' + selectedAccount)
                         .then(res => res.json())
                         .then(data => docs = data);
                 ">
                <h4 class="font-semibold mb-2">Related Documents</h4>
                <ul>
                    <template x-for="doc in docs" :key="doc.id">
                        <li class="border-b py-1 flex justify-between items-center">
                            <span x-text="doc.name"></span>
                            <span x-text="doc.date"></span>
                            <a :href="'{{ url('/') }}/journal_vouchers/' + doc.id" target="_blank"
                               class="text-red-600 hover:text-red-800 ml-2" title="Open PDF">
                                📄
                            </a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- Totals -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-sm text-green-700 font-semibold">Total Income</div>
                <div class="text-2xl font-bold text-green-700">{{ number_format($report['total_income'], 2) }}</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-sm text-red-700 font-semibold">Total Expenses</div>
                <div class="text-2xl font-bold text-red-700">{{ number_format($report['total_expense'], 2) }}</div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-sm text-blue-700 font-semibold">Net Profit</div>
                <div class="text-2xl font-bold text-blue-700">{{ number_format($report['net_profit'], 2) }}</div>
            </div>
        </div>

    </div>
</div>

<script>
    $(function () { $('#accountSelect').select2({ width: '100%' }); });
</script>
</body>
</html>
