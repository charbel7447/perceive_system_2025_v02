        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr class="fw-bold">
                    <th>Class Code</th>
                    <th>Class</th>
                    <th>Class ar</th>
                    <th>Account</th>
                    <th>Account Name</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                    function colorClass($value) {
                        if ($value > 0) return 'text-success';  // green
                        if ($value < 0) return 'text-danger';   // red
                        return 'text-secondary';                 // gray
                    }
                @endphp
                @foreach($trialBalances as $row)
                    @php
                        $totalDebit += $row->total_debit;
                        $totalCredit += $row->total_credit;
                    @endphp
                    <tr>
                        <td>{{ $row->account_id }}</td>
                        <td>{{ $row->class_name_en }}</td>
                        <td>{{ $row->class_name_ar }}</td>
                        <td>{{ $row->account_code }}</td>
                        <td>{{ $row->account_name_en }}</td>
                        <td class="text-end {{ colorClass($row->total_debit) }}">{{ number_format($row->total_debit, 3) }}</td>
                        <td class="text-end {{ colorClass($row->total_credit) }}">{{ number_format($row->total_credit, 3) }}</td>
                        <td class="text-end {{ colorClass($row->balance) }}">{{ number_format($row->balance, 3) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <th colspan="5" class="text-end">Total</th>
                    <th class="text-end {{ colorClass($totalDebit) }}">{{ number_format($totalDebit, 3) }}</th>
                    <th class="text-end {{ colorClass($totalCredit) }}">{{ number_format($totalCredit, 3) }}</th>
                    <th class="text-end {{ colorClass($totalDebit - $totalCredit) }}">{{ number_format($totalDebit - $totalCredit, 3) }}</th>
                </tr>
            </tfoot>
        </table>
 