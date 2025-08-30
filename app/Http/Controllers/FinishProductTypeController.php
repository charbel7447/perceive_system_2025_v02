<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FinishedProduct\Type;
use App\Warehouse;
use DB;
use Auth;

class FinishProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return api([
            'data' => Type::search()
        ]);
    }

    public function search()
    {
        $user = auth()->user();
        $results = Type::when(request('q'), function($query) {
             $query->where('code', 'like', '%'.request('q').'%')
                        ->orWhere('name', 'like', '%'.request('q').'%');
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
        $warehouse_name = Warehouse::where('id','=',1)->value('name');
        $form = array_merge([
            'name' => '',
            'code' => counter()->next('product_type'),
            'warehouse' => $warehouse_name,
            'status' => 1
        ],
            currency()->defaultToArray()
        );

        return api([
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:255',
        ]);

        $model = new Type;
        $model->fill($request->all());
        counter()->increment('product_type');
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $machine = Type::findOrFail($id);
        return api([
            'data' => $machine,
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
        return api([
            'form' => Type::findOrFail($id)
        ]);
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
        $model = Type::findOrFail($id);
            $request->validate([
                'name' => 'nullable|max:255',
                'code' => 'nullable|max:255',
            ]);

            $model->fill($request->all());
            $model->save();

            return api([
                'saved' => true,
                'id' => $model->id
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Type::findOrFail($id);
        $model->delete();
        return api([
            'deleted' => true
        ]);
    }
}
