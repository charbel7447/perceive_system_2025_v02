<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Product\Product;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return api([
            'data' => PaymentRequest::with(['currency', 'items.currency', 'items.client', 'taxes','uom','type','values','attributes.attribute_name'])->search()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return api([
            'data' => PaymentRequest::with(['currency', 'items.currency', 'items.client', 'taxes','uom','type','values','attributes.attribute_name'])->search()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        if($request->hasFile('document4') && $request->file('document4')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document4)) {
                DB::table('products')
                ->where('id', $request->id)
                ->update(['thumbnail' =>  $fileName]);
            
           }
        }


        if($request->hasFile('document1') && $request->file('document1')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document1)) {
                DB::table('products')
                ->where('id', $request->id)
                ->update(['document1' =>  $fileName]);
        
           }
        }

        if($request->hasFile('document2') && $request->file('document2')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document2)) {
                DB::table('products')
                ->where('id', $request->id)
                ->update(['document2' =>  $fileName]);
        
           }
        }

        if($request->hasFile('document3') && $request->file('document3')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document3)) {
                DB::table('products')
                ->where('id', $request->id)
                ->update(['document3' =>  $fileName]);
        
           }
        }

        // return redirect('/api/products');
        // return back();
        return api([
            'saved' => true,
            // 'id' => $request->id
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('/');
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
