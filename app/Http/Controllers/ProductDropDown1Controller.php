<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use App\Product\ProductDropDown1;
use Illuminate\Database\Eloquent;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Input;

use DB;


class ProductDropDown1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_uom_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            if (Auth::check()) {
                return api([
                    'data' => ProductDropDown1::search()
                ]);}
        }
    }

    public function search()
    {
        $results = ProductDropDown1::
            orderBy('unit')
            ->when(request('q'), function($query) {
                $query->where('unit', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'unit','created_by','created_at']);

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
        if ($user->is_uom_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = [
            'name' => null,
        ];

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
        if ($user->is_uom_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $model = new ProductDropDown1;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model ->created_by = $username;
         $model ->name = $request->name;
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
        if ($user->is_uom_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $uom = ProductDropDown1::findOrFail($id);
            return api([
                'data' => $uom,
            ]);
        }
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
        if ($user->is_uom_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => ProductDropDown1::findOrFail($id)
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
        if ($user->is_uom_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = ProductDropDown1::findOrFail($id);

            $request->validate([
                'name' => 'required|max:255',
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
        if ($user->is_uom_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = ProductDropDown1::findOrFail($id);

            $model->delete();

            return api([
                'deleted' => true
            ]);
        }
    }

   
}
