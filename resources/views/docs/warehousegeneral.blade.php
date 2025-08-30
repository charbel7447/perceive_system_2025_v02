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
       Report of  Warehouses:
    </div>
        <table class="items">
            <thead>
                <tr>
                    <th width="20%">Item Code /Barcode</th>
                    <th width="15%">Description</th>
                    <th class="ar" width="10%">Warehouse</th>
                    <th class="ar" width="10%">Vendor</th>
                    <th class="ar" width="10%">Category</th>
                    <th class="ar" width="5%">Unit/Qty</th>
                    <th class="ar" width="15%">Created By</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                    <tr>
                        <td>{{$item->code}}
                            <small>{!! $item->barcode !!}</small>
                        </td>
                        <td>{{$item->description}}</td>
                       
                        <?php
                            $productItem = App\Items\Item::where('product_id','=',$item->id)->get();
                        ?>
                        @foreach ($productItem as $productItemX)
                        <td class="ar">
                        <?php
                         $warehouseName = App\Warehouse::where('id','=',$productItemX->warehouse_id)->get();
                        ?>
                        @foreach ($warehouseName as $warehouseNameX)
                            {{$warehouseNameX->name}}
                        @endforeach
                        </td>
                        @endforeach
                         
                        @foreach ($productItem as $productItemX)
                        <td class="ar">
                        <?php
                         $vendorName = App\Vendor::where('id','=',$productItemX->vendor_id)->get();
                        ?>
                        @foreach ($vendorName as $vendorNameX)
                            {{$vendorNameX->company}}
                        @endforeach
                        </td>
                        @endforeach


                        @foreach ($productItem as $productItemX)
                        <td class="ar">
                        <?php
                         $categoryName = App\Category::where('id','=',$productItemX->category_id)->get();
                        ?>
                        @foreach ($categoryName as $categoryNameX)
                            {{$categoryNameX->name}}
                        @endforeach
                        </td>
                        @endforeach


                        <td class="ar">{{$item->qty}} {{$item->uom['unit']}}</td>
                        <td class="ar">{{$item->created_by}}
                        <small>{{$item->created_at}}</small></td>
                        
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Items.</td>
                    <td colspan="1" class="center">
                    <?php
                        $totalItems = App\Items\Item::count('id');
                      ?>
                      {{  $totalItems}}
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Qty.</td>
                    <td colspan="1" class="center">
                    <?php
                        $totalQty = App\Items\Item::sum('qty');
                      ?>
                      {{  $totalQty}}
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Price.</td>
                    <td colspan="1" class="center">
               
                    <?php
                        $totalPrice = App\Items\Item::sum('price');
                      ?>
                      {{  $totalPrice}}
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