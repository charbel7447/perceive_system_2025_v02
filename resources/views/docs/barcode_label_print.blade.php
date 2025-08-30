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
            margin: auto;
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
    <!-- Add Font Awesome CDN to <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="label-container">
        <div class="label-header">
            <table width="100%">
                <tr>
                    <td style="text-align: left;">
                         <strong>Product Label</strong>
                        </td>
                    <td style="text-align: right;">Category: {{ $model->category->name }}</td>
                </tr>
            </table>
        </div>

        <div class="label-body">
            <div class="product-info"><strong>Price:</strong> {{ number_format($model->price, 3) }} $</div>

            <div class="barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($model->code, 'C39+', 1.5, 50) }}" alt="Barcode">
                <div style="margin-top: 4px;"><strong>{{ $model->code }}</strong></div>
            </div>

            <table class="barcode-table">
                <tr>
                    <td>{!! $model->barcode !!}</td>
                </tr>
            </table>

            <div class="product-info">
                <strong>Description:</strong> {{ Str::limit($model->description, 60, '...') }}
            </div>

            <div class="product-info">
                <strong>Stock:</strong> {{ $model->current_stock }} &nbsp;
                {{ $model->unit }} &nbsp;
            </div>
        </div>

        <div class="label-footer">
            <div><i>Print Timestamp:</i> {{ \Carbon\Carbon::now() }}</div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
