<?php $all_clients = \App\Client::get(); ?>
<table class="items">
   <thead>
       <tr>
            <th class="ac">id</th>
            <th class="ac">ref_number</th>
            <th class="ac">name</th>
            <th class="ac">email</th>
            <th class="ac">username</th>
            <th class="ac">seller_id</th>
            <th class="ac">email_verified</th>
            <th class="ac">email_verify_token</th>
            <th class="ac">phone</th>
            <th class="ac">address</th>
            <th class="ac">state</th>
            <th class="ac">city</th>
            <th class="ac">zipcode</th>
            <th class="ac">country</th>
            <th class="ac">password</th>
            <th class="ac">remember_token</th>
            <th class="ac">image</th>
            <th class="ac">created_at</th>
            <th class="ac">updated_at</th>
            <th class="ac">field1</th>
            <th class="ac">field2</th>
            <th class="ac">field3</th>
            <th class="ac">field4</th>
            <th class="ac">field5</th>
            <th class="ac">field6</th>
            <th class="ac">field7</th>
            <th class="ac">field8</th>
            <th class="ac">field9</th>
            <th class="ac">field10</th>
            <th class="ac">facebook_id</th>
            <th class="ac">google_id</th>
            <th class="ac">allow_mobile</th>
            <th class="ac">loyalty_point</th>
            <th class="ac">company</th>
            <th class="ac">person</th>
            <th class="ac">currency_id</th>
            <th class="ac">vat_status</th>
            <th class="ac">total_revenue</th>
            <th class="ac">unused_credit</th>
            <th class="ac">work_phone</th>
            <th class="ac">billing_address</th>
            <th class="ac">shipping_address</th>
            <th class="ac">user_id</th>
            <th class="ac">created_by</th>
            <th class="ac">to_be_paid</th>
            <th class="ac">paid</th>
            <th class="ac">balance</th>
            <th class="ac">last_payment_date</th>
            <th class="ac">is_customer</th>
            <th class="ac">balance_status</th>
            <th class="ac">price_class</th>
            <th class="ac">tax_id</th>
            <th class="ac">paymentcondition_id</th>
            <th class="ac">deliverycondition_id</th>
            <th class="ac">paymentcondition_name</th>
            <th class="ac">deliverycondition_name</th>
            <th class="ac">postal_code</th>
            <th class="ac">status</th>
            <th class="ac">client_dropdown_1_id</th>
            <th class="ac">client_dropdown_2_id</th>
       </tr>
   </thead>
   <tbody>
      @foreach ($all_clients as $client)
         <tr>
            <td class="ac">{{$client->id}}</td>
            <td class="ac">{{$client->ref_number}}</td>
            <td class="ac">{{$client->name}}</td>
            <td class="ac">{{$client->email}}</td>
            <td class="ac">{{$client->username}}</td>
            <td class="ac">{{$client->seller_id}}</td>
            <td class="ac">{{$client->email_verified}}</td>
            <td class="ac">{{$client->email_verify_token}}</td>
            <td class="ac">{{$client->phone}}</td>
            <td class="ac">{{$client->address}}</td>
            <td class="ac">{{$client->state}}</td>
            <td class="ac">{{$client->city}}</td>
            <td class="ac">{{$client->zipcode}}</td>
            <td class="ac">{{$client->country}}</td>
            <td class="ac">{{$client->password}}</td>
            <td class="ac">{{$client->remember_token}}</td>
            <td class="ac">{{$client->image}}</td>
            <td class="ac">{{$client->created_at}}</td>
            <td class="ac">{{$client->updated_at}}</td>
            <td class="ac">{{$client->field1}}</td>
            <td class="ac">{{$client->field2}}</td>
            <td class="ac">{{$client->field3}}</td>
            <td class="ac">{{$client->field4}}</td>
            <td class="ac">{{$client->field5}}</td>
            <td class="ac">{{$client->field6}}</td>
            <td class="ac">{{$client->field7}}</td>
            <td class="ac">{{$client->field8}}</td>
            <td class="ac">{{$client->field9}}</td>
            <td class="ac">{{$client->field10}}</td>
            <td class="ac">{{$client->facebook_id}}</td>
            <td class="ac">{{$client->google_id}}</td>
            <td class="ac">{{$client->allow_mobile}}</td>
            <td class="ac">{{$client->loyalty_point}}</td>
            <td class="ac">{{$client->company}}</td>
            <td class="ac">{{$client->person}}</td>
            <td class="ac">{{$client->currency_id}}</td>
            <td class="ac">{{$client->vat_status}}</td>
            <td class="ac">{{$client->total_revenue}}</td>
            <td class="ac">{{$client->unused_credit}}</td>
            <td class="ac">{{$client->work_phone}}</td>
            <td class="ac">{{$client->billing_address}}</td>
            <td class="ac">{{$client->shipping_address}}</td>
            <td class="ac">{{$client->user_id}}</td>
            <td class="ac">{{$client->created_by}}</td>
            <td class="ac">{{$client->to_be_paid}}</td>
            <td class="ac">{{$client->paid}}</td>
            <td class="ac">{{$client->balance}}</td>
            <td class="ac">{{$client->last_payment_date}}</td>
            <td class="ac">{{$client->is_customer}}</td>
            <td class="ac">{{$client->balance_status}}</td>
            <td class="ac">{{$client->price_class}}</td>
            <td class="ac">{{$client->tax_id}}</td>
            <td class="ac">{{$client->paymentcondition_id}}</td>
            <td class="ac">{{$client->deliverycondition_id}}</td>
            <td class="ac">{{$client->paymentcondition_name}}</td>
            <td class="ac">{{$client->deliverycondition_name}}</td>
            <td class="ac">{{$client->postal_code}}</td>
            <td class="ac">{{$client->status}}</td>
            <td class="ac">{{$client->client_dropdown_1_id}}</td>
            <td class="ac">{{$client->client_dropdown_2_id}}</td>
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>