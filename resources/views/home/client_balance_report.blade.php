 <table class="items">
   <thead>
       <tr>
        <th class="ac" >Seller</th>
        <th class="ac" >Ref</th>
        <th class="ac" >Client</th>
        <th class="ac">Over 90 Days</th>
        <th class="ac" >60 Days</th>
        <th class="ac" >30 Days</th>
        <th class="ac" >Total Balance</th>
       </tr>
   </thead>
   <tbody>
    <?php $reports = \App\ClientBalanceReport\Item::where('client_balance_report_id','=',$id)->get(); ?>
      @foreach ($reports as $item)
         <tr>
            <td class="ac">
            {{$item->seller_name}} <small>({{$item->seller_id}})</small>
            </td>
            <td class="ac">
                {{$item->reference}}
            </td>
            <td class="ac">
                {{$item->company}} <small style="font-size:11px;">({{$item->name}})</small>
            </td>
            <td class="ac">
                {{$item->balance_90}}
            </td>
            <td class="ac">
                {{$item->balance_60}}
            </td>
            <td class="ac">
                {{$item->balance_30}}
            </td>
            <td class="ac">
                {{$item->balance}}
            </td>
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>