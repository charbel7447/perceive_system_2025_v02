<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attributes\Attributes;
use App\Attributes\Item;
use DB;
use Auth;
use PDF;

class AttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        return api([
            'data' => Attributes::with(['items'])->search()
        ]);
    }

    public function search(Request $request)
    {
            $results = Attributes::with('items')
                ->orderBy('id')
                ->when(request('q'), function($query) {
                    $query->where('id', 'like', '%'.request('q').'%')
                        ->orWhere('description', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get();
                return api([
                    'results' => $results
                ]);

            
            //  $results = DB::table('attributes')
            //     ->select(
            //         'attributes.id', 'attributes.description',
            //         DB::raw('concat(attributes.id, " - ", attributes.description) as text'),
            //         DB::raw('concat(attributes_value.attribute_value) as text2'),
            //         'attributes_value.attribute_id as attribute_id'
            //     )
            //     ->orderBy('attributes.id')
            //     // ->join('attributes_value', 'attributes.id', '=', 'attributes_value.attribute_id')
            //     // ->join('attributes', 'attributes.id', '=', 'attributes_value.attribute_id')
            //     ->join('attributes_value','attributes_value.attribute_id', '=', 'attributes.id')
            //     ->where(function($query) {
            //         $query->where('attributes.description', 'like', '%'.request('q').'%');
            //     })
            //     ->limit(6)
            //     ->get();  
                
            //     return api([
            //     'results' => $results
            //      ]);

       


    }


    public function searchvalue (Request $request)
    {
            $results = Item::with('items')
            ->orderBy('id')
                ->when(request('q'), function($query) {
                    $query->where('id', 'like', '%'.request('q').'%')
                        ->orWhere('attribute_value', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get(['id','attribute_value', 'created_at']);
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
        
        $form = array_merge([
            'description' => '',
            'number' => counter()->next('attributes'),
            'items' => [],
        ]);

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
        $user = auth()->user();
        $request->validate([
            'description' => 'required|max:2000',
            'items' => 'sometimes|array',
            'items.*.attribute_value' => 'nullable',
        ]);

        $model = new Attributes;
        $model->fill($request->except('items'));

        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $result = DB::transaction(function() use ($model, $request) {
            $model->storeHasMany([
                'items' => $request->items,
            ]);
            counter()->increment('attributes');
            return $model;
        });

        return api([
            'saved' => true,
            'id' => $result->id
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
        $data = Attributes::with([
           'items'
        ])->findOrFail($id);

        return api([
            'data' => $data
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
        $form = Attributes::with(['items'])
            ->findOrFail($id);

        return api([
            'form' => $form
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
        $model = Attributes::findOrFail($id);

        $request->validate([
            'description' => 'required|max:2000',
            'items' => 'sometimes|array',
            'items.*.attribute_value' => 'nullable',
        
        ]);

        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;

        $result = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items' => $request->items,
            ]);

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $result->id
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
        $model = Attributes::findOrFail($id);

        // check whether this particular product belongs to

        // quotation
        $quotations = DB::table('items_items')
            ->where('attribute', $model->id)->count();

        if($quotations ) {
            return api([
                'message' => 'Delete all the Material relations with this attribute first',
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
