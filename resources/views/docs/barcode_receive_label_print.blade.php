@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html>
<head>
    <title>Product Barcode Label</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .label-container {
            border: 1px solid #ccc;
            padding: 2px;
            width: 380px;
            margin: 10px auto;
            page-break-inside: avoid; /* important for PDF */
        }
        .label-header, .label-footer {
            border-bottom: 1px solid #999;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .label-header span {
            font-size: 14px;
            font-weight: bold;
        }
        .label-body {
            margin-bottom: 1px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .product-info {
            font-size: 13px;
            padding: 4px 0;
        }
        .label-footer {
            font-size: 11px;
            text-align: right;
            border-top: 1px solid #ccc;
            margin-top: 12px;
            padding-top: 6px;
        }
        .barcode-table {
            width: 100%;
            margin-top: 5px;
        }
        .barcode-table td {
            text-align: center;
        }
    </style>
</head>
<body>

@foreach($model as $product)
    <div class="label-container">
        <div class="label-header">
            <table width="100%">
                <tr>
                    <td style="text-align: left;">
                         <strong>Product Label</strong>
                    </td>
                    <td style="text-align: right;">
                        Category: {{ $product->product->category->name ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="label-body">
            <div class="product-info">
    <table width="100%">
        <tr>
            <td style="text-align:left;font-size:11px;">
                <strong>Price:</strong> {{ number_format($product->product->price ?? 0, 2) }}
            </td>
            <td style="text-align:right;font-size:11px;">
                <strong>Received At:</strong> {{ \Carbon\Carbon::parse($product->created_at)->format('Y-m-d H:i') }}
            </td>
        </tr>
    </table>
</div>
            <div class="barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->code, 'C39+', 1.5, 50) }}" alt="Barcode">
                <div style="margin-top: 4px;"><strong>{{ $product->code }}</strong></div>
            </div>

            <table class="barcode-table">
                <tr>
                    <td>{!! $product->barcode !!}</td>
                </tr>
            </table>

            <div class="product-info">
                <strong>Description:</strong> {{ Str::limit($product->product_name, 60, '...') }}
            </div>

            <div class="product-info">
                <table width="100%">
                    <tr>
                        <td style="text-align:left;font-size:11px;">
                            <strong>Stock:</strong> {{ $product->qty }} {{ $product->uom->unit }}
                        </td>
                        <td style="text-align:right;font-size:11px;">
                            <strong>Vendor:</strong> {{ $product->vendor->company ?? 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
 
        </div>

        <div class="label-footer">
            <div><i>Print Timestamp:</i> {{ \Carbon\Carbon::now() }}</div>
        </div>
    </div>
@endforeach

</body>
</html>
