<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <!-- Your html goes here -->
<div class='panel panel-default'>
    <div class='panel-heading'>Details Form</div>
        <div class="message-pos">
            <div class="message-circ">
                <i class="fa fa-envelope" style="color: #fff; font-size: 20px;"></i>
            </div>
            <div class="chat-container">
                <div class="chat-content" style="display: none;">
                    <div class="hide-chat">
                        <i class="fa fa-close" style="color: #fff;"></i>
                    </div>
                    @include('components.ecomm.chat-app', $comments_data)
                </div>
            </div>
        </div>
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="closing" id="closing">
                <div id="requestform" class='panel-body'>
                    <div> 
                            @if($row->received_by != null && $row->diagnose == "REPLACE")

                                
                                <div class="row" style="background-color: #3C8DBC;">                           
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 25px;">{{ trans('message.table.comments3') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 25px; color: white; margin-top: 5px;">{{$row->notes}}</p>
                                    </div>
                                    
                                </div>

                                
                                <div class="row" style="background-color: #3C8DBC;">                           
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 20px;">{{ trans('message.form-label.checked_by') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 20px; color: white; margin-top: 5px;">{{$row->received_by}}</p>
                                    </div>
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 20px;">{{ trans('message.form-label.checked_date') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 20px; color: white; margin-top: 5px;">{{$row->level6_personnel_edited}}</p>
                                    </div>
                                </div>
                                <hr/>
                            @endif
                            
 
                            
                            <?php   $Mode = $row->mode_of_return;  $Diagnose = $row->diagnose; ?>

                            @if($row->mode_of_return == "DOOR-TO-DOOR")
                            
                                @if($row->diagnose == "REPLACE")
                                
                                    <div class="row" id="dr">
                                        
                                        <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.dr_number') }}</label>
                                        <div class="col-md-4">
                                                <input type='input' name='dr_number' id="dr_number" class='form-control' autocomplete="off" maxlength="50"  onkeypress="return AvoidSpace(event)"  required placeholder="DR#" />                             
                                        </div>
                                            
                                    </div> 
                                    
                                    <hr/>
                                    
                                @endif 
                                
                            @endif    
                            
                            <table class="custom_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.return_reference_no') }}</td>
                                        <td>{{$row->return_reference_no}}</td>
                                        <td>{{ trans('message.form-label.created_at') }}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.purchase_location') }}</td>
                                        <td>{{$row->purchase_location}}</td>
                                        <td>{{ trans('message.form-label.store') }}</td>
                                        <td>{{$row->store}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.customer_last_name') }}</td>
                                        <td>{{$row->customer_last_name}}</td>
                                        <td>{{ trans('message.form-label.customer_first_name') }}</td>
                                        <td>{{$row->customer_first_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.address') }}</td>
                                        <td>{{$row->address}}</td>
                                        <td>{{ trans('message.form-label.email_address') }}</td>
                                        <td>{{$row->email_address}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.contact_no') }}</td>
                                        <td>{{$row->contact_no}}</td>
                                        <td>{{ trans('message.form-label.order_no') }}</td>
                                        <td>{{$row->order_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.purchase_date') }}</td>
                                        <td>{{$row->purchase_date}}</td>
                                        <td>{{ trans('message.form-label.mode_of_payment') }}</td>
                                        <td>{{$row->mode_of_payment}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.items_included') }}</td>
                                        @if($row->items_included_others  != null)
                                            <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                            @else
                                                <td>{{$row->items_included}}</td>
                                        @endif
                                        <td>{{ trans('message.form-label.customer_location') }}</td>
                                        <td>{{$row->customer_location}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                        @if($row->verified_items_included_others  != null)
                                                <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</td>
                                            @else
                                                <td>{{$row->verified_items_included}}</td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <br>

                            @if($row->scheduled_by != null  || $row->scheduled_by != "")
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                        <td>{{$row->scheduled_by}}</td>
                                        <td>{{ trans('message.form-label.scheduled_at') }}</td>
                                        <td>{{$row->level2_personnel_edited}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.return_schedule1') }}</td>
                                        <td>{{$row->return_schedule}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            @endif

                            <br>
                            
                            <table  class='table table-striped table-bordered table-font'>
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                        <th width="10%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                        <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                        <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th>
                                        <th width="10%" class="text-center">{{ trans('message.table.brand') }}</th>
                                        <th width="10%" class="text-center">{{ trans('message.table.serial_no') }}</th>
                                        <th width="15%" class="text-center">{{ trans('message.table.problem_details') }}</th>
                                        <th width="5%" class="text-center">{{ trans('message.table.quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultlist as $rowresult)
                                    <tr>
                                        <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->upc_code}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->cost}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                                        <td style="text-align:center" height="10">{{$rowresult->problem_details}}
                                            @if($rowresult->problem_details_other != null)
                                                <br>
                                                {{$rowresult->problem_details_other}}
                                            @endif
                                        </td>
                                        <td style="text-align:center" height="10">{{$rowresult->quantity}}</td>
                                    </tr>
                                @endforeach
                                
                                </tbody>
                            </table> 

                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.table.comments1') }}</td>
                                        <td>{{$row->comments}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <hr/>

                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.diagnosed_by') }}</td>
                                        <td>{{$row->diagnosed_by}}</td>
                                        <td>{{ trans('message.form-label.diagnosed_at') }}</td>
                                        <td>{{$row->level3_personnel_edited}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.table.comments2') }}</td>
                                        <td>{{$row->diagnose_comments}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($row->diagnose == "REPLACE")
                                <hr/>
                                <table class="custom_normal_table">
                                    <tbody>
                                        <tr>
                                            <td>{{ trans('message.form-label.transacted_by') }}</td>
                                            <td>{{$row->printed_by}}</td>
                                            <td>{{ trans('message.form-label.transacted_at') }}</td>
                                            <td>{{$row->level4_personnel_edited}}</td>
                                        </tr>
                                        @if($row->sor_number != null || $row->sor_number != "")
                                        <tr>
                                            <td>{{ trans('message.form-label.sor_number') }}</td>
                                            <td>{{$row->sor_number}}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @endif 


                            @if($row->returns_status_1 != 20)
                            <hr/>
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.printed_by') }}</td>
                                        <td>{{$row->printed_by}}</td>
                                        <td>{{ trans('message.form-label.printed_at') }}</td>
                                        <td>{{$row->level3_personnel_edited}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                    </div>
                </div>
                <div class='panel-footer'>
                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    
                    <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.close') }}</button>
                    @if($row->diagnose == "REPLACE")
                     <!-- <button class="btn btn-warning pull-right" type="submit" id="check" style="margin-right:10px; width:135px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.check') }}</button> -->
                    @endif
                </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

function chatBox(){
    $('.hide-chat').on('click', function(){
        $(this).hide();
        $('.chat-content').hide();
    })

    $('.message-circ').on('click', function(){
        const scrollBody = $('.scroll-body');

        $('.hide-chat').show();
        $('.chat-content').show();

        scrollBody.ready(function() {
            scrollBody.animate({scrollTop: scrollBody.prop('scrollHeight')}, 1000)
            reloadInfo();
        });
        
        $('.type-message').focus();
    })
}

chatBox();

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


$("#check").on('click',function() {
    var strconfirm = confirm("Are you sure you want to check with SDM?");
        if (strconfirm == true) {
                $("#closing").val("Check with SDM");
                return true;
        }else{
                return false;
                window.stop();
        }
});

var Mode = <?php echo json_encode($Mode); ?>;

var Diagnose = <?php echo json_encode($Diagnose); ?>;

$("#btnSubmit").on('click',function() {
        var signal = 0;
        var alert_message = 0;
        
    if(Mode == "DOOR-TO-DOOR") {  
        
        if(Diagnose == "REPLACE"){
            
            if($("#dr_number").val().includes("DR#")){
                
                if($("#dr_number").val().includes(" ")){
                    
                    signal = 0;
                    alert_message = 1;
                    
                }else if(text_length <= 4){
                    
                        signal = 0;
                        alert_message = 1;
                        
                }else{
                    
                    signal =1;
                    restriction = 0;
                    
                }
                
            }else{
                
                signal = 0;
                alert("Incorrect DR# format! e.g. DR#1001");
    
                return false;  
            }
            
        }
        
    }
        
        
        /*
        var text_length = $("#negative_positive_invoice").val().length;
        
        if($("#negative_positive_invoice").val().includes("INV#")){
            
            if($("#negative_positive_invoice").val().includes(" ")){
                signal = 0;
                alert_message = 1;
            }else if(text_length <= 4){
                    signal = 0;
                    alert_message = 1;
            }else{
                signal =1;
                restriction = 0;
            }
            
        }else{
            
            signal = 0;
            alert("Incorrect Negative/Positive Invoice format! e.g. INV#1001");

            return false;  
        }


       if($("#pos_replacement_ref").val().includes("REP#") || $("#pos_replacement_ref").val().includes("ST#") || $("#pos_replacement_ref").val().includes("CRF#") ){
                
                if($("#pos_replacement_ref").val().includes(" ")){
                    signal = 0;
                   // alert_message = 1;
                    alert("Incorrect POS Replacement Ref# format! e.g. REP#1001/ST#1001");
                    return false;
                }else if(text_length <= 4){
                        signal = 0;
                        //alert_message = 1;
                        alert("Incorrect POS Replacement Ref# format! e.g. REP#1001/ST#1001");
                        return false;
                }else{
                    signal =    1;
                }
                
        }else{
                signal = 0;
               // alert_message = 1;
                alert("Incorrect POS Replacement Ref# format! e.g. REP#1001/ST#1001");
                return false;
        }
            
        */

    $("#closing").val("Close");

});


$(document).ready(function(){
  $("myform").submit(function(){
        $('#btnSubmit').attr('disabled', true);
  });
});


</script>
@endpush 