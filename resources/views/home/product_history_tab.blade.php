@extends('docs.master', ['title' => 'Categories Report '])


@section('content')

    <div class="content-title">
       {{$client_name}} - Sale History for <span style="color:red">{{$product_code}} - {{$product_name}}</span>
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
            @foreach($invoices as $invoice)
            <tr>
                <td class="ac">{{$invoice->number}}</td>
                <td class="ac">{{$invoice->date}}</td>
                <td class="ac">{{$invoice->price}}</td>
                <td class="ac">{{$invoice->quantity}}</td>
                <td class="ac">{{$invoice->created_by}}</td>
            </tr>
            @endforeach
            </tbody>
</table>