<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use DB;
 
use PDF;
use App\Product\Item;
use App\Product\Product;
use App\Uom;

use Auth;
use App\Category;
use App\SubCategory;


class WarehouseController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_warehouses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Warehouse::search()
            ]);
        }
    }
    

    public function search()
    {
        $results = Warehouse::orderBy('name')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('number', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'name', 'number', 'description']);

        return api([
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->is_warehouses_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $form = array_merge([
                'name' => '',
                'number' => counter()->next('warehouse'),
                'description' => ''
            ]);
            return api([
                'form' => $form
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_warehouses_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $user = auth()->user();
            $request->validate([
                'name' => 'required|max:255',
                'number' => 'nullable|max:255',
                'description' => 'nullable'
            ]);
            $model = new Warehouse;
            $model->fill($request->all());
            $model->user_id = auth()->id();
            $username = Auth::user()->name;
            $model ->created_by = $username;
            $model->save();
            $result = DB::transaction(function() use ($model, $request) {
                $model->number = counter()->next('warehouse');
                counter()->increment('warehouse');

                return $model;
            });
            return api([
                'saved' => true,
                'id' => $model->id
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_warehouses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
       
            $warehouse = Warehouse::findOrFail($id);
            return api([
                'data' => $warehouse
            ]);
        }
    }

    public function showProducts($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $user = auth()->user();
        $model = $warehouse->products()
            ->with(['product', 'currency','vendor','category','items','uom','product.vendor','sub_category'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return api([
                'model' => $model,
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
        $user = auth()->user();
        if ($user->is_warehouses_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        
            return api([
                'form' => Warehouse::findOrFail($id)
            ]);
        }
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
        $user = auth()->user();
        if ($user->is_warehouses_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Warehouse::findOrFail($id);

        $request->validate([
            'name' => 'nullable|max:255',
            'number' => 'nullable|max:255',
            'description' => 'nullable'
        ]);

        $model->fill($request->all());
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
     }
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
        $model = Warehouse::findOrFail($id);
        if ($user->is_warehouses_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else if ($model->id == 1){
            return response()->json(['error' => 'Forbidden.'], 413);
        }else{
        
            $model = Warehouse::findOrFail($id);
            // check whether this particular vendor belongs to
            $products = $model->products()->count();
            if($products) {
                return api([
                    'message' => 'Delete all the vendor relations first',
                    'errors' => []
                ], 422);
            }
            $model->delete();
            return api([
                'deleted' => true
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_warehouses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        
            $warehouse = Warehouse::with(['products','uom'])->findOrFail($id);
            $products = Product::where('warehouse_id','=',$id)->where('current_stock','>',0)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.warehouse', compact('warehouse','products'));
             return $pdf->download(now().'--'.'--warehouse.pdf');
           // return view('docs.warehouse', compact('warehouse','products'));
            return redirect()->back();
        }
    }

    public function zero_stock($id)
    {
        $user = auth()->user();
        if ($user->is_warehouses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        
            $warehouse = Warehouse::with(['products','uom'])->findOrFail($id);
            $products = Product::where('warehouse_id','=',$id)->where('current_stock','>=',0)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.warehouse_zero_stock', compact('warehouse','products'));
            return $pdf->download(now().'--'.'--warehouse.pdf');
            // return view('docs.warehouse_zero_stock', compact('warehouse','products'));
            return redirect()->back();
        }
    }
      
    public function pdfgeneral()
    {
        $user = auth()->user();
        if ($user->is_warehouses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
      
            $warehouses = Warehouse::all();
            $products = Items::all();
            $productsItem = Item::all();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.warehousegeneral', compact('warehouses','products','productsItem'));
            return $pdf->download(now().'--'.'--warehouse.pdf');
            //return view('docs.warehousegeneral')
            //->with(compact('warehouses'))->with(compact('products'))->with(compact('productsItem'));
            return redirect()->back();
        }
    }

}