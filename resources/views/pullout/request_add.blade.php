@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
    width: inherit;
    padding: 0 10px; 
    border-bottom: none;
}
#image_preview {
    display: none;
}

input[type='number'] {
    -moz-appearance:textfield;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

</style>
@endpush
@section('content')

@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
    <div class='panel panel-default'>
      <div class='panel-heading'>  
           Pullout Request Form
      </div>
        <div class='panel-body'>
            <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="pulloutForm" enctype="multipart/form-data">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <input type="hidden" value="{{ $pullout_type }}" name="pullout_type" id="pullout_type">
                <input type="hidden" value="{{ $rma_level }}" name="rma_level" id="rma_level">
                <input type="hidden" value="{{ $channel_type->channel_name }}" name="channel_id" id="channel_id">
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="color:red;">&nbsp;Pullout Details</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($channel_type->channel_name == "RETAIL" || $channel_type->channel_name == "FRANCHISE")
                                <label class="control-label">*ST#</label>
                                <input type='input' name='st_number_pull_out' id="st_number_pull_out" autocomplete="off" class='form-control' maxlength="50" placeholder="ST#" onkeypress="return AvoidSpace(event)" /> 
                            @elseif($channel_type->channel_name == "DISTRIBUTION" || $channel_type->channel_name == "ONLINE")
                                <label class="control-label">*REF#</label>
                                <input type='input' name='st_number_pull_out' id="st_number_pull_out" autocomplete="off" class='form-control' maxlength="50" placeholder="REF#" onkeypress="return AvoidSpace(event)" />                             
                            @else
                                <label class="control-label">*{{ trans('message.form-label.st_number') }}</label>
                                <input type='input' name='st_number_pull_out' id="st_number_pull_out" autocomplete="off" class='form-control' maxlength="50" placeholder="ST#/REF#" onkeypress="return AvoidSpace(event)" /> 
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="require control-label">{{ trans('message.form-label.pullout-from') }}</label>
                            <select class="form-control select2" style="width: 100%;" required name="pull_out_from" id="pull_out_from">
                                @foreach($pullOutFromData as $data)
                                    <option value="{{$data->id}}">{{$data->store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="require control-label">{{ trans('message.form-label.pullout-to') }}</label>
                            <select class="form-control select2" style="width: 100%;" required name="pull_out_deliver_to" id="pull_out_deliver_to">
                                @foreach($pullOutToData as $data)
                                    <option value="{{$data->id}}">{{$data->store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div><!-- /.row -->

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label require">*{{ trans('message.form-label.schedule_date') }}</label>
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input class="form-control" required name="pull_out_schedule_date" id="datepicker" onkeydown="return false" tabindex="0" autocomplete="off" type="input" placeholder="yyyy-mm-dd">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">*{{ trans('message.form-label.pullout-reason') }}<span class="text-danger"></span></label>
                            <select class="form-control select2" style="width: 100%;" required name="reasons_id" id="reasons_id">
                                <option value="">-- Select Reason --</option>
                                @foreach($reasonsData as $data)
                                    <option value="{{$data->id}}">{{$data->reason_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">{{ trans('message.form-label.pullout-via') }}<span class="text-danger"></span></label>
                            <select class="form-control select2" style="width: 100%;" required name="paths_id" id="paths_id">
                                <option value="">-- Select Via --</option>
                                @foreach($pathsData as $data)
                                    <option value="{{$data->id}}">{{$data->path_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
               
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" id="carry_division">
                            <label class="control-label require">*Hand Carried By:</label>
                            <input class="form-control" type="text" name="hand_carry_by" id="hand_carry_by"  placeholder="First Name Last Name">
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="scheduler-border">
            <legend class="scheduler-border" style="color:red;">&nbsp;Pullout Items</legend>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label class="control-label">{{ trans('message.form-label.add_item') }}</label>
                            <input class="form-control auto" style="width:420px;" placeholder="Search Item" id="search">
                            <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; width: 520px;">
                                <li>Loading...</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" style=" font-size: 13px;">Upload Generic Format</label>
                            <button type="button" style="width:150px;" class="btn btn-primary" id="upload1" tabindex="-1" data-toggle="modal" data-target="#excel-upload-modals">Upload Excel File</button>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="box-header text-center">
                        <h3 class="box-title"><b>{{ trans('message.form-label.pullout_items') }}</b></h3>
                        </div>
                        <div class="box-body no-padding">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pullout-items">
                                    <tbody>

                                        <tr class="tbl_header_color dynamicRows">
                                            <th width="15%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                            <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                            <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                            <th width="15%" class="text-center">{{ trans('message.table.brand') }}</th>
                                            <!--<th width="15%" class="text-center">{{ trans('message.table.wh_category') }}</th>-->
                                            <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                        </tr>

                                        <tr class="tableInfo">

                                            <td colspan="4" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                            <td align="left" colspan="1">
                                                <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" readonly></td>
                                            </td>
                                            <td colspan="1"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </div>
                    <!--<select id="store_list_from" style="visibility: hidden;">
                            @foreach($pullOutFromData as $data)
                                <option value="{{$data->store_pos_name}}">{{$data->store_pos_name}}</option>
                            @endforeach
                        </select>

                        <select id="store_list_to" style="visibility: hidden;">
                            @foreach($pullOutToData as $data)
                                <option value="{{$data->store_pos_name}}">{{$data->store_pos_name}}</option>
                            @endforeach
                        </select>-->
                    </div>
              
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ trans('message.table.note') }}</label>
                            <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="requestor_comments"></textarea>
                        </div>
                    </div>
             
                </div>
            </fieldset>
             </div>
            <div class='panel-footer'>
                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
             </div>
        </form>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="excel-upload-modal" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Excel</h4>
                </div>
                <form class="form-horizontal" id="excel-form" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                    <div class="modal-body">
                            <div class='callout callout-success'>
                                    <h4>Welcome to Data Importer Tool</h4>
                                    Before uploading a file, please read below instructions : <br/>
                                    * File format should be : XLS file format<br/>
                                    * Do not upload blank ST number.<br/>
                                    * Do not upload items with duplicate SKUs.<br/>
                                    * Do not upload items with negative quantity.<br/>
                                    * Do not upload items with decimal value in quantity.<br/>
                                    * Do not upload the file with blank row in between records.<br/>
                                    * Do not upload items that are not found in IMFS.<br/>
                                    * Do not double click upload excel button.<br/>
                                    * Do not double click submit button.<br/>
                                
                                    * Please limit your items to "<b>90</b>" SKUs per upload.<br/>
                                    
                            </div>
                        <div class="row" style="padding-bottom: 5px;">
                            <div class="text-center col-md-3">
                                <a href='{{ CRUDBooster::mainpath() }}/download-order-template' class="btn btn-primary" role="button">Download Pullout Template</a>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-12">
                                <input type='file' name='import_file' id="import_file" class='form-control' accept=".xls"/>
                                <div class='help-block'>File type supported only : XLS</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        
                        <input type="submit" class="btn btn-success" id="upload-excel" value="Upload Excel">
                        <button type="button" class="btn btn-default" id="upload-close" data-dismiss="modal">Close</button>
                    </div>
        
                </form>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="excel-upload-modals" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Excel</h4>
                </div>
                <form class="form-horizontal" id="excel-form1" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                    <div class="modal-body">
                            <div class='callout callout-success'>
                                    <h4>Welcome to Data Importer Tool</h4>
                                    Before uploading a file, please read below instructions : <br/>
                                    * File format should be : CSV file format<br/>
                                   <!-- * Do not upload blank ST number.<br/> -->
                                    * Do not upload items with duplicate SKUs.<br/>
                                    * Do not upload items with negative quantity.<br/>
                                    * Do not upload items with decimal value in quantity.<br/>
                                    * Do not upload the file with blank row in between records.<br/>
                                    * Do not upload items that are not found in IMFS.<br/>
                                    * Do not double click upload excel button.<br/>
                                    * Do not double click submit button.<br/>
                                
                                    * Please limit your items to "<b>90</b>" SKUs per upload.<br/>
                                    
                            </div>
                        <div class="row" style="padding-bottom: 5px;">
                            <div class="text-center col-md-3">
                                <a href='{{ CRUDBooster::mainpath() }}/download-order-template1' class="btn btn-primary" role="button">Download Pullout Template</a>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-12">
                                <input type='file' name='import_file1' id="import_file1" class='form-control' accept=".csv"/>
                                <div class='help-block'>File type supported only : CSV</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        
                        <input type="submit" class="btn btn-success" id="upload-excel1" value="Upload Excel">
                        <button type="button" class="btn btn-default" id="upload-close1" data-dismiss="modal">Close</button>
                    </div>
        
                </form>
                </div>
            </div>
        </div>
@endsection

@push('bottom')
<script type="text/javascript">
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}

function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

$(document).ready(function() {   
    $(function() {
        $( "#datepicker" ).datepicker({
            minDate: '1', 
            dateFormat: 'yy-mm-dd' 
            
        });
        
    });
});



$("#btnSubmit").on('click',function() {
        var rowCount = $('#pullout-items tr').length;
        var quantityReservable = $('.item_quantity').val();
        var totalQty = $("#totalQuantity").val();
        var signal = 0;
        var alert_message = 0;
        var error_qty = 0;
        var text_length = $("#st_number_pull_out").val().length;
        
        $('.item_quantity').each(function () {
            var quantityReservable1 = $(this).val();
            
            if(quantityReservable1 < 0 || quantityReservable1 == 0){
                //return false;
                error_qty++;
            }
            if(quantityReservable1 == ''){
                //return false;
                error_qty++;
            }
            if(quantityReservable1 == null){
                //return false;
                error_qty++;
            }
            
        });
        
        if($("#channel_id").val() == "RETAIL" || $("#channel_id").val() == "FRANCHISE"){
        if($("#st_number_pull_out").val().includes("ST#")){
            
            if($("#st_number_pull_out").val().includes(" ")){
                signal = 0;
                alert_message = 1;
            }else if(text_length <= 3){
                signal = 0;
                alert_message = 1;
            }else if (rowCount <= 2) {
                signal = 0;
                alert_message = 2;
            }else if(error_qty != 0 || error_qty != '0' ){
                signal = 0;
                alert_message = 3;
            }else if(totalQty == 'NaN'){
                alert_message = 4;
                signal = 0;
            }
            else{
                signal =1;
                restriction = 0;
            }
            
        }else{
            signal = 0;
            alert_message = 1;
        }


       if(signal != 0){
            $("form").submit(function(){
                //$("#reasons_id").prop("disabled", false);
                    $('#btnSubmit').attr('disabled', true);
                    return true; 
            });
            return true;   
  
        }else{
            if(alert_message == 1){
                alert("Incorrect ST# format! e.g. ST#1001");
            }else if(alert_message == 3){
                 alert("Please put item quantity!");
            }else if(alert_message == 4){
                 alert("NaN total is not allowed!");
            }else{
                alert("Please put an item!"); 
            }
            return false;  
        }       
    }else if($("#channel_id").val() == "DISTRIBUTION" || $("#channel_id").val() == "ONLINE"){
        if($("#st_number_pull_out").val().includes("REF#")){
            
            if($("#st_number_pull_out").val().includes(" ")){
                signal = 0;
                alert_message = 1;
            }else if(text_length <= 4){
                signal = 0;
                alert_message = 1;
            }else if (rowCount <= 2) {
                signal = 0;
                alert_message = 2;
            }else if(error_qty != 0 || error_qty != '0' ){
                signal = 0;
                alert_message = 3;
            }else if(totalQty == 'NaN'){
                alert_message = 4;
                signal = 0;
            }
            else{
                signal =1;
                restriction = 0;
            }
            
        }else{
            signal = 0;
            alert_message = 1;
        }


       if(signal != 0){
            $("form").submit(function(){
                //$("#reasons_id").prop("disabled", false);
                    $('#btnSubmit').attr('disabled', true);
                    return true; 
            });
            return true;   
        }else{
            if(alert_message == 1){
                alert("Incorrect REF# format! e.g. REF#1001");
            }else if(alert_message == 3){
                 alert("Please put item quantity!");
            }else if(alert_message == 4){
                 alert("NaN total is not allowed!");
            }else{
                alert("Please put an item!"); 
            }
            return false;  
        }   
    }else{
        if($("#st_number_pull_out").val().includes("ST#") || $("#st_number_pull_out").val().includes("REF#")){
            
            if($("#st_number_pull_out").val().includes(" ")){
                signal = 0;
                alert_message = 1;
            }else if($("#st_number_pull_out").val().includes("ST#")){
                    if(text_length <= 3){
                        signal = 0;
                        alert_message = 1;
                    }else if (rowCount <= 2) {
                        signal = 0;
                        alert_message = 2;
                    }else if(error_qty != 0 || error_qty != '0' ){
                        signal = 0;
                        alert_message = 3;
                    }else if(totalQty == 'NaN'){
                        alert_message = 4;
                        signal = 0;
                    }else{
                        signal = 1;
                        restriction = 0;
                    }
            }else if($("#st_number_pull_out").val().includes("REF#")){
                if(text_length <= 4){
                        signal = 0;
                        alert_message = 1;
                    }else if (rowCount <= 2) {
                        signal = 0;
                        alert_message = 2;
                    }else if(error_qty != 0 || error_qty != '0' ){
                        signal = 0;
                        alert_message = 3;
                    }else if(totalQty == 'NaN'){
                        alert_message = 4;
                        signal = 0;
                    }else{
                        signal = 1;
                        restriction = 0;
                    }
            }else if (rowCount <= 2) {
                signal = 0;
                alert_message = 2;
            }else if(error_qty != 0 || error_qty != '0' ){
                signal = 0;
                alert_message = 3;
            }else if(totalQty == 'NaN'){
                alert_message = 4;
                signal = 0;
            }else{
                signal =1;
                restriction = 0;
            }
            
        }else{
            signal = 0;
            alert_message = 1;
        }


       if(signal != 0){
            $("form").submit(function(){
                //$("#reasons_id").prop("disabled", false);
                    $('#btnSubmit').attr('disabled', true);
                    return true; 
            });
            return true;  
        }else{
            if(alert_message == 1){
                alert("Incorrect ST#/REF# format! e.g. ST#1001/REF#1001");
            }else if(alert_message == 3){
                 alert("Please put item quantity!");
            }else if(alert_message == 4){
                 alert("NaN total is not allowed!");
            }else{
                alert("Please put an item!"); 
            }
            return false;  
        }  
    }

    });
    
    $("#paths_id").change(function () {
         var xxxx = $(this).val();
         if(xxxx == "2"){
            $("#carry_division").show();
            document.getElementById("hand_carry_by").required = true;
         }else{
            $("#carry_division").hide();
            document.getElementById("hand_carry_by").required = false;
            $("#hand_carry_by").val("");
         }
    });

$(document).ready(function() {
    $("#carry_division").hide();
    
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
 
function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

/*$(document).ready(function () {
 $("#excel-form").submit(function(e){
    e.preventDefault();
    
    var form = $("#excel-form")[0];
    var formdata = new FormData(form);

    $.ajax({
     url: "{{ route('pullout.upload.excel-stw') }}",
     type: "POST",
     mimeTypes:"multipart/form-data",
     contentType: false,
     cache: false,
     processData: false,
     data: formdata,
     success: function(data){
        var count = 0;
        var pos_store_from = document.getElementById("store_list_from").value;
        var pos_store_to = document.getElementById("store_list_to").value;

        
        if(pos_store_from != data['from']){
            count++;
        }
        if(pos_store_to != data['to']){
            count++;
        }
        if(data['status'] != "Pending"){
            count++;
        }
        if(data['col0'] != "QTY"){
            count++;
        }
        if(data['col1'] != "Unit"){
            count++;
        }
        if(data['col2'] != "Product ID"){
            count++;
        }
        if(data['col3'] != "Description"){
            count++;
        }
        if(data['col4'] != "Unit Cost"){
            count++;
        }
        if(data['col5'] != "Total"){
            count++;
        }
        if(data['col6'] != "Memo"){
            count++;
        }
        if(count == 0){
            switch (data['checker']) {
                case 0: 
                    var value_st = $("#st_number_pull_out").val();
        
                    if(value_st != data['st_number']){
                            alert("Incorrect ST!");
                    }else{
                    $('.tableInfo').show();
                    $(data['excelorders']).insertAfter($('table tr.dynamicRows:last'));
                    var lastRow = 0;
                    var total_Quantity = calculateTotalQuantity();
                    $("#totalQuantity").val(total_Quantity);
                    $("#st_number_pull_out").val(data['st_number']);
                    
                    $('#excel-upload-modal').modal('hide');
                    $('#st_number_pull_out').prop('readonly',true);
                    document.getElementById("upload").disabled = true;
                    document.getElementById("upload1").disabled = true;
                    restriction++;
                    }
                break;
                case 1:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 2:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 3:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 4:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 5:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 6:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 7:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 8:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                case 9:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
                break;

                default:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
            }
        }else{
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel").disabled = true;
        }
     }
    });
   });
});*/

var stack = [];
var split_array = [];
$(document).ready(function () {
 $("#excel-form1").submit(function(e){
    e.preventDefault();
    
    var form1 = $("#excel-form1")[0];
    var formdata1 = new FormData(form1);

    $.ajax({
     url: "{{ route('pullout.upload.excel-stw1') }}",
     type: "POST",
     mimeTypes:"multipart/form-data",
     contentType: false,
     cache: false,
     processData: false,
     data: formdata1,
     success: function(data){
        var counts = 0;

        if(data['column0'] != "DIGITS CODE"){
            counts++;
        }
        if(data['column1'] != "ITEM DESCRIPTION"){
            counts++;
        }
        if(data['column2'] != "QTY"){
            counts++;
        }

        if(counts == 0){
            switch (data['checker_method']) {
                case 0: 
                    split_array = data['item_id_array'].split(",");
                    for(var x_id=0; x_id < split_array.length; x_id++){
                        stack.push(split_array[x_id]);
                    }
                    $('.tableInfo').show();
                    $(data['excelorders']).insertAfter($('table tr.dynamicRows:last'));
                    var lastRow = 0;
                    var total_Quantity = calculateTotalQuantity();
                    $("#totalQuantity").val(total_Quantity);
                    $('#excel-upload-modals').modal('hide');
                    restriction++;
                    document.getElementById("upload1").disabled = true;
                break;
                case 1:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                    //$('#excel-upload-modals').modal('hide');
                break;

                case 2:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                      $("#import_file1").val("");
                      //$('#excel-upload-modals').modal('hide');
                break;

                case 3:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                      $("#import_file1").val("");
                break;

                case 4:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                      $("#import_file1").val("");
                break;

                case 5:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                break;

                case 6:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                break;

                case 7:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                break;

                case 8:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                break;

                case 9:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
                break;

                case 10:
                    var user_channel = $('#channel_id').val();
                    if(user_channel == "FRANCHISE"){
                        alert("Error! Please check the uploaded file.");
                        document.getElementById("upload-excel1").disabled = true;
                        $("#import_file1").val("");
                    }else{
                        split_array = data['item_id_array'].split(",");
                        for(var x_id=0; x_id < split_array.length; x_id++){
                            stack.push(split_array[x_id]);
                        }
                        $('.tableInfo').show();
                        $(data['excelorders']).insertAfter($('table tr.dynamicRows:last'));
                        var lastRow = 0;
                        var total_Quantity = calculateTotalQuantity();
                        $("#totalQuantity").val(total_Quantity);
                        $('#excel-upload-modals').modal('hide');
                        restriction++;
                        document.getElementById("upload1").disabled = true;
                    }
                break;
                
                default:
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
            }
        }else{
                    alert("Error! Please check the uploaded file.");
                    document.getElementById("upload-excel1").disabled = true;
                    $("#import_file1").val("");
        }
     }
    });
   });
});


$("#upload").on('click',function(){
        if($("#st_number_pull_out").val() == null || $("#st_number_pull_out").val() == ''){
            $("#excel-upload-modal").hide();
             document.getElementById("upload-excel").disabled = true;
            alert('ST number is required!');
        }
});



$("#upload-excel").on('click',function(){
    document.getElementById("upload-excel").disabled = true;
    $('#excel-form').submit();
});

$("#upload-excel1").on('click',function(){
    document.getElementById("upload-excel1").disabled = true;
        if($("#import_file1").val() == null || $("#import_file1").val() == ''){
            alert('Please choose file to upload!');
        }
    $('#excel-form1').submit();
});

$("#upload-close").on('click',function(){
    document.getElementById("upload-excel").disabled = false;
});

$("#upload-close1").on('click',function(){
    $("#import_file1").val("");
    document.getElementById("upload-excel1").disabled = false;
});
var restriction = 0;
var token = $("#token").val();
var item_checker = 0;
var item_restriction = [];
$(document).ready(function(){
    $(function(){
        $("#search").autocomplete({
            source: function (request, response) {
            $.ajax({
                url: "{{ route('pulloutstw.item.search_stw') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "search": request.term
                },
                success: function (data) {
                    if (data.status_no == 1) {
                        $("#val_item").html();
                        var data = data.items;
                        $('#ui-id-2').css('display', 'none');
                        response($.map(data, function (item) {
                            return {
                                id: item.id,
                                stock_code: item.digits_code,
                                stock_upc: item.upc_code,
                                value: item.item_description,
                                stock_brand: item.brand,
                                stock_category: item.category
                            }
                        }));
                    } else {
                        $('.ui-menu-item').remove();
                        $('.addedLi').remove();
                        $("#ui-id-2").append($("<li class='addedLi'>").text(data.message));
                        var searchVal = $("#search").val();
                        if (searchVal.length > 0) {
                            $("#ui-id-2").css('display', 'block');
                        } else {
                            $("#ui-id-2").css('display', 'none');
                        }
                    }
                }
            })
        },
        select: function (event, ui) {
            var e = ui.item;
            if (e.id) {
                if(in_array(e.stock_code.substring(0, 1), item_restriction)){
                    if (!in_array(e.id, stack)) {
                        stack.push(e.id);
                        var new_row = '<tr class="nr" id="rowid' + e.id + '">' +
                                    '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + e.stock_code + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + e.stock_upc + '"></td>' +
                                    '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + e.value + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                                    '<td><input class="form-control text-center no_units item_quantity" data-id="' + e.id + '" data-rate="' + e.stock_price + '" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="qty_' + e.id + '" name="quantity[]" value="1">' +
                                    '<td class="text-center"><button id="' + e.id + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                    '<input style="width:0px;" type="hidden" name="category[]" readonly value="' +e.stock_category + '">' +
                                    '</tr>';
                        $(new_row).insertAfter($('table tr.dynamicRows:last'));
                        // Calculate total quantiy
                        var subTotalQuantity = calculateTotalQuantity();
                        $("#totalQuantity").val(subTotalQuantity);
                        $('.tableInfo').show();

                    }else{
                            $('#qty_' + e.id).val(function (i, oldval) {
                                return ++oldval;
                            });

                            var q = $('#qty_' + e.id).val();
                            var r = $("#rate_id_" + e.id).val();

                            $('#amount_' + e.id).val(function (i, amount) {
                                if (q != 0) {
                                    var itemPrice = (q * r);
                                    return itemPrice;
                                } else {
                                    return 0;
                                }
                            });
                            var subTotalQuantity = calculateTotalQuantity();
                            $("#totalQuantity").val(subTotalQuantity);
                    }
                }else{
                    alert("Invalid Item");
                }

                $(this).val('');
                $('#val_item').html('');
                return false;
            }
        },
        minLength: 1,
        autoFocus: true
        });
    });
});

$(document).ready(function () {
  $('.tableInfo').hide();
});

// Delete item row
$(document).ready(function (e) {
  $('#pullout-items').on('click', '.delete_item', function () {
    var v = $(this).attr("id");
    stack = jQuery.grep(stack, function (value) {
      return value != v;
    });

    $(this).closest("tr").remove();
    var subTotalQuantity = calculateTotalQuantity();
    $("#totalQuantity").val(subTotalQuantity);

  });
});

$(document).on('keyup', '.no_units', function (ev) {
    $('.tableInfo').show();
    var totalQuantity = calculateTotalQuantity();
    $("#totalQuantity").val(totalQuantity);

});

$("#").on('keyup', function () {
  var searchVal = $("#search").val();
  if (searchVal.length > 0) {
    $("#ui-id-2").css('display', 'block');
  } else {
    $("#ui-id-2").css('display', 'none');
  }
});

function calculateTotalQuantity() {
  var totalQuantity = 0;
  $('.item_quantity').each(function () {
    totalQuantity += parseInt($(this).val());
  });
  return totalQuantity;
}


$("#excel-upload-modal").on("hidden.bs.modal", function () {
    document.getElementById("upload-excel").disabled = false;
});

$("#excel-upload-modals").on("hidden.bs.modal", function () {
    $("#import_file1").val("");
    document.getElementById("upload-excel1").disabled = false;
});


$('#hand_carry_by').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z. ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else
        {
        e.preventDefault();
        alert('Please Enter Alphabet');
        return false;
        }
    });

</script>
@endpush 
