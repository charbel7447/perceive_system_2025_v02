@extends('docs.master', ['title' => 'Client Payment '.$model->number, 'options' => $options])
@section('content')
<div class="content-title">
        Payment Receipt
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
                                <td>Number:</td>
                                <td>{{$model->number}}</td>
                            </tr>
                            <tr>
                                <td>Payment Date:</td>
                                <td>{{$model->payment_date}}</td>
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
                            <tr>
                                <td>Currency:</td>
                                <td>{{$model->currency->code}}</td>
                            </tr>
                            <tr>
                                <td>Amount Received:</td>
                                <td>{{moneyFormat($model->amount_received, $model->currency, false)}}</td>
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
                            <th class="ar">Inv. Ref</th>
                                @if($model->amount_received_lbp)
                                <th class="ar">Amount Applied USD</th>
                                <th class="ar">Ammount Applied / LBP</th>
                                <th class="ar">VAT Applied / LBP</th>
                                @else 
                                <th>Amount Applied USD</th>
                                @endif
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->items as $item)
                                <tr>
                                    <td class="ar">
                                        {{$item->invoice->number}}
                                    </td>
                                    <td class="ar">
                                        {{moneyFormat($item->amount_applied, $model->currency, false)}}
                                    </td>
                                    @if($item->amount_applied_lbp != 0)
                                    <td class="ar">
                                        {{moneyFormat($item->amount_applied_lbp, $model->currency, false)}}
                                       * {{moneyFormat($item->amount_applied_lbp_rate, $model->currency, false)}}
                                    </td>
                                    @endif
                                    @if($item->amount_applied_vat != 0)
                                    <td class="ar">
                                        {{moneyFormat($item->amount_applied_vat, $model->currency, false)}}
                                       * {{moneyFormat($item->amount_applied_vat_rate, $model->currency, false)}}
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                       
                        <table class="items">
                        <tfoot>
                            <tr>
                               
                                @if($item->amount_applied_lbp != 0)
                                <td colspan="4">
                                    <strong>Cash USD</strong>
                                </td>
                                @else 
                                <td colspan="2">
                                    <strong>Cash USD</strong>
                                </td>
                                @endif
                                <td class="ar">
                                    <strong> 
                                        @foreach($model->items as $item)
                                       {{moneyFormat($item->amount_applied, $model->currency, false)}}
                                        @endforeach
                                    </strong>
                                </td>
                            </tr>
                            @if($item->amount_applied_lbp != 0)
                            <tr>
                               
                                @if($item->amount_applied_lbp != 0)
                                <td colspan="4">
                                    <strong>Cash LBP</strong>
                                </td>
                                @else 
                                <td colspan="2">
                                    <strong>Cash LBP</strong>
                                </td>
                                @endif
                                <td class="ar">
                                    <strong>
                                        @foreach($model->items as $item)
                                        {{moneyFormat($item->amount_applied_lbp, $model->currency, false)}}
                                        @endforeach
                                        At Rate: {{moneyFormat($item->amount_applied_lbp_rate, $model->currency, false)}}
                                    </strong>
                                </td>
                            </tr>
                            @endif
                            @if($item->amount_applied_vat != 0)
                            <tr>
                                
                                @if($item->amount_applied_lbp != 0)
                                <td colspan="4">
                                    <strong>VAT LBP</strong>
                                </td>
                                @else 
                                <td colspan="2">
                                    <strong>VAT LBP</strong>
                                </td>
                                @endif
                                <td class="ar">
                                    <strong>
                                        @foreach($model->items as $item)
                                        {{moneyFormat($item->amount_applied_vat, $model->currency, false)}}
                                        @endforeach
                                        At Rate: {{moneyFormat($item->amount_applied_vat_rate, $model->currency, false)}}
                                    </strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                              
                                @if($item->amount_applied_lbp != 0)
                                <td colspan="4">
                                    <strong>Total Amount Received (USD)</strong>
                                </td>
                                @else 
                                <td colspan="2">
                                    <strong>Total Amount Received (USD)</strong>
                                </td>
                                @endif
                                <td class="ar">
                                    <strong>
                                        {{moneyFormat($model->amount_received, $model->currency, false)}}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                      <table class="items" style="margin: 1% 0;">
                                <thead>
                                    <tr>
                                       <?php 
                                       $spell = Terbilang::make(round($model->amount_received,2));
                                        ?>
                                        <th width="15%">Only:</th>
                                        <th width="85%" style="text-transform: capitalize;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $spell }} Dollars</th>
                                    </tr>
                                </thead>
                      </table>
 
                      @endsection
                      <style>
                         .content {
                         padding-top: -30px !important;
                         }
                         .header {
                         position: fixed;
                         left: 0;
                         color: #777;
                         text-align: right;
                         }
                         .footer {
                         position: fixed;
                         left: 0;
                         bottom: -20px;
                         color: #777;
                         width: 100%;
                         text-align: center;
                         }
                      </style>

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