@if(isset($options['header-html']) && $options['header-html'])
<htmlpageheader name="header">
    {!! File::get($options['header-html']) !!}
    <div class="header" style="text-align:right;width:100%;margin:30px 0 0 0;">
        <small>Page {PAGENO} of {nb}</small>
        <br>
        <small>Original Doc.</small><br>
        <small><?php $company_name = DB::table('settings')
            ->latest()->take(1)
            ->where('id','=',4)
            ->value('value'); ?>
            {{$company_name}}
        </small>
        {!! File::get($options['header-html']) !!}
        
    </div>
</htmlpageheader>
<sethtmlpageheader name="header" show-this-page="1" />
@endif

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
        Finished Product DataSheet
    </div>
    <table class="summary">
        <tbody>
            <tr>
                <td class="summary-address" style="margin:0 5px;padding:0 5px;">
                    <strong>Description:</strong>
                        {{$model->description}}
                    <br>
                    <hr>
                    @if($model->comment)
                        <strong>Comment:</strong>
                        {{$model->comment}}
                    @endif
                </td>
                <td class="summary-addressx">
                    <strong>&nbsp;</strong>
                </td>
                <!-- <td class="summary-empty"></td> -->
                <td class="summary-info">
                    <table class="info">
                        <tbody>
                            <tr>
                                <td>Number:</td>
                                <td>{{$model->generated_code}}</td>
                            </tr>
                           
                            @if($model->uom_id)
                            <tr>
                                <td>U.O.M:</td>
                                <td>{{$model->uom->unit}}</td>
                            </tr>
                            @endif
                            @if($model->product_type)
                            <tr>
                                <td>Product Type:</td>
                                <td>{{$model->type->code}} - {{$model->type->name}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Currency:</td>
                                <td> {{$model->currency->code}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <strong>Raw Materials</strong>
        <table class="items col-md-6" style="width: 50%">
            <thead>
                <tr>
                    <th width="25%">Item Code</th>
                    <th width="40%">Description</th>
                    <th  width="15%">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->materials as $material)
                    <tr>
                         <td>{{$material->material->code}}</td>
                         <td>{{$material->material->name}}</td>
                         <td>{{$material->percentage}} %</td>
                    </tr>
                    @endforeach
            </tbody>
    </table>
<br>
<hr>
    <strong>Attributes</strong>
        <table class="items col-md-6" style="width: 50%">
            <thead>
                <tr>
                    <th width="25%">Item Code</th>
                    <th width="40%">Description</th>
                    <th  width="15%">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->attributes as $attribute)
                    <tr>
                         <td>{{$attribute->attribute_name->number}}</td>
                         <td>{{$attribute->attribute_name->description}}</td>
                         <td>{{$attribute->values->attribute_value}} </td>
                    </tr>
                    @endforeach
            </tbody>
    </table>

    <br>
<hr>
    <strong>Machines</strong>
        <table class="items col-md-6" style="width: 100%">
            <thead>
                <tr>
                    <th>Seq:</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Speed</th>
                    <th>Comment</th>
                    <th> <strong>Settings/Parameters</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach($model->machines as $machine)
                    <tr>
                         <td>{{$machine->machine_process_id}}</td>
                         <td>{{$machine->machinex->code}}</td>
                         <td>{{$machine->machinex->name}}</td>
                         <td>{{$machine->machines->speed}} </td>
                         <td>{{$machine->machines->comment}} </td>

                         <td>
                           
                            <table class="" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Parameters</th>
                                        <th>Values</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($machine->machines->settings as $setting)
                                    <tr v-for="mass in ma.settings">
                                         <td class="width-6">
                                         {{$setting->settings_id}}
                                         </td>
                                         <td class="width-6">
                                            {{$setting->settings_name}}
                                         </td>
                                         <td class="width-6">
                                            {{$setting->settings_value}}
                                         </td>
                                     </tr>
                                     @endforeach
                                </tbody>
                            </table>
                         </td>

                    </tr>
                    @endforeach
            </tbody>
    </table>
    <hr>
<table class="terms" style="margin-top: 5%;">
<tbody>
  <tr>
    <td class="terms-description">
        <div>
            <u><strong>Image</strong></u>
            <p>&nbsp;</p>
            <div class="terms-foot">
                <img style="max-width: 450px" src="{{ storage_path('app/uploads/'.$model->document.'') }}">
               
              {{-- <img src="{{url('/')}}/uploads/{{$model->document}}" />  --}}
           </div>
         </div>
     </td>
     <td></td>
  </tr>
</tbody>
</table>
<br><br><br>

 
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
</style>