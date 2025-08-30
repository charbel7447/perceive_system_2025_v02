<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use Auth;
use App\UserCategory;
use App\Product\Product;

class CategoryTreeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageCategory()
    {
        $user = auth()->user();
        if ($user->is_categories_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
  
            $categories = Category::where('parent_id', '=', 0)->get();
            return view('categoryTreeview',compact('categories'));
        }
    }

    public function minimum_stock()
    {
        $user = auth()->user();
        $categ1 = UserCategory::where('user_id','=',$user->id)->get();
        $items = array(); 
        foreach ($categ1 as $categ2)
        $items[] = $categ2->category_id;
        {
            $products = Product::whereIn('category_id',$items)->get();
            return view('docs.minimum_stock',compact('products'));
        } 
      
           
    }
    

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCategory(Request $request)
    {
        $this->validate($request, [
        		'name' => 'required',
        	]);
        $input = $request->all();
        $input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];
        
        $model = new Category;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->save();

        return back()->with('success', 'New Category added successfully.');
    }
}
