@extends('docs.master')
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>Main Report Per Client</title>
      <!-- import plugin script -->
   </head>
   <body>
      {{-- @extends('adminlte::page') --}}
      @section('content')
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/all.min.css') }}">
      <!-- Ionicons -->
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
      <div class="col-md-12 box box-primary">
         <a href="{{url('/')}}" class="form-control">Back Home</a>
      </div>
      <div class="col-md-12 box box-primary" style="background-color: #ecf0f5;">
        <h4 class="box-title">Invoices Status</h4>
        <div class="box-body">
           {{ $InvoiceTable }}
        </div>
     </div>

     @endsection

     <style>
      .center {text-align: center !important;}
   </style>
   <style>
      .content {
      padding-top: -30px !important;
      }
      /*   .header {
      position: fixed;
      left: 0;
      top: 170px;
      padding: 10px 10px 0px 10px;
      color: #777;
      width: 100%;
      text-align: center;
      }
      .footer {
      position: fixed;
      left: 0;
      bottom: -50px;
      color: #777;
      width: 100%;
      text-align: center;
      }*/
      .doc-logo {
      width: 140px;
      margin-top: -40px;
      text-align: left;
      float: left; 
      }
      body {
      font-family: sans-serif;
      font-size: 9pt;
      color: #484746;
      }
      pre {
      font-family: sans-serif;
      }
      table {
      border-spacing: 0;
      width: 100%;
      border-collapse: collapse;
      }
      th,
      td{
      font-weight: normal;
      vertical-align: top;
      text-align: left;
      }
      .header-logo {
      width: 25%;
      text-align: right;
      }
      .header-company_name {
      font-size: 18pt;
      font-weight: bold;
      margin-bottom: 5px;
      }
      .content {
      }
      .content-title {
      margin-top: 71px !important;
      margin-bottom: 20px;
      padding: 5px;
      text-align: center;
      font-size: 12pt;
      font-weight: bold;
      border: 0.1mm solid #484746;
      }
      .document-blue {
      color: #3aa3e3;
      }
      .document-orange {
      color: #FF9800;
      }
      .document-red {
      color: #E75650;
      }
      .document-blue_light {
      color: #48606f;
      }
      .document-green {
      color: #66bb6a;
      }
      .summary-address {
      width: 33.333%;
      }
      .summary-addressx {
      width: 33.333%;
      }
      .summary-empty {
      width: 33.333%;
      }
      .summary-info {
      width: 33.333%;
      }
      .info td {
      text-align: right;
      }
      .info td:nth-child(2n) {
      padding-left: 15px;
      }
      .items {
      margin-top: 20px;
      border: 0.1mm solid #484746;
      }
      .items thead th {
      padding: 6px 3px;
      background:#ecf0f5;
      border: 0.1mm solid #484746;
      }
      .items tbody td {
      border: 0.1mm solid #ecf0f5;
      padding: 3px;
      }
      .items tfoot td {
      background: #f1f1f1;
      border: 0.1mm solid #484746;
      text-align: right;
      padding: 4px 3px;
      }
      .item-empty {
      }
      .ar {
      text-align: right;
      }
      .ac {
      text-align: center;
      }
      .terms {
      margin-top: 20px;
      }
      .terms-description {
      width: 70%;
      }
      .footer {
      text-align: center;
      bottom:0 !important;
      position: fixed;
      }
      .header {
      position: fixed;
      left: 0;
      top: 50px;
      color: #777;
      width: 100%;
      text-align: center;
      }
      .footer {
      position: fixed;
      left: 0;
      bottom: -50px;
      color: #777;
      width: 100%;
      text-align: center;
      }
      .box.box-primary {
      border-top-color: #ccd0d5 !important;
      margin: 0 !important;
      padding: 20px 15px !important;
      }
   </style>