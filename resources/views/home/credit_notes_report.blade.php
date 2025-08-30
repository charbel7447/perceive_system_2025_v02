<table class="items">
   <thead>
       <tr>
           <th class="ac" >Number</th>
           <th class="ac" >Client</th>
           <th class="ac" >Amount Received</th>
           <th class="ac">Applied Invoice</th>
           <th class="ac" >Payment Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\CreditNoteReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\CreditNote\CreditNote::where('id','=',$item->credit_note_id)->value('number'); 
            $client = App\Client::where('id','=',$item->client_id)->value('company'); 
            $date = App\CreditNote\CreditNote::where('number','=',$number)->value('payment_date'); 
            $invoice_number = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('number'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$client}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_received,2)}}</td> 
            <td class="td-ac ac">{{$invoice_number}}</td> 
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