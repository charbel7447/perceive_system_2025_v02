<table class="items">
   <thead>
       <tr>
           <th class="ac">Payment Mode</th>
           <th class="ac">Client</th>
           <th class="ac">Amount</th>
           <th class="ac">Date</th>
           <th class="ac">Document Type</th>
           <th class="ac">Document Number</th>
       </tr>
   </thead>
   <tbody>
   <?php $reports = \App\PaymentOptionReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
         <tr>
            <td class="ac">
            <?php $payment_options_name = App\PaymentOptions::where('id','=',$item->payment_options_id)->take(1)->value('name'); ?>
                {{$payment_options_name}}
            </td>
            <td class="ac">
                {{$item->client_name}} <small>({{$item->client_id}})</small>
            </td>
            <td class="ac">
                {{$item->payment}}
            </td>
            <td class="ac">
                {{$item->date}}
            </td>
            <td class="ac">
                {{$item->document}}
            </td>
            <td class="ac">
                {{$item->document_number}}
            </td>
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="6"></td>
     </tr>
 </tfoot>
</table>