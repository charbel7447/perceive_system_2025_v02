<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DB;
use Auth;
use App\PurchaseOrder\PurchaseOrder;
use App\PurchaseRequest\PurchaseRequest;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_employees_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Employee::with('currency')->search()
            ]);
        }
    }




    public function search()
    {
        $results = Employee::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                $query->where('company', 'like', '%'.request('q').'%')
                    ->orWhere('name', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'name', 'company', 'salary','currency_id']);

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
        if ($user->is_employees_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $company =  DB::table('settings')
            ->where('id','=','4')
            ->value('value');

            $form = array_merge([
                'name' => '',
                'title' => '',
                'company' => $company,
                'email' => '',
                'telephone' => '',
                'extension' => '',
                'mobile_number' => '',
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
        if ($user->is_employees_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'name' => 'required|max:255',
                'title' => 'required|max:255',
                'company' => 'required|max:255',
                'salary' => 'nullable|max:255',
                'email' => 'nullable|email|max:255',
                'telephone' => 'nullable|max:255',
                'extension' => 'nullable|max:255',
                'mobile_number' => 'nullable|max:255',
            ]);

            $model = new Employee;
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
        if ($user->is_employees_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $employee = Employee::with(['currency'])->findOrFail($id);
            return api([
                'data' => $employee,
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
        if ($user->is_employees_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Employee::with(['currency'])->findOrFail($id)
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
        if ($user->is_employees_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Employee::findOrFail($id);
            $request->validate([
                'name' => 'nullable|max:255',
                'title' => 'nullable|max:255',
                'company' => 'nullable|max:255',
                'email' => 'nullable|email|max:255',
                'salary' => 'nullable|max:255',
                'telephone' => 'nullable|max:255',
                'extension' => 'nullable|max:255',
                'mobile_number' => 'nullable|max:255',
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
        if ($user->is_employees_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Employee::findOrFail($id);
            $model->delete();
            return api([
                'deleted' => true
            ]);
        }
    }
}
