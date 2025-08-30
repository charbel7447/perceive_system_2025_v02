<table class="items">
   <thead>
       <tr>
        <th class="ac" >Number</th>
           <th class="ac" >Client</th>
           <th class="ac" >Product</th>
           <th class="ac">Qty</th>
           <th class="ac" >Price</th>
           <th class="ac">UOM</th>
           <th class="ac">Total</th>
           <th class="ac">Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\QuotationReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\Quotation\Quotation::where('id','=',$item->quotation_id)->value('number'); 
            $client_id = App\Quotation\Quotation::where('id','=',$item->quotation_id)->value('client_id'); 
            $client = App\Client::where('id','=',$client_id)->value('company'); 

            $product_name = App\Product\Product::where('id','=',$item->product_id)->value('description'); 
            $product_code = App\Product\Product::where('id','=',$item->product_id)->value('id'); 
            $date = App\Quotation\Quotation::where('id','=',$item->quotation_id)->value('date'); 
            $uom_code = App\Uom::where('id','=',$item->uom_id)->value('unit'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$client}}</td> 
            <td class="td-ac ac">{{$product_name}} <small>{{$product_code}}</small></td> 
            <td class="td-ac ac">{{number_format($item->qty,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->unit_price,2)}}</td> 
            <td class="td-ac ac">{{$item->uom}}</td> 
            <td class="td-ac ac">{{number_format($item->qty * $item->unit_price,2) }}</td> 
            <td class="td-ac ac">{{$date}}</td>  
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>