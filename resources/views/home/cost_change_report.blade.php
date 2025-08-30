<table class="items">
   <thead>
       <tr>
           <th class="ac">Product</th>
           <th class="ac">Code</th>
           <th class="ac">Cost_Price</th>
       </tr>
   </thead>
   <tbody>
   <?php $reports = \App\PriceChangeReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
         <tr>
            <td class="ac">
                {{$item->product_name}}
            </td>
            <td>{{$item->product->code}}</td>
            <td class="ac">
               {{$item->sale_price}}
            </td>
         </tr>
      @endforeach
   </tbody>
</table>
