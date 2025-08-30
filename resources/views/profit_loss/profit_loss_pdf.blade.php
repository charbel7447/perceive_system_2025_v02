<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit &amp; Loss PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background-color: #f0f0f0; }
        .income { background-color: #e2f0d9; }
        .expense { background-color: #f4cccc; }
        .subtotal { font-weight:bold; }
        .totals td { font-weight: bold; }
    </style>
</head>
<body>
    <h2>📊 Profit &amp; Loss Report</h2>
    <p>Period: {{ $date_from }} → {{ $date_to }}</p>

    {{-- Income --}}
    @foreach (['4','6'] as $code)
        @if(isset($report['classes'][$code]) && !empty($report['classes'][$code]['accounts']))
            <h3>{{ $report['classes'][$code]['class_name'] }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th align="right">Amount</th>
                        <th align="right">% of Group</th>
                    </tr>
                </thead>
                <tbody>
                @php $groupTotal = $report['classes'][$code]['subtotal']; @endphp
                @foreach($report['classes'][$code]['accounts'] as $a)
                    <tr>
                        <td>{{ $a['code'] }} - {{ $a['name'] }}</td>
                        <td align="right">{{ number_format($a['amount'], 2) }}</td>
                        <td align="right">
                            {{ $groupTotal != 0 ? number_format(($a['amount']/$groupTotal)*100,2) : '0.00' }}%
                        </td>
                    </tr>
                @endforeach
                <tr class="subtotal income">
                    <td align="right">Subtotal – {{ $report['classes'][$code]['class_name'] }}</td>
                    <td align="right">{{ number_format($groupTotal, 2) }}</td>
                    <td align="right">100.00%</td>
                </tr>
                </tbody>
            </table>
        @endif
    @endforeach

    {{-- Expenses --}}
    @foreach (['5','7'] as $code)
        @if(isset($report['classes'][$code]) && !empty($report['classes'][$code]['accounts']))
            <h3>{{ $report['classes'][$code]['class_name'] }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th align="right">Amount</th>
                        <th align="right">% of Group</th>
                    </tr>
                </thead>
                <tbody>
                @php $groupTotal = $report['classes'][$code]['subtotal']; @endphp
                @foreach($report['classes'][$code]['accounts'] as $a)
                    <tr>
                        <td>{{ $a['code'] }} - {{ $a['name'] }}</td>
                        <td align="right">{{ number_format($a['amount'], 2) }}</td>
                        <td align="right">
                            {{ $groupTotal != 0 ? number_format(($a['amount']/$groupTotal)*100,2) : '0.00' }}%
                        </td>
                    </tr>
                @endforeach
                <tr class="subtotal expense">
                    <td align="right">Subtotal – {{ $report['classes'][$code]['class_name'] }}</td>
                    <td align="right">{{ number_format($groupTotal, 2) }}</td>
                    <td align="right">100.00%</td>
                </tr>
                </tbody>
            </table>
        @endif
    @endforeach

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td>Total Income</td>
            <td align="right">{{ number_format($report['total_income'], 2) }}</td>
        </tr>
        <tr>
            <td>Total Expenses</td>
            <td align="right">{{ number_format($report['total_expense'], 2) }}</td>
        </tr>
        <tr>
            <td>Net Profit</td>
            <td align="right">{{ number_format($report['net_profit'], 2) }}</td>
        </tr>
    </table>
</body>
</html>
