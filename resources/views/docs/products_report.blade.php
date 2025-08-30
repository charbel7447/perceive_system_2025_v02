<table class="items" style="margin: 0;padding: 0;">
    <thead> </thead>
</table>
<?php $mytime = Carbon\Carbon::now(); ?>
<header class="header" style="float: left;    position: relative !important;">
    <br>
<h4 style="color: #777;margin: -80px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.&nbsp;&nbsp;  &nbsp;
{{$mytime->format('d/m/Y')}}
</h4>
</header>
@if(settings()->get('footer_line_1') && settings()->get('footer_line_2') || settings()->get('footer_line_3') )
<htmlpagefooter name="footer">
    <div class="footer">
    <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_1')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_2')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_3')}}
   </p>
    @if(settings()->get('footer_line_3') === NULL )
        <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px; display: "> &nbsp;&nbsp;  &nbsp;&nbsp; </p>
    @endif
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" page="O" value="on" show-this-page="1" />
@endif
<section class="content" id="content">
   <div class="content-title">
        Products Custom Report
    </div>
    
    <table class="items">
      <thead>
          <tr>
              <th class="ac">Code</th>
              <th class="ac">Name</th>
              <th class="ac">Vendor</th>
              <th class="ac">Category</th>
              <th class="ac">SubCategory</th>
              <th class="ac">U.O.M</th>
              <th class="ac">Price</th>
              <th class="ac">Cost Price</th>
              <th class="ac">OnHold</th>
              <th class="ac">CurrentStock</th>

              <th class="ac">unitprice</th>
               <th class="ac">original_price</th>
               <th class="ac">cost_price</th>
               <th class="ac">special_price</th>
          
               <th class="ac">status</th>
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
               <th class="ac">added_by</th>
               <th class="ac">new</th>
               <th class="ac">featured</th>
               <th class="ac">best_selling</th>
               <th class="ac">deal_of_the_day</th>
               <th class="ac">deal_date</th>
               <th class="ac">purchase_price</th>
               <th class="ac">shipping_cost</th>
               <th class="ac">unit_price</th>
               <th class="ac">on_hold_qty</th>
               <th class="ac">volume_box</th>
               <th class="ac">ct_box</th>
               <th class="ac">weight_box</th>
               <th class="ac">warehouse_qty</th>
               <th class="ac">product_rating</th>
               <th class="ac">rating_value</th>
               <th class="ac">nb_boxes_1</th>
               <th class="ac">nb_boxes_1_price</th>
               <th class="ac">nb_boxes_2</th>
               <th class="ac">nb_boxes_2_price</th>
               <th class="ac">nb_boxes_3</th>
               <th class="ac">nb_boxes_3_price</th>
               <th class="ac">size</th>
               <th class="ac">location</th>
               <th class="ac">class_a_price</th>
               <th class="ac">class_b_price</th>
               <th class="ac">class_c_price</th>
               <th class="ac">item_box</th>
               <th class="ac">upc_number</th>
          </tr>
      </thead>
      <tbody>
        @foreach ($reports as $item)
        <tr>
            <td class="td-ac ac">{{$item->code}}</td>
            <td class="td-ac ac">{{$item->product_name}}</td> 
            <td class="td-ac ac">{{$item->vendor_name}}</td> 
            <td class="td-ac ac">{{$item->category_name}}</td> 
            <td class="td-ac ac">{{$item->subcategory_name}}</td> 
            <td class="td-ac ac">{{$item->unit}}</td> 
            <td class="td-ac ac">{{$item->price}}</td> 
            <td class="td-ac ac">{{$item->sale_price}}</td> 
            <td class="td-ac ac">{{$item->on_hold_qty}}</td>         
            <td class="td-ac ac">{{$item->current_stock}}</td> 

       

            <td class="td-ac ac">{{$item->unitprice}}</td>  
            <td class="td-ac ac">{{$item->original_price}}</td>  
            <td class="td-ac ac">{{$item->sale_price}}</td>  
            <td class="td-ac ac">{{$item->special_price}}</td>  
         
            <td class="td-ac ac">{{$item->status}}</td>  
            <td class="td-ac ac">{{$item->field1}}</td>  
            <td class="td-ac ac">{{$item->field2}}</td>  
            <td class="td-ac ac">{{$item->field3}}</td>  
            <td class="td-ac ac">{{$item->field4}}</td>  
            <td class="td-ac ac">{{$item->field5}}</td>  
            <td class="td-ac ac">{{$item->field6}}</td>  
            <td class="td-ac ac">{{$item->field7}}</td>  
            <td class="td-ac ac">{{$item->field8}}</td>  
            <td class="td-ac ac">{{$item->field9}}</td>  
            <td class="td-ac ac">{{$item->field10}}</td>  
            <td class="td-ac ac">{{$item->added_by}}</td>  
            <td class="td-ac ac">{{$item->code}}</td>  
            <td class="td-ac ac">{{$item->new}}</td>  
            <td class="td-ac ac">{{$item->featured}}</td>  
            <td class="td-ac ac">{{$item->best_selling}}</td>  
            <td class="td-ac ac">{{$item->deal_of_the_day}}</td>  
            <td class="td-ac ac">{{$item->deal_date}}</td>  
            <td class="td-ac ac">{{$item->purchase_price}}</td>  
         
            <td class="td-ac ac">{{$item->shipping_cost}}</td>  
            <td class="td-ac ac">{{$item->unit_price}}</td>  
            <td class="td-ac ac">{{$item->on_hold_qty}}</td>  
            <td class="td-ac ac">{{$item->volume_box}}</td>  
            <td class="td-ac ac">{{$item->ct_box}}</td>  
            <td class="td-ac ac">{{$item->weight_box}}</td>  
            <td class="td-ac ac">{{$item->warehouse_qty}}</td>  
            <td class="td-ac ac">{{$item->product_rating}}</td>  
            <td class="td-ac ac">{{$item->rating_value}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_1}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_1_price}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_2}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_2_price}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_3}}</td>  
            <td class="td-ac ac">{{$item->nb_boxes_3_price}}</td>  
            <td class="td-ac ac">{{$item->size}}</td>  
            <td class="td-ac ac">{{$item->location}}</td>  
            <td class="td-ac ac">{{$item->class_a_price}}</td>  
            <td class="td-ac ac">{{$item->class_b_price}}</td>  
            <td class="td-ac ac">{{$item->class_c_price}}</td>  
            <td class="td-ac ac">{{$item->item_box}}</td>  
            <td class="td-ac ac">{{$item->upc_number}}</td>  

        </tr>
        @endforeach
          </tbody>
          <tfoot>
            <tr class="ar">
                <td colspan="1">.</td>
                <td colspan="8"  class="ar">Total Qty
                  <?php  $sum = 0; ?>
                    @foreach ($reports as $variants)
                    <?php $quantity = $variants['current_stock'];
                        $act_points = $quantity;
                        $sum += $act_points; ?>
                    @endforeach
                </td>
                <td class="ac" style="text-align:center !important;">
                    {{$sum}}
                </td>
            </tr>
        </tfoot>
    </table>

<br><br><br>
</section>
<style>
                    
    body {
        font-family: sans-serif;
        font-size: 9pt;
        color: #484746;

    }
    pre {
        font-family: sans-serif;
    }

    table {
        border-spacing: 0;
        width: 100%;
        border-collapse: collapse;
    }
    th,
    td{
        font-weight: normal;
        vertical-align: top;
        text-align: left;
    }
   
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {

    }
    .content-title {
        margin-bottom: 20px;
        padding: 5px;
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        border: 0.1mm solid #dedede;
    }

    .document-blue {
        color: #3aa3e3;
    }

    .document-orange {
        color: #FF9800;
    }

    .document-red {
        color: #E75650;
    }

    .document-blue_light {
        color: #48606f;
    }
    .document-green {
        color: #66bb6a;
    }
    .summary-address {
        width: 33.333%;
    }
    .summary-addressx {
        width: 33.333%;
    }
    .summary-empty {
        width: 33.333%;
    }
    .summary-info {
        width: 33.333%;
    }
    .info td {
        text-align: right;
    }
    .info td:nth-child(2n) {
        padding-left: 15px;
    }
    .items {
        margin-top: 20px;
        border: 0.1mm solid #dedede;
    }
    .items thead th {
        padding: 6px 3px;
        background: #dedede;
        border: 0.1mm solid #dedede;
    }
    .items tbody td {
        border: 0.1mm solid #dedede;
        padding: 3px;
    }

    .items tfoot td {
         background: #f1f1f1;
        border: 0.1mm solid #dedede;
        text-align: right;
        padding: 4px 3px;
    }
    .item-empty {

    }
    .ar {
        text-align: right;
    }
    .ac {
        text-align: center;
    }
    .terms {
        margin-top: 20px;
    }
    .terms-description {
        width: 70%;
    }
    .footer {
        text-align: center;
    }
</style>

    <style>
      
        
    .header {
   position: fixed;
   left: 0;
   margin-top: 50px;
   color: #777;
   width: 100%;
   text-align: center;
   }
   .footer {
   position: fixed;
   left: 0;
   bottom: 0px;
   color: #777;
   width: 100%;
   text-align: center;
   }
</style>