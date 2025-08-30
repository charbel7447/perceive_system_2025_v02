@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    {!! File::get($options['header-html']) !!}
    <div class="header" style="text-align:right;width:100%;margin:30px 0 0 0;">
        <small>Page {PAGENO} of {nb}</small>
        <br>
        <small>Original Doc.</small><br>
        <small>{{settings()->get('company_name')}}</small>
        {!! File::get($options['header-html']) !!}
        
    </div>
</htmlpageheader>
<sethtmlpageheader name="header" show-this-page="1" />
@endif

@if(settings()->get('footer_line_1') && settings()->get('footer_line_2') || settings()->get('footer_line_3') )
<htmlpagefooter name="footer">
    <div class="footer">
    <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_1')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_2')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_3')}}
   </p>
    @if(settings()->get('footer_line_3') === NULL )
        <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px; display: "> &nbsp;&nbsp;  &nbsp;&nbsp; </p>
    @endif
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" page="O" value="on" show-this-page="1" />
@endif
<?php
 
 $display_vat_rate  = DB::table('settings')
                ->latest()->take(1)
                ->where('id','=',22)
                ->value('value');

$display_exchange_rate  =DB::table('settings')
                ->latest()->take(1)
                ->where('key','=','display_exchange_rate')
                ->value('value');

        $first_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',1)
                ->value('code');

                    $base_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',1)
                ->value('code');


                $second_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',2)
                ->value('code');
?>
<section class="content" id="content">
    <div class="content-title">
        Purchase Overheads / التكاليف الإضافية للمشتريات {{ $model->number }}
    </div>

    <div>
        @if($model->posted == 1)
            <a href="{{ url('/journal_vouchers/' . $model->journal_id) }}" class="btn" style="background:green;color:#fff;">
                POSTED &nbsp;<i class="fa fa-arrow-right"></i>
            </a>
        @elseif($model->journal_id > 0 && $model->posted == 0)
            <a href="{{ url('/journal_vouchers/' . $model->journal_id) }}" class="btn" style="background:green;color:#fff;">
                JV Created &nbsp;<i class="fa fa-arrow-right"></i>
            </a>
        @endif
    </div>

    <hr>
<table class="summary">
    <tbody>
        <tr>
            {{-- Vendor Info --}}
            <td class="summary-address">
                <strong>Bill From:</strong>
                @if($model->vendor->company)
                    <pre>{{$model->vendor->company}}<br>{{$model->vendor->billing_address}}</pre>
                    <p>
                        <strong>Attention:</strong>
                        {{$model->vendor->person}}
                    </p>
                @else
                    <pre>{{$model->vendor->person}}<br>{{$model->vendor->billing_address}}</pre>
                @endif
                <br>
            </td>

            {{-- Vendor Contact Info --}}
            <td class="summary-addressx">
                <strong>&nbsp;</strong>
                @if($model->vendor->vat_number)
                <p>
                    <strong>VAT Number:</strong>
                    {{$model->vendor->vat_number}}
                </p>
                @endif
                @if($model->vendor->mobile_number)
                <p>
                    <strong>Phone Number:</strong>
                    {{$model->vendor->mobile_number}}
                </p>
                @endif
                @if($model->vendor->email)
                <p>
                    <strong>Email:</strong>
                    {{$model->vendor->email}}
                </p>
                @endif
            </td>

            {{-- Summary Info --}}
            <td class="summary-info">
                <table class="info">
                    <tbody>
                        <tr>
                            <td>Number:</td>
                            <td>{{$model->number}}</td>
                        </tr>
                        <tr>
                            <td>Supplier Invoice:</td>
                            <td>
                                {{$model->bill_number}} - {{$model->bill_date}} - {{moneyFormat($model->bill_total, $model->currency, false)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Payment Date:</td>
                            <td>{{$model->payment_date}}</td>
                        </tr>
                        <tr>
                            <td>Exchange Rate:</td>
                            <td>1 {{$first_currency}} = {{$model->exchangerate}} {{$second_currency}}</td>
                        </tr>
                        @if($model->due_date)
                        <tr>
                            <td>Due Date:</td>
                            <td>{{$model->due_date}}</td>
                        </tr>
                        @endif
                        @if($model->reference)
                        <tr>
                            <td>Reference:</td>
                            <td>{{$model->reference}}</td>
                        </tr>
                        @endif
                        @if($model->purchaseOrder)
                        <tr>
                            <td>Purchase Order:</td>
                            <td>{{$model->purchaseOrder->number}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Currency:</td>
                            <td>{{$model->currency->code}}</td>
                        </tr>
                        <tr>
                            <td>Total:</td>
                            <td>{{moneyFormat($model->total, $model->currency, false)}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>


    
    <hr>

    {{-- Accounts Receivable --}}
    <table class="item-table items">
        <thead>
            <tr>
                <th class="ac">Accounts Receivable<br>حسابات مدينة</th>
                <th class="ac">Description<br>الوصف</th>
                <th class="ac">Date<br>التاريخ</th>
                <th class="ac">Reference<br>المرجع</th>
                <th class="ac">Debit<br>مدين</th>
                @if($model->vat_status == 1 && $display_vat_rate == 1)
                    <th class="ac">Debit Vat<br>ضريبة مدين</th>
                    <th class="ac">Vat Account<br>حساب الضريبة</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($model->items as $item)
                <tr>
                    <td class="ac">{{ $item->account_receivable_number }} - {{ $item->account_receivable_name }}</td>
                    <td class="ac">{{ $item->description }}</td>
                    <td class="ac">{{ $item->date }}</td>
                    <td class="ac">{{ $item->reference }}</td>
                    <td class="ac">{{ $item->debit }}</td>
                    @if($model->vat_status == 1 && $display_vat_rate == 1)
                        <td class="ac">{{ $item->debit_vat }}</td>
                        <td class="ac">{{ $item->account_receivable_debit_vat_name }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    {{-- Accounts Payable --}}
    <table class="item-table items">
        <thead>
            <tr>
                <th class="ac">Accounts Payable<br>حسابات دائنة</th>
                <th class="ac">Description<br>الوصف</th>
                <th class="ac">Date<br>التاريخ</th>
                <th class="ac">Reference<br>المرجع</th>
                <th class="ac">Debit<br>مدين</th>
                @if($model->vat_status == 1 && $display_vat_rate == 1)
                    <th class="ac">Debit Vat<br>ضريبة مدين</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($model->items2 as $item2)
                <tr>
                    <td class="ac">{{ $item2->account_payable_number }} - {{ $item2->account_payable_name }}</td>
                    <td class="ac">{{ $item2->description }}</td>
                    <td class="ac">{{ $item2->date }}</td>
                    <td class="ac">{{ $item2->reference }}</td>
                    <td class="ac">{{ $item2->debit }}</td>
                    @if($model->vat_status == 1 && $display_vat_rate == 1)
                        <td class="ac">{{ $item2->debit_vat }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    {{-- Description --}}
    @if($model->description)
    <div class="row">
        <div class="col col-6">
            <label>Description / الوصف</label>
            <pre>{{ $model->description }}</pre>
        </div>
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
        vertical-align: top;
        text-align: left;
    }
   
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {

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
        margin-top: 20px;
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
        padding: 4px 3px;
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