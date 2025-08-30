@php $movements = \App\JournalVoucher\JournalVoucherMovement::orderByDesc('movement_date')->get(); @endphp
            <table id="movementTable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="table-dark text-center">
                        <th>#</th>
                        <th>Type</th>
                        <th>JV Number</th>
                        <th>Document</th>
                        <th>Document Type</th>
                        <th>Doc Date</th>
                        <th>Currency</th>
                        <th>Exchange Rate</th>
                        <th>Total Debit</th>
                        <th>Total Credit</th>
                        <th>Created By</th>
                        <th>Movement Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $move)
                    <tr class="text-center align-middle">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                                {{ $move->type }}
                        </td>
                        <td>{{ $move->number }}</td>
                        <td>
                            <strong>{{ $move->document_number }}</strong>
                        </td>
                        <td>
                            <small>{{ $move->document_name }}</small>
                        </td>
                        <td>{{ $move->document_date ?? '-' }}</td>
                         <td>
        <?php $currency_name = \App\Currency::where('id','=',$move->currency_id)->value('code'); ?>
        {{ $currency_name ?? '-' }}
    </td>
                        <td>{{ number_format($move->exchange_rate, 3) }}</td>
                        <td class="text-success">{{ number_format($move->total_debit, 3) }}</td>
                        <td class="text-danger">{{ number_format($move->total_credit, 3) }}</td>
                        <td>{{ $move->created_by }}</td>
                        <td>{{ $move->movement_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
       