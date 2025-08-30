@extends('layout.masterCustom')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">Top 5 Invoices</div>
                <div class="panel-body">
                    <div id="invoicesChartContainer" style="height: 300px; width: 100%;"></div>
                    <p class="text-muted mt-3">
                        Total Invoice Amount: {{ number_format($topInvoices->sum('total'), 2) }} {{ $topInvoices->first()->currency->code ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">Top 5 Bills</div>
                <div class="panel-body">
                    <div id="billsChartContainer" style="height: 300px; width: 100%;"></div>
                    <p class="text-muted mt-3">
                        Total Bill Amount: {{ number_format($topBills->sum('total'), 2) }} {{ $topBills->first()->currency->code ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const topInvoices = @json($topInvoices);
        const topBills = @json($topBills);

        const invoiceData = topInvoices.map(i => ({
            label: i.client?.company || "Unknown",
            y: parseFloat(i.total)
        }));

        const billData = topBills.map(b => ({
            label: b.vendor?.company || "Unknown",
            y: parseFloat(b.total)
        }));

        new CanvasJS.Chart("invoicesChartContainer", {
            animationEnabled: true,
            theme: "light2",
            title: { text: "Top 5 Client Invoices" },
            data: [{ type: "column", dataPoints: invoiceData }]
        }).render();

        new CanvasJS.Chart("billsChartContainer", {
            animationEnabled: true,
            theme: "light2",
            title: { text: "Top 5 Vendor Bills" },
            data: [{ type: "column", dataPoints: billData }]
        }).render();
    });
</script>
