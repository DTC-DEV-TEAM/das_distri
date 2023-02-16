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
      <div class='panel-heading'>Pullout Request Form</div>
        <div class='panel-body'>
            <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="pulloutForm"  enctype="multipart/form-data">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <input type="hidden" value="{{ $pullout_type }}" name="pullout_type" id="pullout_type">
                <input type="hidden" value="{{ $rma_level }}" name="rma_level" id="rma_level">
                <input type="hidden" value="{{ $temp_reference }}" name="temp_reference1" id="temp_reference1">

            <fieldset class="scheduler-border">
                    <legend class="scheduler-border" style="color:red;">&nbsp;Pullout Details</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="require control-label">*ST#:</label>
                                <input type='input' required name='st_number_pull_out' id="st_number_pull_out"  autocomplete="off" class='form-control' maxlength="50" placeholder="ST#" onkeypress="return AvoidSpace(event)" /> 
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

                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">{{ trans('message.form-label.add_item') }}</label>
                            <input class="form-control auto" style="width:420px;" placeholder="Search Item" id="search">
                            @if($pullout_type == 'rma')
                            <p style="color:red; font-style: italic;">*If the same SKUs, create only one request. *If different SKUs, create another pullout request.</p>
                            @endif
                            <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; width: 520px;">
                                <li>Loading...</li>
                            </ul>
                        </div>
                    </div>

                    @if($pullout_type == 'good')
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Upload ST Format 1:</label>
                            <button type="button" style="width:150px;" class="btn btn-primary" id="upload" tabindex="-1" data-toggle="modal" data-target="#excel-upload-modal">Upload Excel File</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Upload ST Format 2:</label>
                            <button type="button" style="width:150px;" class="btn btn-primary" id="upload1" tabindex="-1" data-toggle="modal" data-target="#excel-upload-modals">Upload Excel File</button>
                        </div>
                    </div>
                    @endif

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
                                            <th width="20%" class="text-center">{{ trans('message.table.problem_details') }}</th>
                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                        </tr>

                                        <tr class="tableInfo">

                                            <td colspan="4" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                            <td align="left" colspan="1">
                                                <input type='number' name="total_quantity" class="form-control text-center" id="totalQuantity" readonly></td>
                                            </td>
                                            <td colspan="1"></td>
                                            <td colspan="1"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </div>
                        <select id="from_store" style="visibility: hidden;">
                            @foreach($pullOutFromData as $data)
                                <option value="{{$data->store_name}}">{{$data->store_name}}</option>
                            @endforeach
                    </select>
                    </div>
              
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ trans('message.table.note') }}</label>
                            <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="requestor_comments"></textarea>
                        </div>
                    </div>
             
                </div>
            </fieldset>
            <di id="srof_details_div">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="color:red;">&nbsp;SROF Details</legend>
                    <h5 style="color:red;"><strong>Customer Information</strong></h5>
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">*{{ trans('message.form-label.first_name') }}</label>
                                            <input type='input'  name='first_name' id="first_name" autocomplete="off" class='form-control' maxlength="50" placeholder="First Name" /> 
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">{{ trans('message.form-label.middle_initial') }}</label>
                                            <input type='input'  name='middle_initial' id="middle_initial" autocomplete="off" class='form-control' maxlength="50" placeholder="Middle Initial" /> 
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">*{{ trans('message.form-label.last_name') }}</label>
                                            <input type='input'  name='last_name' id="last_name" autocomplete="off" class='form-control' maxlength="50" placeholder="Last Name" /> 
                                    </div>
                                </div>
                    </div>
                    <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                            <label class="require control-label">*{{ trans('message.form-label.address') }}</label>
                                            <input type='input'  name='address' id="address" autocomplete="off" class='form-control' maxlength="250" placeholder="Address" /> 
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">*{{ trans('message.form-label.email_address') }}</label>
                                            <input type='input'  name='email_address' id="email_address" autocomplete="off" class='form-control' maxlength="50" placeholder="Email Address" /> 
                                            <p style="font: italic bold 12px/30px arial, arial;">Type N/A if not applicable</p>
                                    </div>
                                </div>

                    </div>
                    <div class="row">
                            <!--<div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label require">*{{ trans('message.form-label.date_received') }}</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="form-control" required name="date_received" id="datepicker1" onkeydown="return false" tabindex="0" autocomplete="off" type="input" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">{{ trans('message.form-label.time_received') }}</label>
                                            <input type='time' required name='time_received' id="time_received" autocomplete="off" class='form-control' maxlength="50" placeholder="Home#" /> 
                                    </div>
                            </div>-->

                            <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">*{{ trans('message.form-label.company_store') }}</label>
                                            <input type='input'  name='company_store' id="company_store" autocomplete="off" class='form-control' maxlength="250" placeholder="Company" /> 
                                            <p style="font: italic bold 12px/30px arial, arial;">Type N/A if not applicable</p>
                                    </div>
                            </div>
                    </div>
                    <h5 style="color:red;"><strong>Contact Number</strong></h5>
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">{{ trans('message.form-label.work_number') }}</label>
                                            <input type='input'  name='work_number' id="work_number" autocomplete="off" class='form-control' maxlength="50" placeholder="Work#" /> 
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">{{ trans('message.form-label.home_number') }}</label>
                                            <input type='input'  name='home_number' id="home_number" autocomplete="off" class='form-control' maxlength="50" placeholder="Home#" /> 
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="require control-label">{{ trans('message.form-label.mobile_number') }}</label>
                                            <input type='number'  name='mobile_number' id="mobile_number" autocomplete="off" class='form-control' maxlength="50" placeholder="Mobile#" onKeyPress="if(this.value.length==11) return false;" onkeyup="myFunction()" /> 
                                            <p style="font: italic bold 12px/30px arial, arial;">e.g 09123456789</p>
                                    </div>
                                </div>
                    </div>
                    <hr color="black" > 
                    <h5 style="color:red;"><strong>Unit Information</strong></h5>
                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label require">*{{ trans('message.form-label.date_purchased') }}</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input class="form-control"  name="date_purchased" id="datepicker2" onkeydown="return false" tabindex="0" autocomplete="off" type="input" placeholder="yyyy-mm-dd">
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label class="require control-label">*{{ trans('message.form-label.rs_invoice_number') }}</label>
                                        <input type='input'  name='rs_invoice_number' id="rs_invoice_number" autocomplete="off" class='form-control' maxlength="50" placeholder="INV#" onkeypress="return AvoidSpace(event)" /> 
                                </div>
                            </div>
                    </div>

                    <div class="row">
                            <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="require control-label">{{ trans('message.form-label.items_included') }}</label>
                                        <select class="js-example-basic-multiple"  name="items_included[]" id="items_included" multiple="multiple"   style="width:100%">
                                                @foreach($items_included_list as $datas)     
                                                    <option value="{{$datas->items_description_included}}">{{$datas->items_description_included}}</option>
                                                @endforeach
                                        </select>
                                        <p style="font: italic bold 12px/30px arial, arial;">e.g Box, Charger</p>
                                    </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                                <br>
                                                <input type='input'  name='other_items_included' id="other_items_included" autocomplete="off" class='form-control' maxlength="50" placeholder="OTHER INCLUDED ITEMS" style="height:40px;" /> 
                                        </div>
                                </div>
                    </div>
                    <hr color="black" > 
                    <div id="show">
                    <h5 style="color:red;"><strong>Service Details</strong></h5>
                    <label class="require control-label">*{{ trans('message.form-label.action_taken_type') }}</label>
                    <div class="row">
                            @foreach($service_details_list as $data)
                            <div class="col-md-6">
                                <label class="radio-inline control-label col-md-5"><input type="radio" id="action_taken_type"   name="action_taken_type" value="{{$data->action_taken}}" >{{$data->action_taken}}</label>
                                <br>
                            </div>
                             @endforeach
                    </div>
                    <br>
                    <hr color="black" > 
                    </div>
                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label class="require control-label">*{{ trans('message.form-label.prepared_by') }}</label>
                                        <input type='input'  name='prepared_by' id="prepared_by" autocomplete="off" class='form-control' maxlength="50" placeholder="First Name Last Name" /> 
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
                        <h4 class="modal-title">Add Serial</h4>
                    </div>
                    <form id="myform" method="" >
                        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="modal-body">
                                <div class='callout callout-danger' id="error-message">
                                        <p id="p1"></p>
                                </div>
                                <div id="screens"> 
                                        <input type="hidden" value="{{ $temp_reference }}" name="temp_reference" id="temp_reference">
                                        <input type="hidden"  name="button_ids" id="button_ids">                                        
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" id="save_serial" value="Save">
                            <button type="button" class="btn btn-default" id="close_serial" data-dismiss="modal" onclick="removeElements()">Close</button>
                        </div>
            
                    </form>
                    </div>
                </div>
            </div>
@endsection
@push('bottom')
<script type="text/javascript">
var length_checker = 0;
var required_checker = "0";
function myFunction() {
        var myLength = $("#mobile_number").val().length;
        if(myLength < 11){
                length_checker++;
        }else{
                length_checker = 0;
        }
  
}


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
    
    $("#required_label").text("*");

    $("#reasons_id").change(function () {
         var x = $(this).val();
         $("#reasons_id").prop("disabled", true);
         if(x == "16"){
            document.getElementById("action_taken_type").required = true;
            //document.getElementById("show").style.visibility = "none";
            $("#show").show();
            document.getElementById("action_taken_type").required = true;
            document.getElementById("first_name").required = true;
            document.getElementById("last_name").required = true;
            document.getElementById("address").required = true;
            document.getElementById("email_address").required = true;
            document.getElementById("company_store").required = true;
            document.getElementById("datepicker2").required = true;
            document.getElementById("rs_invoice_number").required = true;
            document.getElementById("items_included").required = true;
            document.getElementById("prepared_by").required = true;
            required_checker = "0";
         }else{
            document.getElementById("action_taken_type").required = false;
            //document.getElementById("show").style.visibility = "hidden";
            $("#show").hide();
            document.getElementById("first_name").required = false;
            document.getElementById("last_name").required = false;
            document.getElementById("address").required = false;
            document.getElementById("email_address").required = false;
            document.getElementById("company_store").required = false;
            document.getElementById("datepicker2").required = false;
            document.getElementById("rs_invoice_number").required = false;
            document.getElementById("items_included").required = false;
            document.getElementById("prepared_by").required = false;            
            required_checker = "1";
            $("#srof_details_div").hide();

         }

   });

    $('#error-message').hide();

    if($('#excel-upload-modal').modal('hide') == true)
    {
        alert('yes');
    }

    $(function() {
        $( "#datepicker" ).datepicker({
            minDate: '1', 
            dateFormat: 'yy-mm-dd' 
            
        });

    });

    $(function() {
        $( "#datepicker1" ).datepicker({
            maxDate: '0', 
            dateFormat: 'yy-mm-dd' 
            
        });
    });

    $(function() {
        $( "#datepicker2" ).datepicker({
            maxDate: '0', 
            dateFormat: 'yy-mm-dd' 
            
        });
    });

    $(function() {
        $( "#datepicker3" ).datepicker({
            minDate: '1', 
            dateFormat: 'yy-mm-dd' 
            
        });
    });
});


$(document).ready(function() {
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

$("form").submit(function(){
    $("#reasons_id").prop("disabled", false);
   // $('#btnSubmit').attr('disabled', true);
});

$("#btnSubmit").on('click',function() {
var rowCount = $('#pullout-items tr').length;
var quantityReservable = $('.item_quantity').val();
var totalQty = $("#totalQuantity").val();
var signal = 1;
var alert_message = 0;
var email_field =  $('#email_address').val();
var field3 =  $('#work_number').val();
var field1 =  $('#home_number').val();
var field2 =  $('#mobile_number').val();
var error_qty = 0;
var text_length = $("#st_number_pull_out").val().length;
var text_length1 = $("#rs_invoice_number").val().length;
var items_included_field = $("#other_items_included").val();        
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

if($("#mobile_number").val().length == 0){
    length_checker = 0;
}
if(required_checker == 1 || required_checker == "1"){
        if(!$("#st_number_pull_out").val().includes("ST#")){
                signal = 0;
                alert_message = 1;
        }else if($("#st_number_pull_out").val().includes(" ")){
                signal = 0;
                alert_message = 1;
        }else if(text_length <= 3){
                signal = 0;
                alert_message = 1;
        }else if (rowCount <= 2) {
                signal = 0;
                alert_message = 2;            
        }else {
        if(error_qty != 0 || error_qty != '0' ){
                signal = 0;
                alert_message = 3;
        }else if(totalQty == 'NaN'){
                alert_message = 4;
                signal = 0;
        }
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
            }else if(alert_message == 5){
                 alert("Incorrect INVOICE# format! e.g. INV#1001");
            }else if(alert_message == 3){
                 alert("Please put item quantity!");
            }else if(alert_message == 4){
                 alert("NaN total is not allowed!");
            }else if(alert_message == 6){
                 alert("Invalid Email Address!");
            }else if(alert_message == 7){
                 alert("Please fill out atleast one contact number!");
            }else if(alert_message == 8){
                 alert("Minimum of 11 digits is allowed for mobile number!");
            }else{
                alert("Please put an item!"); 
            }
            return false;  
        }
}else{
    


        if(!$("#st_number_pull_out").val().includes("ST#")){
                signal = 0;
                alert_message = 1;
        
        }else if($("#st_number_pull_out").val().includes(" ")){
                signal = 0;
                alert_message = 1;
        }else if(text_length <= 3){
                signal = 0;
                alert_message = 1;
        }else if (rowCount <= 2) {
                signal = 0;
                alert_message = 2;            
        }else {
        
        if(error_qty != 0 || error_qty != '0' ){
                signal = 0;
                alert_message = 3;
        }else if(totalQty == 'NaN'){
                alert_message = 4;
                signal = 0;
        }else if(email_field == ""){
                signal = 0;
                alert_message = 6;
        }else if(email_field != "" && email_field != "N/A"){
            if(!email_field.includes("@") || !email_field.includes(".")){
                signal = 0;
                alert_message = 6;
            }else{
                if(field1 == null && field2 == null && field3 == null || field1 == '' && field2 == '' && field3 == ''){
                signal = 0;
                alert_message = 7;
                }else if(length_checker != 0){
                        signal = 0;
                        alert_message = 8;
                }else if(!$("#rs_invoice_number").val().includes("INV#")){
                        signal = 0;
                        alert_message = 5;
                }else if($("#rs_invoice_number").val().includes(" ")){
                        signal = 0;
                        alert_message = 5;
                }else if(text_length1 <= 4){
                    signal = 0;
                    alert_message = 5;
                }else if(items_included_field == "COMPLETE" || items_included_field == "complete" || items_included_field == "COMPLETES" || items_included_field == "completes" ){
                    signal = 0;
                    alert_message = 9;
                }
            }
        }else if(field1 == null && field2 == null && field3 == null || field1 == '' && field2 == '' && field3 == ''){
                signal = 0;
                alert_message = 7;
        }else if(length_checker != 0){
                signal = 0;
                alert_message = 8;
        }else if(!$("#rs_invoice_number").val().includes("INV#")){
                signal = 0;
                alert_message = 5;
        }else if($("#rs_invoice_number").val().includes(" ")){
                signal = 0;
                alert_message = 5;
        }else if(text_length1 <= 4){
                signal = 0;
                alert_message = 5;
        }else if(items_included_field == "COMPLETE" || items_included_field == "complete" || items_included_field == "COMPLETES" || items_included_field == "completes" ){
                    signal = 0;
                    alert_message = 9;
        }
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
            }else if(alert_message == 5){
                 alert("Incorrect INVOICE# format! e.g. INV#1001");
            }else if(alert_message == 3){
                 alert("Please put item quantity!");
            }else if(alert_message == 4){
                 alert("NaN total is not allowed!");
            }else if(alert_message == 6){
                 alert("Invalid Email Address!");
            }else if(alert_message == 7){
                 alert("Please fill out atleast one contact number!");
            }else if(alert_message == 8){
                 alert("Minimum of 11 digits is allowed for mobile number!");
            }else if(alert_message == 9){
                 alert("Complete is not allowed in items included!");
            }else{
                alert("Please put an item!"); 
            }
            return false;  
        }
}
});


var id_qty = 0;
var stack = [];
var token = $("#token").val();
var myStr = '';
var xx_len = 0;
var restriction = 0;
var blank = 0;
var execute = 0;
var button_asc = 0;
var temp = '';
var problem_loop = 0;
$(document).ready(function(){
    $(function(){
        $("#search").autocomplete({
            source: function (request, response) {
            $.ajax({
                url: "{{ route('pulloutstw.item.search_sts') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "search": request.term
                },
                success: function (data) {
                    if($("#reasons_id").val() == "" || $("#reasons_id").val() == null){
                        alert("You must choose pullout reason first!");
                        $("#search").val("");
                    }else{
                    if(execute == 0){
                    var rowCount = $('#pullout-items tr').length;
                    var from_checking = $('#from_store').val();
                    if(from_checking.includes("CLEARANCE")) {
                        myStr = data.sample;
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
                        restriction = 0;
                    }else{
                        if($("#reasons_id").val() == "19" || $("#reasons_id").val() == 19 || $("#reasons_id").val() == "35" || $("#reasons_id").val() == 35){
                            restriction = 0;
                        }else{
                            restriction++;   
                        }                 
            	    if(rowCount == 2){
                         myStr = data.sample;
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
                    }else if($("#reasons_id").val() == "19" || $("#reasons_id").val() == 19 || $("#reasons_id").val() == "35" || $("#reasons_id").val() == 35){
                         myStr = data.sample;
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
                    }else{
                        if($("#reasons_id").val() == "16" || $("#reasons_id").val() == 16){
                            alert("Only 1 item allowed!");
                            $("#search").val("");
                        }else{
                            alert("Only 1 item allowed!");
                            $("#search").val("");
                        }
                    }
                    }
                }else{
                    alert("Please fill out the problem details!");
                    $("#search").val("");
                }
                }
                }
            })
        },
        select: function (event, ui) {
            var e = ui.item;
            if (e.id) {
                if (!in_array(e.stock_code, stack)) {
                    temp = $("#temp_reference1").val();
                    button_asc++;
                    problem_loop++;
                    stack.push(e.stock_code);
                    if(restriction != 0){
                    var new_row = '<tr class="nr" id="rowid' + e.id + '">' +
                            '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + e.stock_code + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + e.stock_upc + '"></td>' +
                                '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + e.value + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                                '<td><input class="form-control text-center no_units item_quantity" readonly   data-id="'+e.stock_code +''+button_asc+''+temp+'" data-rate="' + e.stock_price + '" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="'+e.stock_code +'" name="quantity[]" value="1"><input  type="hidden" name="serialize[]" id="serialize_'+e.stock_code +''+button_asc+''+temp+'" value="0" readonly ><input  type="hidden" name="line_id[]"  value="'+e.stock_code +''+button_asc+''+temp+'" readonly ><input  type="hidden" name="visible_qty[]" id="'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ></td>' +
                                '<td><select class="js-example-basic-multiple" required name="' + e.stock_code + '[]" id="problem_details_'+ e.id +'" multiple="multiple" style="width:100%"></select><br><br><input class="form-control text-center" type="text" name="problem_details_other[]" id="problem_details_other_'+ e.id +'"></td>'+
                                '<td class="text-center"><button id="'+e.stock_code +''+button_asc+''+temp+'" onclick="reply_click1(this.id)" class="btn btn-xs btn-danger delete_item" style="width:60px;height:30px;font-size: 11px;text-align: center;">REMOVE</button><br><br><button type="button" style="width:60px;height:30px;font-size: 11px;text-align: center;" class="btn btn-success" id="'+e.stock_code +''+button_asc+''+temp+'" tabindex="-1" data-toggle="modal" data-target="#excel-upload-modal"  onclick="reply_click(this.id)">ADD SN</button></td>' +
                                '<input type="hidden" name="category[]" readonly value="' +e.stock_category + '">' +
                                '</tr>';
                    }else{
                        var new_row = '<tr class="nr" id="rowid' + e.id + '">' +
                                '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + e.stock_code + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + e.stock_upc + '"></td>' +
                                '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + e.value + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                                '<td><input class="form-control text-center no_units item_quantity"    data-id="'+e.stock_code +''+button_asc+''+temp+'" data-rate="' + e.stock_price + '" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="'+e.stock_code +'" name="quantity[]" value="1"><input  type="hidden" name="serialize[]" id="serialize_'+e.stock_code +''+button_asc+''+temp+'" value="0" readonly ><input  type="hidden" name="line_id[]"  value="'+e.stock_code +''+button_asc+''+temp+'" readonly ><input  type="hidden" name="visible_qty[]" id="'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ></td>' +
                                '<td><select class="js-example-basic-multiple" required name="' + e.stock_code + '[]" id="problem_details_'+ e.id +'" multiple="multiple" style="width:100%"></select><br><br><input class="form-control text-center" type="text" name="problem_details_other[]" id="problem_details_other_'+ e.id +'"></td>'+
                                '<td class="text-center"><button id="'+e.stock_code +''+button_asc+''+temp+'" onclick="reply_click1(this.id)" class="btn btn-xs btn-danger delete_item" style="width:60px;height:30px;font-size: 11px;text-align: center;">REMOVE</button><br><br><button type="button" style="width:60px;height:30px;font-size: 11px;text-align: center;" class="btn btn-success" id="'+e.stock_code +''+button_asc+''+temp+'" tabindex="-1" data-toggle="modal" data-target="#excel-upload-modal"  onclick="reply_click(this.id)">ADD SN</button></td>' +
                                '<input type="hidden" name="category[]" readonly value="' +e.stock_category + '">' +
                                '</tr>';
                    }
                    $(new_row).insertAfter($('table tr.dynamicRows:last'));
                    $('.js-example-basic-multiple').select2();
                    $(".js-example-basic-multiple").select2({
                    theme: "classic"
                    });
                  var strArray = myStr.split(",");
                  for(var x=0; x < strArray.length; x++){
                        $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'">'+strArray[x]+'</option>');
                  }
                  $('#problem_details_other_'+e.id).hide();
                  $('#problem_details_'+e.id).change(function(){
                        if($('#problem_details_'+e.id).val() != null){
                            var arrx = $(this).val();
                            execute = 0;
                        }else{
                            var arrx = "";
                            execute++;
                        }
                        var s = arrx;
                        if(s.includes("OTHERS")) {
                            $('#problem_details_other_'+e.id).show();
                            $('#problem_details_other_'+e.id).attr('required', 'required');
                        }else{
                            $('#problem_details_other_'+e.id).val("");
                            $('#problem_details_other_'+e.id).hide();  
                            $('#problem_details_other_'+e.id).removeAttr('required');
                        }
                    })
                    if($('#problem_details_'+e.id).val() == null || $('#problem_details_'+e.id).val() == ''){
                        execute++;
                    }
                    blank++;
                    // Calculate total quantiy
                    var subTotalQuantity = calculateTotalQuantity();
                    $("#totalQuantity").val(subTotalQuantity);
                    $('.tableInfo').show();

                } else {
                    if(!related_items.includes(e.stock_code)){

                    $('#' + e.stock_code).val(function (i, oldval) {
                    
                        return ++oldval;
                    });
                    
                    var temp_qty = $('#'+ e.stock_code).attr("data-id");

                    var q = $('#' +e.stock_code).val();
                    var r = $("#rate_id_" + e.id).val();

                    $('#amount_' + e.id).val(function (i, amount) {
                        if (q != 0) {
                            var itemPrice = (q * r);
                            return itemPrice;
                        } else {
                            return 0;
                        }
                    });
                    $('#'+temp_qty).val(q);
                    var subTotalQuantity = calculateTotalQuantity();
                    $("#totalQuantity").val(subTotalQuantity);
                    }else{
                        alert("You can not add this item!");
                    }
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

var count_of_id = 0;
var div_container = ''; 
var div_container1 =[]; 
var qty_current = [];
var related_items = [];
var qty_current1 = 0;
var count_validation = 0;
var axxxx = 0;
var fields = 0;
var disable = 0;
var count_null_values = '';
var old_value = 0;
var serialize_value = 0;
function reply_click1(clicked_id)
  {
        div_container = clicked_id;
        $("#button_ids").val(clicked_id);
        var data = $('#myform').serialize();
        $.ajax({
             type: 'GET',
             url: '{{ url('admin/pullout_sts/SoftDeleteSerail') }}',
             data: data,
             success: function( response ){
                 console.log(response);
             },
             error: function( e ) {
                 console.log(e);
             }             
        });
  }
  
function reply_click(clicked_id)
  {
      serialize_value = clicked_id;
      $("#button_ids").val(clicked_id);
      div_container = clicked_id;
      count_null_values = $("#"+clicked_id).val();
      if(div_container1.includes(clicked_id)){
        qty_current1 = $("#"+clicked_id).val()+div_container;
        fields = document.getElementById("screens").childElementCount;
            for (iz = 0; iz <=count_of_id; iz++) { 
                var child = $('#second'+div_container+iz);
                child.show();
            }


        for (ii = 0; ii <= count_null_values; ii++) { 
            if(ii == 0){
                 ii = 1;
             }
             var x_var =  $('#serial_number_'+ii).val();
            if(x_var == null || x_var == ''){
                disable++;
            }else{
                disable = 0;
            }
        }

        if(disable == 0){
            $("#save_serial").attr("disabled", true);  
        }else{
            $("#save_serial").attr("disabled", false); 
        }
      }else{
          
        axxxx = $("#"+clicked_id).val();
        qty_current.push(axxxx+div_container);
        var ch = document.getElementById("screens");
        
        var  dcode = clicked_id.substr(0, 8);
                
        for (i = 0; i <=axxxx; i++) {
            count_of_id++;
            count_validation++;
            if(i == 0){
             i = 1;
            }
        document.querySelector('#screens').insertAdjacentHTML(
        'afterbegin',
        `
        <div id="second`+clicked_id+i+`">
        <label>Serial`+i+` #:</label>
        <input type="text" name="serial_number[]`+i+`" class="form-control"  required  id="serial_number_`+i+`"   placeholder="Serial`+i+` #" />
        <input type="hidden" name="code[]"  id="code"`+i+` value="`+dcode+`"  />
        <br>
        </div>
        `      
        )
        } 
        $("#save_serial").attr("disabled", false);  
        
      }
  }
  
  function removeElements() {
    $('#error-message').hide();
    if(div_container1.includes(div_container)){
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.hide();
        }
    }else{
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
        }
    }
}


// Delete item row
$(document).ready(function (e) {
  $('#pullout-items').on('click', '.delete_item', function () {
    //problem_loop = problem_loop - 1;
    var  v = $(this).attr("id").substr(0, 8);
    stack = jQuery.grep(stack, function (value) {
      return value != v;
    });

    $(this).closest("tr").remove();
    var subTotalQuantity = calculateTotalQuantity();
    $("#totalQuantity").val(subTotalQuantity);
    execute = 0;

    for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
    }
     //div_container1 = [];
  });
});

$(document).on('keyup', '.no_units', function (ev) {
    $('#'+ $(this).attr("data-id")).val(this.value);
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
var add = 0;
var permission = 0;
$("#save_serial").on('click',function(){
        var strconfirm = confirm("Are you sure you want to save this serials?");
        if (strconfirm == true) {
            var data = $('#myform').serialize();
                for (ii = 0; ii <= count_null_values; ii++) { 
                    if(ii == 0){
                        ii = 1;
                    }
                   var x_var =  $('#serial_number_'+ii).val();
                   if(x_var == null || x_var == ''){
                        permission++
                    }else{
                        permission = 0;
                    }            
                } 
                if(permission == 0){
                $("#save_serial").attr("disabled", true);
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_sts/AddSerail') }}',
                        data: data,
                        success: function(data){
                           if(data['serail_restriction'] != 'available'){
                                $('#error-message').show();
                                var myJSON =  JSON.stringify(data['serail_restriction']);
                                var newStr = myJSON.replace(/'"]}/g, "");
                                var newStr1 = newStr.replace(/key 'serial_number/g, "Serial Number!");
                                document.getElementById("p1").innerHTML = newStr1.substr(28,200);
                           }else{
                                $('#error-message').hide();
                                $("#serialize_"+serialize_value).val("1");
                                $('#excel-upload-modal').modal('hide');
                                //$("#save_serial").attr("disabled", true);
                                div_container1.push(div_container);
                                related_items.push(div_container.substr(0, 8));
                                for (iz = 0; iz <=count_of_id; iz++) { 
                                     var child = $('#second'+div_container+iz);
                                     child.hide();
                                     $("#serial_number_"+iz).attr("disabled", true);
                                 }
                                 console.log(data);
                                 $("#"+div_container.substr(0, 8)).prop("readonly", true);
                           }
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                        
                        
                  });
                }else{
                    alert("Please fill out serial number!");
                    permission= 0;
                }
                  return false;
                  window.stop();
        }else{
                  return false;
                  window.stop();
        }
      });

$(document).ready(function() {
     $('.js-example-basic-multiple').select2();
     $(".js-example-basic-multiple").select2({
     theme: "classic"
     });
     $('#other_items_included').hide();  
     $('#other_store_diagnosis').hide();  
}); 

$('#items_included').change(function(){
    if($('#items_included').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#other_items_included').show();
        $('#other_items_included').attr("required", true);
    }else{
        $('#other_items_included').val("");
        $('#other_items_included').hide();  
        $('#other_items_included').attr("required", false);
    }
});


$('#diagnosis').change(function(){
    if($('#diagnosis').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#other_store_diagnosis').show();
        $('#other_store_diagnosis').attr("required", true);
    }else{
        $('#other_store_diagnosis').hide();  
        $('#other_store_diagnosis').attr("required", false);  
    }
});

$("#excel-upload-modal").on("hidden.bs.modal", function () {
    if(div_container1.includes(div_container)){
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.hide();
        }
    }else{
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
        }
    }
});

$('#first_name').keypress(function (e) {
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

    $('#middle_initial').keypress(function (e) {
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

    $('#last_name').keypress(function (e) {
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

    $('#prepared_by').keypress(function (e) {
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
