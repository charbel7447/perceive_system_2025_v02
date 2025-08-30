<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #004d99; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>General Ledger Report</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>JV #</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach($items as $item)
            @if($item->journalVoucher->status_id == 2)
                @php $balance += $item->debit - $item->credit; @endphp
                <tr>
                    <td>{{ $item->journalVoucher->date ?? '' }}</td>
                    <td>({{ $item->journal_voucher_id }}) {{ $item->journalVoucher->number}}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->debit,2) }}</td>
                    <td>{{ number_format($item->credit,2) }}</td>
                    <td>{{ number_format($balance,2) }}</td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
