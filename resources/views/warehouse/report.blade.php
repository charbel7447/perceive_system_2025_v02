@extends('docs.master', ['title' => 'Warehouses Report '])


@section('content')

    <div class="content-title">
    #Report of  Warehouses
    </div>
        <table class="items">
            <thead>
                <tr>
                    <th width="1%">ID#</th>
                    <th width="15%">Item Code / BarCode</th>
                    <th width="20%">Product Name</th>
                    <th width="10%">Warehouse</th>
                    <th width="10%">Category</th>
                    <th width="10%">Qty.</th>
                    <th width="10%">Vendor</th>
                    <th width="10%">Status</th>
                    <th width="10%">Date</th>
       
                </tr>
            </thead>
            <tbody>
            <?php $i = 0 ; ?>
            <?php $sumqty = 0 ; ?>
                @foreach($reports as $report)
                    <tr>
                        <td>
                           
                            {{ ++$i }}
                        </td>
                        <td class="al">
                        {{$report->products->code }}
                        {!! $report->products->barcode !!}
                        </td>
                        <td class="al">
                        {{$report->products->description }}
                        </td>
                        <td>
                        @if ($report->warehouse['name'])
                            {{$report->warehouse['name']}}
                        @elseif ($report->product)
                            {{$report->product->warehouse['name'] }}
                        @else
                            @foreach ($report->products->items as $key1)
                                <?php $getWarehouseName = App\Warehouse::where('id','=',$key1->warehouse_id)->get(); ?>
                                    @foreach ($getWarehouseName as $key2)
                                        {{$key2->name}}
                                    @endforeach
                            @endforeach
                        @endif
                        
                        </td>
                        <td class="al">
                        @if ($report->category['name'])
                        {{$report->category['name']}}
                        @elseif ($report->product)
                            {{$report->product->category['name'] }}
                        @else
                            @foreach ($report->products->items as $key3)
                                <?php $getCategoryName = App\Category::where('id','=',$key3->category_id)->get(); ?>
                                    @foreach ($getCategoryName as $key4)
                                        {{$key4->name}}
                                    @endforeach
                            @endforeach
                        @endif
                        </td>
                       
                        <td class="al">
                       <?php $sumqty += $report->products->qty ?>
                        {{$report->products->qty}} {{$report->products->uom['unit']}}
                        </td>
                        <td class="al">
                        <!-- {{$report->product}} -->
                        @if ($report->products->vendor['company'])
                        {{$report->products->vendor['company']}}
                        @elseif ($report->product)
                            {{$report->product->vendor['company'] }}
                        @else
                            @foreach ($report->products->items as $key5)
                                <?php $getVendorName = App\Vendor::where('id','=',$key5->vendor_id)->get(); ?>
                                    @foreach ($getVendorName as $key6)
                                        {{$key4->company}}
                                    @endforeach
                            @endforeach
                        @endif

                        </td>
                        <td class="al">
                        @if($report->products->status)
                        {{ $report->products->status }}
                        @else
                        @foreach ($report->products->items as $key7)
                                <?php $getStatus = App\Items\Item::where('product_id','=',$key7->product_id)->get(); ?>
                                    @foreach ($getStatus as $key8)
                                        <?php echo $getStatus = App\Items\Items::where('id','=',$key8->product_id)->pluck('status'); ?>

                                    @endforeach
                            @endforeach
                        @endif
                        </td>
                        <td class="al">
                        {{$report->products->created_at }}
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Items.</td>
                    <td colspan="1" class="center">
               
                     {{$i}}
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="1" class="item-empty"></td>
                    <td colspan="2">Total Qty.</td>
                    <td colspan="1" class="center">
                      {{ $sumqty }}
                    </td>
                </tr>
             
                
            </tfoot>
    </table>
    
<br><br><br>

 
    @endsection
