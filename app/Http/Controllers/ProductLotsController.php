<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product\Lots;
use App\Product\Product;
use Auth;

class ProductLotsController extends Controller
{


  public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->is_uom_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            if (Auth::check()) {
                if($request->has('product_id')){
                    // throw new \Exception ($request->product_id);
                    return api([
                    'data' => Lots::with(['category','sub_category','vendor','uom'])->where('product_id','=',$request->product_id)->where('qty','>',0)->search()
                    ]);
                }else if($request->has('q')){
                    // throw new \Exception ($request->q);
                    return api([
                    'data' => Lots::with(['category','sub_category','vendor','uom'])->where('code','like','%'.$request->q.'%')->where('qty','>',0)->search()
                    ]);
                }else{
                    return api([
                    'data' => Lots::with(['category','sub_category','vendor','uom'])->where('qty','>',0)->search()
                ]);
                }
            }
        }
    }

public function showLots(Request $request, $id)
{
    $query = \App\Product\Lots::with(['uom', 'vendor'])
        ->where('product_id', $id)
        ->where('qty', '>', 0);

    // 🔍 Search filter only on products_lots table
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('product_name', 'like', "%{$search}%"); // if you have lot_number or other fields
        });
    }

    $model = $query
        ->orderBy('id', 'desc')
        ->paginate(15);

    return api([
        'model' => $model
    ]);
}

public function updateLot(Request $request, $id)
    {

        // throw new \Exception ($request->qty.'  '.$id);
        $lot = Lots::findOrFail($id);


        $product_id = Lots::where('id','=',$id)->value('product_id');
        $lot->delete();
        $sumQty =  Lots::where('product_id','=',$product_id)->sum('qty');

        Product::where('id','=',$product_id)->update(['current_stock' => $sumQty]);
      
        // ✅ update lot

        return response()->json([
            'success' => true,
            'message' => 'Lot updated successfully',
            'data'    => $lot
        ]);
    }


    public function deleteLot(Request $request, $id)
    {

        // throw new \Exception ($request->qty.'  '.$id);
        $lot = Lots::findOrFail($id);

        Lots::where('id','=',$id)->update([
            'qty' => $request->qty
        ]);

        $product_id = Lots::where('id','=',$id)->value('product_id');
        $sumQty =  Lots::where('product_id','=',$product_id)->sum('qty');

        Product::where('id','=',$product_id)->update(['current_stock' => $sumQty]);
      
        // ✅ update lot
        return back();

        return response()->json([
            'success' => true,
            'message' => 'Lot updated successfully',
            'data'    => $lot
        ]);
    }
}
