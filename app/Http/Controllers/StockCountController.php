<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product\Product;
use App\Product\Lots;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;

use App\StockCount\StockCount;
use App\StockCount\StockCountLot; // 👈 new pivot model for lots
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockCountController extends Controller
{
    /** Show all stock counts */
    public function index()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        $subsubcategories = SubSubCategory::all();
        $stockCounts = StockCount::with('lots')->orderBy('id','desc')->get();

        return view('stock_count.index', compact('categories','subcategories','subsubcategories','stockCounts'));
    }

    /** Start a new stock count */
    public function store(Request $request)
    {
        $stockCount = StockCount::create([
            'count_date' => now(),
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'sub_sub_category_id' => $request->sub_sub_category_id,
            'user_id' => Auth::id(),
            'created_by' => Auth::user()->name
        ]);

        $query = Lots::with('product');

        if ($request->category_id) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        }
        if ($request->sub_category_id) {
            $query->whereHas('product', fn($q) => $q->where('sub_categoryid', $request->sub_category_id));
        }
        if ($request->sub_sub_category_id) {
            $query->whereHas('product', fn($q) => $q->where('sub_sub_categoryid', $request->sub_sub_category_id));
        }

        $lots = $query->get();
    
        foreach ($lots as $lot) {
            StockCountLot::create([
                'stock_count_id' => $stockCount->id,
                'lot_id' => $lot->id,
                'product_id' => $lot->product_id,
                'code' => $lot->code,
                'category_id' => $lot->product->category_id,
                'sub_category_id' => $lot->product->sub_categoryid,
                'sub_sub_category_id' => $lot->product->sub_sub_categoryid,
                'uom_id' => $lot->product->uom_id,
                'current_stock' => $lot->qty,
                'balance' => $lot->qty,
                'inventoried_stock' => 0,
            ]);
        }

        return redirect()->route('stock_count.show', $stockCount->id);
    }

    /** Show stock count with lots */
    public function show($id)
    {
        $stockCount = StockCount::with('lots')->findOrFail($id);
        return view('stock_count.show', compact('stockCount'));
    }

    
    /** Scan barcode from product_lots */
    public function scanAjax(Request $request, $stockCountId)
    {
        $request->validate(['barcode' => 'required|string']);

        $lot = StockCountLot::with(['category','subCategory','subSubCategory','lot.product'])
            ->where('stock_count_id', $stockCountId)
            ->where('code', $request->barcode)
            ->first();

        if (!$lot) {
            return response()->json(['error' => 'Lot not found in this stock count.']);
        }

        return response()->json([
            'id' => $lot->id,
            'code' => $lot->code,
            'current_stock' => $lot->current_stock,
            'category_id' => $lot->category_id,
            'category_name' => optional($lot->category)->name,
            'sub_category_id' => $lot->sub_category_id,
            'sub_category_name' => optional($lot->subCategory)->name,
            'sub_sub_category_id' => $lot->sub_sub_category_id,
            'sub_sub_category_name' => optional($lot->subSubCategory)->name,
            'inventoried_stock' => $lot->inventoried_stock,
            'scanned_at' => $lot->scanned_at,
            'variance' => $lot->inventoried_stock - $lot->current_stock,
            'product_name' => $lot->lot->product->description ?? ''
        ]);
    }

    /** Confirm scanned qty */
    public function confirmAjax(Request $request, $stockCountId, $lotId)
    {
        $request->validate(['inventoried_stock' => 'required|numeric|min:0']);

        $lot = StockCountLot::where('stock_count_id', $stockCountId)
            ->where('id', $lotId)
            ->firstOrFail();

        $lot->inventoried_stock = $request->inventoried_stock;
        $lot->scanned_at = now();
        $lot->variance = $lot->inventoried_stock - $lot->current_stock;
        $lot->save();

        return response()->json($lot);
    }

    /** Final submit → update Lots & Product stock */
    public function submit(StockCount $stockCount)
    {
        DB::transaction(function () use ($stockCount) {
            foreach ($stockCount->lots as $item) {
                $lot = Lots::find($item->lot_id);
                if ($lot) {
                    $lot->qty = $item->inventoried_stock;
                    $lot->save();
                }
            }

            // sync product stock as sum of its lots
            $productIds = $stockCount->lots->pluck('product_id')->unique();
            foreach ($productIds as $pid) {
                $sumQty = Lots::where('product_id', $pid)->sum('qty');
                Product::where('id', $pid)->update(['current_stock' => $sumQty]);
            }

            $stockCount->submitted_at = now();
            $stockCount->save();
        });

        return response()->json(['success' => true]);
    }

    public function variance(Request $request, $id)
{
    $stockCount = StockCount::findOrFail($id);

    $q = StockCountLot::query()->where('stock_count_id', $id);

    // Optional filters
    if ($request->filled('code')) {
        $q->where('code', 'like', "%{$request->code}%");
    }

    if ($request->filled('status')) {
        // status = match|over|short
        if ($request->status === 'match') {
            $q->whereColumn('inventoried_stock', '=', 'balance');
        } elseif ($request->status === 'over') {
            $q->whereColumn('inventoried_stock', '>', 'balance');
        } elseif ($request->status === 'short') {
            $q->whereColumn('inventoried_stock', '<', 'balance');
        }
    }

    $lots = $q->orderBy('inventoried_stock','desc')->paginate(50)->withQueryString();

    // Totals
    $totals = StockCountLot::selectRaw("
        SUM(current_stock) as sum_current,
        SUM(inventoried_stock) as sum_inventoried,
        SUM(inventoried_stock - balance) as sum_variance
    ")->where('stock_count_id', $id)->first();

    return view('stock_count.variance', compact('stockCount', 'lots', 'totals'));
}

public function exportVarianceCsv(Request $request, $id): StreamedResponse
{
    $filename = "stock_count_{$id}_variance.csv";

    $q = StockCountLot::where('stock_count_id', $id)->orderBy('code');

    $response = new StreamedResponse(function () use ($q) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, [
            'Code', 'Category ID', 'SubCategory ID', 'SubSubCategory ID',
            'UOM ID', 'Current Stock', 'balance', 'Inventoried Stock', 'Variance', 'Status', 'Last Scan'
        ]);

        $q->chunk(1000, function ($rows) use ($handle) {
            foreach ($rows as $row) {
                $variance = (int)$row->inventoried_stock - (int)$row->balance;
                $status = $variance === 0 ? 'match' : ($variance > 0 ? 'over' : 'short');
                fputcsv($handle, [
                    $row->code,
                    $row->category_id,
                    $row->sub_category_id,
                    $row->sub_sub_category_id,
                    $row->uom_id,
                    $row->current_stock,
                    $row->balance,
                    $row->inventoried_stock,
                    $variance,
                    $status,
                    $row->scanned_at,
                ]);
            }
        });

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

    return $response;
}
}