<?php
            use Illuminate\Support\Str;
        ?>
    <div class="col-md-12" style="column-count: x;width:100%;">
    <?php $count=0; ?>
        @foreach ($reports as $item)
        
        <div class="col-md-x" style="width:31.5%;margin:0 0.5%;float:left;border: 1px solid #dedede;padding: 5px;
        min-height: 210px;
    max-height: 210px;">
      
        <table style="margin: 0 5px 0 0;">
            <tbody>
            <tr>
                    <td>
                        &nbsp;
                    </td>
</tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td class="ac td-ac" style="margin: 0 50px 0 0;">
                    <strong>{!! $item->barcode !!}
                    </strong>
                    <img style="margin:0 0px 0 145px;" width="60" height="60" src="/assets/uploads/media-uploader/{{$item->thumbnail}}" />
</br>
                    <strong style="margin-bottom:10px;">{{ $item->code }}
                    
                    </strong>
                    <?php $new_code = implode('', str_split(sprintf('%09d', $item->code ), 3)); ?>
                    
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
                    <td class="ac td-ac">
                        <strong>{{Str::limit($item->product_name, 60, $end='...')}}</strong>
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
                    <td class="ac td-ac">
                        <b><span style="color:red;">Category: </span><strong style="margin:0 10% 0 0px !important">{{$item->category_name}}</strong> </b>
                        <b><span style="color:red;">Price: </span><strong>{{$item->price}} $</strong> </b>
                    </td>
                    <td class="ac td-ac">
                    &nbsp;
                    </td>
                </tr>
               
               
            </tbody>
        </table>
        </div>
        <?php $count += 1; ?>
        <?php if($count == 3){
                    echo "<p style='padding: 0;
                    margin: 0;
                    font-size: 11px;
                    line-height: 6px;'>&nbsp;</p>";
                    $count =0;
                }
        ?>
        @endforeach
    </div>

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
   td > strong > div {
        margin: 20px 5% 0px 15% !important;
        float:left
       }
</style>