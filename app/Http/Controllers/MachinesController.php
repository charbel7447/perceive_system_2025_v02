<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Machines\Machines;

use App\Machines\Settings as MachineSettings;
use DB;
use Auth;

class MachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return api([
            'data' => Machines::search()
        ]);
    }

    public function search()
    {
        $user = auth()->user();
        $results = Machines::with(['settings'])->when(request('q'), function($query) {
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
            'speed' => 0,
            'code' => counter()->next('machines'),
            'settings' => [],
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
            'settings' => 'sometimes|array',
        ]);

        $model = new Machines;
        // $model->fill($request->all());
        $model->fill($request->except('settings'));
        counter()->increment('machines');
        // $model->save();
        $result = DB::transaction(function() use ($model, $request) {
            $model->storeHasMany([
                'settings' => $request->settings,
            ]);
            counter()->increment('machines');
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
        $machine = Machines::with(['settings'])->findOrFail($id);
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
            'form' => Machines::with(['settings'])->findOrFail($id)
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
        $model = Machines::findOrFail($id);
            $request->validate([
                'name' => 'nullable|max:255',
                'code' => 'nullable|max:255',
                'settings' => 'sometimes|array',
            ]);

            // $model->fill($request->all());
            $model->fill($request->except('items', 'taxes','settings'));
            // $model->save();
            $result = DB::transaction(function() use ($model, $request) {

                $model->updateHasMany([
                    'settings' => $request->settings
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
        $model = Machines::findOrFail($id);
            $model->delete();
            return api([
                'deleted' => true
            ]);
    }
}
