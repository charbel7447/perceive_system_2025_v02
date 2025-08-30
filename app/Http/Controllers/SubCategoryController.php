<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SubCategory;
use App\UserCategory;
use DB;
use Auth;
use App\Product\Item;
use App\Product\Product;
use PDF;
use App\Support\Counter;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_subcategories_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => SubCategory::search()
            ]);
        }
    }


    public function search()
    {

        $user = auth()->user();
        // $categ1 = UserCategory::where('user_id','=',$user->id)->get();
        $items = array(); 
        // foreach ($categ1 as $categ2)
        // $items[] = $categ2->sub_category_id;
        // {
        if(request()->has('parent_id')) {
            $results = DB::table('categories')
                ->select(
                    'sub_category.id', 'categories.name','categories.number',
                    DB::raw('concat(sub_category.number, " - ", sub_category.name) as text'),
                    'sub_category.name as sub_category_name', 'sub_category.parent_id'
                )
                ->join('sub_category', 'categories.id', '=', 'sub_category.parent_id')
                ->where('sub_category.parent_id', '=', request('parent_id'))
                // ->whereIn('sub_category.id',$items)
                ->where(function($query) {
                    $query->where('categories.number', 'like', '%'.request('q').'%')
                        ->orWhere('categories.name', 'like', '%'.request('q').'%');
                })
                ->orderby('order','asc')
                ->limit(30)
                ->get();
        } 
        elseif(request()->has('category_id')) {
            $results = DB::table('categories')
                ->select(
                    'sub_category.id', 'categories.name','categories.number',
                    DB::raw('concat(sub_category.number, " - ", sub_category.name) as text'),
                    'sub_category.name as sub_category_name', 'sub_category.parent_id'
                )
                ->join('sub_category', 'categories.id', '=', 'sub_category.parent_id')
                ->where('sub_category.parent_id', '=', request('category_id'))
                // ->whereIn('sub_category.id',$items)
                ->where(function($query) {
                    $query->where('categories.number', 'like', '%'.request('q').'%')
                        ->orWhere('categories.name', 'like', '%'.request('q').'%');
                })
                ->orderby('order','asc')
                ->limit(30)
                ->get();
        } 
        else {
        $results = SubCategory::with(['parent'])->orderBy('order','asc')
        // ->whereIn('id',$items)
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('number', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(30)
            ->get(['id', 'name', 'number', 'description']);
        }
    // }
        return api([
            'results' => $results
        ]);
    }


    
    public function searchall()
    {
        if(request()->has('category_id')) {
            // DB::table('test4')
            //     ->where('id', 1)
            //     ->update(['body' => request('sub_categoryid')]);

            $results = SubCategory::orderBy('order','asc')
            ->where('category_id','=',request('category_id'))
            ->limit(30)
            ->get(['id', 'name', 'number', 'description']);

        }else{
            $results = SubCategory::orderBy('order','asc')
                ->when(request('q'), function($query) {
                    $query->where('name', 'like', '%'.request('q').'%')
                        ->orWhere('number', 'like', '%'.request('q').'%')
                        ->orWhere('description', 'like', '%'.request('q').'%');
                })
                ->limit(30)
                ->get(['id', 'name', 'number', 'description']);
        }
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
        if ($user->is_subcategories_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
    
            $form = array_merge([
                'name' => '',
                'number' => counter()->next('subcategory'),
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
        if ($user->is_subcategories_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
      
            $request->validate([
                'name' => 'required|max:255',
                'number' => 'nullable|max:255',
                'parent_id' => 'required',
                'description' => 'nullable'
            ]);

            $model = new SubCategory;
            $model->fill($request->all());
            // $model->user_id = auth()->id();
            $username = Auth::user()->name;
            // $model ->created_by = $username;

            $last_number = SubCategory::orderby('id','desc')->value('id');
            $model ->title = $request->name;
            $model ->status = $request->status;
            $model ->category_id = $request->category_id;
            $model ->number = $last_number + 1;
            $model ->title = $request->name;
            $model ->description = $request->description;
            $model ->name = $request->name;
            $model ->image = null;
            $model ->order = $request->order;
            
            
            $result = DB::transaction(function() use ($model, $request) {
                    //  $lastCategNumb = Category::where('id','=',$request->parent_id)->take(1)->latest()->value('number');
                    // $model->number = $lastCategNumb.'-'.counter()->next('subcategory');
                    counter()->increment('subcategory');
        
                    return $model;
                });
                

            $model->save();
       

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
        if ($user->is_subcategories_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
     
            $category = SubCategory::findOrFail($id);
            return api([
                'data' => $category
            ]);
        }
       
    }

    public function showProducts($id)
    {
        $category = SubCategory::findOrFail($id);

        $model = $category->products()
            ->with(['product', 'currency','uom','vendor','vendor1','warehouse','category','product.vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return api([
            'model' => $model
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
        if ($user->is_subcategories_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
      
            return api([
                'form' => SubCategory::with(['parent'])->findOrFail($id)
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
        if ($user->is_subcategories_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = SubCategory::findOrFail($id);

        $request->validate([
            'name' => 'nullable|max:255',
            'number' => 'nullable|max:255',
            'parent_id' => 'nullable',
            'description' => 'nullable'
        ]);

            $model->fill($request->all());
            // $model->user_id = auth()->id();
            $username = Auth::user()->name;
            // $model ->created_by = $username;

            $last_number = SubCategory::orderby('id','desc')->value('id');
            $model ->title = $request->name;
             $model ->status = $request->status;
            $model ->category_id = $request->category_id;
            // $model ->number = $last_number + 1;
            $model ->title = $request->name;
            $model ->description = $request->description;
            $model ->name = $request->name;
            // $model ->image = null;
            $model ->order = $request->order;
            $model->save();

     
             
                return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_subcategories_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $category = SubCategory::findOrFail($id);
            $products = Product::with(['items','items.vendor'])->where('sub_category_id','=',$id)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.sub_categories', compact('category','products'));
            return $pdf->download(now().'--'.'--sub_category.pdf');
            // return view('docs.warehouse', compact('warehouse','products'));
            return redirect()->back();
        }
        
    }
      
    public function pdfgeneral()
    {
        $user = auth()->user();
        if ($user->is_subcategories_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        
            $category = SubCategory::all();
            $products = Items::all();
            $productsItem = Item::all();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.categoriesgeneral', compact('category','products','productsItem'));
            return $pdf->download(now().'--'.'--category.pdf');
            //return view('docs.warehousegeneral')
            //->with(compact('warehouses'))->with(compact('products'))->with(compact('productsItem'));
            return redirect()->back();
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
        if ($user->is_subcategories_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        
            $model = SubCategory::findOrFail($id);
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
}
