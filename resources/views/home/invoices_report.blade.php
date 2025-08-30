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