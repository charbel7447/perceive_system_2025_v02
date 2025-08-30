@extends('layout.master')
@section('parentPageTitle', 'Pages')
@section('title', config('app.name').' Import Tools')



@section('content')
<?php
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="row">
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import Products  With Excel to Database
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/products_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools_template_products.xlsx">Excel Template</a>
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import Clients Info With Excel to Database
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/clients_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools_template_clients.xlsx">Excel Template</a>
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import Active Code
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/products_import2" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                   
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import Images Table
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/products_import_images" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                   
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import Suppliers
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/suppliers_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                   
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import client_balances
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/client_balances_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                   
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            <div class="container mt-5 text-center">
                <h4 class="mb-4">
                    Import suppliers_items
                </h4>
                <form action="{{ url('/') }}/import_tools/perceive_system/suppliers_items" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="line-height: 1.3;">Import data</button>
                   
                    <a class="btn btn-primary" style="line-height: 1.3;" href="{{ url('/') }}/import_tools">Clear Data</a>
                </form>
                
            </div>
        </div>
        
</div>

</body>
</html>
@endsection
