<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <!-- Your html goes here -->
<div class='panel panel-default'>
    <div class='panel-heading'>Details Form</div>
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
                            <!--
                            <div class="row" style="background-color: #3C8DBC; height:50px;"> 
                                <div class="col-md-6" style="margin-top: 10px; color: white; font-size: 20px;">
                                        
                                        <span >Please Upload Return Form here: </span><a style="margin-top: 2px; color: white;" href="https://drive.google.com/drive/folders/174H74xguMR9rwig12YBIx7jSkXxSEQk9?usp=sharing" target="_blank">&nbsp;<i class="fa fa-cloud-upload fa-lg" ></i></a>
                                   
                                </div>
                            
                            </div> 
                              
                            <hr/>-->
                            
                            @if($row->diagnose == "REPLACE")
                                <div class="row" id="replacement">                           
                                    <label class="control-label col-md-2" style="margin-top:-5px;">{{ trans('message.form-label.negative_positive_invoice') }}</label>
                                    <div class="col-md-4">
                                            <input type='input' name='negative_positive_invoice' id="negative_positive_invoice" class='form-control' autocomplete="off" maxlength="50" placeholder="INV#" onkeypress="return AvoidSpace(event)"  required/>                             
                                    </div>
                                     
    
                                </div> 
                                
                                <div class="row" id="replacement1">
                                    <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.pos_replacement_ref') }}</label>
                                    <div class="col-md-4">
                                            <input type='input' name='pos_replacement_ref' id="pos_replacement_ref" class='form-control' autocomplete="off" maxlength="50"  onkeypress="return AvoidSpace(event)"  required placeholder="REP#" />                             
                                    </div>
                                    
                                    <div class="col-md-6">
                                            
                                            <p style="color:red;"><label style="color:black;">Notes:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*PLEASE CREATE POS REPLACEMENT STOCK ADJUSTMENT TRANSACTION. 
                                              
                                            </p>
                                              
                                            
                                            
                                    </div>
                                
                                </div>                  
                            
                            
                                <hr/>
                            @endif
                            
                            <!-- 1r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.return_reference_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->return_reference_no}}</p>
                                </div>
    
                                
                                <label class="control-label col-md-2">{{ trans('message.form-label.created_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->created_at}}</p>
                                </div>
                            </div>
                            <!-- 2r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_location}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->store}}</p>
                                </div>
                            </div>
                            <!-- 3r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_last_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_last_name}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_first_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_first_name}}</p>
                                </div>
                            </div>
                            <!-- 4r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.address') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->address}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.email_address') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->email_address}}</p>
                                </div>
                            </div>
                            <!-- 5r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.contact_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->contact_no}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.order_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->order_no}}</p>
                                </div>
                            </div>
                            <!-- 6r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_date') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_date}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_payment') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_payment}}</p>
                                </div>
                            </div>                           
                            <!-- 7r -->
                            <!--
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_name}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_account_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_account_no}}</p>
                                </div>
                            </div> -->
                            <!-- 8r -->
                            <!--
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_account_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_account_name}}</p>
                                </div>
                            -->
                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->items_included_others  != null)
                                            <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                        @else
                                            <p>{{$row->items_included}}</p>
                                    @endif
                                   
                                </div>

                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                            </div>

                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.verified_items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->verified_items_included_others  != null)
                                            <p>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                        @else
                                            <p>{{$row->verified_items_included}}</p>
                                    @endif
                                   
                                </div>
                            </div>
                            <hr/>
                            @if($row->scheduled_by != null  || $row->scheduled_by != "")
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->scheduled_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level1_personnel_edited}}</p>
                                </div>
                            </div>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.return_schedule1') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->return_schedule}}</p>
                                </div>
                            </div>
                            @endif
                            <!--
                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                                
                                <label class="control-label col-md-2">{{ trans('message.form-label.sor_number') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->sor_number}}</p>
                                </div>
                            </div> -->
                            <br>
                            <!--TABLE-->
                            <!--<div class="table-responsive">
                                <div class="pic-container">
                                    <div class="pic-row"> -->
                                        <table  class='table table-striped table-bordered'>
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
                                    <!--</div>
                                </div>
                            </div>-->         
                            
                      
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                                <div class="col-md-10">
                                    <p>{{$row->comments}}</p>
                                </div>
                            </div>

                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.diagnosed_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->diagnosed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.diagnosed_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level2_personnel_edited}}</p>
                                </div>
                            </div>

                            <div class="row"> 
                               
                                <label class="control-label col-md-2">{{ trans('message.table.comments2') }}</label>
                                    <div class="col-md-10">
                                        <p>{{$row->diagnose_comments}}</p>
                                    </div>
                            </div>
                            
                            @if($row->diagnose == "REPLACE")
                                    <hr/>
                                    <div class="row">                           
                                        <label class="control-label col-md-2">{{ trans('message.form-label.transacted_by') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->transacted_by}}</p>
                                        </div>
                                        <label class="control-label col-md-2">{{ trans('message.form-label.transacted_at') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->level3_personnel_edited}}</p>
                                        </div>
                                    </div>
                                    @if($row->sor_number != null || $row->sor_number != "")
                                    <div class="row">                              
                                        <label class="control-label col-md-2">{{ trans('message.form-label.sor_number') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->sor_number}}</p>
                                        </div>
                                    </div>
                                    @endif
                            @endif 


                            @if($row->returns_status_1 != 20)
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.printed_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->printed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.printed_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level3_personnel_edited}}</p>
                                </div>
                            </div>
                            @endif
                            <!--
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level6_personnel_edited}}</p>
                                </div>
                            </div>
                            -->

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

$("#btnSubmit").on('click',function() {
        var signal = 0;
        var alert_message = 0;
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
            alert_message = 1;
        }


       if($("#pos_replacement_ref").val().includes("REP#") ){
                
                if($("#pos_replacement_ref").val().includes(" ")){
                    signal = 0;
                   // alert_message = 1;
                    alert("Incorrect POS Replacement Ref# format! e.g. REP#1001");
                    return false;
                }else if(text_length <= 4){
                        signal = 0;
                        //alert_message = 1;
                        alert("Incorrect POS Replacement Ref# format! e.g. REP#1001");
                        return false;
                }else{
                    signal =    1;
                }
                
        }else{
                signal = 0;
               // alert_message = 1;
                alert("Incorrect POS Replacement Ref# format! e.g. REP#1001");
                return false;
        }
            

       if(signal != 0){
            return true;   
        }else{
            if(alert_message == 1){
                alert("Incorrect Negative/Positive Invoice format! e.g. INV#1001");
            }
            return false;  
        }
        
        

        
    $("#closing").val("Close");
});


$(document).ready(function(){
  $("myform").submit(function(){
        $('#btnSubmit').attr('disabled', true);
  });
});


</script>
@endpush 