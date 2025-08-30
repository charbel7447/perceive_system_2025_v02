<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use App\DeliveryCondition;
use Illuminate\Database\Eloquent;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Input;

use DB;


class DeliveryConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_deliverycondition_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        if (Auth::check()) {
            return api([
                'data' => DeliveryCondition::search()
            ]);}
        }
    }

    public function search()
    {
        $results = DeliveryCondition::orderBy('name')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'name','created_by','created_at']);

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
        if ($user->is_deliverycondition_create == 0 && $user->is_admin != 1){
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
        if ($user->is_deliverycondition_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $model = new DeliveryCondition;
        $model->fill($request->all());
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_deliverycondition_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $delivery = DeliveryCondition::findOrFail($id);
        return api([
            'data' => $delivery,
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
        if ($user->is_deliverycondition_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'form' => DeliveryCondition::findOrFail($id)
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
        if ($user->is_deliverycondition_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = DeliveryCondition::findOrFail($id);

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
        if ($user->is_deliverycondition_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = DeliveryCondition::findOrFail($id);

        $model->delete();

        return api([
            'deleted' => true
        ]);
    }
    }

    
}
