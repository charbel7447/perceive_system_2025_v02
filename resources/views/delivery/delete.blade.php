@extends('adminlte::page')

@section('content')
 

 <div class="box">
            <div class="box-header with-border">

        <h3 class="box-title"><strong>Delete Product</strong></h3>
                  <a class="btn btn-navbar" style="    margin: 0 10px;    float: right; background-color:#3c8dbc;color: #fff; border-radius: 5px;" href="{{route('sweet.index')}}">Go Back</a>

     
                  <hr> 
              
                  <form method="POST" action="{{ route('sweet.destroy',$sweets) }}" enctype="multipart/form-data">
                  {{method_field('delete')}}
                     {{ csrf_field() }}
                     <p>Are you sure? you want to delete <a >{{$sweets->title}}</a> ?</p>
                
                <div class="col-md-12"> 
                  <div class="col-md-4" style="margin: 2% auto;">
                   <img style="width: 100%" src="{{url('/storage')}}/sweet/{{$sweets->image}}"> </br>
                   </div>
                 <div class="col-md-8" style="margin: 2% auto;">
                     <label>Product ID : </label> {!! $sweets->id !!}</br>
                     <label>Product Title : </label> {!! $sweets->title !!} </br>
                   <label>Product Body: </label> {!! $sweets->body!!} </br>
                    
     

                       <div class="form-group col-md-12" style="text-align: center;margin:1% 0 0 0 ;">
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 10%;">Delete</button>
                     </div> 
</div>
                
                  
            
                        </div> 


                       
            
                        
                    
               
                     
                  </form>
               </div> 
            </div>
            
  
 </div>
      <script src="vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
      
    </script>
<style>
  .cke_contents {height: 100x !important;}
</style>
@endsection
