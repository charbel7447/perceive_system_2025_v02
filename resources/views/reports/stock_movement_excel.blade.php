<table>
    <thead>
        <tr>
            <th>Icon</th>
            <th>Item Code</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Warehouse</th>
            <th>Category</th>
            <th>Type</th>
            <th>Document</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>
                @php
                    $icons = [
                        'Initial Stock' => '→',
                        'Deleted Stock' => '←',
                        'Edited Stock' => '↓',
                        'Manually Product Division Stock' => '↑',
                        'Partially Transfer Stock' => '↑',
                        'Division/Addition U.O.M Changed Stock' => '→',
                        'Manually Transfer Stock' => '↑',
                        'Aggregation Product Stock' => '↓',
                        'Receive Order Changed Stock' => '→',
                        'Invoiced Deleted' => '→',
                        'Invoiced Updated' => '←',
                        'Invoiced Draft' => '←',
                        'Invoiced Confirmed' => '←',
                        'Invoiced' => '←',
                    ];
                @endphp
                {{ $icons[$item->type] ?? '↑' }}
            </td>
            <td>{{ $item->product_code }}</td>
            <td>{{ \Illuminate\Support\Str::limit($item->product_name, 80) }}</td>
            <td>{{ number_format($item->qty, 2) }} {{ $item->uom }}</td>
            <td>{{ $item->price }} {{ $item->currency }}</td>
            <td>{{ $item->warehouse_id }}</td>
            <td>{{ $item->category_id }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->purchase_order ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
