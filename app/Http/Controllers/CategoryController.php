<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\UserCategory;
use DB;
use Auth;
use App\Product\Item;
use App\Product\Product;
use PDF;
// use App\Api\Categories\Categories;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_categories()
    {
        return api([
            'data' => Category::get()
        ]);

    }
    
    public function index()
    {
        $user = auth()->user();
        if ($user->is_categories_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Category::search()
            ]);
        }
    }


    public function search()
    {
        $user = auth()->user();
        // $categ1 = UserCategory::where('user_id','=',$user->id)->get();
        // $items = array(); 
        // foreach ($categ1 as $categ2)
        // $items[] = $categ2->category_id;
        // {
        $results = Category::orderBy('order','asc')
            //  ->whereIn('id',$items)
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('number', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(30)
            ->get(['id', 'name', 'number', 'description']);
        // }
        return api([
            'results' => $results
        ]);
    }


    public function searchall()
    {
        $results = Category::orderBy('order','asc')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('number', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(30)
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
        if ($user->is_categories_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
    
            $form = array_merge([
                'name' => '',
                'number' => counter()->next('category'),
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
        if ($user->is_categories_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
     
            $request->validate([
                'name' => 'required|max:255',
                'number' => 'nullable|max:255',
                'parent_id' => 'nullable',
                'description' => 'nullable'
            ]);

            $model = new Category;
            $model->fill($request->all());
            // $model->user_id = auth()->id();
            $username = Auth::user()->name;
            // $model ->created_by = $username;

            $last_number = Category::orderby('id','desc')->value('id');
            $model ->title = $request->name;
            $model ->status = $request->status;
            $model ->featured = 1;
            $model ->number = $last_number + 1;
            $model ->title = $request->name;
            $model ->description = $request->description;
            $model ->name = $request->name;
            $model ->image = null;
            $model ->order = $request->order;
            

            
            $result = DB::transaction(function() use ($model, $request) {
                    // $model->number = counter()->next('category');
                    counter()->increment('category');
        
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
        if ($user->is_categories_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
      
            $category = Category::findOrFail($id);
            return api([
                'data' => $category
            ]);
        }
    }

    public function showProducts($id)
    {
        $category = Category::findOrFail($id);

        $model = $category->products()
            ->with(['product', 'currency','uom','vendor','vendor1','warehouse','product.vendor'])
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
        if ($user->is_categories_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
     
            return api([
                'form' => Category::with(['parent'])->findOrFail($id)
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
        if ($user->is_categories_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Category::findOrFail($id);

            $request->validate([
                'name' => 'nullable|max:255',
                'number' => 'nullable|max:255',
                'parent_id' => 'nullable',
                'description' => 'nullable'
            ]);

            // $model->fill($request->all());
            // $username = Auth::user()->name;
            // $model ->created_by = $username;
            // $model->save();

            $model ->title = $request->name;
            $model ->status = $request->status;
            $model ->featured = 1;
            $last_number = Category::orderby('id','desc')->value('id');
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
        if ($user->is_categories_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
      
            $category = Category::findOrFail($id);
            $products = Product::with(['items','items.vendor'])->where('category_id','=',$id)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.categories', compact('category','products'));
            return $pdf->download(now().'--'.'--category.pdf');
            // return view('docs.warehouse', compact('warehouse','products'));
            return redirect()->back();
        }
    }
      
    public function pdfgeneral()
    {
        $user = auth()->user();
        if ($user->is_categories_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $category = Category::all();
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
        if ($user->is_categories_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
   
            $model = Category::findOrFail($id);
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
