@extends('adminlte::page')
@section('content')
<div class="box">
   <div class="box-header with-border">
      <h3 class="box-title"><strong>Create Delivery Condition</strong></h3>
      <a class="btn btn-navbar" style="    margin: 0 10px;    float: right; background-color:#3c8dbc;color: #fff; border-radius: 5px;" href="{{route('delivery.index')}}">Go Back</a>
     <br>
      <section class="content">
         <div class="row">
            <!-- left column -->
            <div class="col-md-12">
               <!-- general form elements -->
               <div class="box box-primary"  style="margin: 2% 0;">
                  <div class="box-header with-border">
                  </div>
                  <!-- /.box-header -->
                  <!-- form start -->
                  <form method="POST" action="{{ route('delivery.store') }}" enctype="multipart/form-data">
                     {{ csrf_field() }}
                     <div class="box-body">
                        <div class="form-group">
                           <label for="exampleInputEmail1">Delivery Condition Name</label>
                           <textarea type="text" class="ckeditor form-control" name="name" id="name" required   placeholder="Condition %"></textarea>
                        </div>
                     </div>
                     <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                     </div>
               </div>
               <!-- /.box -->
            </div>
            <!-- /.box-body -->
            </form>
         </div>
         <!-- /.box -->
   </div>
</div>
</section>
</div>
</div>
<script src="{{url('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script></script>
<style>
   /* .cke_contents {height: 100x !important;} */
</style>
@endsection