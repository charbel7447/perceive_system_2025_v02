<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Damaged\Damaged;
use DB;
use Auth;

class DamagedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return api([
            'data' => Damaged::with(['items','items.product','items.uom1'])->search()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = [
            'number' => counter()->next('damaged_request'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'terms' => null,
            'manager_id' => null,
            'exchangerate' => null,
            'manager_id' => null,
            'manager' => null,
            'items' => [
                [
                    'vendor_id' => null,
                    'vendor' => null,
                    'product' => null,
                    'product_id' => null,
                    'attribute_id' => null,
                    'vendor_reference' => null,
                    'tax_name'  => null,
                    'tax_rate'  => null,
                    'unit_price' => 0,
                    'transfer_qty' =>1,
                    'qty' => 1,
                    'uom'=> 0,
                    'employee'=> 0,
                    'taxes' => []
                ]
            ]
        ];
        return api([
            'form' => $form
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
        $model = new Damaged;
        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $items = collect($request->items);
    
        $model = DB::transaction(function() use ($model, $request) {
            
            $model->storeHasMany([
                'items' => $request->items
            ]);
            counter()->increment('damaged_request');
            return $model;
        });
        $model->save();
        return api([
            'saved' => true,
            'id' => $model->id
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
         $data = Damaged::with(['items','items.product','items.uom1'])->findOrFail($id);
            return api([
                'data' =>  $data,
            ]);
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

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        $data =  Damaged::with(['items','items.product','items.uom1'])->findOrFail($id);
        $doc  = 'docs.damaged';

        return pdf($doc, $data);
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
        $user = auth()->user();
        if ($user->is_transfer_delete == 0){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $user = auth()->user();
            $model = Damaged::findOrFail($id);
            $model->items()->delete();
            $model->delete();
            return api([
                    'deleted' => true
            ]);
        }
    }
}
