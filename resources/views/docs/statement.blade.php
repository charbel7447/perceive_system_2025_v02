<!-- <?php
$invoiceTillDate = App\Invoice\Invoice::where('date','<=',$StartDates)->where('client_id','=','704')->where('status_id','!=',1)->sum('total');
?>
<?php 
    use Rmunate\Utilities\SpellNumber;
?>
{{$invoiceTillDate}} -->
@foreach ( $statement as $statements)
<?php 
        $company_name = \App\Settings::where('key','=','company_name')->value('value');
        $header_line_1 = \App\Settings::where('key','=','header_line_1')->value('value');
        $header_line_2 = \App\Settings::where('key','=','header_line_2')->value('value');
        $header_line_3 = \App\Settings::where('key','=','header_line_3')->value('value');
        $company_name = \App\Settings::where('key','=','company_name')->value('value');
        $footer_line_1 = \App\Settings::where('key','=','footer_line_1')->value('value');
        $footer_line_2 = \App\Settings::where('key','=','footer_line_2')->value('value');
        $footer_line_3 = \App\Settings::where('key','=','footer_line_3')->value('value');
    ?>
<table class="summary">
        <tbody>
            <tr>
                <!-- <td style="vertical-align:middle;padding:30px 0 10px 0;width:100px">
                    <img style="margin:0 0px 0 10px;" width="180" height=""  src="../images/logo.png" />
                </td> -->
                <td style="vertical-align:left;text-align:left;font-size:14px;">
                     
                    <span style="vertical-align:left;text-align:left;font-size:16px;font-weight:bold;">{{$header_line_1}}</span><br>
                    {{$header_line_2}}<br>
                    {{$header_line_3}}<br>
                </td>
                <td style="vertical-align:top;text-align:right;padding-left:20px">
                    <small >
                        <strong>Customer: #</strong>
                        {{$client->id}}
                    </small>
                    </br>
                    {{$statements->created_at->format('d/m/Y')}}
                </td>
            </tr>
        </tbody>
    </table>
 
</header>
<footer class="footer">
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: -0px 0;">{{$footer_line_1}}</p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">{{$footer_line_2}}</p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px; display: ">{{$footer_line_3}}</p>
</footer>
    
    <div class="content-title">
        Statement Of Account
    </div>
    
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address"  style="margin:0 5px;padding:5px 5px;border:2px solid ">
                    <strong>To:</strong>
                    
                        <pre style="margin: 10px 0 0 0;padding: 0;">{{$client->company}}<br>{{$client->billing_address}}</pre>
                        <p style="margin:0;padding: 0;">
                            <strong>Attention:</strong>
                            {{$client->person}}
                        </p>
                        <p>
                            &nbsp;
                        </p>
                   
                </td>
                <td class="summary-address" style="margin:0 5px;padding:5px 5px;border:2px solid ">
                    <strong>Client Details:</strong>
                        <pre>{{$client->billing_address}}</pre>
                        <p>
                            {{$client->city}},{{$client->state}},{{$client->zipcode}}
                        </p>
                        <p>
                            {{$client->phone}}
                        </p>
                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info" style="width:40x%;margin:0 5px;padding:5px 5px;border:2px solid ">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Document Number:</td>
                                <td>SOA-{{ $statements->id + 1000000 }}</td>
                                <!--<td>SOA-{{ $statements->client_id }}{{ $statements->created_at->format('dm') }}</td>-->
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

                                $clientDN = App\DebitNote\DebitNote::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');

                                $clientCN = App\CreditNote\CreditNote::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');

                                $clientCP = App\ClientPayment\ClientPayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
                                $clientRV  = \App\ReceiptVoucher\ReceiptVoucher::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('client_id','=',$client->id)->sum('total_debit_usd');
                                // Remaining
                                $RemainingInvoice = App\Invoice\Invoice::where('date','<=',$StartDates)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
                                $RemainingAD = App\AdvancePayment\AdvancePayment::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                                $RemainingDN = App\DebitNote\DebitNote::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                                $RemainingCN = App\CreditNote\CreditNote::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                                $RemainingCP = App\ClientPayment\ClientPayment::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                                $RemainingRV = \App\ReceiptVoucher\ReceiptVoucher::where('date','<=',$StartDates)->where('client_id','=',$client->id)->sum('total_debit_usd');
                                $clientRemainingBalance = $RemainingInvoice + $RemainingDN - $RemainingAD - $RemainingCN - $RemainingCP - $RemainingRV;
                                ?>
                                <td>{{moneyFormat( $clientRemainingBalance + $clientInvoice - $clientCP - $clientAD - $clientCN + $clientDN - $clientRV, $client->currency, false)}}</td>
                                <!-- <td>{{moneyFormat( $clientInvoice - $clientCP - $clientAD - $clientCN + $clientDN, $client->currency, false)}}</td> -->
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
                    <th class="center" width="10%">Ref. Num</th>
                    <th class="center" width="40%">Description</th>
                    <th class="center" width="8%">Credit</th>
                    <th class="center" width="8%">Debit</th>
                    <th class="center" width="10%">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $xxx = App\Statement\Item::where('reference_date','>=',$StartDates)->where('reference_date','<=',$EndDates)
                    ->where('client_id','=',$client->id)
                    ->orderby('reference_date','asc')
                    ->where('statement_id','=',$statements->id)
                    ->get();

                    $firstRecord = App\Statement\Item::where('reference_date','>=',$StartDates)->where('reference_date','<=',$EndDates)
                    ->where('client_id','=',$client->id)
                    ->orderby('reference_date')
                    ->where('statement_id','=',$statements->id)
                    ->first();
                    
                    $invoiceTillDate = App\Invoice\Invoice::where('date','<=',$StartDates)->where('client_id','=',$client->id)->where('status_id','!=',1)->sum('total');
                    $cpTillDate = App\ClientPayment\ClientPayment::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                    $rvTillDate = \App\ReceiptVoucher\ReceiptVoucher::where('date','<=',$StartDates)->where('client_id','=',$client->id)->sum('total_debit_usd');
                    $cnTillDate = App\CreditNote\CreditNote::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                    $dnTillDate = App\DebitNote\DebitNote::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');
                    $apTillDate = App\AdvancePayment\AdvancePayment::where('payment_date','<=',$StartDates)->where('client_id','=',$client->id)->sum('amount_received');

                    $runningBalancebeforedate = $invoiceTillDate + $dnTillDate - $cpTillDate - $cnTillDate - $apTillDate - $rvTillDate;
                   ?>
                @if($runningBalancebeforedate)
                    <tr>
                    <td>{{$StartDates}} </td>
                    <td>Running</td>
                    <td>Balance Before <strong>{{$StartDates}}</strong>
                    <strong style="text-align:right !important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Running:&nbsp;&nbsp;({{ moneyFormat($runningBalancebeforedate, $client->currency, false) }})</strong>
                    </td>
                    <td>--</td>
                    <td>--</td>
                    <td>({{ moneyFormat($runningBalancebeforedate, $client->currency, false) }})</td>
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
                    if($item->type == 'invoice' || $item->type == 'debitnote')
                    {
                        $Balance += round(($item->amount_applied),2);
                    }elseif($item->type == 'clientpayment' || $item->type == 'receiptvoucher' || $item->type == 'creditnote'  || $item->type == 'advancepayment'){
                        if($item->type == 'receiptvoucher'){
                             $Balance -=round(($item->amount_applied),2);
                        }else{
                             $Balance -=round(($item->amount_applied),2);
                        }   
                    }

                    $invItems = App\Invoice\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('invoice_id','=',$item->reference_id)
                    ->get();

                    $cpItems = App\ClientPayment\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('client_payment_id','=',$item->reference_id)
                    ->where('invoice_id','!=',0)
                    ->get();

                    $rvItems = App\ReceiptVoucher\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('receipt_voucher_id','=',$item->reference_id)
                    ->get();

                    $cnItems = App\CreditNote\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('credit_notes_id','=',$item->reference_id)
                    ->get();

                    $cdItems = App\DebitNote\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('debit_notes_id','=',$item->reference_id)
                    ->get();

                    $adItems = App\AdvancePayment\Item::where('created_at','>=',$StartDates)
                    ->where('created_at','<=',$EndDates)
                    ->where('advance_payment_id','=',$item->reference_id)
                    ->get();
                           
                ?>
                
                    <tr>
                 
                        <td>{{$item->reference_date}} </td>
                        <td>{{$item->reference_number}}</td>
                        @if($item->type == 'invoice')
                            <td style="">
                                 @if($item->reference_number == 'Equiplast_Balance')
                                      <strong >Reconcile balance amount</strong>
                                    @else
                                    @foreach ($invItems as $invItem)
                                    <?php 
                                        $productIN = App\Product\Product::where('id','=',$invItem->item_id)->get();
                                    ?>
                                    @foreach($productIN as $productI)
                                    <strong style="font-size: 14x;">Product:</strong>{{$productI->description}}
                                    @endforeach    
                                       <span style="padding: 0 0px 0 20px;font-size: 14x;">
                                            <strong>Qty:</strong>
                                            {{$invItem->quantity}} x 
                                            <strong>U.P:</strong>
                                            {{$invItem->price}}
                                       </span>
                                       <br>
                                    @endforeach
                                    @endif 
                            </td>
                        @endif
                        @if($item->type == 'clientpayment') 
                        <td style="">
                            @foreach ($cpItems as $cpItem)
                            <?php
                                $invoice = App\Invoice\Invoice::where('id','=',$cpItem->invoice_id)->pluck('number');
                            ?>
                               <span style="font-size: 14x;">
                                   Client Payment for Invoice ID <b>{{$invoice}}</b><br>
                                   @if($cpItem->amount_applied > 1)
                                   Amount Applied <b>USD</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied ,$client->currency, false)}}<br>
                                   @endif
                                   @if($cpItem->amount_applied_lbp > 1)
                                   Amount Applied <b>LBP</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied_lbp ,$client->currency, false)}} / {{$cpItem->amount_applied_lbp_rate}} = {{moneyFormat( $cpItem->amount_applied_lbp / $cpItem->amount_applied_lbp_rate ,$client->currency, false)}}<br>
                                   @endif
                                   @if($cpItem->amount_applied_vat > 1)
                                   Amount Applied <b>VAT Lbp</b>:&nbsp;&nbsp;{{moneyFormat( $cpItem->amount_applied_vat ,$client->currency, false)}} / {{$cpItem->amount_applied_vat_rate}} = {{moneyFormat( $cpItem->amount_applied_vat / $cpItem->amount_applied_vat_rate ,$client->currency, false)}}<br>
                                   @endif
                               </span>
                            
                            @endforeach
                        </td>
                        @endif

                         @if($item->type == 'receiptvoucher') 
                        <td style="">
                            @foreach ($rvItems as $rvItem)
                            <?php
                                $rvNumber = App\ReceiptVoucher\ReceiptVoucher::where('id','=',$rvItem->receipt_voucher_id)->value('number');
                            ?>
                               <span style="font-size: 14x;">
                                    <b style="color:red;">({{$rvItem->account_receivable_number}})</b> - {{$rvItem->account_receivable_name}}
                                     <b style="color:red;">({{$rvItem->payment_mode}})</b> - {{$rvItem->debit}} {{$rvItem->account_receivable_currency_code}}
                                    @if($rvItem->debit_vat > 0)<br><b style="color:red;">VAT {{$rvItem->debit_vat}} {{$rvItem->account_receivable_currency_code}}  @endif
                               </span>
                            <br>
                            @endforeach
                        </td>
                        @endif
                        @if($item->type == 'creditnote') 
                        <td style="font-size: 14x;">
                            @foreach ($cnItems as $cnItem)
                            <?php
                                $invoiceName = App\Invoice\Invoice::where('id','=',$cnItem->invoice_id)->pluck('number');
                            ?>
                                Credit Note Applied : {{moneyFormat($cnItem->amount_applied ,$client->currency, false)}} USD for Invoice Number:  <b>{{ $invoiceName }}</b>
                               
                            @endforeach
                        </td>
                        @endif
                        @if($item->type == 'debitnote') 
                        <td style="font-size: 14x;">
                            @foreach ($cdItems as $cdItem)
                            <?php
                                $invoiceNamex = App\Invoice\Invoice::where('id','=',$cdItem->invoice_id)->pluck('number');
                            ?>
                                Debit Note Applied : {{moneyFormat($cdItem->amount_applied ,$client->currency, false)}} USD for Invoice Number:  <b>{{ $invoiceNamex }}</b>
                               
                            @endforeach
                        </td>
                        @endif
                        @if($item->type == 'advancepayment') 
                        <td style="font-size: 14x;">
                            @foreach ($adItems as $adItem)
                            <?php
                                $invoiceNamea = App\Invoice\Invoice::where('id','=',$adItem->invoice_id)->pluck('number');
                            ?>
                                Advance Payment Applied : {{moneyFormat($adItem->amount_applied ,$client->currency, false)}} USD for Invoice Number:  <b>{{ $invoiceNamea }}</b>
                               
                            @endforeach
                        </td>
                        @endif
                        
                        @if($item->type == 'clientpayment' || $item->type == 'receiptvoucher' || $item->type == 'creditnote' || $item->type == 'advancepayment' )
                        <td>{{ moneyFormat($item->amount_applied, $client->currency, false) }}</td>
                        @else
                        <td> -- </td>
                        @endif
                        @if($item->type == 'invoice' || $item->type == 'debitnote')
                        <td>{{ moneyFormat($item->amount_applied, $client->currency, false) }}</td>
                        @else
                        <td> -- </td>
                        @endif
                        <td>{{ moneyFormat($Balance, $client->currency, false) }}</td>
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
              <th class="center" width="22%">{{ moneyFormat($runningBalancebeforedate, $client->currency, false) }} </th>
            </tr>
        </thead>
  </table>
    <?php 
            $totalsum1 = App\Invoice\Invoice::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         ?>
    @if($totalsum1 > 0)    
    <table class="items" style="margin: 0px 0 0 0 ;padding: 0;">
        <thead>
       
           <tr> 
           <th class="center" width="54%" style="font-weight: bold;">{{date('d-m-Y', strtotime($statements->date)) }}&nbsp;&nbsp;-&nbsp;&nbsp;{{date('d-m-Y', strtotime($statements->due_date)) }}</th>
              <th class="center" width="23%">Invoices Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum1, $client->currency, false) }} </th>
            </tr>
        </thead>
  </table>
  @endif
      <?php 
            $totalsum2 = App\ClientPayment\ClientPayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
         ?>
  @if($totalsum2 > 0) 
  <table class="items" style="margin: 0;padding: 0;">
        <thead>
     
           <tr>
           <th class="center" width="54%" style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
              <th class="center" width="23%">Client Payments Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum2, $client->currency, false) }} </th>
            </tr>
        </thead>
  </table>
  @endif

    <?php 
            $totalsum7 = App\ReceiptVoucher\ReceiptVoucher::where('date','>=',$StartDates)->where('date','<=',$EndDates)->where('client_id','=',$client->id)->sum('total');
         ?>
  @if($totalsum7 > 0) 
    <table class="items" style="margin: 0;padding: 0;">
        <thead>
       
           <tr>
           <th class="center" width="54%" style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
              <th class="center" width="23%">ReceiptVoucher Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum7, $client->currency, false) }} </th>
            </tr>
        </thead>
  </table>
    @endif
       <?php 
            $totalsum3 = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
         ?>
  @if($totalsum3 > 0) 
  <table class="items" style="margin: 0;padding: 0;">
        <thead>
      
           <tr>
              <th class="center" width="54%" style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
              <th class="center" width="23%">Advance Payments Amount</th>
              <th class="center" width="22%">{{ moneyFormat($totalsum3, $client->currency, false) }} </th>
            </tr>
        </thead>
  </table>
  @endif
      <?php 
        $totalsum4 = App\CreditNote\CreditNote::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
     ?>
  @if($totalsum4 > 0) 
  <table class="items" style="margin: 0;padding: 0;">
    <thead>
 
       <tr>
          <th class="center" width="54%" style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
          <th class="center" width="23%">Credit Notes Amount</th>
          <th class="center" width="22%">{{ moneyFormat($totalsum4, $client->currency, false) }} </th>
        </tr>
    </thead>
</table>
  @endif
      <?php 
        $totalsum5 = App\DebitNote\DebitNote::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
     ?>
  @if($totalsum5 > 0) 
<table class="items" style="margin: 0;padding: 0;">
    <thead>
 
       <tr>
          <th class="center" width="54%" style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
          <th class="center" width="23%">Debit Notes Amount</th>
          <th class="center" width="22%">{{ moneyFormat($totalsum5, $client->currency, false) }} </th>
        </tr>
    </thead>
</table>
  @endif
   <?php 
            $totalsum6 = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$StartDates)->where('payment_date','<=',$EndDates)->where('client_id','=',$client->id)->sum('amount_received');
         ?>
  @if($totalsum6 > 0) 
   <table class="items" style="margin: 0;padding: 0;">
        <thead>
        
           <tr>
              <th class="center" width="54%"  style="border-left-color: #dedede !important;border-bottom-color: #dedede !important;border-top-color: #dedede !important;"></th>
              <th class="center" width="23%"  style="font-weight: bold;">Remaining Balance</th>
              <th class="center" width="22%">{{ moneyFormat($runningBalancebeforedate + $totalsum1 - $totalsum2 - $totalsum3 - $totalsum4 + $totalsum5 - $totalsum7 , $client->currency, false) }} </th>
            </tr>

            
        </thead>
  </table>
  @endif
  <table class="items" style="margin: 0;padding: 0;">
     <thead>
      <?php 
      
      $sum1x = $runningBalancebeforedate + $totalsum1 - $totalsum2 - $totalsum3 - $totalsum4 + $totalsum5 - $totalsum7 ;
      $spell = Terbilang::make(round($sum1x,2));  
       

               SpellNumber::getLanguages();
               $spell = SpellNumber::value(round($sum1x,2))->locale('en')
               ->currency('Dollars')
               ->fraction('cents')
               ->toMoney();

?>
       <tr>
         <th class="center" width="10%" >Only:</th>
         <th class="" width="90%" style="text-transform: capitalize;" >&nbsp;&nbsp;&nbsp;&nbsp;{{$spell}}  </th>
       </tr>
     </thead>
  </table>
 @endforeach
<style>
    .center {text-align: center !important;}
   .content {
   padding-top: -30px !important;
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
        margin-top: -120px;
    }
    .content-title {
    margin-top: 0px !important;
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
        border: 0.2mm solid #000;
    }
    .items thead th {
        padding: 6px 3px;
        background: #dedede;
        border: 0.2mm solid #000;
    }
    .items tbody td {
        border: 0.2mm solid #000;
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