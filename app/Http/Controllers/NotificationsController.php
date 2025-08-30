<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications;
use App\User;
use DB;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        return api([
            'data' => Notifications::where('manager_id','=',$manager_id)->where('viewed','=',null)->search()
        ]);
    }

    public function search()
    {
        $user = auth()->user();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');

        $results = Notifications::all();

        return api([
                'results' => $results
        ]);
    }

    public function markAs(Request $request)
    {
        // dd($request);

        // $model = Notifications::where('id','=',$request->status)->get();

        DB::table('notifications')
                    ->where('id', $request->status)
                    ->update(['viewed' => 1]);

        return \Redirect::back()->with('message','Operation Successful !');
     
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
