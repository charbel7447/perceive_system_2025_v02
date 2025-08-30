@php use Illuminate\Support\Str; @endphp 

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 8pt; /* smaller font */
        color: #333;
        margin: 0;
        padding: 0;
    }

    .catalogue-container {
        width: 100%;
        overflow: hidden;
    }

    .label-box {
        width: 31.5%;
        margin: 0 1% 10px 0;
        float: left;
        border: 1px solid #ccc;
        padding: 6px 8px;
        height: 130px; /* shorter height for compact */
        box-sizing: border-box;
        border-radius: 4px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Barcode and thumbnail container: inline-flex to align horizontally */
    .barcode-thumb-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .barcode {
        width: 140px;
        height: 30px;
        display: block;
    }

    .thumbnail {
        max-height: 60px;
        max-width: 80px;
        object-fit: contain;
        border: 1px solid #ddd;
        padding: 2px;
        background: #fff;
    }

    .product-code {
        font-weight: bold;
        font-size: 9pt;
        text-align: center;
        margin: 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-name {
        text-align: center;
        font-weight: bold;
        font-size: 8pt;
        margin: 2px 0 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    table.meta-table {
        width: 100%;
        font-size: 7pt; /* smaller font */
        border-collapse: collapse;
    }
    table.meta-table td {
        vertical-align: middle;
        padding: 1px 4px;
        white-space: nowrap; /* keep info on one line */
    }
    table.meta-table td.left {
        text-align: left;
        width: 50%;
        color: #cc0000; /* red labels */
    }
    table.meta-table td.right {
        text-align: right;
        width: 50%;
        color: #cc0000;
    }

    .page-break {
        clear: both;
        height: 1px;
        margin: 10px 0;
        page-break-after: auto;
    }

    @media print {
        .label-box {
            page-break-inside: avoid;
        }
    }
</style>

<div class="catalogue-container">
    @php $count = 0; @endphp

    @foreach ($items as $item)
        @php
            $thumbPath = storage_path('app/uploads/' . $item->thumbnail);
            $thumbnail = file_exists($thumbPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($thumbPath))
                : null;
            $item->uom = json_decode($item->uom);
        @endphp

        <div class="label-box">

            <div class="barcode-thumb-row">
                <img src="data:image/png;base64,{{ $item->barcode }}" class="barcode" alt="barcode">

                @if($thumbnail)
                    <img src="{{ $thumbnail }}" class="thumbnail" alt="thumbnail">
                @endif
            </div>

            <div class="product-code" title="{{ $item->code }}">{{ $item->code }}</div>
            <div class="product-name" title="{{ $item->product_name }}">{{ Str::limit($item->product_name, 60, '...') }}</div>

            <table class="meta-table">
                <tr>
                    <td class="left">
                        Category: <strong>{{ $item->category_name }}</strong><br>
                        Price: <strong>{{ number_format($item->price, 2) }} $</strong>
                    </td>
                    <td class="right">
                        Stock: <strong>{{ $item->current_stock }}</strong><br>
                        Unit: <strong>{{ $item->uom->name ?? '' }}</strong>
                    </td>
                </tr>
            </table>

        </div>

        @php
            $count++;
            if ($count == 3) {
                echo '<div class="page-break"></div>';
                $count = 0;
            }
        @endphp
    @endforeach
</div>
