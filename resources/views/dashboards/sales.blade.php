@extends('layout.masterCustom')

@section('content')
@if ($model['display_sales'] == 1)
<div class="row displaysales">
    {{-- Left Column --}}
    <div class="col col-4">
        {{-- Clients --}}
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Clients</span>
            </div>
            <div class="panel-body">
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Total Revenue</th>
                            <th>Unused Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model['top_clients'] as $client)
                        <tr onclick="window.location='{{ url('/clients/' . $client['id']) }}'">
                            <td>
                                {{ $client['company'] }}
                                <br>
                                <small style="color:red;">({{ $client['person'] }})</small>
                            </td>
                            <td>{{ number_format($client['total_revenue'], 2) }} {{ $client['currency']['code'] }}</td>
                            <td>{{ number_format($client['unused_credit'], 2) }} {{ $client['currency']['code'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Latest Sold Products --}}
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Latest Sold Products</span>
            </div>
            <div class="panel-body">
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model['top_saled_products'] as $sale)
                        <tr onclick="window.location='{{ url('/sales_orders/' . $sale['id']) }}'">
                            <td>{{ $sale['number'] }}</td>
                            <td>
                                <ul>
                                    @foreach($sale['items'] as $item)
                                    <li style="display:flow-root; border-bottom: 1px solid; line-height: 20px;">
                                        <p>
                                            <span style="float:left;">{{ $item['productd']['description'] }}</span>
                                            <span style="float:right;"><strong>Qty.:</strong> {{ $item['quantity'] }}{{ $item['uomd']['unit'] }}</span>
                                        </p>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $sale['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col col-8">
        {{-- Quotations --}}
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Latest 5 Quotations</span>
            </div>
            <div class="panel-body">
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Details</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model['top_quotations'] as $quote)
                        <tr onclick="window.location='{{ url('/quotations/' . $quote['id']) }}'">
                            <td>{{ $quote['number'] }}</td>
                            <td>
                                <ul>
                                    @foreach($quote['items'] as $item)
                                    <li style="display:flow-root; border-bottom: 1px solid; line-height: 20px;">
                                        <p>
                                            <span style="float:left;">{{ $item['productd']['description'] }}</span>
                                            <span style="float:right;">
                                                <strong>Qty.:</strong> {{ $item['qty'] }}{{ $item['uomd'] }} -
                                                <strong>Price:</strong> {{ number_format($item['unit_price'], 2) }} {{ $model['currency'] }}
                                            </span>
                                        </p>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $quote['clientd']['company'] }}</td>
                            <td>{{ number_format($quote['total'], 2) }} {{ $quote['currency'] }}</td>
                            <td>{{ $quote['date'] }}</td>
                            <td>{{ $quote['created_by'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sales Orders --}}
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Latest 5 Sales Orders</span>
            </div>
            <div class="panel-body">
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Details</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model['top_sales_order'] as $order)
                        <tr onclick="window.location='{{ url('/sales_orders/' . $order['id']) }}'">
                            <td>{{ $order['number'] }}</td>
                            <td>
                                <ul>
                                    @foreach($order['items'] as $item)
                                    <li style="display:flow-root; border-bottom: 1px solid; line-height: 20px;">
                                        <p>
                                            <span style="float:left;">{{ $item['productd']['description'] }}</span>
                                            <span style="float:right;">
                                                <strong>Qty.:</strong> {{ $item['quantity'] }}{{ $item['uomd']['uom'] }} -
                                                <strong>Price:</strong> {{ number_format($item['price'], 2) }} {{ $model['currency'] }}
                                            </span>
                                        </p>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $order['clientd']['company'] }}</td>
                            <td>{{ number_format($order['total'], 2) }} {{ $order['currency'] }}</td>
                            <td>{{ $order['date'] }}</td>
                            <td>{{ $order['created_by'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Chart Section --}}
<div class="row mt-4">
    <div class="col-md-6">
        <div id="clientsRevenueChart" style="height: 370px; width: 100%;"></div>
    </div>
    <div class="col-md-6">
        <div id="salesOrdersChart" style="height: 370px; width: 100%;"></div>
    </div>
</div>
@endif
@endsection
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    window.onload = function () {
        const topClients = {!! json_encode($model['top_clients']->toArray()) !!};
        const topSales = {!! json_encode($model['top_sales_order']->toArray()) !!};

        // Prepare dataPoints for Clients Revenue Pie
        const clientRevenueData = topClients.map(client => ({
            label: client.company,
            y: parseFloat(client.total_revenue)
        }));

        const clientsChart = new CanvasJS.Chart("clientsRevenueChart", {
            animationEnabled: true,
            title: {
                text: "Top Clients Revenue"
            },
            data: [{
                type: "pie",
                indexLabel: "{label}: {y}",
                dataPoints: clientRevenueData
            }]
        });
        clientsChart.render();

        // Prepare dataPoints for Sales Orders Bar
        const salesData = topSales.map(sale => ({
            label: sale.number,
            y: parseFloat(sale.total)
        }));

        const salesChart = new CanvasJS.Chart("salesOrdersChart", {
            animationEnabled: true,
            title: {
                text: "Top Sales Orders"
            },
            axisY: {
                title: "Amount"
            },
            data: [{
                type: "column",
                dataPoints: salesData
            }]
        });
        salesChart.render();
    };
</script>
