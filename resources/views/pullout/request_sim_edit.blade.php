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
                <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="row"> 
                            <div class='form-group'>
                                <div class='col-md-6'>    
                                    <label class="control-label">SOR#/MOR#:</label>   
                                    <input type='input' name='sor_mor_number' id="sor_mor_number" class='form-control' autocomplete="off" required  maxlength="50" placeholder="SOR#/MOR#" onkeypress="return AvoidSpace(event)"/>                 
                                </div>
                            </div>
                        </div>
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
                            <label class="control-label col-md-2">ST#/Ref#:</label>
                            <div class="col-md-4">
                                @if($row->st_number_pull_out == null)
                                    ____________________
                                @else 
                                    @if($row->revise_st_number_pull_out != null)
            
                                                    <p>{{$row->revise_st_number_pull_out}}</p>
                        			@else
                        
                         					<p>{{$row->st_number_pull_out}}</p>
                         
                        			@endif
                                @endif
                            </div>                            
                        </div>
                        @if ($row->pullout_type == 'rma')
                        <div class="row">
                                <label class="control-label col-md-2">SROF#:</label>
                                 <div class="col-md-4">
                                        <p>{{$row->srof_number}}</p>
                                </div>
                        </div>
                        @endif
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
                                     <p>{{$row->path_name}} </p>
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
                        <hr color="black">
                        <div class="row">
                            <label class="control-label col-md-1">Comment:</label>
                            <div class="col-md-4">
                                @if($row->comments == null)
                                        ____________________
                                 @else 
                                        <p>{{$row->comments}}</p>
                                @endif
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
        var text_length = $("#sor_mor_number").val().length;
        
        if($("#sor_mor_number").val().includes("SOR#") || $("#sor_mor_number").val().includes("MOR#")){
            
            if($("#sor_mor_number").val().includes(" ")){
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


       if(signal != 0){
            return true;   
        }else{
            if(alert_message == 1){
                alert("Incorrect SOR#/MOR# format! e.g. SOR#1001/MOR#1001");
            }
            return false;  
        }
        
    });

$( "#datepicker" ).datepicker( {minDate: '0', dateFormat: 'yy-mm-dd' } );

$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});
</script>
@endpush