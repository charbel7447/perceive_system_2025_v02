@extends('layout.master')
@section('parentPageTitle', 'Pages')
@section('title', 'Minimum Stock - Report')



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
                <h2 class="mb-4">
                    Import Clients Info With Excel to Database
                </h2>
                <form action="{{ url('/') }}/import_clients/perceive_system/product_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Import data</button>
                    <a class="btn btn-primary" href="{{ url('/') }}/import_clients_template.xlsx">Excel Template</a>
                    <a class="btn btn-primary" href="{{ url('/') }}/import_clients">Clear Data</a>
                </form>
                
            </div>
        </div>
</div>

</body>
</html>
@endsection