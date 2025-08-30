<?php
            use Illuminate\Support\Str;
        ?>
    <div class="col-md-12" style="column-count: x;width:100%;">
    <?php $count=0; ?>
        @foreach ($model as $item)
        
        <div class="col-md-x" style="width:31.5%;margin:0 0.5% 0 0;float:left;border: 1px solid #dedede;padding: 5px;
        min-height: 110px;
    max-height: 110px;">
      
        <table style="margin: 0 5px 0 0;">
            <tbody>
  
                <tr>
                    <td class="ac td-ac" style="margin: 0 x0px 0 0;">
                 
                    <img src="data:image/png;base64,{{$item->barcode}}" alt="star" width="150" height="30">
                
                    <img style="margin:0 0px 0 10px;" width="45" height="45" src="/public/assets/uploads/media-uploader/{{$item->thumbnail}}" />
                    
                    <?php $new_code = implode('', str_split(sprintf('%09d', $item->code ), 3)); ?>
                    
                    </td>
                </tr>
           
                <tr>
                    
                    <td class="ac td-ac">
                        <strong style="font-size:9px;">{{$item->product_name}}</strong>
                    </td>
                </tr>
                <tr>
                  
                    <td class="ac td-ac">
                        <b><strong style="color:darkblue;font-size:9px;">{{ $item->code }}    &nbsp;</strong><span style="color:red;font-size:9px;">Category: </span><strong style="margin:0 10% 0 0px !important;font-size:9px;">{{$item->category_name}}</strong> </b>
</br>
                        <b><span style="color:red;;font-size:9px;">Sale Price: </span><strong style=";font-size:9px;">{{$item->price}} $</strong> <span style="color:red;;font-size:9px;">Unit Price: </span><strong style=";font-size:11px;">{{$item->unitprice}} $</strong> </b>
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
  
</style>

    <style>
 
   td > strong > div {
        margin: 5px 1% 0px 1% !important;
        float:left
       }
</style>