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
            <div class='panel-body'>
             <div id="printableArea"> 
                <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="row"> 
                            <div class='form-group'>
                                <div class='col-md-5'>    
                                    <label class="control-label">Return Schedule:</label>   
                                    <input type='input' name='return_schedule' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
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
                                    <p>{{$row->logiscticslevel}}</p>
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
                        <!--<hr color="black" > 
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
                                       @if($row->pullout_status == 'VOID WARRANTY' || $row->pullout_status == 'REPAIRED') 
                                            <p>{{$row->rejected_at_level5}}</p> 
                                       @else
                                            <p>{{$row->approved_at_level5}}</p> 
                                        @endif
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
                    <input type='submit' class='btn btn-primary' value='Save'/>
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

  $( "#datepicker" ).datepicker( { minDate: '1', dateFormat: 'yy-mm-dd' } );

$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});
</script>
@endpush