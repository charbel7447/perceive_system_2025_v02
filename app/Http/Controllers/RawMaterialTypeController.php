<?php

namespace App\Http\Controllers;

use App\RawMaterialType;
use Illuminate\Http\Request;

class RawMaterialTypeController extends Controller
{    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       return api([
           'data' => RawMaterialType::search()
       ]);
   }

   public function search()
   {
       $user = auth()->user();
       $results = RawMaterialType::when(request('q'), function($query) {
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
       $form = array_merge([
           'name' => '',
           'code' => counter()->next('raw_material_type'),
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

       $model = new RawMaterialType;
       $model->fill($request->all());
       counter()->increment('raw_material_type');
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
       $machine = RawMaterialType::findOrFail($id);
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
           'form' => RawMaterialType::findOrFail($id)
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
       $model = RawMaterialType::findOrFail($id);
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
       $model = RawMaterialType::findOrFail($id);
           $model->delete();
           return api([
               'deleted' => true
           ]);
   }
}
