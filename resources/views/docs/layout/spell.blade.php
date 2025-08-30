    <table class="items" style="margin: 0% 0;">
            <thead>
                <tr>
                   <?php 
                   $spell = Terbilang::make($model->sub_total);
                   $spellt = Terbilang::make($tax * 1507);
                    ?>
                    <th width="15%">Note:</th>
                    <th width="85%" style="text-transform: capitalize;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $spell }} Dollars 

                      {{-- <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;And
                      <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;{{ $spellt }} <span style="text-transform: uppercas;">Lebanese Pound</span> Only --}}
                    </th>
                </tr>
            </thead>
    </table>
    <table class="terms" style="margin-top: 5%;">
   <tbody>
      <tr>
         <td class="terms-description">
            <div>
               <strong>Terms and Conditions</strong>
               <p>&nbsp;</p>
               <pre>{{$model->terms}}</pre>
            </div>
         </td>
         <td></td>
      </tr>
   </tbody>
</table>
<br><br><br>