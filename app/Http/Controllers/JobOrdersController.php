<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FinishedProduct\FinishedProduct;
use App\FinishedProduct\Item as ProductItem;

use App\FinishedProduct\ItemSearch as ProductItemSearch;
use DB;
use Auth;
use App\UserCategory;
use App\Category;
use App\JobOrders\JobOrders;
use App\Product\Product;
use App\StockMovement\StockMovement;
use App\SubCategory;
use App\Warehouse;
// use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

class JobOrdersController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => FinishedProduct::with(['currency', 'items.currency', 'items.client', 'taxes','uom','type','values','attributes.attribute_name'])->search()
        ]);
    }
            
    }

//     public function search(Request $request)
//     {
//         $user = auth()->user();

//         $request->validate([
//             'client_id' => 'sometimes|required|integer|exists:clients,id'
//         ]);

//         $categ1 = UserCategory::where('user_id','=',$user->id)->get();
//         $items = array(); 
//         foreach ($categ1 as $categ2)
//         $items[] = $categ2->category_id;
//         {
//         if(request()->has('client_id')) {
//             $results = DB::table('products')
//                 ->select(
//                     'products.id', 'products.minimum_stock','products.uom_id',
//                     DB::raw('concat(products.code, " - ", products.description) as text'),
//                     DB::raw('concat(uom.unit) as uom'),
//                     'product_items.price as vendor_price', 'product_items.client_id'
//                     ,'product_items.reference',
//                     // ,'product_items.tax_rate' ,'product_items.tax_name'
//                     'product_taxes.name','product_taxes.rate'
//                 )
//                 ->join('product_items', 'products.id', '=', 'product_items.product_id')
//                 ->join('product_taxes', 'products.id', '=', 'product_taxes.product_id')
//                 ->join('clients', 'clients.id', '=', 'product_items.client_id')
//                 ->join('uom', 'uom.id', '=', 'products.uom_id')
//                 ->whereIn('category_id',$items)
//                 ->where('product_items.client_id', '=', request('client_id'))
//                 ->where(function($query) {
//                     $query->where('products.code', 'like', '%'.request('q').'%')
//                         ->orWhere('products.description', 'like', '%'.request('q').'%')
//                         ->orWhere('products.uom_id', 'like', '%'.request('q').'%')
//                         ->orWhere('product_items.reference', 'like', '%'.request('q').'%');
//                 })
//                 ->limit(6)
//                 ->get();
//         }
//         else if(request()->has('sub_category')) {
//             $results = FinishedProduct::with('taxes','uom')
//             ->orderBy('code')
//             ->whereIn('category_id',$items)
//             ->where('products.sub_category_id', '=', request('sub_category_id'))
//             ->when(request('q'), function($query) {
//                 $query->where('code', 'like', '%'.request('q').'%')
//                     ->orWhere('description', 'like', '%'.request('q').'%');
//             })
//             ->limit(6)
//             ->get(['id', 'code', 'description', 'unit_price','uom_id']);
//         } 
//         else {
//             $results = FinishedProduct::with('taxes','uom')
//                 ->orderBy('code')
//                 ->whereIn('category_id',$items)
//                 ->when(request('q'), function($query) {
//                     $query->where('code', 'like', '%'.request('q').'%')
//                         ->orWhere('description', 'like', '%'.request('q').'%');
//                 })
//                 ->limit(6)
//                 ->get(['id', 'code', 'description', 'minimum_stock','uom_id']);
//         }

//         return api([
//             'results' => $results
//         ]);
//     }
// }

public function search(Request $request)
{
    $request->validate([
        'client_id' => 'sometimes|required|integer|exists:clients,id'
    ]);

    if(request()->has('client_id')) {
        $results = DB::table('finished_product')
            ->select(
                'finished_product.id', 'finished_product.unit_price','finished_product.uom_id',
                DB::raw('concat(finished_product.code, " - ", finished_product.description) as text'),
                DB::raw('concat(uom.unit) as uom'),
                'finished_product_items.price as vendor_price', 'finished_product_items.client_id',
                'finished_product_items.reference',
                'finished_product_taxes.name','finished_product_taxes.rate'
            )
            ->join('finished_product_items', 'products.id', '=', 'finished_product_items.product_id')
            ->join('finished_product_taxes', 'products.id', '=', 'finished_product_taxes.product_id')
            ->join('clients', 'clients.id', '=', 'finished_product_items.client_id')
            ->join('uom', 'uom.id', '=', 'products.uom_id')
            ->where('finished_product_items.client_id', '=', request('client_id'))
            ->where(function($query) {
                $query->where('products.code', 'like', '%'.request('q').'%')
                    ->orWhere('products.description', 'like', '%'.request('q').'%')
                    ->orWhere('products.uom_id', 'like', '%'.request('q').'%')
                    ->orWhere('finished_product_items.reference', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get();
            } else {
                $results = FinishedProduct::with('taxes','uom')
                    ->orderBy('code')
                    ->when(request('q'), function($query) {
                        $query->where('code', 'like', '%'.request('q').'%')
                            ->orWhere('description', 'like', '%'.request('q').'%');
                    })
                    ->limit(15)
                    ->get(['id', 'code', 'generated_code','description', 'unit_price','uom_id','qty_on_hand']);
            }

            return api([
                'results' => $results
            ]);
        }

 
        public function searchboth(Request $request)
        {
            $request->validate([
                'client_id' => 'sometimes|required|integer|exists:clients,id'
            ]);
        
            if(request()->has('client_id')) {
                $results = DB::table('products')
                    ->select(
                        'products.id', 'products.unit_price','products.uom_id',
                        DB::raw('concat(products.code, " - ", products.description) as text'),
                        DB::raw('concat(uom.unit) as uom'),
                        'product_items.price as vendor_price', 'product_items.client_id',
                        'product_items.reference',
                        'product_taxes.name','product_taxes.rate'
                    )
                    ->join('product_items', 'products.id', '=', 'product_items.product_id')
                    ->join('product_items', 'products.id', '=', 'product_items.product_id')
                    ->join('product_taxes', 'products.id', '=', 'product_taxes.product_id')
                    ->join('clients', 'clients.id', '=', 'product_items.client_id')
                    ->join('uom', 'uom.id', '=', 'products.uom_id')
                    ->where('product_items.client_id', '=', request('client_id'))
                    ->where(function($query) {
                        $query->where('products.code', 'like', '%'.request('q').'%')
                            ->orWhere('products.description', 'like', '%'.request('q').'%')
                            ->orWhere('products.uom_id', 'like', '%'.request('q').'%')
                            ->orWhere('product_items.reference', 'like', '%'.request('q').'%');
                    })
                    ->limit(15)
                    ->get();
                    } else {
                        $results = FinishedProduct::with('taxes','uom')
                        ->orderBy('code')
                        ->when(request('q'), function($query) {
                            $query->where('code', 'like', '%'.request('q').'%')
                                ->orWhere('description', 'like', '%'.request('q').'%');
                        })
                        ->limit(15)
                        ->get(['id', 'code', 'description', 'unit_price','uom_id','qty_on_hand']);
                    }
        
                    return api([
                        'results' => $results
                    ]);
                }
        
    
    public function create()
    {
        $user = auth()->user();
        $warehouse_name = Warehouse::where('id','=',1)->value('name');
        if ($user->is_products_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $form = array_merge([
                'code' => counter()->next('finished_product'),
                'minimum_stock' => 0,
                'description' => '',
                'has_inventory' => 0,
                'product_type' => 2,
                'unit_price' => 0,
                'qty_on_hand' => 0,
                'warehouse' => $warehouse_name,
                // 'uom_id' => 0,
                // 'category_id' => 0,
                // 'sub_category_id' => 0,
                // 'sub_sub_category_id' => 0,
                // 'warehouse_id' => 1,
                'items' => [],
                'attributes' => [],
                'values' => [],
                'attributes_value' => [],
                'taxes' => [],
                'materials' => [],
            ],
                currency()->defaultToArray()
            );

            return api([
                'form' => $form
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
                
                $model = new JobOrders();
                $model->fill($request->except('items'));
        
                $model->user_id = auth()->id();
                $username = Auth::user()->name;
                
                $model ->created_by = $username;
                $model->save();
                

                return api([
                    'saved' => true,
                    'id' => $model->id
                ]);
            }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
       
            $data = FinishedProduct::with([
                'currency', 'items.currency', 'items.client','materials','materials.material', 'taxes','uom','type','values','attributes.attribute_name'
                ])->findOrFail($id);

            return api([
                'data' => $data
            ]);
        }
    }

    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{ 
                    $form = FinishedProduct::with(['currency', 'items.currency','materials','materials.material','values.attribute','values.values', 'items.client', 'taxes','uom','type','values'])
                    ->findOrFail($id);

            return api([
                'form' => $form
            ]);
        }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        // $customPaper = array(0,0,250,491);
        $customPaper = array(0,0,150,291);
        $products = FinishedProduct::with(['currency', 'items.currency', 'items.client', 'taxes','uom','values','attributes.attribute_name','attributes'])->findOrFail($id);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.finish_product_label', compact('products'));
       //  return View('docs.finish_product_label', compact('products'));
        return $pdf->download('fp_product_label.pdf');
    
        }
        // $data = FinishedProduct::with(['currency', 'items.currency', 'items.client', 'taxes','uom',
        // 'warehouse','category','sub_category','values','attributes.attribute_name'])
        //     ->findOrFail($id);
        // $doc  = 'docs.product_label';
        
        // return pdfLabel($doc, $data);
    }

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = FinishedProduct::findOrFail($id);

        $request->validate([
            'description' => 'nullable',
            'minimum_stock' => 'nullable',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'has_inventory' => 'nullable',
            'uom_id' => 'nullable',
            'product_type' => 'nullable',
            'category_id' => 'nullable',
            'sub_category_id' => 'nullable',
            'warehouse_id' => 'nullable',
            'items' => 'sometimes|array',
            'items.*.reference' => 'nullable',
            'items.*.client_id' => 'nullable|integer|exists:clients,id',
            'items.*.price' => 'nullable',
            'items.*.currency_id' => 'nullable|integer|exists:currencies,id',
            'values' => 'sometimes|array',
            'values.*.attribute_id'=> 'nullable',
            'values.*.attribute_value'=> 'nullable',
            'taxes.*.name' => 'nullable',
            'taxes.*.rate' => 'nullable',
            'taxes.*.tax_authority' => 'nullable'
        ]);

        $qty = $model->qty_on_hand;
        $model->fill($request->except('items', 'taxes','values'));
        $username = Auth::user()->name;
        $model->status = 'Manually Update';
        $model ->created_by = $username;

        $code =  FinishedProduct::where('id','=',$id)->value('code');
        // $barcode = new BarcodeGenerator();
        // $barcode->setText($code);
        // $barcode->setType(BarcodeGenerator::Code39);
        // $barcode->setScale(1,5);
        // $barcode->setThickness(25);
        // $barcode->setFontSize(10);
        // $code = $barcode->generate();
        //$code = \DNS1D::getBarcodeHTML($code, 'C39',1.05,25);
        $code = \DNS1D::getBarcodePNG($code, 'C39',1.05,40,array(1,1,1,1), true);
        $code = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
        $model->barcode = $code;

        // only update if no qty in hand
        if($qty == 0) {
            if($request->has('has_inventory')) {
                $model->has_inventory = $request->has_inventory;
            }
        }

        $result = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items' => $request->items,
                'taxes' => $request->taxes,
                'values' => $request->values,
                'materials' => $request->materials
            ]);

            return $model;
        });

       

        return api([
            'saved' => true,
            'id' => $result->id
        ]);
    }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_products_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = FinishedProduct::findOrFail($id);

            // check whether this particular FinishedProduct belongs to
            $items = $model->items()->count();

            // quotation
            // $quotations = DB::table('quotation_items')
                // ->where('product_id', $model->id)->count();

            // sales order
            // $salesOrders = DB::table('sales_order_items')
                // ->where('product_id', $model->id)->count();
            // invoice

            // $invoices = DB::table('invoice_items')
                // ->where('product_id', $model->id)->count();
            // purchase order

            $purchaseOrders = DB::table('purchase_order_items')
                ->where('product_id', $model->id)->count();
            // bills

            // $bills = DB::table('bill_items')
                // ->where('product_id', $model->id)->count();
            // if yes provide warning

            if($items || $purchaseOrders ) {
                return api([
                    'message' => 'Delete all the FinishedProduct relations first',
                    'errors' => []
                ], 422);
            }


                
            $model->items()->delete();
            $model->delete();

            
            return api([
                'deleted' => true
            ]);
        }
    }
}
