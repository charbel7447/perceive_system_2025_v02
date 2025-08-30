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
<?php 
$first_currency = DB::table('currencies')
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
        Debit Note Receipt
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address">
                    <strong>Payment From:</strong>
                    @if($model->client->company)
                        <pre>{{$model->client->company}}<br>{{$model->client->billing_address}}</pre>
                        <p>
                            <strong>Attention:</strong>
                            {{$model->client->person}}
                        </p>

                    @else
                        <pre>{{$model->client->person}}<br>{{$model->client->billing_address}}</pre>
                    @endif
                </td>
                <td class="summary-addressx">
                    <strong>&nbsp;</strong>
                        
                         @if($model->client->work_phone)
                        <p>
                            <strong>VAT Number:</strong>
                            {{$model->client->work_phone}}
                        </p>
                        @endif
                        <p>
                            <strong>Phone Number:</strong>
                            {{$model->client->mobile_number}}
                        </p>
                        <p>
                            <strong>Email:</strong>
                            {{$model->client->email}}
                        </p>
                  
                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>DN Number:</td>
                                <td>{{$model->number}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
        <table class="items">
            <tbody>
                <tr>
                    <td width="40%">Payment Date:</td>
                    <td width="60%">{{$model->payment_date}}</td>
                </tr>
                <tr>
                    <td>Payment Mode:</td>
                    <td>{{$model->payment_mode}}</td>
                </tr>
                @if($model->payment_reference)
                <tr>
                    <td>Payment Reference:</td>
                    <td>{{$model->payment_reference}}</td>
                </tr>
                @endif
                @if($model->quotation)
                <tr>
                    <td>Quotation:</td>
                    <td>{{$model->quotation->number}}</td>
                </tr>
                @endif
                <tr>
                    <td>Currency:</td>
                    <td>{{$model->currency->code}}</td>
                </tr>
                @if($model->amount_received > 0)
                <tr>
                    <td>Debit Amount {{$first_currency }}:</td>
                    <td>{{moneyFormat($model->amount_received, $model->currency, false)}}</td>
                </tr>
                @endif
                @if($model->amount_received_lbp > 0)
                <tr>
                    <td>Debit Amount {{$second_currency }}:</td>
                    <td>{{moneyFormat($model->amount_received_lbp, $model->currency, false)}} <br>
                        With ExchangeRate {{moneyFormat($model->exchangerate, $model->currency, false)}} <br>
                        <strong>{{moneyFormat($model->amount_received_lbp / $model->exchangerate, $model->currency, true)}}</strong>
                    </td>
                </tr>
                @endif
                <tr>
                    <td>Description</td>
                    <td><pre>{{$model->description}}</pre></td>
                </tr>
            </tbody>
    </table>
    @if($model->applied_amount && $model->applied_date)
        <table class="items">
            <tbody>
                <tr>
                    <td width="60%">Amount Applied</td>
                    <td width="40%">{{moneyFormat($model->applied_amount, $model->currency, false)}}</td>
                </tr>
                <tr>
                    <td>Amount Applied Date</td>
                    <td>{{$model->applied_date}}</td>
                </tr>
            </tbody>
        </table>
         
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