<table class="items">
   <thead>
       <tr>
        <th class="ac" >Number</th>
           <th class="ac" >Vendor</th>
           <th class="ac" >Applied Bill</th>
           <th class="ac" >Bill Total Amount</th>
           <th class="ac" >Amount Paid USD</th>
           <th class="ac">Amount Paid LBP / Rate</th>
           {{-- <th class="ac">Amount Paid Vat / Rate</th> --}}
           <th class="ac" >Payment Date</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\VendorPaymentReport\Item::where('report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
        <?php
            $number = App\VendorPayment\VendorPayment::where('id','=',$item->vendor_payment_id)->value('number'); 
            $vendor = App\vendor::where('id','=',$item->vendor_id)->value('company'); 
            $date = App\VendorPayment\VendorPayment::where('number','=',$number)->value('payment_date'); 
            $bill_number = App\Bill\Bill::where('id','=',$item->bill_id)->value('number'); 
            $bill_total = App\Bill\Bill::where('id','=',$item->bill_id)->value('total'); 
        ?>
         <tr>
            <td class="td-ac ac">{{$number}}</td>
            <td class="td-ac ac">{{$vendor}}</td> 
            <td class="td-ac ac">{{$bill_number}}</td> 
            <td class="td-ac ac">{{$bill_total}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_applied,2)}}</td> 
            <td class="td-ac ac">{{number_format($item->amount_applied_lbp,2)}} / {{number_format($item->exchange_rate,2)}}</td> 
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