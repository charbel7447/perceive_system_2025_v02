<?php 
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
?>
@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    
    <table class="summary">
        <tbody>
            <tr>
                <td style="vertical-align:middle;padding:30px 0 10px 0;width:100px">{!! File::get($options['header-html']) !!}</td>
                <td style="vertical-align:left;text-align:left;font-size:14px;">
                     
                    <span style="vertical-align:left;text-align:left;font-size:16px;font-weight:bold;">{{settings()->get('header_line_1')}}</span><br>
                    {{settings()->get('header_line_2')}}<br>
                    {{settings()->get('header_line_3')}}<br>
                </td>
                <td style="vertical-align:top;text-align:right;padding-left:20px">
                    <small >
                        <strong>Customer: #</strong>
                        {{$model->client->id}}
                    </small>
                </td>
            </tr>
        </tbody>
    </table>
        
</htmlpageheader>
<sethtmlpageheader name="header" show-this-page="1" />
@endif

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

 <?php 
 
 $display_vat = DB::table('settings')
                ->latest()->take(1)
                ->where('id','=',22)
                ->value('value');
        $first_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',1)
                ->value('code');

                $second_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',2)
                ->value('code');
                ?>

<section class="content" id="content">
    <div class="content-title" style="border:0;">
        INVOICE
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address" style="margin:0 5px;padding:5px 5px;border:2px solid ">
                    <strong>Bill To:</strong>
                    @if($model->client->company)
                        <pre>{{$model->client->company}}<br>{{$model->client->billing_address}}</pre>
                        <p>
                            {{$model->client->city}},{{$model->client->state}},{{$model->client->zipcode}}
                        </p>
                        <p>
                            {{$model->client->phone}}
                        </p>
                    @else
                        <pre>{{$model->client->person}}<br>{{$model->client->billing_address}}</pre>
                    @endif
                </td>
                <td class="summary-address" style="margin:0 5px;padding:5px 5px;border:2px solid ">
                    <strong>Ship To:</strong>
                    @if($model->client->company)
                    <pre>{{$model->client->company}}<br>{{$model->client->shipping_addresss}} &nbsp;</pre>
                        <p>
                            {{$model->client->city}},{{$model->client->state}},{{$model->client->zipcode}}
                        </p>
                        <p>
                            {{$model->client->phone}}
                        </p>
                    @else
                        <pre>{{$model->client->person}}<br>{{$model->client->shipping_addresss}} &nbsp;</pre>
                    @endif
                  
                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info" style="margin:0 5px;padding:5px 5px;border:2px solid ">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Number:</td>
                                <td>{{$model->number}}</td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <?php $date = \DateTime::createFromFormat('Y-m-d', $model->date)->format('m-d-Y') ?>
                                <td>{{$date}}</td>
                            </tr>
                            <!--@if($model->due_date)-->
                            <!--<tr>-->
                            <!--    <td>Due Date:</td>-->
                            <!--    <td>{{$model->due_date}}</td>-->
                            <!--</tr>-->
                            <!--@endif-->
                            @if($model->reference)
                            <tr>
                                <td>Reference:</td>
                                <td>{{$model->reference}}</td>
                            </tr>
                            @endif
                            @if($model->invoiceable)
                                <tr>
                                    <td>{{$model->parent_type}}:</td>
                                    <td>{{$model->invoiceable->number}}</td>
                                </tr>
                            @endif
                            <tr >
                                <!--<td>Total {{$model->currency->code}}:</td>-->
                                 <td>Total:</td>
                                <td style="font-weight:bold;">${{moneyFormat($model->total, $model->currency, false)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
        <table class="items">
            <thead>
                <tr>
                    <th class="ac">#&nbsp;</th>
                    <th class="ac">Item Code</th>
                    <th class="ac">Quantity</th>
                    <th>Description</th>
                    
                    <th class="ac">Pack</th>
                   
                    <th class="ac">Price</th>
                    <th class="ac">Promo</th>
                     <th class="ac">Net Price</th>
                      <!-- <th class="ac">Unit Price</th> -->
                    <th class="ac">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                @foreach($model->items as $item)
                 <?php $count += 1; ?>
                    <tr style="vertical-align:middle">
                        <td class="ac">{{$count}}</td>
                        <?php $comany_type = DB::table('settings')->where('key','=','company_type')->value('value'); ?>
                        @if($comany_type == 0)
                         <td class="ac">{{$item->product->code}}
                        
                            <!--  <?php  $barcode = \DNS1D::getBarcodePNG($item->product->code, 'C39',1,30);
                            ?> -->
                            <br>
                               <img style="margin:0 0 0 5px" src="data:image/png;base64,{{$item->product->barcode}}" alt="star" width="80" height="30"> 
                         </td>
                        @else
                         <td>{{$item->product->generated_code}}</td>
                        @endif
                        <td class="ac" style="font-weight:bold;">{{$item->quantity}}</td>
                        <td class="acx">
                            <pre>{{$item->product->description}}</pre>
                        </td>
                        <td class="ac">
                            <pre>{{$item->product->item_box}}</pre>
                        </td>
                        
                        <td class="ac" style="font-weight:bold;">
                        
                            @if($item->price == 0)
                                Free
                            @else
                                ${{moneyFormat($item->price, $model->currency, false)}}
                            @endif
                        </td>
                        
                        <td class="ac" style="font-weight:bold;">
                            @if($item->discount_usd > 0)
                                (${{$item->discount_usd}})
                            @elseif($item->discount_per > 0)
                                (%{{$item->discount_per}})
                           
                            @elseif($item->price == 0)
                                (Free)
                            @else
                                -
                            @endif
                        </td>
                        <td class="ac" style="font-weight:bold;">
                        <?php $last_net_price = 0; ?>
                            @if($model->discount_usd > 0)
                            <?php $last_net_price = $item->price - $item->discount_usd; ?>
                            ${{number_format($item->price - $item->discount_usd,3)}}
                             @elseif($model->discount_per > 0)
                             <?php $last_net_price = ($item->price) - (($item->price * $item->discount_per) / 100); ?>

                              ${{number_format(($item->price) - (($item->price * $item->discount_per) / 100),3)}}
                              @else
                              <?php $last_net_price = ($item->price); ?>
                               ${{number_format($item->price,3)}}
                            @endif
                            
                        </td>
                        <td class="ac" style="font-weight:bold;">
                            @if($model->discount_usd > 0)
                                ${{moneyFormat($item->quantity * $item->price - ($item->quantity * $item->discount_usd), $model->currency, false)}}
                            @elseif($model->discount_per > 0)
                                ${{moneyFormat(($item->quantity * $item->price) - (($item->quantity * $item->price) * ($item->discount_per/100)), $model->currency, false)}}
                            @else
                                ${{moneyFormat($item->quantity * $item->price, $model->currency, false)}}
                            @endif
                        </td>
                    </tr>
                    @if($model->client->vat_status == 1)  
                    @foreach($item['taxes'] as $tax)
                        {{-- <tr class="item-tax">
                            <td class="ar" colspan="5">{{$tax->name}} @ {{$tax->rate}}%
                            <span style="float: right;text-align:right">{{$second_currency}} {{moneyFormat($item->quantity * $item->price * ($tax->rate / 100 ) * 1507, $model->currency, false)}}</span>
                        </td></tr> --}}
                    @endforeach
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                <td colspan="3" class="item-empty" style="text-align:left;font-weight:bold;border:none">Note:</td>
                    <td colspan="2" style="border:none">Total quantity.</td>
                    <td colspan="1" class="center" style="font-weight:bold;border:none">
               
                      <?php
                        $totalquantity = App\Invoice\Item::where('invoice_id','=',$model->id)->sum('quantity');
                      ?>
                      {{  $totalquantity}}
                    </td>
                    
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3" class="item-empty" style="border:none">{{$model->note}}</td>
                    <td colspan="2" style="border:none">{{$model->line4_text}}</td>
                    <td colspan="1" style="font-weight:bold;">
                        {{$model->line4_value}}
                    </td>
                    <td colspan="2">Sub Total</td>
                    <td colspan="1">
                        {{moneyFormat($model->sub_total, $model->currency, false)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="item-empty" style="border:none"></td>
                    <td colspan="2" style="border:none">{{$model->line3_text}}.</td>
                    <td colspan="1" style="font-weight:bold;">
                        {{$model->line3_value}}
                    </td>
                    <td colspan="2">Discount <small>(Fixed Amount)</small></td>
                    <td colspan="1">
                         {{moneyFormat($model->discount, $model->currency, false)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="item-empty"></td>
                    <td colspan="3">Discount <small style="color:red;">{{$model->discount_percentage}} %</small></td>
                    <td colspan="1">
                         {{moneyFormat((($model->sub_total) * ($model->discount_percentage/100)), $model->currency, false)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="item-empty"></td>
                    <td colspan="3">Shipping</td>
                    <td colspan="1">
                         {{moneyFormat($model->shipping, $model->currency, false)}}
                    </td>
                </tr>
               
                @if($model->client->vat_status == 1)  
                @foreach(selectedTax2($model->items) as $key => $tax)
                <tr>
                    <td colspan="4" class="item-empty"></td>
                    @if($display_vat == 1)
                    <td colspan="2" class="item-empty">{{$second_currency}} {{moneyFormat($tax * $model->vatrate, $model->currency, false)}}</td> --}}
                    @else
                    <td colspan="2"></td>
                    @endif
                    <td colspan="2">{{$key}}</td>
                    <td>
                         {{moneyFormat($tax, $model->currency, true)}}
                    </td>
                </tr>
                @endforeach
                @endif
                @if($model->client->vat_status == 2)  
                @foreach(selectedTax2($model->items) as $key => $tax)
                <tr>
                    <td colspan="4" class="item-empty"></td>
                    <td colspan="2" class="item-empty">{{$first_currency}} {{moneyFormat($tax, $model->currency, false)}}</td>
                    <td colspan="2">{{$key}}</td>
                    <td>
                        {{$first_currency}} {{moneyFormat($tax, $model->currency, false)}}
                    </td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="5" class="item-empty"></td>
                    <td colspan="3">
                        <strong>Total:</strong>
                    </td>
                    <td>
                        <strong>{{moneyFormat($model->total, $model->currency)}}</strong>
                    </td>
                </tr>
            </tfoot>
    </table>
    <table class="items" style="margin: 0% 0;">
        <thead>
            <tr>
               
               <?php
               use Rmunate\Utilities\SpellNumber;

               SpellNumber::getLanguages();
               $spell = SpellNumber::value(round($model->total,2))->locale('en')
               ->currency('Dollars')
               ->fraction('cents')
               ->toMoney();

                ?>
                <th width="15%">Only:</th>
                <th width="85%" style="text-transform: capitalize;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $spell }} 
           <!-- @if($model->client->vat_status == 1)  
           @foreach(selectedTax2($model->items) as $key => $tax)
                
                <?php   
                     $spellt = Terbilang::make($tax*1507.5);
                ?>
                  <br>
                  &nbsp;&nbsp;&nbsp;&nbsp;And
                  <br>
                  &nbsp;&nbsp;&nbsp;&nbsp;{{ $spellt }} <span style="text-transform: uppercas;">Lebanese Pound</span>  
            @endforeach      
            @endif -->
                </th>

             
            </tr>
        </thead>
</table>
<table class="terms" style="margin-top: 0%;">
<tbody>
  <tr>
    <td class="terms-description">
        <div>
            <u><strong>Terms and Conditions</strong></u>
            <div class="terms-foot">
                @if($model->delivery_date)
                <p><strong style="margin:5px 0 !important;">Delivery Time:</strong>&nbsp;&nbsp;{{$model->delivery_date}}</p>
               
                @endif
                
                <div><p style="line-height: 1px;">&nbsp;</p></div>
                <p><strong style="margin:5px 0 !important;">Payment Condition:</strong>&nbsp;&nbsp;{!!$model->paymentcondition->name!!}</p>
                <div><p style="line-height: 1px;">&nbsp;</p></div>    
                <p><strong>Delivery Terms:</strong>&nbsp;&nbsp;{!!$model->deliverycondition->name!!}</p>
                <div><p style="line-height: 1px;">&nbsp;</p></div>   
                @if($model->terms) 
                <p><strong>Note:</strong>&nbsp;&nbsp;{!!$model->terms!!}</p>
                @endif
           </div>
         </div>
     </td>
     <td></td>
  </tr>
</tbody>
</table>

<div class="col-md-12" style="width: 100%;text-align: center;">
    <div class="col-md-6" style="width: 49%;float: left;margin: 0px;padding: 0px;">
       <h4>{{$model->client->company}}</h4>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0;">Signature</h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0;">Date</h4>
       </div>
       <br>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 10px 0px;border-bottom:1px solid;"></h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 10px 0px;border-bottom:1px solid;"></h4>
       </div>
    </div>
    <div class="col-md-6" style="width: 49%;float: left;margin: 0px;padding: 0px;">
       <h4><?php echo $companyname = DB::table('settings')->where('id','=',4)->value('value'); ?></h4>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;">Signature</h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;">Date</h4>
       </div>
       <br>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 10px 0;border-bottom:1px solid;"></h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 10px 0px;border-bottom:1px solid;"></h4>
       </div>
    </div>
 </div>
  
 
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
        vertical-align: middle;
        text-align: left;
    }
   
    .header-company_name {
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .content {
        padding-top:-60px;
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
        margin-top: 0px;
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
        padding: 2px 3px;
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
       color: #777;
       text-align: right;
       }
       .footer {
       position: fixed;
       left: 0;
       bottom: -30px;
       color: #777;
       width: 100%;
       text-align: center;
       }
</style>