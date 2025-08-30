<table>
    <tr>
        <td colspan="3" style="font-size:16px; font-weight:bold;">📊 Profit &amp; Loss Report</td>
    </tr>
    <tr>
        <td colspan="3">Period: {{ $date_from }} → {{ $date_to }}</td>
    </tr>
    <tr></tr>

    {{-- Income Section --}}
    @foreach (['4','6'] as $code)
        @if(isset($report['classes'][$code]) && !empty($report['classes'][$code]['accounts']))
            <tr style="background-color:#e2f0d9; font-weight:bold;">
                <td colspan="3">
                    {{ $report['classes'][$code]['class_name'] ?? $code }}
                </td>
            </tr>
            <tr style="font-weight:bold;">
                <td>Account</td>
                <td align="right">Amount</td>
                <td align="right">% of Group</td>
            </tr>

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
            <tr style="background-color:#d9ead3; font-weight:bold;">
                <td align="right">Subtotal – {{ $report['classes'][$code]['class_name'] }}</td>
                <td align="right">{{ number_format($groupTotal, 2) }}</td>
                <td align="right">100.00%</td>
            </tr>
            <tr></tr>
        @endif
    @endforeach

    {{-- Expense Section --}}
    @foreach (['5','7'] as $code)
        @if(isset($report['classes'][$code]) && !empty($report['classes'][$code]['accounts']))
            <tr style="background-color:#f4cccc; font-weight:bold;">
                <td colspan="3">
                    {{ $report['classes'][$code]['class_name'] ?? $code }}
                </td>
            </tr>
            <tr style="font-weight:bold;">
                <td>Account</td>
                <td align="right">Amount</td>
                <td align="right">% of Group</td>
            </tr>

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
            <tr style="background-color:#fce5cd; font-weight:bold;">
                <td align="right">Subtotal – {{ $report['classes'][$code]['class_name'] }}</td>
                <td align="right">{{ number_format($groupTotal, 2) }}</td>
                <td align="right">100.00%</td>
            </tr>
            <tr></tr>
        @endif
    @endforeach

    {{-- Totals --}}
    <tr style="font-weight:bold;">
        <td>Total Income</td>
        <td align="right">{{ number_format($report['total_income'], 2) }}</td>
        <td></td>
    </tr>
    <tr style="font-weight:bold;">
        <td>Total Expenses</td>
        <td align="right">{{ number_format($report['total_expense'], 2) }}</td>
        <td></td>
    </tr>
    <tr style="background-color:#c9daf8; font-weight:bold;">
        <td>Net Profit</td>
        <td align="right">{{ number_format($report['net_profit'], 2) }}</td>
        <td></td>
    </tr>
</table>
