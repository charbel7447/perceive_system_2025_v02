@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    {!! File::get($options['header-html']) !!}
    <div class="header" style="text-align:right;width:100%;margin:30px 0 0 0;">
        <small>Page {PAGENO} of {nb}</small>
        <br>
        <small>Original Doc.</small><br>
        <small><?php $company_name = DB::table('settings')
            ->latest()->take(1)
            ->where('id','=',4)
            ->value('value'); ?>
            {{$company_name}}
        </small>
        {!! File::get($options['header-html']) !!}
        
    </div>
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

<section class="content" id="content">
    <div class="content-title">
        QUOTATION
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address" style="margin:0 5px;padding:0 5px;">
                    <strong>To:</strong>
                    @if($model->client->company)
                        <pre>{{$model->client->company}}<br>{{$model->client->billing_address}}</pre>
                        <p>
                            <strong>Attention:</strong>
                            {{$model->client->person}}
                        </p>
                    @else
                        <pre>{{$model->client->person}}<br>{{$model->client->billing_address}}</pre>
                    @endif
                </td>
                <td class="summary-addressx">
                    <strong>&nbsp;</strong>
                        
                    @if($model->client->work_phone)
                    <p>
                        <strong>VAT Number:</strong>
                        {{$model->client->work_phone}}
                    </p>
                @endif  
                @if($model->client->mobile_number)  
                    <p>
                        <strong>Phone Number:</strong>
                        {{$model->client->mobile_number}}
                    </p>
                    @endif  
                @if($model->client->email)  
                    <p>
                        <strong>Email:</strong>
                        {{$model->client->email}}
                    </p>
                    @endif  
                  
                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Number:</td>
                                <td>{{$model->number}}</td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>{{$model->date}}</td>
                            </tr>
                            @if($model->reference)
                            <tr>
                                <td>Reference:</td>
                                <td>{{$model->reference}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Total {{$model->currency->code}}:</td>
                                <td>{{$model->currency->code}}&nbsp;{{moneyFormat($model->total, $model->currency, false)}}</td>
                            </tr>
                            {{-- @if($model->client->vat_status == 1)  
                            @foreach(selectedTax($model->items) as $key => $tax)
                            <tr>
                                <td>Total LBP VAT:</td>
                                <td>
                                   {{moneyFormat($tax * 1507, $model->currency, false)}}
                                </td>
                            </tr>
                            @endforeach
                            @endif --}}
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
        <table class="items">
            <thead>
                <tr>
                    <th width="15%">Item Code</th>
                    <th width="40%">Description</th>
                    <th class="ar" width="15%">Unit Price</th>
                    <th class="ar" width="10%">Qty / UOM</th>
                    <th class="ar" width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->items as $item)
                    <tr>
                        <?php $comany_type = DB::table('settings')->where('key','=','company_type')->value('value'); ?>
                        @if($comany_type == 0)
                         <td>{{$item->product->code}}</td>
                        @else
                         <td>{{$item->product->generated_code}}</td>
                        @endif
                        <td>
                            <pre>{{$item->product->description}}</pre>
                        </td>
                        <td class="ar">
                            {{moneyFormat($item->unit_price, $model->currency, false)}}
                        </td>
                        <td class="ar">{{$item->qty}} / 
                        {{$item->uom_unit}}
                        </td>
                        <td class="ar">
                            {{moneyFormat($item->qty * $item->unit_price, $model->currency, false)}}
                        </td>
                    </tr>
                    @if($model->client->vat_status == 1)  
                    @foreach($item['taxes'] as $tax)
                        {{-- <tr class="item-tax">
                            <td class="ar" colspan="5">{{$tax->name}} @ {{$tax->rate}}%
                            <span style="float: right;text-align:right">LBP {{moneyFormat($item->qty * $item->unit_price * ($tax->rate / 100 ) * 1507, $model->currency, false)}}</span>
                        </td></tr> --}}
                    @endforeach
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Qty.</td>
                    <td colspan="1" class="center">
               
                      <?php
                        $totalQty = App\Quotation\Item::where('quotation_id','=',$model->id)->sum('qty');
                      ?>
                      {{  $totalQty}}
                    </td>
                    <td colspan="1"></td>
                </tr>
                <tr>
                    <td colspan="2" class="item-empty"></td>
                    <td colspan="2">Sub Total</td>
                    <td>
                        {{moneyFormat($model->sub_total, $model->currency, false)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="item-empty"></td>
                    <td colspan="2">Discount</td>
                    <td>
                        {{moneyFormat($model->discount, $model->currency, false)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="item-empty"></td>
                    <td colspan="2">Shipping</td>
                    <td>
                         {{moneyFormat($model->shipping, $model->currency, false)}}
                    </td>
                </tr>
                <?php $dispaly_vat = DB::table('settings')
                ->latest()->take(1)
                ->where('id','=',22)
                ->value('value');

                $second_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',2)
                ->value('code');
                ?>
                @if($model->client->vat_status == 1)  
                @foreach(selectedTax($model->items) as $key => $tax)
                <tr>
                    @if($dispaly_vat == 1)
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
                @foreach(selectedTax($model->items) as $key => $tax)
                <tr>
                    <td colspan="2" class="item-empty">USD {{moneyFormat($tax, $model->currency, false)}}</td>
                    <td colspan="2">{{$key}}</td>
                    <td>
                        USD {{moneyFormat($tax, $model->currency, false)}}
                    </td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="2" class="item-empty"></td>
                    <td colspan="2">
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
                     $spell = Terbilang::make($model->total);
                ?>
                <th width="15%">Only:</th>
                <th width="85%" style="text-transform: capitalize;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $spell }}  {{$model->currency->name}} 
           
           <!-- @if($model->client->vat_status == 1)             
           @foreach(selectedTax($model->items) as $key => $tax)
                
                <?php   
                     $spellt = Terbilang::make($tax*$model->vatrate);
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

<table class="terms" style="margin-top: 5%;">
<tbody>
  <tr>
    <td class="terms-description">
        <div>
            <u><strong>Terms and Conditions</strong></u>
            <p>&nbsp;</p>
            <div class="terms-foot">
                <p><strong style="margin:10px 0 !important;">Delivery Time:</strong>&nbsp;&nbsp;{{$model->delivery_date}}</p>
                <div><p style="line-height: 1px;">&nbsp;</p></div>   
                <p><strong>Validity Date:</strong>&nbsp;&nbsp;{{$model->due_date}}</p>
                <div><p style="line-height: 1px;">&nbsp;</p></div>
                <p><strong style="margin:10px 0 !important;">Payment Condition:</strong>&nbsp;&nbsp;{!!$model->paymentcondition->name!!}</p>
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
<br><br><br>

<div class="col-md-12" style="width: 100%;text-align: center;">
    <div class="col-md-6" style="width: 49%;float: left;margin: 0px;padding: 0px;">
       <h4>Customer</h4>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;">Signature</h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;">Date</h4>
       </div>
       <br>
       <div class="col-md-6">
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;"></h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;"></h4>
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
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;"></h4>
          <h4 style="width: 49%;float: left;margin: 0px;padding: 0px;"></h4>
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