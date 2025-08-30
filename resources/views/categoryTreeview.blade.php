<!DOCTYPE html>
<html>
<head>
	<title>Category Treeview</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="{{url('/')}}/css/treeview.css" rel="stylesheet">
</head>
<body>
	<div class="container">     
		<div class="panel panel-primary">
			<div class="col col-md-6 panel-heading">Manage Category TreeView</div>
			<div class="col col-md-6 panel-heading" style="text-align: right;
			color: #fff !important;"><a style="text-align: right;
			color: #fff !important;" href="{{url('/')}}">Back Home</a></div>
	  		<div class="panel-body">
	  			<div class="row">
	  				<div class="col-md-12">
	  					<h3>Category List</h3>
				        <ul id="tree1" style="display: none">
				            @foreach($categories as $category)
				                <li><i class="fa fa-plus"></i>
				                   {{ $category->name }}
								   <a href="{{url('/')}}/categories/{{ $category->id }}"></a> 
				                    @if(count($category->childs))
				                        @include('manageChild',['childs' => $category->childs])
				                    @endif
				                </li>
				            @endforeach
				        </ul>
						<ul id="" style="padding: 0;">
				            @foreach($categories as $category)
				                <li class="manage-li">
				                  
								   <a class="" href="{{url('/')}}/categories/{{ $category->id }}/edit">{{ $category->number }} - {{ $category->name }}</a> 
								   <a class="manage-categ" href="{{url('/')}}/categories/{{ $category->id }}/edit">Total Products</a> 
								   <?php $subcategories = App\SubCategory::where('parent_id','=',$category->id)->get(); ?>
								 <ul>
									@foreach($subcategories as $subcategory)
											<li class="manage-li1">
												<a href="{{url('/')}}/subcategories/{{$subcategory->id}}/edit" target="_blank">{{$subcategory->number}} -- {{$subcategory->name}}</a>
													<?php $getProducts = App\Product\Product::where('sub_category_id','=',$subcategory->id)->get(); ?>
													@foreach($getProducts as $getProduct)
															<li class="manage-li2">{{$getProduct->code}} - {{$getProduct->description}}
																<span style="float: right;">{{$getProduct->qty_on_hand}}</span>
															</li>
													@endforeach
											</li>
								   @endforeach
								 </ul>
				                </li>
				            @endforeach
				        </ul>
	  				</div>
	  				
	  			</div>

	  			
	  		</div>
        </div>
    </div>
    <script src="{{url('/')}}/js/treeview.js"></script>
</body>
</html>
<style>
.glyphicon-minus-sign:before {
    content: "\f067" !important;
}
.manage-categ {
	float: right;
}
.manage-li {
	background: #ddd;
    padding: 10px;
	margin: 10px 0;
	list-style: none;
    border: 1px solid #333;
}
.manage-li1 {
    padding: 10px 0;
}

.manage-li2 {
    padding: 10px 0;
	margin: 0 30px;
}
</style>