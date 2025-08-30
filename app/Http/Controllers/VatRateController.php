<?php

namespace App\Http\Controllers;
use Auth;
use App\VatRate\VatRate;
use Illuminate\Database\Eloquent;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use DB;
use App\Currency;
class VatRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_exchangerate_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        if (Auth::check()) {
            return api([
                'data' => VatRate::with(['currency','currency1'])->search()
            ]);}
        }
    }

    public function search()
    {
        $results = VatRate::
            orderBy('id')
            ->when(request('q'), function($query) {
                $query->where('currency1', 'like', '%'.request('q').'%')
                    ->orWhere('currency2', 'like', '%'.request('q').'%')
                    ->orWhere('exchangedate', 'like', '%'.request('q').'%')
                    ->orWhere('value1', 'like', '%'.request('q').'%')
                    ->orWhere('value2', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'currency1', 'currency2','value1','value2','exchangedate','created_by','created_at']);

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
        if ($user->is_exchangerate_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'value2' => '',
            'value1' => '',
            'exchangedate' => date('Y-m-d'),
            'currency1_id' => '',
            'currency2_id' => '',
            'currency2' => ''
        ],
            currency()->defaultToArray()
        );

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
        if ($user->is_exchangerate_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'value2' => 'required|max:255',
            'value1' => 'nullable|max:255',
            'exchangedate' => 'required',
            'currency1_id' => 'nullable|integer|exists:currencies,id',
            'currency2_id' => 'required|integer|exists:currencies,id'
        ]);
        $model = new VatRate();
        // $model->currency1 = $request->currency1_id;
        $model->currency1 = $request->currency_id;
        $model->currency2 = $request->currency2_id;
        // $model->value1 = $request->value1;
        $model->value1 = 1;
        $model->value2 = $request->value2;
        $model->exchangedate = $request->exchangedate;
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
        if ($user->is_exchangerate_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $exchangerate = VatRate::findOrFail($id);
        return api([
            'data' => $exchangerate
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
        //
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
        //
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
        if ($user->is_exchangerate_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = VatRate::findOrFail($id);

        $model->delete();

            return api([
                'deleted' => true
            ]);
        }
    }
}
