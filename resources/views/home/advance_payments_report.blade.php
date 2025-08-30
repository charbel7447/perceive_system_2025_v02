<table class="items">
   <thead>
       <tr>
           <th class="ac">Number</th>
           <th class="ac">Client</th>
           <th class="ac">Amount Received</th>
           <th class="ac">Amount Received USD</th>
           <th class="ac">Amount Received LBP</th>
           <th class="ac">Exchange Rate</th>
           <th class="ac">Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\AdvancePaymentReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $client = App\Client::where('id','=',$item->client_id)->value('company'); 
            $date = App\AdvancePayment\AdvancePayment::where('number','=',$item->number)->value('payment_date'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$item->number}}</td>
            <td class="td-ac ac">{{$client}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_received,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_received_usd,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_received_lbp,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->exchange_rate,2)}}</td> 
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