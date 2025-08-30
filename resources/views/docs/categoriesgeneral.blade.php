@extends('docs.master', ['title' => 'Categories Report '])


@section('content')

    <div class="content-title">
       Report of  Categories:
    </div>
        <table class="items">
            <thead>
                <tr>
                    <th width="20%">Item Code /Barcode</th>
                    <th width="15%">Description</th>
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

 
    @endsection
