<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General Ledger Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- jQuery + Select2 for searchable dropdown -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

    <style>
        body, html { font-family: Lato !important}
        body { background-color: #f9fafb; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 6px 10px; }
        /* Slide-in sidebar */
        #rightPane {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        #rightPane.hidden {
            transform: translateX(100%);
            opacity: 0;
        }
        #rightPane.visible {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen p-6">

<div class="flex flex-col md:flex-row gap-4 relative overflow-hidden">
    <!-- Left Column -->
    <div id="leftCol" class="w-full transition-all">
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">📒 General Ledger</h2>

            <!-- Reports Buttons -->
            <div class="flex gap-2 mb-6">
                <a href="{{ route('general_ledger.exportExcel', request()->all()) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-1">
                    ⬇️ Export Excel
                </a>
                <a href="{{ route('general_ledger.exportPdf', request()->all()) }}"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center gap-1">
                    ⬇️ Export PDF
                </a>
                <a href="{{ route('general_ledger.index') }}"
                   class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 flex items-center gap-1">
                    ❌ Clear
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('general_ledger.index') }}" class="flex flex-wrap gap-4 mb-6">
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
                <div class="min-w-[250px]">
                    <label class="block text-sm font-medium">Account</label>
                    <select id="accountSelect" name="account_id" class="w-full border rounded-lg">
                        <option value="">All Accounts</option>
                        @foreach($classes as $class)
                            <optgroup label="{{ $class->name_en }}">
                                @foreach($class->accounts as $acc)
                                    <option value="{{ $acc->id }}" {{ $account_id == $acc->id ? 'selected' : '' }}>
                                        {{ $acc->code }} - {{ $acc->name_en }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700">
                        🔍 Filter
                    </button>
                </div>
            </form>

            <!-- Ledger Report -->
            @foreach($ledgerData as $classCode => $accounts)
                <h3 class="text-xl font-semibold mt-6 mb-2 border-b pb-1 text-gray-700">
                    {{ $classes->where('code',$classCode)->first()->name_en }}
                </h3>

                @foreach($accounts as $acc)
                    <div class="mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">
                            {{ $acc['account']->code }} - {{ $acc['account']->name_en }}
                        </h4>

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="border px-2 py-1 text-left">Date</th>
                                    <th class="border px-2 py-1 text-left">Voucher No</th>
                                    <th class="border px-2 py-1 text-left">Description</th>
                                    <th class="border px-2 py-1 text-right">Debit</th>
                                    <th class="border px-2 py-1 text-right">Credit</th>
                                    <th class="border px-2 py-1 text-right">Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="bg-yellow-50 font-medium">
                                    <td colspan="5" class="text-right px-2 py-1">Opening Balance</td>
                                    <td class="text-right px-2 py-1">{{ number_format($acc['opening'], 2) }}</td>
                                </tr>
                                @forelse($acc['rows'] as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-2 py-1">{{ $row['date'] }}</td>
                                        <td class="border px-2 py-1">
                                            <a href="javascript:void(0)"
                                               onclick="openJV('{{ url('/docs/journal_vouchers/'.$row['voucher_id']) }}')"
                                               class="text-blue-600 hover:underline">
                                                {{ $row['voucher_no'] }}
                                            </a>
                                        </td>
                                        <td class="border px-2 py-1">{{ $row['description'] }}</td>
                                        <td class="border px-2 py-1 text-right">{{ number_format($row['debit'], 2) }}</td>
                                        <td class="border px-2 py-1 text-right">{{ number_format($row['credit'], 2) }}</td>
                                        <td class="border px-2 py-1 text-right">{{ number_format($row['balance'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-2">No transactions</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-green-50 font-medium">
                                    <td colspan="5" class="text-right px-2 py-1">Closing Balance</td>
                                    <td class="text-right px-2 py-1">{{ number_format($acc['closing'], 2) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Right Sidebar -->
    <div id="rightPane" class="hidden fixed top-0 right-0 h-full w-full md:w-1/3 bg-white shadow-xl border-l border-gray-200 z-50 flex flex-col">
        <div class="flex justify-between items-center p-3 border-b">
            <h3 class="font-semibold text-gray-700">Journal Voucher</h3>
            <div>
                <a id="openNewTabLink" href="#" target="_blank"
                   class="hidden bg-blue-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-blue-700">Open in New Tab</a>
                <button onclick="closeJV()" class="ml-2 text-gray-500 hover:text-red-600">✖</button>
            </div>
        </div>
        <div id="loader" class="hidden text-center py-4">Loading...</div>
        <iframe id="jvFrame" src="" class="flex-1 w-full opacity-0 transition-opacity duration-300"></iframe>
    </div>
</div>

<!-- Scripts -->
<script>
    $(document).ready(function () {
        $('#accountSelect').select2({ width: '100%' });
    });

    const leftCol = document.getElementById('leftCol');
    const rightPane = document.getElementById('rightPane');
    const jvFrame = document.getElementById('jvFrame');
    const loader = document.getElementById('loader');
    const openNewTabLink = document.getElementById('openNewTabLink');

    function openJV(rawUrl) {
        const url = rawUrl + (rawUrl.includes('?') ? '&' : '?') + 'embed=1';
        leftCol.classList.remove('w-full'); leftCol.classList.add('md:w-2/3');
        rightPane.classList.remove('hidden'); setTimeout(() => rightPane.classList.add('visible'), 10);
        openNewTabLink.href = rawUrl; openNewTabLink.classList.remove('hidden');
        loader.classList.remove('hidden'); jvFrame.classList.add('opacity-0');
        jvFrame.onload = () => { loader.classList.add('hidden'); jvFrame.classList.remove('opacity-0'); };
        jvFrame.src = url;
    }

    function closeJV() {
        rightPane.classList.remove('visible');
        setTimeout(() => rightPane.classList.add('hidden'), 300);
        leftCol.classList.remove('md:w-2/3'); leftCol.classList.add('w-full'); jvFrame.src = '';
    }
</script>

</body>
</html>
