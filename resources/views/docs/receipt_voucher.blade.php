@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    {!! File::get($options['header-html']) !!}
    <div class="header" style="text-align:right;width:100%;margin:30px 0 0 0;">
        <small>Page {PAGENO} of {nb}</small>
        <br>
        <small>Original Doc.</small><br>
        <small><?php $company_name = DB::table('settings')
            ->latest()->take(1)
            ->where('id','=',4)
            ->value('value'); ?>
            {{$company_name}}
        </small>
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

<section class="content" id="content">
    <div class="content-title">
        Receipt Voucher
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address" style="margin:0 5px;padding:0 5px;">
                    <strong>To:</strong>
                    @if($model->client->company)
                        <pre>{{$model->client->company}}<br>{{$model->client->billing_address}}</pre>
                        <p>
                            <strong>Attention:</strong>
                            {{$model->client->person}}
                        </p>
                    @else
                        <pre>{{$model->client->person}}<br>{{$model->client->billing_address}}</pre>
                    @endif

                                        @if($model->client->work_phone)
                    <p>
                        <strong>VAT Number:</strong>
                        {{$model->client->work_phone}}
                    </p>
                @endif  
                @if($model->client->mobile_number)  
                    <p>
                        <strong>Phone Number:</strong>
                        {{$model->client->mobile_number}}
                    </p>
                    @endif  
                @if($model->client->email)  
                    <p>
                        <strong>Email:</strong>
                        {{$model->client->email}}
                    </p>
                    @endif  
                  
<p>
    <strong>Vat Status:</strong>
    {{ $model->vat_status }}
</p>

<p>
    <strong>Client Balance:</strong>
    {{ $model->client_balance }}
</p>

<p>
    <strong>Document Balance Amount:</strong>
    {{ $model->balance_amount }}
</p>



                </td>
                <td class="summary-addressx">
                    <strong>&nbsp;</strong>
                        

                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Number:</td>
                                <td>{{$model->number}}</td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>{{$model->date}}</td>
                            </tr>
                            @if($model->reference)
                            <tr>
                                <td>Reference:</td>
                                <td>{{$model->reference}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Total {{$model->currency->code}}:</td>
                                <td>{{$model->currency->code}}&nbsp;{{moneyFormat($model->total, $model->currency, false)}}</td>
                            </tr>

                            <tr>
                                <td>Exchange Rate:</td>
                                <td>{{$model->exchange_rate}}</td>
                            </tr>

                            <tr>
                                <td>Vat %:</td>
                                <td>{{$model->global_vat_percentage}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
        <table class="items">
            <thead>
                <tr>
                      <th>Accounts Receivable / حسابات مدينة</th>
                                    <th>Payment Mode</th>
                                    <th>Reference / المرجع</th>
                                    <th>Currency / العملة</th>
                                    <th>Description / الوصف</th>
                                    
                                    <th>Date / التاريخ</th>
                                    
                                    <th>Debit / مدين</th>
                                    <th>Debit USD / مدين</th>
                                    <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                        Debit Vat / ضريبة مدين
                                    </th>
                                    <th v-if="model.vat_status == 1 && (display_vat_rate == 1)">
                                        Vat Account / حساب الضريبة
                                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->items as $item)
                    <tr> <td>
                                    <span type="text" class="form-control">
                                        {{ $item->account_receivable_number }} - {{ $item->account_receivable_name }}
                                    </span> 
                                </td>
                                <td>
                                     <span type="text" class="form-control">
                                        {{ $item->payment_mode }}
                                    </span> 
                                </td>
                                   <td>
                                    <span type="text" class="form-control">
                                        {{ $item->reference }}
                                    </span> 
                                </td>
                                    <td>
                                    <span type="text" class="form-control">
                                        {{ $item->account_receivable_currency_code }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ $item->description }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ $item->date }}
                                    </span> 
                                </td>
                                  <td>
                                    <span type="text" class="form-control">
                                        {{ $item->debit }}
                                    </span> 
                                </td>
                                  <td>
                                    <span type="text" class="form-control">
                                        {{ $item->debit_usd }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ $item->debit_vat }}
                                    </span> 
                                </td>
     
                                @if($model->vat_status == 1 && ($model->display_vat_rate == 1) && $item->account_receivable_debit_vat_id > 0)
                                <td >
                                    <span class="form-control">
                                        <b>
                                            {{ $item->account_receivable_debit_vat_code }}</b> - 
                                            {{ $item->account_receivable_debit_vat_name }}
                                        </span>
                                </td>
                                @else
                                <td >
                                    &nbsp;
                                </td>
                                @endif
                                </tr>
                @endforeach
            </tbody>
</table>
<hr>
<h4>Applied Invoices</h4>
 <table class="items" style="margin:0;">
            <thead>
                <tr>
                    <th>Number</th>
                                    <th>Date / التاريخ</th>
                                    <th>Invoice Total</th>
                                    <th>runningBalance</th>
                                    <th>Amount Applied</th>
                                    <th>Amount Applied USD</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->invoices as $item)
                    <tr> <td>
                                    <span type="text" class="form-control">
                                        {{ $item->number }}
                                    </span> 
                                </td>
                                <td>
                                     <span type="text" class="form-control">
                                        {{ $item->date }}
                                    </span> 
                                </td>
                                   <td>
                                    <span type="text" class="form-control">
                                        {{ $item->total }}
                                    </span> 
                                </td>
                                    <td>
                                    <span type="text" class="form-control">
                                        {{ $item->runningBalance }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ $item->amount_applied }}
                                    </span> 
                                </td>
                                <td>
                                    <span type="text" class="form-control">
                                        {{ $item->amount_applied_usd }}
                                    </span> 
                                </td>
                                
                                </tr>
                @endforeach
            </tbody>
</table>
 
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