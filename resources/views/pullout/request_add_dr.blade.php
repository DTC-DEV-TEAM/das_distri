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
        <div class='panel-heading'>Edit Form</div>
            <div class='panel-body'>
             <div id="printableArea"> 
                <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="row"> 
                            <div class='form-group'>
                                <div class='col-md-6'>    
                                        <label class="require control-label">*{{ trans('message.form-label.dr_number') }}</label>
                                        <input type='input' required name='dr_number' id="dr_number" autocomplete="off" class='form-control' maxlength="50" placeholder="DR#" onkeypress="return AvoidSpace(event)" />                                              
                                 </div>
                            </div>
                        </div>
                        <!--<hr color="black" >
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="require control-label">{{ trans('message.form-label.items_inclusion') }}</label>
                                    <select class="js-example-basic-multiple"  name="items_inclusion[]" id="items_inclusion" multiple="multiple" required  style="width:100%">
                                           @foreach($expected_items_included_list as $datas)     
                                                <option value="{{$datas->items_description_included}}">{{$datas->items_description_included}}</option>
                                            @endforeach 
                                    </select>
                                    <p style="font: italic bold 12px/30px arial, arial;">e.g Box, Charger</p>
                                </div>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                            <br>
                                            <input type='input'  name='items_inclusion_other' id="items_inclusion_other" autocomplete="off" class='form-control' maxlength="50" placeholder="OTHER EXPECTED INCLUSION ITEMS" style="height:40px;" /> 
                                </div>
                            </div>
                        </div> -->
                        <hr color="black" >
                        <div class="row">                           
                            <label class="control-label col-md-2">Requestor:</label>
                            <div class="col-md-4">
                                    <p>{{$row->requestorlevel}}</p>
                            </div>
 
                            <label class="control-label col-md-2">Created Date:</label>
                            <div class="col-md-4">
                                    <p>{{$row->created_at}}</p>
                            </div>
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">Requested Date for Pullout:</label>
                            <div class="col-md-4">
                                    <p>{{$row->pull_out_schedule_date}}</p>
                            </div>
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">MP#:</label>
                            <div class="col-md-4">
                                    <p>{{$row->reference}}</p>
                            </div> 
                            <label class="control-label col-md-2">SROF#:</label>
                            <div class="col-md-4">
                                    <p>{{$row->srof_number}}</p>
                            </div>                            
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout From:</label>
                            <div class="col-md-4">
                                    <p>{{$row->store_name}}</p>
                            </div> 
                            <label class="control-label col-md-2">Deliver To:</label>
                            <div class="col-md-4">
                                    <p>{{$row->stores_deliver_to}}</p>
                            </div>                            
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout Reason:</label>
                            <div class="col-md-4">
                                    <p>{{$row->reason_name}}</p>
                            </div> 
                            <label class="control-label col-md-2">Pullout Via:</label>
                            <div class="col-md-4">
                                     <p>{{$row->path_name}}</p>
                            </div>                            
                        </div>
                        @if($row->path_name == "REQUESTOR")
                        <div class="row"> 
                            <label class="control-label col-md-2">Hand Carried By:</label>
                            <div class="col-md-4">
                                     <p>{{$row->hand_carry_by}}</p>
                            </div>                            
                        </div>
                        @endif
                        <div class="row"> 
                            <label class="control-label col-md-2">Approver:</label>
                            <div class="col-md-4">
                                    <p>{{$row->approverlevel}}</p>
                            </div> 
                             <label class="control-label col-md-2">Approved Date:</label>
                            <div class="col-md-4">
                                     <p>{{$row->approved_at_level1}}</p>
                            </div>                            
                        </div>
                        @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                        <hr color="black" > 
                        @if($row->sor_mor_number != NULL)
                            <div class="row"> 
                                <label class="control-label col-md-2">SDM Specialist:</label>
                                <div class="col-md-4">
                                        <p>{{$row->simlevel}}</p>
                                </div> 
                                 <label class="control-label col-md-2">SOR/MOR Date:</label>
                                <div class="col-md-4">
                                         <p>{{$row->approved_at_level2}}</p>
                                </div>                            
                             </div>  
                             <div class="row"> 
                                <label class="control-label col-md-2">SOR#/MOR#:</label>
                                <div class="col-md-4">
                                        <p>{{$row->sor_mor_number}}</p>
                                </div>                             
                             </div>  
                        @endif
                        <!--TABLE-->
                        <br>
                        <table  class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th style="text-align:center" height="10">Digits Code</th>
                                        <th style="text-align:center" height="10">UPC Code</th>
                                        <th style="text-align:center" height="10">Item Description</th>
                                        <th style="text-align:center" height="10">Brand</th>
                                        <th style="text-align:center" height="10">WH Category</th>
                                        <th style="text-align:center" height="10">Qty</th>
                                     </tr>
                                </thead>
                        <tbody>
                            @foreach($resultlist as $rowresult)
                                <tr>
                                      <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->upc_code}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->category}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->quantity}}</td>                    
                                </tr>
                            @endforeach
                                        <tr>
                                            <td style="text-align:right" height="10" colspan="5"><label>Total Quantity:</label></td>
                                            <td style="text-align:center" height="10"><p>{{$row->total_quantity}}</p></td>    
                                        </tr>
                        </tbody>
                        </table>
                        @endif
                        <hr color="black" > 
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout Date:</label>
                            <div class="col-md-4">
                                    <p>{{$row->approved_at_level3}}</p>
                            </div> 
                            <label class="control-label col-md-2">Approved By:</label>
                            <div class="col-md-4">
                                @if($row->logiscticslevel != null )
                                        <p>{{$row->logiscticslevel}}</p>
                                    @else
                                        ____________________
                                @endif
                            </div>                            
                        </div> 
                        <hr color="black" > 
                        <div class="row"> 
                            <label class="control-label col-md-2">Released Date:</label>
                            <div class="col-md-4">
                                    <p>{{$row->released_date}}</p>
                            </div> 
                            <label class="control-label col-md-2">Released By:</label>
                            <div class="col-md-4">
                                    <p>{{$row->released_by}}</p>
                            </div>                            
                        </div> 
                       <!-- <hr color="black" > 
                        <div class="row"> 
                            <label class="control-label col-md-2">WRF #:</label>
                            <div class="col-md-4">
                                    <p>{{$row->wrf_number}}</p>
                            </div> 
                            <label class="control-label col-md-2">Scanned By:</label>
                            <div class="col-md-4">
                                    <p>{{$row->scanned_by}}</p>
                            </div>                            
                        </div> 
                        <div class="row">
                                <label class="control-label col-md-2">WRF Date:</label>
                                <div class="col-md-4">
                          
                                        <p>{{$row->wrf_date}}</p>
                                    </div> 
                        </div>-->
                        <hr color="black" > 
                        <div class="row"> 
                                <label class="control-label col-md-2">Received Date:</label>
                                <div class="col-md-4">
                                      <!--  <p>{{ $row->received_at != null ? date('m-d-Y', strtotime($row->received_at)): "" }}</p>
                                      -->
                                        <p>{{$row->received_at}}</p> 
                                </div> 
                                <label class="control-label col-md-2">Received By:</label>
                                <div class="col-md-4">
                                        <p>{{$row->received_by}}</p> 
                                </div>                            
                        </div>
                        <hr color="black" > 
                        <div class="row"> 
                                <label class="control-label col-md-2">Transacted Date:</label>
                                <div class="col-md-4">
                                       <!-- <p>{{ $row->approved_at_level5 != null ? date('m-d-Y', strtotime($row->approved_at_level5)): "" }}</p>
                                       -->
                                        <p>{{$row->approved_at_level5}}</p> 
                                    </div> 
                                <label class="control-label col-md-2">Transacted By:</label>
                                <div class="col-md-4">
                                        <p>{{$row->received_by}}</p> 
                                </div>                            
                        </div>
                        <div class="row"> 
                                <label class="control-label col-md-2">Warranty Status:</label>
                                <div class="col-md-4">
                                        <p>{{$row->pullout_status}}</p> 
                                </div> 
                          
                        </div>
                        <hr color="black" > 
                        <div class="row">
                                <label class="control-label col-md-2">Comment:</label>
                                <div class="col-md-9">
                                        <p>{{$row->comments2}}</p>
                                </div>  
                        </div>

                </div>
                
            </div>
            <div class='panel-footer'>           
                    <input type='submit' class='btn btn-primary' id="save_button" value='Save'/>
            </div>
        </form>
    </div>
@endsection

@push('bottom')
<script type="text/javascript">

$(document).ready(function() {
     $('.js-example-basic-multiple').select2();
     $(".js-example-basic-multiple").select2({
     theme: "classic"
     });
     $('#items_inclusion_other').hide();  
}); 

$('#items_inclusion').change(function(){
    if($('#items_inclusion').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#items_inclusion_other').show();
        $('#items_inclusion_other').attr("required", true);
    }else{
        $('#items_inclusion_other').val("");
        $('#items_inclusion_other').hide();  
        $('#items_inclusion_other').attr("required", false);
    }
});

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

$("#save_button").on('click',function() {
        var signal = 0;
        var alert_message = 0;
        var text_length = $("#dr_number").val().length;
        //var text_length1 = $("#st_number_pull_out").val().length;
        var items_inclusion_field = $("#items_inclusion_other").val();
 
        if($("#dr_number").val().includes("DR#")){
            
            if($("#dr_number").val().includes(" ")){
                signal = 0;
                alert_message = 1;
            }else if(text_length <= 3){
                    signal = 0;
                    alert_message = 1;
            }else if(items_inclusion_field == "COMPLETE" || items_inclusion_field == "complete" || items_inclusion_field == "COMPLETES" || items_inclusion_field == "completes" ){
                    signal = 0;
                    alert_message = 6;
            }else{
                signal = 1;
                restriction = 0;
            }
        }else{
            signal = 0;
            alert_message = 1;
        }

       if(signal != 0){
            return true;   
        }else{
            if(alert_message == 1){
                alert("Incorrect DR# format! e.g. DR#1001");
                
            }else if(alert_message == 6){
                alert("Complete is not allowed in expected items inclusion!");
            }
            return false;  
        }

});
</script>
@endpush