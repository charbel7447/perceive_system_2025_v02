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



<section class="content" id="content">
    <div class="content-title">
        Invoices Report
        <?php   
            $reportsfrom_date = \App\InvoiceReport\InvoiceReport::where('id','=',$id)->value('from_date');
            
            $reportsto_date = \App\InvoiceReport\InvoiceReport::where('id','=',$id)->value('to_date');
            
             $item_id = \App\InvoiceReport\InvoiceReport::where('id','=',$id)->value('product_id');
        ?>
        
            @if($reportsfrom_date != "1990-01-01 00:00:00")
             From: {{date('d-m-Y', strtotime($reportsfrom_date))}} -
            @endif
        
        
            @if($reportsto_date != "2030-01-01 00:00:00")
                To: {{date('d-m-Y', strtotime($reportsto_date))}}
            @endif
            
            @if($item_id > 0)
            <br>
            <?php $item_description = \App\Product\Product::where('id','=',$item_id)->value('description'); ?> 
                
                <span style="font-size:11px;" >
                    For {{$item_id}} - {{$item_description}}
                </span>
            @endif
    
    </div>
    

<table class="items">
   <thead>
       <tr>
        <th class="ac" >Inv#</th>
        <th class="ac">Date</th>
           <th class="ac" >Client</th>
    
           <th class="ac">Total</th>
           <th class="ac">Status</th>
          
       </tr>
   </thead>
   <tbody>
      <?php 
        $reports = \App\InvoiceReport\Item::where('report_id','=',$id)->get();
        $total_sum = 0;
        $total_qty = 0;
      ?>
      @foreach ($reports as $item)
        <?php
            $number = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('number'); 
            $client_id = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('client_id'); 
            $client = App\Client::where('id','=',$client_id)->value('company'); 

            $product_name = App\Product\Product::where('id','=',$item->product_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$item->product_id)->value('id'); 
            $date = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('date'); 
            
            $qty = App\Invoice\Item::where('invoice_id','=',$item->invoice_id)->sum('quantity');
            
            $total_sum += ($item->total); 
            $total_qty += $qty;
            
            $status = '';
            if($item->status_id == 1){
                $status = 'DRAFT';
            }
            if($item->status_id == 2){
                $status = 'SENT';
            }
            if($item->status_id == 6){
                $status = 'CONFIRMED';
            }
            if($item->status_id == 3){
                $status = 'PARTIALLY_PAID';
            }
            if($item->status_id == 4){
                $status = 'PAID';
            }
            if($item->status_id == 5){
                $status = 'VOID';
            }
            if($item->status_id == 7){
                $status = 'ADJUSTED';
            }
            if($item->status_id == 9){
                $status = 'REOPEN';
            }
            if($item->status_id == 10){
                $status = 'RETURNED';
            }
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
             <td class="td-ac ac">{{$date}}</td>  
            <td class="td-ac ac">{{$client}}</td> 
          
            <td class="td-ac ac">{{number_format($item->total,3) }}</td> 
            <td class="td-ac ac">{{$status}}</td> 
           
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="3">Total Amount</td>
         <td>{{number_format($total_sum,3)}}</td>
         <td colspan="1"></td>
     </tr>
      <tr class="ar">
         <td colspan="3">Total Qty.</td>
         <td>{{number_format($total_qty,3)}}</td>
         <td colspan="1"></td>
     </tr>
 </tfoot>
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
        margin-top: 5px;
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
   margin-top: 0px;
   color: #777;
   width: 100%;
   text-align: center;
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