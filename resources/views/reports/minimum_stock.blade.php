@extends('layout.master')
@section('parentPageTitle', 'Pages')
@section('title', 'Minimum Stock - Report')



@section('content')
<?php
use Carbon\Carbon;
?>

<!-- Page header section  -->
<div class="">
    <div class="col-lg-12 col-md-12">
    <div class="section_title">
            <div class="mr-3">
               <div class="row">
                  <div class="col-auto">
                     <div class="stamp stamp-md bg-warning">
                        <i class="fa fa-shopping-cart" style="font-size: 15px;"></i>
                     </div>
                  </div>
                  <div class="col">
                     <h3> @yield('title') </h3>
                     <small>Statistics, Predictive Analytics Data Visualization, Big Data Analytics, etc.</small>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="" style="width: 100%;padding-bottom: 20px;">
      <div class="col-lg-12 col-md-12">
            <div class="body" style="padding-top: 0;padding: 0;">
                <div class="">
                    <div style="width: 100%;">
                        <?php $min_products = \App\Product\Product::where('current_stock','<',1)->get(); ?>
                        <table class="items">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($min_products as $min_product)
                            <tr>
                                @if($min_product->current_stock <= $min_product->minimum_qty)
                                    <li>{{$min_product->code}}</li>
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <style>
.input-group-text, .input-group>.form-control, select.form-control:not([size]):not([multiple]) {
  font-size: 12px;
}
  
     .btn-primary {
    border-radius: 5px !important;
   }

   btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #59C4BC;
    border-color: #59C4BC;
}

.input-group-prepend > span, .input-group-prepend > label  {
      background: #e9ecef !important;
}
.form-control {
  background: #ddd !important;
}
textarea.form-control {
/*    height: 40px;
    padding: 10px 10px 0 10px;*/
    resize: none;
}
.needed-packages {
  font-size: 10px !important;
}
.cliche-details > input {
      border: 1px solid #fff !important;
}
  .ellipsis {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
}
   .canvasjs-chart-credit {
    display: none;
}
th  {
    font-weight: 600 !important;
    font-size:12px !important;
}
.td-ac {
    font-size:10px !important;
}
.td-ac-cur {
    font-size:12px !important;
    border: 1px solid #dedede;
    padding: 5.6px 6px;
}

    
.table tr td, .table tr th
{
    font-size:10px !important;
    padding: 8px;
}

.po-total, .po-subtotal {
    color:#59C4BC;
    font-weight:600;
}
.job-view {
    background: #59C4BC;
    padding: 5px;
    border-radius: 5px;
    color: #fff;
}
.job-view:hover {
    color:#59C4BC !important;font-weight:600;
    background: #fff !important;
    border: 1px solid #59C4BC;
}
.po-payment-condition {
    /* font-size: 9px !important; */
}

.page-link {
    position: relative;
    display: block;
    font-size: 10px !important;
}
#main-content {
    padding: 0 10px !important;
}
.card .body {
    padding: 10px 15px !important;
}
</style>
<style>
   .td-ac  {
   padding: 5px 5px 0 5px;
   }
   .th-ac  {
   padding: 5px 5px 0 5px;
   }
</style>

<style>
    .approved_line {text-decoration: line-through;}
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
        margin-bottom: 20px;
        padding: 5px;
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        border: 0.1mm solid #dedede;
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
        margin-top: 0px;
        border: 0.1mm solid #dedede;
    }
    .items thead th {
        padding: 6px 3px;
        background: #dedede;
        border: 0.1mm solid #dedede;
    }
    .items tbody td {
        border: 0.1mm solid #dedede;
        padding: 3px;
            vertical-align: middle;
    }

    .items tfoot td {
         background: #f1f1f1;
        border: 0.1mm solid #dedede;
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
    }
</style>

    <style>
      .canvasjs-chart-credit {
    display: none;
}
       .content {
       padding-top: -30px !important;
       }
       .header {
       position: fixed;
       left: 0;
       color: #777;
       text-align: right;
       }
       .footer {
       position: fixed;
       left: 0;
       bottom: -20px;
       color: #777;
       width: 100%;
       text-align: center;
       }
    </style>
<link rel="stylesheet" href="{{ asset('') }}">
@stop

@section('vendor-script')
<script src="{{ asset('') }}"></script>
@stop

@section('page-script')
<script src="{{ asset('') }}"></script>
@stop