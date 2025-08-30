@extends('layout.masterCustom')

@section('title', 'Stock Movement Report')

@section('content')
<div class="container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4>List Of Stock Movement</h4>
        <a href="{{ route('stock_movement.export') }}" class="btn btn-success">
            Report &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table id="stockMovementTable" class="table table-bordered table-striped text-center">
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
                                'Initial Stock' => 'fa-arrow-right text-success',
                                'Deleted Stock' => 'fa-arrow-left text-danger',
                                'Edited Stock' => 'fa-arrow-down text-danger',
                                'Manually Product Division Stock' => 'fa-arrow-up text-success',
                                'Partially Transfer Stock' => 'fa-arrow-up text-success',
                                'Division/Addition U.O.M Changed Stock' => 'fa-arrow-right text-primary',
                                'Manually Transfer Stock' => 'fa-arrow-up text-success',
                                'Aggregation Product Stock' => 'fa-arrow-down text-primary',
                                'Receive Order Changed Stock' => 'fa-arrow-right text-success',
                                'Invoiced Deleted' => 'fa-arrow-right text-success',
                                'Invoiced Updated' => 'fa-arrow-left text-danger',
                                'Invoiced Draft' => 'fa-arrow-left text-primary',
                                'Invoiced Confirmed' => 'fa-arrow-left text-danger',
                                'Invoiced' => 'fa-arrow-left text-danger',
                            ];
                            $icon = $icons[$item->type] ?? 'fa-arrow-up text-success';
                        @endphp
                        <i class="fa {{ $icon }}"></i>
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
    </div>
</div>
@endsection

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endsection

@section('scripts')
<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#stockMovementTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Stock Movement Export',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            pageLength: 25,
            order: [[9, 'desc']],
            responsive: true
        });
    });
</script>
@endsection
