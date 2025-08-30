<table>
    <thead style="background-color:#004d99; color:white;">
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
            <tr style="background-color: {{ $loop->even ? '#f2f2f2' : '#ffffff' }}">
                <td>{{ $item->journalVoucher->date ?? '' }}</td>
                <td>{{ $item->journal_voucher_id }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ number_format($item->debit,2) }}</td>
                <td>{{ number_format($item->credit,2) }}</td>
                <td>{{ number_format($balance,2) }}</td>
            </tr>
        @endif
        @endforeach
    </tbody>
</table>
