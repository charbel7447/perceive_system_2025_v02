@extends('docs.master')
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>Main Report Per Client</title>
      <!-- import plugin script -->
      <script src='{{url('/')}}/js/Chart.min.js'></script>
   </head>
   <body>
     
      {{-- @extends('adminlte::page') --}}
      @section('content')
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/all.min.css') }}">
      <!-- Ionicons -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
      <?php 
         $yearF =  now()->year.'-01-01';
         $yearE =  now()->year+'1'.'-01-01';
         $yearF01 =  now()->year.'-01-01';
         $yearE01 =  now()->year.'-01-31';
         $yearF02 =  now()->year.'-02-01';
         $yearE02 =  now()->year.'-02-31';
         $yearF03 =  now()->year.'-03-01';
         $yearE03 =  now()->year.'-03-31';
         $yearF04 =  now()->year.'-04-01';
         $yearE04 =  now()->year.'-04-31';
         $yearF05 =  now()->year.'-05-01';
         $yearE05 =  now()->year.'-05-31';
         $yearF06 =  now()->year.'-06-01';
         $yearE06 =  now()->year.'-06-31';
         $yearF07 =  now()->year.'-07-01';
         $yearE07 =  now()->year.'-07-31';
         $yearF08 =  now()->year.'-08-01';
         $yearE08 =  now()->year.'-08-31';
         $yearF09 =  now()->year.'-09-01';
         $yearE09 =  now()->year.'-09-31';
         $yearF10 =  now()->year.'-10-01';
         $yearE10 =  now()->year.'-10-31';
         $yearF11 =  now()->year.'-11-01';
         $yearE11 =  now()->year.'-11-31';
         $yearF12 =  now()->year.'-12-01';
         $yearE12 =  now()->year.'-12-31';
         
            // All Invoices per month 
         $totalsum1 = App\Invoice\Invoice::where('date','>=', $yearF01)->where('date','<=', $yearE01)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum2 = App\Invoice\Invoice::where('date','>=', $yearF02)->where('date','<=', $yearE02)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum3 = App\Invoice\Invoice::where('date','>=', $yearF03)->where('date','<=', $yearE03)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum4 = App\Invoice\Invoice::where('date','>=', $yearF04)->where('date','<=', $yearE04)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum5 = App\Invoice\Invoice::where('date','>=', $yearF05)->where('date','<=', $yearE05)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum6 = App\Invoice\Invoice::where('date','>=', $yearF06)->where('date','<=', $yearE06)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum7 = App\Invoice\Invoice::where('date','>=', $yearF07)->where('date','<=', $yearE07)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum8 = App\Invoice\Invoice::where('date','>=', $yearF08)->where('date','<=', $yearE08)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum9 = App\Invoice\Invoice::where('date','>=', $yearF09)->where('date','<=', $yearE09)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum10 = App\Invoice\Invoice::where('date','>=', $yearF10)->where('date','<=', $yearE10)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum11 = App\Invoice\Invoice::where('date','>=', $yearF11)->where('date','<=', $yearE11)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum12 = App\Invoice\Invoice::where('date','>=', $yearF12)->where('date','<=', $yearE12)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         
         // All Advance Payments + Client Payments per month 
         $cpsum1 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF01)->where('payment_date','<=', $yearE01)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum1 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF01)->where('payment_date','<=', $yearE01)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad1 =  $cpsum1 + $adsum1 ;
         
         $cpsum2 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF02)->where('payment_date','<=', $yearE02)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum2 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF02)->where('payment_date','<=', $yearE02)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad2 =  $cpsum2 + $adsum2 ;
         
         $cpsum3 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF03)->where('payment_date','<=', $yearE03)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum3 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF03)->where('payment_date','<=', $yearE03)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad3 =  $cpsum3 + $adsum3 ;
         
         $cpsum4 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF04)->where('payment_date','<=', $yearE04)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum4 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF04)->where('payment_date','<=', $yearE04)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad4 =  $cpsum4 + $adsum4 ;
         
         $cpsum5 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF05)->where('payment_date','<=', $yearE05)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum5 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF05)->where('payment_date','<=', $yearE05)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad5 =  $cpsum5 + $adsum5 ;
         
         $cpsum6 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF06)->where('payment_date','<=', $yearE06)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum6 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF06)->where('payment_date','<=', $yearE06)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad6 =  $cpsum6 + $adsum6 ;
         
         $cpsum7 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF07)->where('payment_date','<=', $yearE07)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum7 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF07)->where('payment_date','<=', $yearE07)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad7 =  $cpsum7 + $adsum7 ;
         
         $cpsum8 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF08)->where('payment_date','<=', $yearE08)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum8 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF08)->where('payment_date','<=', $yearE08)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad8 =  $cpsum8 + $adsum8 ;
         
         $cpsum9 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF09)->where('payment_date','<=', $yearE09)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum9 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF09)->where('payment_date','<=', $yearE09)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad9 =  $cpsum9 + $adsum9 ;
         
         $cpsum10 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF10)->where('payment_date','<=', $yearE10)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum10 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF10)->where('payment_date','<=', $yearE10)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad10 =  $cpsum10 + $adsum10 ;
         
         $cpsum11 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF11)->where('payment_date','<=', $yearE11)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum11 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF11)->where('payment_date','<=', $yearE11)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad11 =  $cpsum11 + $adsum11 ;
         
         $cpsum12 = App\ClientPayment\ClientPayment::where('payment_date','>=', $yearF12)->where('payment_date','<=', $yearE12)->where('client_id','=',$client->id)->sum('amount_received');
         $adsum12 = App\AdvancePayment\AdvancePayment::where('payment_date','>=', $yearF12)->where('payment_date','<=', $yearE12)->where('client_id','=',$client->id)->sum('amount_received');  
         $cpad12 =  $cpsum12 + $adsum12 ;

               $Month1QtyProduc1 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
                     ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
                     ->where('product_id','=',1)->where('status_id','!=',1)
                     ->sum('qty');

               $Month1QtyProduc2 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
                     ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
                     ->where('product_id','=',2)->where('status_id','!=',1)
                     ->sum('qty');      
                     
               $Month1QtyProduc3 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
                     ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
                     ->where('product_id','=',3)->where('status_id','!=',1)
                     ->sum('qty');      
               
               $Month1QtyProduc4 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
               ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
               ->where('product_id','=',4)->where('status_id','!=',1)
               ->sum('qty');      
               $Month1QtyProduc5 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
               ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
               ->where('product_id','=',5)->where('status_id','!=',1)
               ->sum('qty');    
               $Month1QtyProduc6 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
               ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
               ->where('product_id','=',6)->where('status_id','!=',1)
               ->sum('qty');    
               $Month1QtyProduc7 = App\Invoice\Invoice::join('invoice_items','invoices.id','=','invoice_items.id')
               ->where('invoices.client_id','=',$client->id)->where('date','>=', $yearF)->where('date','<=', $yearE)
               ->where('product_id','=',7)->where('status_id','!=',1)
               ->sum('qty');   
               
               
               $ProductName1 = App\Product\Product::where('id','=',1)->pluck('description');
               $ProductName2 = App\Product\Product::where('id','=',2)->pluck('description');
               $ProductName3 = App\Product\Product::where('id','=',3)->pluck('description');
               $ProductName4 = App\Product\Product::where('id','=',4)->pluck('description');
               $ProductName5 = App\Product\Product::where('id','=',5)->pluck('description');
               $ProductName6 = App\Product\Product::where('id','=',6)->pluck('description');
               $ProductName7 = App\Product\Product::where('id','=',7)->pluck('description');

       
         
         ?>
 
       {{-- {{$Month1QtyProduc1}}
       {{$Month1QtyProduc2}}
       {{$Month1QtyProduc3}}
       {{$Month1QtyProduc4}}
       {{$Month1QtyProduc5}}
       {{$Month1QtyProduc6}}
       {{$Month1QtyProduc7}} --}}
         
      
      <div class="col-md-12 box box-primary">
         <a href="{{url('/')}}" class="form-control">Back Home</a>
      </div>
      <div class="col-md-12 box box-primary" style="background-color: #ecf0f5;">
         <h4 class="box-title">Invoices Status</h4>
         <div class="box-body">
            {{ $InvoiceTable }}
         </div>
      </div>
      <div class="col-md-12 box box-primary" style="background-color: #ecf0f5;">
         <div class="col-md-6">
            <h4>All Invoices Between {{$yearE}} and {{$yearF}} For {{ $client->company}}</h4>
            <!-- line chart canvas element -->
            <canvas id="buyers" width="600" height="400">
         </div>
         <div class="col-md-6">
            <h4>All Payments Between {{$yearE}} and {{$yearF}} For {{ $client->company}}</h4>
            <!-- line chart canvas element -->
            <canvas id="payments" width="600" height="400"></canvas>
         </div>
      </div>
      <div class="col-md-12 box box-primary" style="background-color: #ecf0f5;">
         <div class="col-md-6">
            <h4 class="box-title">Saled Products Per Qty for {{$client->company}} per {{now()->year}}</h4>
            <div class="box-body">
               <canvas id="countries" width="600" height="400"></canvas>
            </div>
         </div>
         <div class="col-md-6">
            <h4 class="box-title">Client Payments</h4>
            <div class="box-body">
               {{$PaymentsTable}}
            </div>
         </div>
      </div>
      <?php 
         $totalsum1x = App\Invoice\Invoice::where('date','>=',$yearF)->where('date','<=',$yearE)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
         $totalsum2x = App\ClientPayment\ClientPayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->id)->sum('amount_received');
         $totalsum3x = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->id)->sum('amount_received');
               
         ?>
      <div class="box box-primary statement col-md-12" style="background-color: #ecf0f5;">
         <h4> Fiscal Year - Statement Of Account - <strong>{{$client->company}}</strong> 
            <span style="float: right;"><strong>Remaining Balance</strong>:&nbsp;&nbsp;&nbsp;{{ moneyFormat($totalsum1x - $totalsum2x - $totalsum3x , $client->currency, false) }}&nbsp;&nbsp;USD</span>
         </h4>
         <table class="items">
            <thead>
               <tr>
                  <th class="center" width="12%">Date</th>
                  <th class="center" width="12%">Ref. Num</th>
                  <th class="center" width="30%">Description</th>
                  <th class="center" width="15%">Credit</th>
                  <th class="center" width="15%">Debit</th>
                  <th class="center" width="15%">Balance</th>
               </tr>
            </thead>
            <tbody>
               <?php 
                  $invoicesx = App\Invoice\Invoice::where('date','>=',$yearF)->where('date','<=',$yearE)->where('client_id','=',$client->id)->where('status_id','!=','1')->get();
                  ?>
               @foreach($invoicesx as $item)
               <tr>
                  <?php 
                     $invoicesItems = App\Invoice\Item::where('invoice_id','=',$item->id)->get();
                        //  {{$dateE}}
                     
                     // $dateS = $statements->date;
                     // $dateE = $statements->due_date;
                     
                     // $invoicesItems = App\Invoice\Item::whereBetween('created_at', [$dateS, $dateE])->where('invoice_id','=',$item->id)->get();
                     
                     $FInvoices = App\Invoice\Invoice::where('date','>=',$yearF)->where('date','<=',$yearE)->where('client_id','=',$client->id)->where('status_id','!=','1')->first();
                     
                     $F1Invoices = $FInvoices->total;
                       	if($loop->first)
                              {$invBalance = ($F1Invoices);
                         }else{  $invBalance +=($item->total);}
                     
                     
                     ?>
                  <td>{{$item->date}} </td>
                  <td>{{$item->number}}</td>
                  <td >
                     @foreach($invoicesItems as $invoicesI)
                     <!-- {{$invoicesI->product_id}} -->
                     <?php
                        $productIN = App\Product\Product::where('id','=',$invoicesI->product_id)->get();
                        ?>
                     @foreach($productIN as $productI)
                     <strong>ItemCode:</strong>{{$productI->code}} x <strong>Qty:</strong>{{$invoicesI->qty}}
                     @endforeach
                     @endforeach
                  </td>
                  <td>{{ moneyFormat($item->total, $client->currency, false) }}</td>
                  <td>{{ moneyFormat($item->amount_payed, $client->currency, false) }}</td>
                  <td>&nbsp; +  {{ moneyFormat($invBalance , $client->currency, false) }}</td>
               </tr>
               @endforeach
               <?php
                  $totalcp = App\ClientPayment\ClientPayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->client_id)->latest()->take(1)->get();
                  
                  $SumOfInvoices = $invoicesx->sum('total');
                  
                  ?>
               @foreach($payment as $itemp)
               <?php
                  if($loop->first)
                  {$cpBalance = ($SumOfInvoices) - ($itemp->amount_received);
                  
                  
                  }else
                  {
                  
                    $cpBalance -=($itemp->amount_received);
                  }
                  ?>
               <tr>
                  <td>{{$itemp->payment_date}}</td>
                  <td>{{$itemp->number}}</td>
                  <td>{{ moneyFormat($itemp->amount_received , $client->currency, false) }}</td>
                  <td>{{ moneyFormat($itemp->total , $client->currency, false)}}</td>
                  <td>{{ moneyFormat($itemp->amount_received , $client->currency, false) }}</td>
                  <td> &nbsp; - {{ moneyFormat($cpBalance , $client->currency, false) }}</td>
               </tr>
               @endforeach
               @foreach($advancepayment as $itemap)
               <?php
                  if($loop->first)
                  {$apBalance = ($cpBalance) - ($itemap->amount_received);
                  
                  
                  }else
                  {
                  
                    $apBalance -=($itemap->amount_received);
                  }
                  ?>
               <tr>
                  <td>{{$itemap->payment_date}}</td>
                  <td>{{$itemap->number}}</td>
                  <td>{{$itemap->description}}</td>
                  <td>{{$itemap->total}}</td>
                  <td>{{ moneyFormat($itemap->amount_received , $client->currency, false) }}</td>
                  <td> &nbsp; - {{ moneyFormat($apBalance, $client->currency, false) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <table class="items" style="margin: 25px 0 0 0 ;padding: 0;  col-md-12" style="background-color: #ecf0f5;">
            <thead>
               <?php 
                  $totalsum1x = App\Invoice\Invoice::where('date','>=',$yearF)->where('date','<=',$yearE)->where('client_id','=',$client->id)->where('status_id','!=','1')->sum('total');
                  ?>
               <tr>
                  <th class="center" width="54%" style="font-weight: bold;">Summary Between </th>
                  <th class="center" width="23%">Invoices Amount</th>
                  <th class="center" width="22%">{{ moneyFormat($SumOfInvoices, $client->currency, false) }} </th>
               </tr>
            </thead>
         </table>
         <table class="items" style="margin: 0;padding: 0;">
            <thead>
               <?php 
                  $totalsum2x = App\ClientPayment\ClientPayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->id)->sum('amount_received');
                  ?>
               <tr>
                  <th class="center" width="54%" style="font-weight: bold;">Fiscal {{$yearF}}&nbsp;&nbsp;-&nbsp;&nbsp; {{$yearE}}</th>
                  <th class="center" width="23%">Client Payment Amount</th>
                  <th class="center" width="22%">{{ moneyFormat($totalsum2x, $client->currency, false) }} </th>
               </tr>
            </thead>
         </table>
         <table class="items" style="margin: 0;padding: 0;">
            <thead>
               <?php 
                  $totalsum3x = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->id)->sum('amount_received');
                  ?>
               <tr>
                  <th class="center" width="54%" style="border-color: #ecf0f5  !important;"></th>
                  <th class="center" width="23%" style="    border-left: 1.6px solid #000 !important">Advance Payment Amount</th>
                  <th class="center" width="22%">{{ moneyFormat($totalsum3x, $client->currency, false) }} </th>
               </tr>
            </thead>
         </table>
         <table class="items" style="margin: 0;padding: 0;">
            <thead>
               <?php 
                  $totalsum4x = App\AdvancePayment\AdvancePayment::where('payment_date','>=',$yearF)->where('payment_date','<=',$yearE)->where('client_id','=',$client->id)->sum('amount_received');
                  ?>
               <tr>
                  <th class="center" width="54%"  style="border-color: #ecf0f5  !important;"></th>
                  <th class="center" width="23%"  style="font-weight: bold;    border-left: 1.6px solid #000 !important">Remaining Balance</th>
                  <th class="center" width="22%">{{ moneyFormat($totalsum1x - $totalsum2x - $totalsum3x , $client->currency, false) }} </th>
               </tr>
            </thead>
         </table>
      </div>
      <style>
         .col-md-6 {padding: 10px !important;}
      </style>
      <!-- bar chart canvas element -->
      <script>
         // line chart data
         var buyerData = {
             labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
             datasets : [
             {
                 fillColor : "rgba(172,194,132,0.4)",
                 strokeColor : "#ACC26D",
                 pointColor : "#fff",
                 pointStrokeColor : "#9DB86D",
                 data : [{{$totalsum1}},{{$totalsum2}},{{$totalsum3}},{{$totalsum4}},{{$totalsum5}},{{$totalsum6}},{{$totalsum7}},{{$totalsum8}},{{$totalsum9}},{{$totalsum10}},{{$totalsum11}},{{$totalsum12}}]
             }
         ]
         }
         // get line chart canvas
         var buyers = document.getElementById('buyers').getContext('2d');
         // draw line chart
         new Chart(buyers).Line(buyerData);
         
         
          // line chart payments
          var paymentsData = {
             labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
             datasets : [
             {
                 fillColor : "rgba(172,194,132,0.4)",
                 strokeColor : "#ACC26D",
                 pointColor : "#fff",
                 pointStrokeColor : "#9DB86D",
                 data : [{{$cpad1}},{{$cpad2}},{{$cpad3}},{{$cpad4}},{{$cpad5}},{{$cpad6}},{{$cpad7}},{{$cpad8}},{{$cpad9}},{{$cpad10}},{{$cpad11}},{{$cpad12}}]
             }
         ]
         }
              // pie chart data
              var pieData = [
                {
                    label : "{{$ProductName1}}",
                    value: {{$Month1QtyProduc1}},
                    color:"#878BB6"
                },
                {
                    label : "{{$ProductName2}}",
                    value : {{$Month1QtyProduc2}} ,
                    color : "#4ACAB4"
                },
                {
                  label : "{{$ProductName3}}",
                    value : {{$Month1QtyProduc4}},
                    color : "chocolate"
                },
                {
                  label : "{{$ProductName4}}",
                    value : {{$Month1QtyProduc5}},
                    color : "brown"
                }
                ,
                {
                  label : "{{$ProductName5}}",
                    value : {{$Month1QtyProduc6}},
                    color : "#222d32"
                }
                ,
                {
                  label : "{{$ProductName6}}",
                    value : {{$Month1QtyProduc7}},
                    color : "#e4022d"
                }
                ,
                {
                  label : "{{$ProductName7}}",
                    value : {{$Month1QtyProduc3}},
                    color : "coral"
                }
            ];

       
            // pie chart options
            var pieOptions = {
                 segmentShowStroke : false,
                 animateScale : true
            }
            // get pie chart canvas
            var countries= document.getElementById("countries").getContext("2d");
            // draw pie chart
            new Chart(countries).Pie(pieData, pieOptions);


         // get line chart canvas
         var paymantQ = document.getElementById('payments').getContext('2d');
         // draw line chart
         new Chart(paymantQ).Line(paymentsData);
         
      </script>
      <style>
         .center {text-align: center !important;}
      </style>
      <style>
         .content {
         padding-top: -30px !important;
         }
         /*   .header {
         position: fixed;
         left: 0;
         top: 170px;
         padding: 10px 10px 0px 10px;
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
         }*/
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
         background:#ecf0f5;
         border: 0.1mm solid #484746;
         }
         .items tbody td {
         border: 0.1mm solid #ecf0f5;
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
         .box.box-primary {
         border-top-color: #ccd0d5 !important;
         margin: 0 !important;
         padding: 20px 15px !important;
         }
      </style>
 

      @endsection