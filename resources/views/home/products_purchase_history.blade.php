@extends('docs.master', ['title' => 'Categories Report '])


@section('content')

    <div class="content-title">
       {{$vendor_name}} - Purchase History for <span style="color:red">{{$product_code}} - {{$product_name}}</span>
    </div>
        <table class="items" style="margin:0 0 20px 0;">
            <thead>
                <tr>
                    <th class="ac">Number</th>
                    <th class="ac">Date</th>
                    <th class="ac">Price</th>
                    <th class="ac">Qty</th>
                    <th class="ac">Created By</th>
                    
                </tr>
            </thead>
            <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td class="ac">{{$purchase->number}}</td>
                <td class="ac">{{$purchase->date}}</td>
                <td class="ac">{{$purchase->unit_price}}</td>
                <td class="ac">{{$purchase->qty}}</td>
                <td class="ac">{{$purchase->created_by}}</td>
            </tr>
            @endforeach
            </tbody>
</table>