@php use Illuminate\Support\Str; @endphp 

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 9pt;
        color: #333;
    }

    .catalogue-container {
        width: 100%;
    }

    .label-box {
        width: 31.5%;
        margin: 0 1% 10px 0;
        float: left;
        border: 1px solid #ccc;
        padding: 8px;
        height: 140px;
        box-sizing: border-box;
        border-radius: 4px;
        position: relative;
    }

    .barcode {
        width: 150px;
        height: 30px;
        display: block;
        margin: 0 auto;
    }

    .thumbnail {
        position: absolute;
        top: 8px;
        right: 8px;
        max-height: 60px;
        max-width: 80px;
        object-fit: contain;
        border: 1px solid #ddd;
        padding: 2px;
        background: #fff;
    }

    .product-code {
        font-weight: bold;
        font-size: 10pt;
        text-align: center;
        margin: 5px 0;
    }

    .product-name {
        text-align: center;
        font-weight: bold;
        font-size: 9pt;
        margin: 5px 0;
    }

    table.meta-table {
        width: 100%;
        margin-top: 10px;
        font-size: 8pt;
        border-collapse: collapse;
    }
    table.meta-table td {
        vertical-align: top;
        padding: 0 4px;
        line-height: 1.4;
    }
    table.meta-table td.left {
        text-align: left;
        width: 50%;
    }
    table.meta-table td.right {
        text-align: right;
        width: 50%;
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
            @if($thumbnail)
                <img src="{{ $thumbnail }}" class="thumbnail" alt="thumbnail">
            @endif

            <img src="data:image/png;base64,{{ $item->barcode }}" class="barcode" alt="barcode">
            <div class="product-code">{{ $item->code }}</div>
            <div class="product-name">{{ Str::limit($item->product_name, 60, '...') }}</div>

            <table class="meta-table">
                <tr>
                    <td class="left">
                        <div><span style="color:red;">Category:</span> <strong>{{ $item->category_name }}</strong></div>
                        <div><span style="color:red;">Price:</span> <strong>{{ number_format($item->price, 2) }} $</strong></div>
                    </td>
                    <td class="right">
                        <div><span style="color:red;">Stock:</span> <strong>{{ $item->current_stock }}</strong></div>
                        <div><span style="color:red;">Unit:</span> <strong>
                            {{ $item->uom->name }}
                        </strong></div>
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
