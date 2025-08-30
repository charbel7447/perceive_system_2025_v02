<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice\Invoice;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;
use App\Client;
use DB;
use Auth;
use App\PaymentCondition;
use App\ExchangeRate\ExchangeRate;
use App\Invoice\Item;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\VatRate\VatRate;
use App\FinishedProduct\FinishedProduct;

use Carbon\Carbon;


use App\StockMovement\StockMovement;

use App\Mail\Invoices\Send;
use App\Mail\Invoices\Confirmed;
use App\Mail\Invoices\Declined;
use App\Settings;
use Mail;
use App\User;
use App\Notifications;
use App\CreditNote\CreditNote;
use App\CreditNote\Item as CreditNoteItem;
class ProductHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function products_history($client,$product)
    {
        $client_name = \App\Client::where('id','=',$client)->value('name');
        $product_name = \App\Product\Product::where('id','=',$product)->value('name');
        $product_code = $product;
        $invoices = DB::table('invoices')
        ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
        ->where('invoices.client_id','=',$client)
        ->where('invoice_items.item_id','=',$product)
        ->orderby('invoices.date','desc')
        ->get();
        // dd($invoices);
        return view('home.product_history_tab',compact('invoices','client_name','product_name','product_code'));
    }
    

    public function products_purchase_history($vendor,$product)
    {
        $vendor_name = \App\Vendor::where('id','=',$vendor)->value('person');
        $product_name = \App\Product\Product::where('id','=',$product)->value('name');
        $product_code = $product;
        $purchases = DB::table('purchase_orders')
        ->join('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
        ->where('purchase_orders.vendor_id','=',$vendor)
        ->where('purchase_order_items.product_id','=',$product)
        ->orderby('purchase_orders.date','desc')
        ->get();
        // dd($invoices);
        return view('home.products_purchase_history',compact('purchases','vendor_name','product_name','product_code'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
