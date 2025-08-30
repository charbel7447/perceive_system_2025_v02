@extends('adminlte::page')
@section('content')
<div class="box">
   <div class="box-header with-border">
      <h3 class="box-title">Delivery Conditions</h3>
      <a class="btn btn-navbar" style="float: right; background-color:#3c8dbc;color: #fff;border-radius: 5px;" href="{{route('delivery.create')}}">Create Condition</a>
      <a class="btn btn-navbar" style="    margin: 0 10px;    float: right; background-color:#3c8dbc;color: #fff; border-radius: 5px;" href="{{route('delivery.index')}}">Reset Search</a>
      <form class="lockscreen-credentials" style="margin: 0% 35% 0 35%;">
         <div class="input-group">
            {!! Form::open(['method'=>'GET','url'=>'delivery','class'=>' search-form  ','role'=>'search'])  !!}
            <input type="text" style="    border: 1px solid #d4d8df;" class="form-control form-control-navbar" name="search"  type="search" placeholder="Search delivery condition ..." aria-label="Search">
            <div class="input-group-btn">
               <button style="background-color:#3c8dbc;height: 32px;" class="btn btn-navbar" type="submit"><i style="    color: #fff !important;" class="fa fa-search text-muted"></i></button>
            </div>
            {!! Form::close() !!}
         </div>
      </form>
   </div>
   <!-- /.box-header -->
   <div class="box-body">
      {{ $tableView }}
   </div>
</div>
@endsection