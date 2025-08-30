@extends('docs.master', ['title' => 'Categories Report '])


@section('content')

  <?php
   $items = Storage::disk('local')->allfiles();
 ?>
@foreach($items as $key => $node)
        {{$node}}
@endforeach