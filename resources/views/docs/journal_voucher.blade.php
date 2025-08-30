<?php 
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
?>
 


@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    <table class="summary">
        <tr>
            <td style="vertical-align:middle;width:100px">{!! File::get($options['header-html']) !!}</td>
            <td style="text-align:left;font-size:14px;">
                <span style="font-size:16px;font-weight:bold;">{{ settings()->get('header_line_1') }}</span><br>
                {{ settings()->get('header_line_2') }}<br>
                {{ settings()->get('header_line_3') }}
            </td>
            <td style="text-align:right;">
                <strong>Related Doc: </strong> #{{ $model->document_number }}
            </td>
        </tr>
    </table>
</htmlpageheader>
<sethtmlpageheader name="header" show-this-page="1" />
@endif

@if(settings()->get('footer_line_1') || settings()->get('footer_line_2') || settings()->get('footer_line_3'))
<htmlpagefooter name="footer">
    <div class="footer" style="font-size:11px;color:#777;">
        {{ settings()->get('footer_line_1') }}<br>
        {{ settings()->get('footer_line_2') }}<br>
        {{ settings()->get('footer_line_3') }}
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" page="O" value="on" show-this-page="1" />
@endif

<section class="content" id="content">
    <div class="content-title">JOURNAL VOUCHER</div>

    <table class="summary">
        <tr>
            <td class="summary-address">
                <strong>Document Type:</strong><br>
                @switch($model->document_type)
                    @case(1) Sales Invoice @break
                    @case(2) Purchase Invoice @break
                    @case(3) Purchase Order @break
                    @case(4) Sales Order @break
                    @case(5) Manual Journal Entry @break
                    @default Not Set
                @endswitch
                <br><strong>Exchange Rate:</strong> {{ $model->exchange_rate }}
                <br><strong>Vat Rate:</strong> {{ $model->vat_rate }}
            </td>

            <td class="summary-address">
                <strong>Related Document:</strong><br>
                <a>{{ $model->document_number }}</a>
            </td>

            <td class="summary-info">
                <table class="info">
                    <tr><td>Number:</td><td>{{ $model->number }}</td></tr>
                    <tr><td>Date:</td><td>{{ \Carbon\Carbon::parse($model->date)->format('m-d-Y') }}</td></tr>
                    @if($model->reference)
                    <tr><td>Reference:</td><td>{{ $model->reference }}</td></tr>
                    @endif
                    <tr><td>Total Debit:</td><td><strong>{{ moneyFormat($model->total_debit, $model->currency) }}</strong></td></tr>
                    <tr><td>Total Credit:</td><td><strong>{{ moneyFormat($model->total_credit, $model->currency) }}</strong></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Ledger Account</th>
                <th>Description</th>
                <th class="ac">Debit</th>
                <th class="ac">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->items as $item)
            <tr>
                <td>{{ $item->account_code }} - {{ $item->account_name_en }} - {{ $item->account_name_ar }}</td>
                <td>{{ $item->description }}</td>
                <td class="ar">{{ moneyFormat($item->debit, $model->currency, false) }}</td>
                <td class="ar">{{ moneyFormat($item->credit, $model->currency, false) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="ar"><strong>Totals</strong></td>
                <td class="ar"><strong>{{ moneyFormat($model->total_debit, $model->currency, false) }}</strong></td>
                <td class="ar"><strong>{{ moneyFormat($model->total_credit, $model->currency, false) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($model->terms)
    <div class="terms" style="margin-top:20px;">
        <strong>Note:</strong>
        <div>{!! $model->terms !!}</div>
    </div>
    @endif
</section>
<style>
                    
    body {
        font-family: sans-serif;
        font-size: 9pt;
        color: #484746;

    }
    pre {
        font-family: sans-serif;
    }

    table {
        border-spacing: 0;
        width: 100%;
        border-collapse: collapse;
    }
    th,
    td{
        font-weight: normal;
        vertical-align: middle;
        text-align: left;
    }
   
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {
        padding-top:-60px;
    }
    .content-title {
        margin-bottom: 20px;
        padding: 5px;
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        border: 0.1mm solid #dedede;
    }

    .document-blue {
        color: #3aa3e3;
    }

    .document-orange {
        color: #FF9800;
    }

    .document-red {
        color: #E75650;
    }

    .document-blue_light {
        color: #48606f;
    }
    .document-green {
        color: #66bb6a;
    }
    .summary-address {
        width: 33.333%;
    }
    .summary-addressx {
        width: 33.333%;
    }
    .summary-empty {
        width: 33.333%;
    }
    .summary-info {
        width: 33.333%;
    }
    .info td {
        text-align: right;
    }
    .info td:nth-child(2n) {
        padding-left: 15px;
    }
    .items {
        margin-top: 0px;
        border: 0.1mm solid #dedede;
    }
    .items thead th {
        padding: 6px 3px;
        background: #dedede;
        border: 0.1mm solid #dedede;
    }
    .items tbody td {
        border: 0.1mm solid #dedede;
        padding: 3px;
    }

    .items tfoot td {
         background: #f1f1f1;
        border: 0.1mm solid #dedede;
        text-align: right;
        padding: 2px 3px;
    }
    .item-empty {

    }
    .ar {
        text-align: right;
    }
    .ac {
        text-align: center;
    }
    .terms {
        margin-top: 20px;
    }
    .terms-description {
        width: 70%;
    }
    .footer {
        text-align: center;
    }
</style>

    <style>
      
       .header {
       position: fixed;
       left: 0;
       color: #777;
       text-align: right;
       }
       .footer {
       position: fixed;
       left: 0;
       bottom: -30px;
       color: #777;
       width: 100%;
       text-align: center;
       }
</style>