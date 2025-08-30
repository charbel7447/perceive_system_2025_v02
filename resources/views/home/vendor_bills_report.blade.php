<table class="items">
   <thead>
       <tr>
            <th class="ac" >Number</th>
           <th class="ac" >Vendor</th>
           <th class="ac" >Purchase Order</th>
           <th class="ac" >Bill Items</th>
           <th class="ac" >Unit Price</th>
           <th class="ac">Qty</th>
           <th class="ac">UOM</th>
           <th class="ac">Total</th>
           <th class="ac" >Bill Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\VendorBillReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\Bill\Bill::where('id','=',$item->bill_id)->value('number'); 
            $vendor = App\Vendor::where('id','=',$item->vendor_id)->value('company'); 
            $purchase_number = App\PurchaseOrder\PurchaseOrder::where('id','=',$item->purchase_order_id)->value('number'); 
            $bill_items = App\Bill\Item::where('bill_id','=',$item->bill_id)->get(); 
            $product_name = App\Product\Product::where('id','=',$item->product_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$item->product_id)->value('id'); 
            $date = App\Bill\Bill::where('id','=',$item->bill_id)->value('date'); 
            
        ?>
         @foreach ($bill_items as $bill_item)
             <?php
                $product_name = App\Product\Product::where('id','=',$bill_item->product_id)->value('description'); 
                $product_code = App\Product\Product::where('id','=',$bill_item->product_id)->value('id'); 
                $uom_code =  App\Product\Product::where('id','=',$bill_item->product_id)->value('uom'); 
            ?>
            <tr>
                <td class="td-ac ac">{{$number}}</td>
                <td class="td-ac ac">{{$vendor}}</td> 
                <td class="td-ac ac">{{$purchase_number}}</td> 
                <td class="td-ac ac">{{$product_name}} <small>{{$product_code}}</small></td> 
                <td class="td-ac ac">{{number_format($bill_item->qty,2)}}</td> 
                <td class="td-ac ac">{{number_format($bill_item->unit_price,2)}}</td> 
                <td class="td-ac ac">{{$uom_code}}</td> 
                <td class="td-ac ac">{{number_format($bill_item->qty * $bill_item->unit_price,2) }}</td> 
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