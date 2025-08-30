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
<div class="content-title" style="margin-top:80px;">
 Employee Report
</div>

<table class="summary">
 <tbody>
     <tr>
         <?php $employeeName = App\EmployeeReport\EmployeeReport::latest()->with(['employee'])->take(1)->get(); ?>
         <td class="summary-address">
             @foreach($employeeName as $x1)
             <strong>To:</strong>
                 <pre style="margin: 10px 0 0 0;padding: 0;">{{$x1->employee->name}}</pre>
                 <p style="margin:0;padding: 0;">
                     <strong>{{$x1->employee->company}}</strong>
                 </p>
            @endforeach
         </td>
         <td class="summary-empty"></td>
         <td class="summary-info" style="width:40%;">
             <table class="info">
                 <tbody>
                    @foreach($employeeName as $x1)
                     <tr>
                         <td>Document Number:</td>
                         <td>RPT-{{$x1->id + 1000000}}</td>
                     </tr>
                     <tr>
                         <td>From Date:</td>
                         <td>{{$x1->from_date}}</td>
                     </tr>
                     <tr>
                         <td>To Date:</td>
                         <td>{{$x1->to_date}}</td>
                     </tr>
                     <tr>
                         <td>Currency:</td>
                         <td>{{$x1->employee->currency['code']}}</td>
                     </tr>
                    @endforeach
                 </tbody>
             </table>
         </td>
     </tr>
 </tbody>
</table>

 <table class="items">
     <thead>
         <tr>
             <th class="center">Date</th>
             <th class="center">Ref. Num</th>
             <th class="center" style="display:none">Employee</th>
             <th class="center">Description</th>
             <th class="center">Amount</th>
             <th class="center">Salary - Deposit</th>
         </tr>
     </thead>
     <tbody>
        @foreach ($reports as $report)
         @if($report->type == 'payroll')
          <tr>
              <td>{{$report->payroll_date}}</td>
              <td>{{$report->number}}</td>
              <td  style="display:none">{{$report->employee->name}}</td>
              <td>Payroll</td>
              @if($report->amount_paid > 0)
                <td>{{$report->amount_paid}}&nbsp;{{$report->currency->code}}</td>
              @endif
              @if($report->amount_paid_lbp > 0)
                <td>{{$report->amount_paid_lbp}}&nbsp;{{$report->currency->code}} -- {{$report->exchangerate}}</td>
              @endif
              <td>{{$report->employee->salary}}&nbsp;{{$report->employee->currency['code']}}</td>
          </tr>
          @endif
          @if($report->type == 'deposit')
          <tr>
              <td>{{$report->deposit_date}}</td>
              <td>{{$report->number}}</td>
              <td style="display:none">{{$report->employee->name}}</td>
              <td>Deposit - to {{$report->to_account->name}}</td>
              @if($report->to_account->id == 1)
              <td>{{$report->deposit_amount}}&nbsp;USD</td>
              @endif
              @if($report->to_account->id == 2)
              <td>{{$report->deposit_amount}}&nbsp;LBP</td>
              @endif
              <td>{{$report->employee->salary}}&nbsp;{{$report->employee->currency['code']}} - {{$report->employee->deposit}}</td>
          </tr>
          @endif
          @if($report->type == 'return_deposit')
          <tr>
              <td>{{$report->return_deposit_date}}</td>
              <td>{{$report->number}}</td>
              <td style="display:none">{{$report->employee->name}}</td>
              <td>Return Deposit - from {{$report->from_account->name}}</td>
              @if($report->from_account->id == 1)
              <td>{{$report->return_deposit_amount}}&nbsp;USD</td>
              @endif
              @if($report->from_account->id == 2)
              <td>{{$report->return_deposit_amount}}&nbsp;LBP</td>
              @endif
              <td>{{$report->employee->salary}}&nbsp;{{$report->employee->currency['code']}} - {{$report->employee->deposit}}</td>
          </tr>
          @endif
        @endforeach
     </tbody>
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