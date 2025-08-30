@extends('email.master')

@section('content')
<?php

    $requester = App\User::where('id','=',$data->user_id)->value('name');
    $manager_id= App\User::where('id','=',$data->user_id)->value('manager_id');
    $manager = App\User::where('id','=',$manager_id)->value('name');

?>

<pre>Dear Mr. {{$manager}},</pre>
<pre>For your Approval Attached Sales Order Request,</pre>
<pre>Doc. Number {{$data['number']}} waiting your approval - created by {{$requester}} at ({{$data->request_date}})</pre>


<a href="{{url('/')}}/sales_orders/{{$data['id']}}">Link Here </a> 

@endsection
