@foreach ( $statement as $statements)

     <table class="items" style="margin: 0;padding: 0;">
           <thead>
              
           </thead>
     </table>

   <header class="header" style="float: left;">
  <br>
  <img src="images/polyxpert-logo.png" width="140" style="margin-top: -70px;text-align: left;float: left;" /><br>
   <h4 style="color: #777;margin: -30px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.</h4>
   <h4 style="color: #777;margin: -20px 0 0 0;padding: -60px 0;text-align: right;">{{settings()->get('footer_line_2')}}</h4>
   <h4 style="color: #777;margin: -10px 0 0 0;padding: -40px 0;text-align: right;">{{$statements->created_at->format('d/m/Y')}}</h4>
</header>
<footer class="footer">
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: -0px 0;">{{settings()->get('footer_line_2')}} &nbsp;&nbsp; &#8226; &nbsp;&nbsp; Lebanon - Bekaa &nbsp;&nbsp; &#8226; &nbsp;&nbsp;Kherbet Qanafar &nbsp;&nbsp; &#8226; &nbsp;&nbsp; Plastic Resin Distributor</p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">info@polyxpert.me &nbsp;&nbsp; &#8226; &nbsp;&nbsp; www.polyxpert.me &nbsp;&nbsp; &#8226; &nbsp;&nbsp; Tel.: +961 01 234 567  &nbsp;&nbsp; &#8226; &nbsp;&nbsp;Fax: +961 646 830 &nbsp;&nbsp; &#8226; &nbsp;&nbsp; MOF No. 3420789-601</p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px; display: "> &nbsp;&nbsp;  &nbsp;&nbsp; </p>
</footer>
    
    <div class="content-title">
        Statement Of Account
    </div>
    
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address">
                    <strong>To:</strong>
                    
                        <pre>{{$client->company}}<br>{{$client->billing_address}}</pre>
                        <p>
                            <strong>Attention:</strong>
                            {{$client->person}}
                        </p>
                   
                </td>
                <td class="summary-empty"></td>
                <td class="summary-info" style="width:40%;">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Document Number:</td>

                                <td>SOA-{{ $statements->client_id }}{{ $statements->created_at->format('dm') }}</td>
                            </tr>
                            <tr>
                                <td>From Date:</td>
                                <td>{{$statements->date}}</td>
                            </tr>
                            <tr>
                                <td>To Date:</td>
                                <td>{{$statements->due_date}}</td>
                            </tr>
                            <tr>
                                <td>Currency:</td>
                                <td>USD</td>
                            </tr>
                            <tr>
                                <td>Client Total Balance:</td>

                                <?php
                                $clientInvoice = App\Invoice\Invoice::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');

                                $clientAD = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');

                                ?>

                                <td>{{moneyFormat( $clientInvoice - $client->total_revenue - $clientAD, $client->currency, false)}}</td>
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
                    <th class="center" width="12%">Date</th>
                    <th class="center" width="12%">Ref. Num</th>
                    <th class="center" width="15%">Debit</th>
                    <th class="center" width="15%">Credit</th>
                    <th class="center" width="15%">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $Inv = App\Invoice\Invoice::where('date','>=',$StartDates)->where('date','<=',$EndDates)
                    ->where('invoices.status_id','!=',1)
                    ->leftjoin('client_payment_items','invoices.id','=','client_payment_items.invoice_id')
                    ->leftjoin('client_payments','client_payment_items.client_payment_id','=','client_payments.id')
                    ->where('invoices.client_id','=',$client->id)
                    ->select('invoices.number as invoice_number',
                    'client_payments.number as client_payments_number',
                    'invoices.total as invoice_amount',
                    'client_payments.amount_received as client_payments_amount',
                    'invoices.date as invoice_date',
                    'client_payments.payment_date as client_payments_date'
                    )
                    ->orderBy('invoice_date','asc')
                    ->get();

                   

                     ?>
                @foreach($Inv as $item)
                    <tr>
                 
                        <td>{{$item->invoice_date}} </td>
                        <td>{{$item->invoice_number}}</td>
                        <td>-</td>
                        <td>{{ moneyFormat($item->invoice_amount, $client->currency, false) }}</td>
                        <td>Balance</td>
                    </tr>
                        <td>{{$item->client_payments_date}} </td>
                        <td>{{$item->client_payments_number}}</td>
                        <td>{{$item->client_payments_amount}}</td>
                        <td>-</td>
                        <td>Balance</td>
                    <tr>

                    </tr>
                @endforeach
            </tbody>
    </table>
 @endforeach
<style>
    .center {text-align: center !important;}
   .content {
   padding-top: -30px !important;
   }
   .doc-logo {
     width: 140px;
     margin-top: -40px;
     text-align: left;
     float: left; 
   }
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
