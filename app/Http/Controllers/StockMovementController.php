<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product\Product;
use App\StockMovement\StockMovement;
use Auth;
use App\Excel\StockMovementBladeExport;
use Maatwebsite\Excel\Facades\Excel;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_stockmovement_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = StockMovement::with('uom','currency','warehouse','category','sub_category')->orderby('created_at','desc')->search();    
        return api([
            'data' => $data
        ]);
       }
    }

    public function list()
    {
         $data = StockMovement::query()
        ->orderByDesc('created_at')
        ->get();

    return view('reports.stock_movement', compact('data'));

    }


public function export(Request $request)
{
    $query = StockMovement::query()->orderBy('created_at', 'desc');

    // Optional filters (example)
    if ($request->type) {
        $query->where('type', $request->type);
    }

    if ($request->product_code) {
        $query->where('product_code', 'like', '%' . $request->product_code . '%');
    }

    $data = $query->get();

    return Excel::download(new StockMovementBladeExport($data), 'stock_movement.xlsx');
}

    public function search(Request $request)
    {
            $results = StockMovement::with('taxes')
                ->orderBy('code')
                ->when(request('q'), function($query) {
                    $query->where('product_code', 'like', '%'.request('q').'%')
                        ->orWhere('product_name', 'like', '%'.request('q').'%')
                        ->orWhere('purchase_order', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get();
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
