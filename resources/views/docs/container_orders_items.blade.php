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
        Container ORDER
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address">
                    <strong>To:</strong>
                    @if($model->shipper->company)
                        <pre>{{$model->shipper->company}}<br>{{$model->shipper->billing_address}}</pre>
                        <p>
                            <strong>Attention:</strong>
                            {{$model->shipper->person}}
                        </p>
                    @else
                        <pre>{{$model->shipper->person}}<br>{{$model->shipper->billing_address}}</pre>
                    @endif
                    @if($model->shipper->vat_number)
                    <p>
                        <strong>VAT Number:</strong>
                        {{$model->shipper->vat_number}}
                    </p>
                    @endif  
                    <p>
                        <strong>Container Size:</strong>
                        {{$model->container_size}}
                    </p>
                    <p>
                        <strong>Fees:</strong>
                        {{$model->shipper_fees}}
                    </p>
                </td>
                <td class="summary-empty"></td>
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
                                <td>Currency:</td>
                                <td>{{$model->currency->code}}</td>
                            </tr>
                            {{-- <tr>
                                <td>Exchange Rate:</td>
                                <td>1 USD = {{$model->exchangerate}} LBP</td>
                            </tr> --}}
                            <tr>
                                <td>Total:</td>
                                <td>{{moneyFormat($model->total, $model->currency, false)}}</td>
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
                    <th  class="ac">PurchaseOrder</th>
                    <th  class="ac">Description</th>
                    <th class="ac" >Vendor</th>
                    <th class="ac" >Weight Box</th>
                    <th class="ac" >Volume Box</th>
                    <th class="ac" >Dutty</th>
                    <th class="ac" >Unit Price</th>
                    <th class="ac" >Qty / UOM</th>
                    <th class="ac" >Total</th>
                  
                </tr>
            </thead>
            <tbody>
            <?php 
                $subtotal = 0;
                $totalQty =0;
            ?>
                @foreach($model->items as $item)
                    @foreach($item->products_items as $item)
                    <tr>
                        <td class="ac" style="vertical-align:middle">{{$item->purchase_order_item_number}}</td>
                        <td class="ac" style="vertical-align:middle">
                            <?php $name = \App\Product\Product::where('id','=',$item->product_id)->value('description'); ?>
                            {{$name}}<br><small>({{$item->product_id}})</small>
                        </td>
                        <td class="ac" style="vertical-align:middle">
                            <?php $vendor = \App\Vendor::where('id','=',$item->vendor_id)->value('company'); ?>
                                {{$vendor}}
                        </td>
                        <td class="ac" style="vertical-align:middle">{{$item->weight_box}}</td>
                        <td class="ac" style="vertical-align:middle">{{$item->volume_box}}</td>
                        <td class="ac" style="vertical-align:middle">{{$item->tax_rate}}%</td>
                        <td class="ac" style="vertical-align:middle">{{$item->unit_price}}</td>
                        <td class="ac" style="vertical-align:middle">{{$item->qty}} {{$item->uom_unit}}</td>
                        <td class="ac" style="vertical-align:middle">{{($item->qty * $item->unit_price) + (($item->qty * $item->unit_price * $item->tax_rate)/100 )}}</td>
                        <?php 
                            $subtotal += $item->qty * $item->unit_price;
                            $totalQty += $item->qty;
                         ?>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="item-empty"></td>
                    <td colspan="2">Total Qty.</td>
                    <td colspan="1" class="center">
                      {{  $totalQty}}
                    </td>
                    <td colspan="1"></td>
                </tr>
                <tr>
                    <td colspan="6" class="item-empty"></td>
                    <td colspan="2">Sub Total</td>
                    <td>
                        {{moneyFormat($subtotal, $model->currency, false)}}
                    </td>
                </tr>
                <?php $dispaly_vat = DB::table('settings')
                ->latest()->take(1)
                ->where('id','=',21)
                ->value('value');

                $second_currency = DB::table('currencies')
                ->latest()->take(1)
                ->where('id','=',2)
                ->value('code');
                ?>
                @if($model->shipper->vat_status == 1)  
                <tr>
                    @if($dispaly_vat == 1)
                    <td colspan="4" class="item-empty">{{$second_currency}} {{moneyFormat($model->totaltax * $model->exchangerate, $model->currency, false)}}</td>
                    @else
                    <td colspan="2"></td>
                    @endif
                    <td colspan="2">Vat:</td>
                    <td>
                         {{moneyFormat($model->totaltax, $model->currency, true)}}
                    </td>
                </tr>
                @endif
                <tr>
                    <td colspan="6" class="item-empty"></td>
                    <td colspan="2">
                        <strong>Total:</strong>
                    </td>
                    <td>
                        <strong>{{moneyFormat($model->total, $model->currency)}}</strong>
                    </td>
                </tr>
                
            </tfoot>
    </table>
    <table class="items" style=" padding-top: -30px !important;margin-top: -30px !important;">
        <thead>
            <?php $spell = Terbilang::make($model->total);?>
            <tr>
                <th width="15%">Only:</th>
                <th width="85%" style="text-transform: capitalize;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $spell }} {{$model->currency->name}}  </th>
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
                        <p><strong style="margin:10x 0 !important;">Payment Condition:</strong>&nbsp;&nbsp;{!!$model->paymentcondition->name!!}</p>
                        <div><p style="line-height: 1px;">&nbsp;</p></div>    
                        <p><strong>Delivery Terms:</strong>&nbsp;&nbsp;{!!$model->deliverycondition->name!!}</p>
                        <div><p style="line-height: 1px;">&nbsp;</p></div>    
                        @if($model->delivery_time)
                        <p><strong>Delivery Time:</strong>&nbsp;&nbsp;{!!$model->delivery_time!!}</p>
                        @endif
                   </div>
                 </div>
             </td>
             <td></td>
          </tr>
        </tbody>
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