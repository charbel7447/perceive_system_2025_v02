<table class="items">
   <thead>
       <tr>
              <th class="ac" width="10.5%">PO-Number</th>
              <th class="ac" width="15%">Vendor</th>
              <th class="ac" width="20%">Product</th>
              <th class="ar" width="5%">Qty Received</th>
              <th class="ac" width="5%">UOM</th>
              <th class="ac" width="10.5%">PO Reference</th>
              <th class="ac" width="10%">Receive Date</th>
              <th class="ac" width="17%">Received By</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\ReceiveOrderReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\ReceiveOrder\ReceiveOrder::where('id','=',$item->receive_order_id)->value('number'); 
            $received_by = App\ReceiveOrder\ReceiveOrder::where('id','=',$item->receive_order_id)->value('created_by'); 
            $Po_number = App\PurchaseOrder\PurchaseOrder::where('id','=',$item->purchase_order_id)->value('number'); 
            $vendor = App\vendor::where('id','=',$item->vendor_id)->value('company'); 
            $date = App\ReceiveOrder\ReceiveOrder::where('number','=',$number)->value('date'); 
            $uom = App\Uom::where('id','=',$item->uom_id)->value('unit'); 
            $purchase_items = App\PurchaseOrder\Item::where('id','=',$item->purchase_order_item_id)->get(); 
        ?>
        @foreach ($purchase_items as $purchase_item)
        <?php 
            $product_name = App\Product\Product::where('id','=',$purchase_item->product_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$purchase_item->product_id)->value('id'); 
            $uom_code = App\Uom::where('id','=',$purchase_item->uom_id)->value('unit'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$vendor}}</td> 
            <td class="td-ac ac">{{$product_name}} <small>{{$product_code}}</small></td> 
            <td class="td-ac ac">{{number_format($purchase_item->qty,2)}}</td> 
            <td class="td-ac ac">{{$uom_code}}</td> 
            <td class="td-ac ac">{{$Po_number}}</td> 
            <td class="td-ac ac">{{$date}}</td>  
            <td class="td-ac ac">{{$received_by}}</td>  
         </tr>
         @endforeach
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>