@extends('email.master')

@section('content')
<?php

    $requester = App\User::where('id','=',$data->user_id)->value('name');
    $manager_id= App\User::where('id','=',$data->user_id)->value('manager_id');
    $manager = App\User::where('id','=',$manager_id)->value('name');

?>

<pre>Dear Mr. {{$requester}},</pre>
<pre>Supplier Invoice Number {{$data['number']}} declined by {{$manager}} at ({{$data->declined_date}})</pre>


<a href="{{url('/')}}/bills/{{$data['id']}}">Link Here </a> 

@endsection
