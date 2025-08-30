<table class="items" style="margin: 0;padding: 0;">
    <thead> </thead>
</table>
<?php $mytime = Carbon\Carbon::now(); ?>
<header class="header" style="float: left;    position: relative !important;">
    <br>
<h4 style="color: #777;margin: -80px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.&nbsp;&nbsp;  &nbsp;
{{$mytime->format('d/m/Y')}}
</h4>
</header>

@if(settings()->get('footer_line_1') && settings()->get('footer_line_2') || settings()->get('footer_line_3') )
<htmlpagefooter name="footer">
    <div class="footer">
    <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_1')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_2')}}
   </p>
   <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px;">
   {{settings()->get('footer_line_3')}}
   </p>
    @if(settings()->get('footer_line_3') === NULL )
        <p style="line-height: 1.2;font-size: 11px; color: #777;margin: 0px;padding: 0px; display: "> &nbsp;&nbsp;  &nbsp;&nbsp; </p>
    @endif
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" page="O" value="on" show-this-page="1" />
@endif

<section class="content" id="content">
 <div class="content-title">
     Client Balance Report
 </div>
 
 <table class="items">
   <thead>
       <tr>
        <th class="ac" >Seller</th>
        <th class="ac" >Ref</th>
        <th class="ac" >Client</th>
        
       
        <th class="ac">Over 90 Days</th>
         <th class="ac" >60 Days</th>
         <th class="ac" >30 Days</th>
        <th class="ac" >Total Balance</th>
       </tr>
   </thead>
   <tbody>
      @foreach ($reports as $item)
         <tr>
            <td class="ac">
            {{$item->seller_name}} <small>({{$item->seller_id}})</small>
            </td>
            <td class="ac">
                {{$item->reference}}
            </td>
            <td class="ac">
                {{$item->company}} <small style="font-size:11px;">({{$item->name}})</small>
            </td>
           
            <td class="ac">
                {{$item->balance_90}}
            </td>
            <td class="ac">
                {{$item->balance_60}}
            </td>
             <td class="ac">
                {{$item->balance_30}}
            </td>
            <td class="ac">
                {{$item->balance}}
            </td>
         </tr>
      @endforeach
   </tbody>
   <tfoot>
     <tr class="ar">
         <td colspan="7"></td>
     </tr>
 </tfoot>
</table>
</section>
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
        width: 70%;
    }
    .footer {
        text-align: center;
    }
</style>

    <style>
      
        
    .header {
   position: fixed;
   left: 0;
   margin-top: 50px;
   color: #777;
   width: 100%;
   text-align: center;
   }
   .footer {
   position: fixed;
   left: 0;
   bottom: 0px;
   color: #777;
   width: 100%;
   text-align: center;
   }
</style>