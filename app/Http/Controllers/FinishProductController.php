<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FinishedProduct\FinishedProduct;
use App\FinishedProduct\Item as ProductItem;


use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;


use Illuminate\Database\Eloquent;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Input;

use File;
use Hash;
use Artisan;
use App\FileUpload;

use App\FinishedProduct\ItemSearch as ProductItemSearch;
use DB;
use Auth;
use App\UserCategory;
use App\Category;
use App\Product\Product;
use App\StockMovement\StockMovement;
use App\SubCategory;
use App\Warehouse;
// use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

class FinishProductController extends Controller
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
                'machines' => [],
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
                $request->validate([
                    'description' => 'required',
                    'minimum_stock' => 'required|numeric|min:0',
                    // 'uploaded_logo_file' => 'nullable|image|max:2048',
                    // 'uploaded_logo' => 'nullable|image|max:2048',
                    'currency_id' => 'required|integer|exists:currencies,id',
                    'has_inventory' => 'nullable',
                    'product_type' => 'nullable',
                    'uom_id' => 'required|min:0',
                    'type_id' => 'required|min:0',
                    'items' => 'sometimes|array',
                    'items.*.reference' => 'nullable|max:255',
                    'items.*.client_id' => 'nullable|integer|exists:clients,id',
                    'items.*.price' => 'nullable|numeric|min:0',
                    'items.*.currency_id' => 'nullable|integer|exists:currencies,id',
                    'items.*.tax_name' => 'nullable|max:255',
                    'items.*.tax_rate' => 'nullable|numeric|min:0',
                    'values' => 'sometimes|array',
                    'values.*.attribute_id'=> 'nullable',
                    'values.*.attribute_value'=> 'nullable',
                    'taxes.*.name' => 'nullable|max:255',
                    'taxes.*.rate' => 'nullable|numeric|min:0',
                    'taxes.*.tax_authority' => 'nullable|max:255',
                    'machines' => 'sometimes|array',
                ]);
        
                $model = new FinishedProduct;
                $model->fill($request->except('items', 'taxes','values','materials','machines','settings','machines.settings'));
        

                if($request->hasFile('document') && $request->file('document')->isValid()) {
                    // store in public uploads folder by default
                   if($fileName = uploadFile($request->document)) {
                        $model->document = $fileName;
                   }
                }

                $model->user_id = auth()->id();
                $model->has_inventory = $request->has_inventory;
                $username = Auth::user()->name;
                $model->status = 'Manually Creation';
                $model ->created_by = $username;
                $model ->company = '1';
                // $model ->qty_on_hand = '0';
                $type_code = DB::table('product_type')
                ->latest()->take(1)
                ->where('id','=',$request->type_id)
                ->value('code');

                $att_code = 'FP'.$type_code;
                $values =  collect($request->values);
                foreach($values as $value){
                    // $att_code =  $att_code.''.$value['attribute']['number'].$value['attribute_value'];
                    $att_code =  $att_code.''.$value['attribute_value'];
                }
                $att_code = $att_code.counter()->next('finished_product');;
               
                // DB::table('test')
                //         ->where('id', 1)
                //         ->update(['body' => $request->machines]);

                        
                $model->generated_code = $att_code;

                $code = \DNS1D::getBarcodePNG($att_code, 'C39',1.05,40,array(1,1,1,1), true);
                $code = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
                $model->barcode = $code;

                $result = DB::transaction(function() use ($model, $request) {
                    $type_code = DB::table('product_type')
                                ->latest()->take(1)
                                ->where('id','=',$request->type_id)
                                ->value('code');

                    $model->code =counter()->next('finished_product');
                    $model->storeHasMany([
                        'items' => $request->items,
                        'taxes' => $request->taxes,
                        'values' => $request->values,
                        'materials' => $request->materials,
                        'machines.settings' => $request->machines,
                        // 'settings' => $request->settings,
                    ]);
        
                    counter()->increment('finished_product');
        
                    return $model;
                });

                return api([
                    'saved' => true,
                    'id' => $result->id
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
                'currency', 'items.currency', 'items.client','materials','materials.material', 'taxes','uom','type','values','attributes.attribute_name','machines','machines.machinex','machines.settings'
                ])->findOrFail($id);

            return api([
                'data' => $data
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{ 
                   
            $form = FinishedProduct::with(['currency', 'items.currency','materials','materials.material','values.attribute','values.values', 'items.client', 'taxes','uom','type','values','machines','machines.machinex','machines.settings'])
            ->findOrFail($id);
                    if($request->has('mode')) {
                        switch ($request->mode) {
                            case 'finished_product':
                                // i
                                $form = FinishedProduct::with(['currency', 'items.currency','materials','materials.material','values.attribute','values.values', 'items.client', 'taxes','uom','type','values','machines','machines.machinex','machines.settings'])
                                ->findOrFail($id);

                                break;
                            default:
                                abort(404, 'Invalid Mode');
                                break;
                        }
                    } 

            return api([
                'form' => $form
            ]);
        }
    }

    public function pdf($id, Request $request)
    {
      
        $data = FinishedProduct::with(['currency', 'items.currency','materials','materials.material','values.attribute','values.values', 'items.client', 'taxes','uom','type','values','machines','machines.machinex','machines.settings'])
        ->findOrFail($id);
        $doc  = 'docs.finished_product_dataSheet';

        return pdf($doc, $data);
    }

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = FinishedProduct::findOrFail($id);

        // $request->validate([
        //     'description' => 'nullable',
        //     'minimum_stock' => 'nullable',
        //     'currency_id' => 'nullable|integer|exists:currencies,id',
        //     'has_inventory' => 'nullable',
        //     'uom_id' => 'nullable',
        //     'product_type' => 'nullable',
        //     'category_id' => 'nullable',
        //     'sub_category_id' => 'nullable',
        //     'warehouse_id' => 'nullable',
        //     'items' => 'nullable|array',
        //     'items.*.reference' => 'nullable',
        //     'items.*.client_id' => 'nullable|integer|exists:clients,id',
        //     'items.*.price' => 'nullable',
        //     'items.*.currency_id' => 'nullable|integer|exists:currencies,id',
        //     'values' => 'nullable|array',
        //     'values.*.attribute_id'=> 'nullable',
        //     'values.*.attribute_value'=> 'nullable',
        //     'taxes.*.name' => 'nullable',
        //     'taxes.*.rate' => 'nullable',
        //     'taxes.*.tax_authority' => 'nullable'
        // ]);

        $qty = $model->qty_on_hand;
        $model->fill($request->except('items', 'taxes','values','machines','settings','machines.settings'));
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

        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // DB::table('test')
        // ->where('id', 1)
        //        ->update(['body' => $request->document1]);


        $items = collect($request->items);
        $taxes = collect($request->taxes);
        $values = collect($request->values);
        $materials = collect($request->materials);
        $machines = collect($request->machines);

        $result = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items' => $request->items,
                'taxes' => $request->taxes,
                'values' => $request->values,
                'materials' => $request->materials,
                'machines.settings' => $request->machines,
                // 'settings' => $request->settings,
            ]);

            return $model;
        });
        $model->save();
       

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

    
    protected function createHTMLfile($file, $type)
    {
        $path = storage_path('app/uploads/').$file;
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $data = File::get($path);
        $base64 = 'data:image/' .$extension. ';base64,' . base64_encode($data);

        $h ='<div>';
        $h ='    <img style="width: 210px;margin-top:-40px;float:left;" src="'.$base64.'">';
        $h .='</div>';

        $path = storage_path('app/'.$type.'.html');

        File::put($path, $h);

        return $path;
    }

    
    protected function uploadIfExist($settings, $file)
    {
        if(request()->hasFile($file) && request()->file($file)->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile(request()->file($file))) {
                // overwrite previous uploaded file
                deleteFile(settings()->get($settings));
                settings()->set($settings, $fileName);

                return $fileName;
           }
           if($fileName = uploadLogo(request()->file($file))) {
            // overwrite previous uploaded file
            deleteFile(settings()->get($settings));
            settings()->set($settings, $fileName);

            return $fileName;
       }
        }
    }

}
