@extends('adminlte::page')
@section('content')
<div class="box">
   <div class="box-header with-border">
      <h3 class="box-title">
         <strong>Edit Condition</strong>
      </h3>
      <a class="btn btn-navbar" style="    margin: 0 10px;    float: right; background-color:#3c8dbc;color: #fff; border-radius: 5px;" href="{{route('condition.index')}}">Go Back</a>
      <hr>
      <form method="POST" action="{{ route('condition.update',$condition) }}" enctype="multipart/form-data">
         {{method_field('patch')}}
         {{ csrf_field() }}
         <div class="col-md-12">
            <div class="form-group">
               <label for="title">Condition Title</label>
               <textarea class="ckeditor form-control" name="name" id="name">{{ $condition->name }}
               </textarea> </br>
            </div>
            <div class="form-group col-md-2" style="text-align: center;margin: 0% 0 0 0 ;">
               <button type="submit" class="btn btn-primary" style="margin-bottom: 10%;">Publish</button>
            </div>
         </div>
      </form>
   </div>
</div>
</div>
<script src="{{url('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<!--<script src="//cdn.ckeditor.com/4.7.0/full/ckeditor.js"></script>-->
<style>
   .cke_contents {height: 100x !important;}
</style>
@endsection