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
                    <input type="hidden" value="{{ $edit_type }}" name="edit_type" id="edit_type">
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
                                    @if($row->revise_st_number_pull_out != null)
            
                                                    <p>{{$row->revise_st_number_pull_out}}</p>
                        			@else
                        
                         					<p>{{$row->st_number_pull_out}}</p>
                         
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
                        <hr color="black" >  
                        @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                                              
                        <div class="row"> 
                            @if ($row->pullout_type == "rma") 
                                <label class="control-label col-md-2">RMA Specialist:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div> 
                            @else
                                <label class="control-label col-md-2">SIM Specialist:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div>                              
                            @endif
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
                        <!--TABLE-->
                        <br>
                        @endif
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
                        <hr color="black">
                        <div class="row">
                            <label class="control-label col-md-2">Comment:</label>
                            <div class='col-md-12'>
                                <textarea class="form-control" rows="7" name="comments3" id="comments3"></textarea>
                            </div>
                        </div>
                </div>
            </div>
            <div class='panel-footer'>           
                <input type='submit' class='btn btn-danger' id="cancelled" value='Cancel' style="width:85px;"/>
            </div>
        </form>
    </div>
@endsection

@push('bottom')
<script type="text/javascript">
function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

$( "#datepicker" ).datepicker( { minDate: '0', dateFormat: 'yy-mm-dd' } );

$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});
$("#cancelled").on('click',function(){
    var strconfirm = confirm("Are you sure you want to cancel this pullout request?");
        if (strconfirm == true) {
            return true;
        }else{
            return false;
            window.stop();
        }
});
</script>
@endpush