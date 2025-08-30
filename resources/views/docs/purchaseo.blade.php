
<table class="items" style="margin: 0;padding: 0;">
    <thead> </thead>
</table>
<?php $mytime = Carbon\Carbon::now(); ?>
<header class="header" style="float: left;    position: relative !important;">
<br>
<img src="images/logo.png" width="220" style="margin-top: -80px;text-align: left;float: left;background-color: transparent;text-decoration: none;"  /><br>
<h4 style="color: #777;margin: -30px 0 0 0;padding: -50px 0;text-align: right;">Original Doc.</h4>
<h4 style="color: #777;margin: -20px 0 0 0;padding: -60px 0;text-align: right;">{{settings()->get('company_name')}}</h4>
<h4 style="color: #777;margin: -10px 0 0 0;padding: -40px 0;text-align: right;">{{$mytime->format('d/m/Y')}}</h4>
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
        Purchase Order Report
        <?php $ReportId1 = App\PurchaseOrderReport\PurchaseOrderReport::latest()->take(1)->get(); ?>
         @foreach ($ReportId1 as $report)
            @if($report->from_date)
            From: {{date('d-m-Y', strtotime($report->from_date))}} -
            @endif
            @if($report->to_date)
            To: {{date('d-m-Y', strtotime($report->to_date))}}
            @endif
         @endforeach
    </div>
    
    <table class="items">
      <thead>
          <tr>
              <th class="ac">PO-Number</th>
              <th class="ac">Vendor</th>
              <th class="ac">Product</th>
              <th class="ac">Qty</th>
              <th class="ac">Price</th>
              <th class="ac">UOM</th>
              <th class="ac">Request Date</th>
          </tr>
      </thead>
      <tbody>
         @foreach ($reports as $item)
            <tr>
               <td>
                  <?php $getPRNumber = App\PurchaseOrder\PurchaseOrder::where('id','=',$item->purchase_order_id)
                     ->where('status_id','>',1)
                     ->where('date','>=',$item->from_date)
                     ->where('date','<=',$item->to_date)
                     ->get(); 
                  ?>
                  @foreach ($getPRNumber as $PRNumber)
                     {{$PRNumber->number}}
                  @endforeach
               </td>
               <td>
                  {{$item->vendor->company}}<br>
                  <small>({{$item->vendor->person}})</small>
               </td>
               <td>
                  {{$item->product->description}}<br>
                  <small>({{$item->product->code}})</small>
               </td>
               <td>
                  {{$item->qty}}
               </td>
               <td>
                  {{$item->unit_price}}
                  @foreach ($getPRNumber as $PRNumber)
                  {{$PRNumber->currency->code}}
                  @endforeach
               </td>
               <td>
                  {{$item->uom->unit}}
               </td>
               <td>
                  @foreach ($getPRNumber as $PRNumber)
                     {{$PRNumber->date}}
                  @endforeach
               </td>
            </tr>
         @endforeach
      </tbody>
      <tfoot>
         <tr class="ar">
             <td colspan="3">Total Qty.</td>
             <td colspan="1"  class="ar">
               <?php  $sum = 0; ?>
                 @foreach ($reports as $variants)
                 <?php $quantity = $variants['qty'];
                     $act_points = $quantity;
                     $sum += $act_points; ?>
                 @endforeach
                {{$sum}}
                
             
             </td>
             <td colspan="4"></td>
         </tr>
     </tfoot>
</table>

<br><br><br>
</section>
<style>
   .center {text-align: center !important;}
  .content {
  padding-top: -30px !important;
  }
  /*.doc-logo {*/
  /*  width: 140px;*/
  /*  margin-top: -40px;*/
  /*  text-align: left;*/
  /*  float: left; */
  /*}*/
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
       margin-top: 121px !important;
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
       background: #f8f8f8;
       border: 0.1mm solid #484746;
   }
   .items tbody td {
       border: 0.1mm solid #484746;
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

</style>
