<table class="items" style="margin: 0;padding: 0;">
    <thead> </thead>
</table>
<?php $mytime = Carbon\Carbon::now(); ?>
<header class="header" style="float: left;    position: relative !important;">
    <br>
<h4 style="color: #777;margin: -80px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.&nbsp;&nbsp;  &nbsp;
{{$mytime->format('d/m/Y')}}
</h4>
</header>
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
 Quantity / Products
</div>
<table class="summary">
    <tbody>
        <tr>
            <td class="summary-address">
                <strong>To:</strong>
                
                    <p style="margin: 10px 0 0 0;padding: 0;"></p>{{$client->company}}<br>{{$client->billing_address}}</p>
                    <p  style="margin: 10px 0 0 0;padding: 0;">
                        <strong>Attention:</strong>
                        {{$client->person}}
                    </p>
               
            </td>
            <?php 
                $yearF =  now()->year;
                $yearE =  now()->year+'1';
            ?>
            <td class="summary-empty"></td>
            <td class="summary-info" style="width:40%;">
                <table class="info">
                    <tbody>
                        
                        <tr>
                            <td>From Date:</td>
                            <td>01-01-{{$yearF}}</td>
                        </tr>
                        <tr>
                            <td>To Date:</td>
                            <td>01-01-{{$yearE}}</td>
                        </tr>
                        <tr>
                            <td>Currency:</td>
                            <td>USD</td>
                        </tr>
                        <tr>
                            <td>Client Total Balance:</td>

                            <?php
                            $clientInvoice = App\Invoice\Invoice::where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');

                            $clientAD = App\AdvancePayment\AdvancePayment::where('client_id','=',$client->id)->sum('amount_received');
                            
                            $totalPayments = App\ClientPayment\ClientPayment::where('client_id','=',$client->id)->sum('amount_received');
                            ?>

                            <td>{{moneyFormat( $clientInvoice - $totalPayments - $clientAD, $client->currency, false)}}</td>
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
            <th class="center" width="12%">Ref. Num</th>
            <th class="center" width="12%">Qty. Of Product</th>
            <th class="center" width="30%">Unit Price</th>
         
        </tr>
    </thead>
    <tbody>
        @foreach ($Inv as $Inv1)
         <tr>
            <td>{{$Inv1->invoice_number}}</td>
            <td>{{$Inv1->invoice_qty}} Of {{$Inv1->product_name}}</td>
            <td>{{$Inv1->invoice_price}}</td>
          
          </tr>
        @endforeach
    </tbody>
</table>
</section>
<style>
    .center {text-align: center !important;}
   .content {
   padding-top: -30px !important;
   }
   /*.doc-logo {*/
   /*  width: 140px;*/
   /*  margin-top: -40px;*/
   /*  text-align: left;*/
   /*  float: left; */
   /*}*/
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
    .header-logo {
        width: 25%;
        text-align: right;
    }
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {
   
    }
    .content-title {
    margin-top: 71px !important;
        margin-bottom: 20px;
        padding: 5px;
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        border: 0.1mm solid #484746;
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
        border: 0.1mm solid #484746;
    }
    .items thead th {
        padding: 6px 3px;
        background: #f8f8f8;
        border: 0.1mm solid #484746;
    }
    .items tbody td {
        border: 0.1mm solid #484746;
        padding: 3px;
    }
   
    .items tfoot td {
         background: #f1f1f1;
        border: 0.1mm solid #484746;
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
        bottom:0 !important;
        position: fixed;
    }
   
   
    .header {
   position: fixed;
   left: 0;
   top: 50px;
   color: #777;
   width: 100%;
   text-align: center;
   }
   .footer {
   position: fixed;
   left: 0;
   bottom: -50px;
   color: #777;
   width: 100%;
   text-align: center;
   }
   
   </style>
   