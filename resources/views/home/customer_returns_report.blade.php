<table class="items">
   <thead>
       <tr>
              <th class="ac" width="10.5%">Doc-Number</th>
              <th class="ac" width="15%">Client</th>
              <th class="ac" width="20%">Product</th>
              <th class="ar" width="5%">Qty Returned</th>
              <th class="ac" width="10.5%">Invoice Reference</th>
              <th class="ac" width="10%">Returned Date</th>
              <th class="ac" width="17%">Returned By</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\CustomerReturnReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\CustomerReturn\CustomerReturn::where('id','=',$item->customer_return_id)->value('number'); 
            $received_by = App\CustomerReturn\CustomerReturn::where('id','=',$item->customer_return_id)->value('created_by'); 
            $Po_number = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('number'); 
            $vendor = App\Client::where('id','=',$item->client_id)->value('company'); 
            $date = App\CustomerReturn\CustomerReturn::where('number','=',$number)->value('date'); 
            $purchase_items = App\Invoice\Item::where('invoice_id','=',$item->invoice_item_id)->get(); 
        ?>
        @foreach ($purchase_items as $purchase_item)
        <?php 
            $product_name = App\Product\Product::where('id','=',$purchase_item->item_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$purchase_item->item_id)->value('id'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$vendor}}</td> 
            <td class="td-ac ac">{{$product_name}} <small>{{$product_code}}</small></td> 
            <td class="td-ac ac">{{number_format($item->qty_returned,2)}}</td> 
            <td class="td-ac ac">{{$Po_number}}</td> 
            <td class="td-ac ac">{{$date}}</td>  
            <td class="td-ac ac">{{$received_by}}</td>  
         </tr>
         @endforeach
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="6"></td>
     </tr>
 </tfoot>
</table>