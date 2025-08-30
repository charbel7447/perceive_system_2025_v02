<table class="items">
   <thead>
       <tr>
           <th class="ac">Product</th>
           <th class="ac">Code</th>
           <th class="ac">Box_Price</th>
           <th class="ac">Unit_Price</th>
           <th class="ac">Class A</th>
           <th class="ac">Class B</th>
           <th class="ac">Class C</th>
           <th class="ac">Nb Boxes A</th>
           <th class="ac">Boxes Price</th>
           <th class="ac">Nb Boxes B</th>
           <th class="ac">Boxes Price</th>
           <th class="ac">Nb Boxes C</th>
           <th class="ac">Boxes Price</th>
       </tr>
   </thead>
   <tbody>
   <?php $reports = \App\PriceChangeReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
         <tr>
            <td class="ac">
                {{$item->product_name}}
            </td>
            <td class="ac">
               {{$item->product->code}}
            </td>
            <td class="ac">
               {{$item->unit_price}}
            </td>
            <td class="ac">
               {{$item->unitprice}}
            </td>
            <td class="ac">
               {{$item->class_a_price}}
            </td>
            <td class="ac">
               {{$item->class_b_price}}
            </td>
            <td class="ac">
               {{$item->class_c_price}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_1}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_1_price}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_2}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_2_price}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_3}}
            </td>
            <td class="ac">
               {{$item->nb_boxes_3_price}}
            </td>
         </tr>
      @endforeach
   </tbody>
</table>
