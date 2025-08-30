@extends('layout.masterCustom')
@section('content')
<div class="containerx">
    <div class="row">
        <!-- Vendors Panel -->
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">Vendors</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Person</th>
                                <th>Company</th>
                                <th>Expenses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_vendors as $vendor)
                            <tr onclick="window.location='/vendors/{{ $vendor->id }}'">
                                <td>{{ $vendor->person }}</td>
                                <td>{{ $vendor->company }}</td>
                                <td>{{ number_format($vendor->total_expense, 2) }} {{ $vendor->currency->code }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Warehouses Panel -->
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">Warehouses</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Total Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_warehouses as $warehouse)
                            <tr onclick="window.location='/warehouses/{{ $warehouse->id }}'">
                                <td>{{ $warehouse->number }}</td>
                                <td>{{ $warehouse->name }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @foreach ($wh_product->where('warehouse_id', $warehouse->id) as $wh)
                                        <li style="border-bottom: 1px solid #ccc; padding: 5px 0;">
                                            <span class="pull-left">{{ $wh->description }}</span>
                                            <span class="pull-right"><strong>Qty.:</strong> {{ $wh->current_stock }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Latest Purchase Orders -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Latest 5 Purchase Orders</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Details</th>
                                <th>Vendor</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_purchase_order as $purchase)
                            <tr onclick="window.location='/purchase_orders/{{ $purchase->id }}'">
                                <td>{{ $purchase->number }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @foreach ($purchase->items as $pur)
                                        <li style="border-bottom: 1px solid #ccc;">
                                            <span class="pull-left">{{ $pur->productd->description }}</span>
                                            <span class="pull-right">
                                                <strong>Qty.:</strong> {{ $pur->qty }} {{ $pur->uom->code }} -
                                                <strong>Price:</strong> {{ number_format($pur->unit_price, 2) }} 
                                                <?php $currency = \App\Currency::where('id','=',$purchase->currency_id)->value('code'); ?>
                                                {{ $currency }}
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $purchase->vendord->company }}</td>
                                <td>{{ number_format($purchase->total, 2) }} {{ $purchase->currency->code }}</td>
                                <td>{{ $purchase->date }}</td>
                                <td>{{ $purchase->created_by }}</td>
                                <td><small>{{ strtoupper(str_replace('_', ' ', $purchase->status_text)) }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Products</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Warehouse</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_products as $product)
                            <tr onclick="window.location='/products/{{ $product->id }}'">
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->warehoused->name }}<br><small>({{ $product->warehoused->number }})</small></td>
                                <td>{{ $product->categoryd->name }}<br><small>({{ $product->categoryd->number }})</small></td>
                                <td>
                                    @if ($product->sub_categoryd)
                                        {{ $product->sub_categoryd->title }}<br>
                                        <small>({{ $product->sub_categoryd->id }})</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $product->current_stock }} {{ $product->uom }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h5>Top Vendors by Expenses</h5>
            </div>
            <div class="panel-body">
                <canvas id="vendorChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h5>Warehouse Stock Distribution</h5>
            </div>
            <div class="panel-body">
                <canvas id="warehouseChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const vendorData = @json($vendorExpenseData);
    const warehouseData = @json($stockByWarehouse);

    new Chart(document.getElementById('vendorChart'), {
        type: 'bar',
        data: {
            labels: vendorData.map(d => d.label),
            datasets: [{
                label: 'Total Expense',
                backgroundColor: '#36a2eb',
                data: vendorData.map(d => d.value)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Top Vendors'
                }
            }
        }
    });

    new Chart(document.getElementById('warehouseChart'), {
        type: 'pie',
        data: {
            labels: warehouseData.map(d => d.label),
            datasets: [{
                label: 'Stock',
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                data: warehouseData.map(d => d.value)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Warehouse Stock Distribution'
                }
            }
        }
    });
</script>
