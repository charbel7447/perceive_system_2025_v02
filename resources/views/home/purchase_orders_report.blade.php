<table class="items">
   <thead>
       <tr>
            <th class="ac" >Number</th>
           <th class="ac" >Vendor</th>
           <th class="ac" >Order Items</th>
           <th class="ac" >Unit Price</th>
           <th class="ac">Qty</th>
           <th class="ac">UOM</th>
           <th class="ac">Total</th>
           <th class="ac" >Order Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\PurchaseOrderReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\PurchaseOrder\PurchaseOrder::where('id','=',$item->purchase_order_id)->value('number'); 
            $vendor = App\Vendor::where('id','=',$item->vendor_id)->value('company'); 
            $purchase_items = App\PurchaseOrder\Item::where('purchase_order_id','=',$item->purchase_order_id)->get(); 
            $product_name = App\Product\Product::where('id','=',$item->product_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$item->product_id)->value('id'); 
            $date = App\PurchaseOrder\PurchaseOrder::where('id','=',$item->purchase_order_id)->value('date'); 
            
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
                <td class="td-ac ac">{{number_format($purchase_item->unit_price,2)}}</td> 
                <td class="td-ac ac">{{$uom_code}}</td> 
                <td class="td-ac ac">{{number_format($purchase_item->qty * $purchase_item->unit_price,2) }}</td> 
                <td class="td-ac ac">{{$date}}</td>  
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