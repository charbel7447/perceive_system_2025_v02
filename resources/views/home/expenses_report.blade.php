<table class="items">
   <thead>
       <tr>
           <th class="ac" >Number</th>
           <th class="ac" >Vendor</th>
           <th class="ac" >Amount Paid</th>
           <th class="ac">Amount Paid LBP / Rate</th>
           <th class="ac" >Payment Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\ExpensesReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\Expense::where('id','=',$item->expenses_id)->value('number'); 
            $vendor = App\Client::where('id','=',$item->vendor_id)->value('company'); 
            $date = App\Expense::where('number','=',$number)->value('payment_date'); 
            $invoice_number = App\Invoice\Invoice::where('id','=',$item->invoice_id)->value('number'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$vendor}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_paid,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_paid_lbp,2)}} / {{number_format($item->exchange_rate,2)}}</td> 
            <td class="td-ac ac">{{$item->payment_date}}</td>  
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>