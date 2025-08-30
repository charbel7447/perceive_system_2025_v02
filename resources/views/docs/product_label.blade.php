<?php
    use Illuminate\Support\Str;
?>
<table style="margin: 0 5px 0 0;">
    <tbody>
        <tr>
            <td>
                &nbsp;
            </td>
            <td class="ac td-ac" style="margin: 0 50px 0 0;">
               <strong>{!! $products->barcode !!}</strong>
               <?php $new_code = implode('', str_split(sprintf('%09d', $products->code ), 3)); ?>
               <strong style="margin-bottom:10px;">{{ $products->code }}</strong>
                <p>&nbsp;</p>
            </td>
        </tr>
       
        <tr>
            <td>
                &nbsp;
            </td>
            <td class="ac td-ac">
                 <strong>{{Str::limit($products->description, 60, $end='...')}}</strong>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td class="ac td-ac">
                <b>Category: <strong style="margin:0 10% 0 0px !important">{{$products->category->name}}</strong> </b>
                <b style="font-size:18px;">Price:   <strong>{{$products->price}} $</strong> </b>
            </td>
            <td class="ac td-ac">
            &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                {{-- @foreach ($products->items as $item1)
                <strong>Vendor:{{$item1->vendor->company }} </strong>
                @endforeach --}}
            </td>
        </tr>
    </tbody>
</table>

<table style="padding: 0px !important;margin: 0px;">
    <tbody>
        <th>
            <td class="al" >
                {{-- {{$products->created_at}}  --}}
               <strong style="margin:0 0 0 10px !important; "> {{Carbon\Carbon::now()}}</strong>
            </td>
            <td class="ac" >
               
            </td>
            <td class="ac" >
                <?php $company_name = DB::table('settings')
                ->latest()->take(1)
                ->where('id','=',4)
                ->value('value'); ?>
               <strong> {{$company_name}}</strong>
            </td>
        </th>
    </tbody>
</table>


<style>
    /* @page { size: 10cm 20cm landscape; } */
    * {
        margin: 0 !important;
        padding: 0 !important;
        /* max-height: 250px !important;
        max-width: 491px !important; */
    }
    .barcode-img {
        margin: 30px !important;
        /* padding: 100px 0 0 0; */
        width: 250px;
    }
</style>

<style>
                    
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
        margin-top: 20px;
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
        width: 50%;
    }
    .footer {
        text-align: center;
    }
</style>

    <style>
      
       .header {
       position: fixed;
       left: 0;
       color: #777;
       text-align: right;
       }
       .footer {
       position: fixed;
       left: 0;
       bottom: -30px;
       color: #777;
       width: 100%;
       text-align: center;
       }
       td > strong > div {
        margin: 20px 0% 0px 31.5% !important;
       }
</style>