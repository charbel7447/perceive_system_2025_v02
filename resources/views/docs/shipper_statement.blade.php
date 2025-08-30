@foreach ( $statement as $statements)
 
<table class="items" style="margin: 0;padding: 0;">
    <thead>
       
    </thead>
</table>

<header class="header" style="float: left;    position: relative !important;">
<br>
<img  width="220" style="margin-top: -70px;text-align: left;float: left;background-color: transparent;text-decoration: none;"  /><br>
<h4 style="color: #777;margin: -60px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.
<br>
{{$statements->created_at->format('d/m/Y')}}</h4>
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
    <div class="content-title">
        Statement Of Account
    </div>
    
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address" style="width:20%;">
                    <strong>To:</strong>
                    
                        <pre style="margin: 10px 0 0 0;padding: 0;">{{$vendor->company}}<br>{{$vendor->billing_address}}</pre>
                        <p style="margin:0;padding: 0;">
                            <strong>Attention:</strong>
                            {{$vendor->person}}
                        </p>
                   
                </td>
                <td class="summary-info" style="width:40%;">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Document Number:</td>
                                <td>SOA-{{ $statements->id + 1000000 }}</td>
                                <!--<td>SOA-{{ $statements->shipper_id }}{{ $statements->created_at->format('dm') }}</td>-->
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
                                <td>Shipper Total Balance:</td>

                                <?php
                                $vendorBill = App\ShipperBill\ShipperBill::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('shipper_id','=',$vendor->id)->where('status_id','>=','1')->sum('total');
                                $shipperPayment = App\ShipperPayment\ShipperPayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('shipper_id','=',$vendor->id)->sum('amount_paid');

                                // Remaining
                                $RemainingBill = App\ShipperBill\ShipperBill::where('date','<=',$StartDates)->where('shipper_id','=',$vendor->id)->where('status_id','>=','1')->sum('total');
                                $RemainingPayment = App\ShipperPayment\ShipperPayment::where('payment_date','<=',$StartDates)->where('shipper_id','=',$vendor->id)->sum('amount_paid');
                               
                                $vendorRemainingBalance = $RemainingBill - $RemainingPayment;
                                ?>
                                <td>{{moneyFormat( $vendorRemainingBalance + $vendorBill - $shipperPayment, $vendor->currency, false)}}</td>
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
                    <th class="center" width="9%">Date</th>
                    <th class="center" width="12%">Ref. Num</th>
                    <th class="center" width="40%">Description</th>
                    <th class="center" width="8%">Credit</th>
                    <th class="center" width="8%">Debit</th>
                    <th class="center" width="10%">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $xxx = App\ShipperStatement\Item::where('reference_date','>=',$StartDates)->where('reference_date','<=',$EndDates)
                    ->where('shipper_id','=',$vendor->id)
                    ->orderby('reference_date','asc')
                    ->where('statement_id','=',$statements->id)
                    ->get();

                    $firstRecord = App\ShipperStatement\Item::where('reference_date','>=',$StartDates)->where('reference_date','<=',$EndDates)
                    ->where('shipper_id','=',$vendor->id)
                    ->orderby('reference_date')
                    ->where('statement_id','=',$statements->id)
                    ->first();
                    
                    $billsTillDate = App\ShipperBill\ShipperBill::where('date','<=',$StartDates)->where('shipper_id','=',$vendor->id)->where('status_id','>=',1)->sum('total');
                    $paymentsTillDate = App\shipperPayment\shipperPayment::where('payment_date','<=',$StartDates)->where('shipper_id','=',$vendor->id)->sum('amount_paid');

                    $runningBalancebeforedate = $billsTillDate  - $paymentsTillDate;
                   ?>

                @if($runningBalancebeforedate > 0 )
                    <tr>
                    <td>{{$StartDates}} </td>
                    <td>Running</td>
                    <td>Balance Before <strong>{{$StartDates}}</strong></td>
                    <td>-</td>
                    <td>-</td>
                    <td><strong>Running: </strong>{{ moneyFormat($runningBalancebeforedate, $vendor->currency, false) }}</td>
                </tr>
                @endif
                @foreach($xxx as $item)
                <?php
                    if($loop->first)
                    {
                        // $Balance = round(($firstRecord->amount_applied) - ($item->amount_applied),2);
                        if($runningBalancebeforedate > 0 ){
                             $Balance = round(($runningBalancebeforedate) + ($firstRecord->amount_applied) - ($item->amount_applied),2);
                        }else{
                            $Balance = round(($firstRecord->amount_applied) - ($item->amount_applied),2);
                        }
                        
                    }
                    if($item->type == 'bill')
                    {
                        $Balance += round(($item->amount_applied),2);
                    }elseif($item->type == 'shipperpayment'){
                        $Balance -=round(($item->amount_applied),2);
                    }

                    $invItems = App\ShipperBill\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('shipper_bill_id','=',$item->reference_id)
                    ->get();

                    $cpItems = App\ShipperPayment\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('shipper_payment_id','=',$item->reference_id)
                    ->where('shipper_bill_id','!=',0)
                    ->get();

                ?>
                
                    <tr>
                 
                        <td>{{$item->reference_date}} </td>
                        <td>{{$item->reference_number}}</td>
                        @if($item->type == 'bill')
                            <td style="">
                                    @foreach ($invItems as $invItem)
                                    <?php 
                                        $productIN = App\Product\Product::where('id','=',$invItem->product_id)->get();
                                    ?>
                                    @foreach($productIN as $productI)
                                    <strong style="font-size: 14x;">Product:</strong>{{$productI->description}}
                                    @endforeach    
                                       <span style="padding: 0 0px 0 20px;font-size: 14x;">
                                            <strong>Qty:</strong>
                                            {{$invItem->quantity}} x 
                                            <strong>U.P:</strong>
                                            {{$invItem->unit_price}}
                                       </span>
                                       <br>
                                    @endforeach
                                
                            </td>
                        @endif
                        @if($item->type == 'shipperpayment') 
                        <td style="">
                            @foreach ($cpItems as $cpItem)
                            <?php
                                $invoice = App\ShipperBill\ShipperBill::where('id','=',$cpItem->shipper_bill_id)->pluck('number');
                            ?>
                               <span style="font-size: 14x;">
                                   Shipper Payment for Bill ID <b>{{$invoice}}</b><br>
                                   @if($cpItem->amount_applied > 1)
                                   Amount Applied <b>USD</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied ,$vendor->currency, false)}}<br>
                                   @endif
                                   @if($cpItem->amount_applied_lbp > 1)
                                   Amount Applied <b>LBP</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied_lbp ,$vendor->currency, false)}} / {{$cpItem->amount_applied_lbp_rate}} = {{moneyFormat( $cpItem->amount_applied_lbp / $cpItem->amount_applied_lbp_rate ,$vendor->currency, false)}}<br>
                                   @endif
                                   @if($cpItem->amount_applied_vat > 1)
                                   Amount Applied <b>VAT Lbp</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied_vat ,$vendor->currency, false)}} / {{$cpItem->amount_applied_vat_rate}} = {{moneyFormat( $cpItem->amount_applied_vat / $cpItem->amount_applied_vat_rate ,$vendor->currency, false)}}<br>
                                   @endif
                               </span>
                            
                            @endforeach
                        </td>
                        @endif
                        
                        @if($item->type == 'shipperpayment')
                        <td>{{ moneyFormat($item->amount_applied, $vendor->currency, false) }}</td>
                        @else
                        <td> - </td>
                        @endif
                        @if($item->type == 'bill')
                        <td>{{ moneyFormat($item->amount_applied, $vendor->currency, false) }}</td>
                        @else
                        <td> - </td>
                        @endif
                        <td>{{ moneyFormat($Balance, $vendor->currency, false) }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    <!-- running balance -->
    <table class="items" style="margin: 25px 0 0 0 ;padding: 0;">
        <thead>
           <tr> 
              <th class="center" width="54%" style="font-weight: bold;">Summary Between </th>
              <th class="center" width="23%">Running Balance</th>
              <th class="center" width="22%">{{ moneyFormat($runningBalancebeforedate, $vendor->currency, false) }} </th>
            </tr>
        </thead>
  </table>
    <table class="items" style="margin: 0px 0 0 0 ;padding: 0;">
        <thead>
         <?php 
            $totalsum1 = App\ShipperBill\ShipperBill::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('shipper_id','=',$vendor->id)->where('status_id','>=','1')->sum('total');
         ?>
           <tr> 
           <th class="center" width="54%" style="font-weight: bold;">{{date('d-m-Y', strtotime($statements->date)) }}&nbsp;&nbsp;-&nbsp;&nbsp;{{date('d-m-Y', strtotime($statements->due_date)) }}</th>
              <th class="center" width="23%">Bills Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum1, $vendor->currency, false) }} </th>
            </tr>
        </thead>
  </table>
  <table class="items" style="margin: 0;padding: 0;">
        <thead>
         <?php 
            $totalsum2 = App\ShipperPayment\ShipperPayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('shipper_id','=',$vendor->id)->sum('amount_paid');
         ?>
           <tr>
           <th class="center" width="54%" style="border-left-color: #ecf0f5 !important;border-bottom-color: #ecf0f5 !important;border-top-color: #ecf0f5 !important;"></th>
              <th class="center" width="23%">Shipper Payments Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum2, $vendor->currency, false) }} </th>
            </tr>
        </thead>
  </table>

  <table class="items" style="margin: 0;padding: 0;">
     <thead>
      <?php 
      
      $sum1x = $runningBalancebeforedate + $totalsum1 - $totalsum2;
      $spell = Terbilang::make(round($sum1x,2));   ?>
       <tr>
         <th class="center" width="10%" >Only:</th>
         <th class="" width="90%" style="text-transform: capitalize;" >&nbsp;&nbsp;&nbsp;&nbsp;{{$spell}} Dollars </th>
       </tr>
     </thead>
  </table>
 @endforeach
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
       bottom: 0px;
       color: #777;
       width: 100%;
       text-align: center;
       }
</style>