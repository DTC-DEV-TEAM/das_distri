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
            <div id="requestform" class='panel-body'>
             <div> 
                <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                    <input type="hidden" value="" name="button_type" id="button_type">
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
                                        <p >{{$row->pull_out_schedule_date}}</p>
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
                        <div class="row">
                            @if ($row->pullout_type == 'rma')
                            
                                    <label class="control-label col-md-2">SROF#:</label>
                                     <div class="col-md-4">
                                            <p>{{$row->srof_number}}</p>
                                    </div>
                                
                            @endif
                                
                            @if($srof_detials->rs_invoice_number != null)
                                
                                    <label class="control-label col-md-2">Invoice#:</label>
                                     <div class="col-md-4">
                                            <p>{{$srof_detials->rs_invoice_number}}</p>
                                    </div>
                                
                            @endif
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
                        @if($row->approver_franchise_by != null || $row->approver_franchise_by != "")
                            <div class="row"> 
                                <label class="control-label col-md-2">Franchise Approver:</label>
                                <div class="col-md-4">
                                        <p>{{$row->approver_franchise}}</p>
                                </div>
                                @if($row->approver_franchise_at != null || $row->approver_franchise_at != "")
                                    <label class="control-label col-md-2">Franchise Approved Date:</label>
                                    <div class="col-md-4">
                                            <p>{{$row->approver_franchise_at}}</p>
                                    </div> 
                                @else
                                    <label class="control-label col-md-2">Franchise Rejected Date:</label>
                                    <div class="col-md-4">
                                            <p>{{$row->approver_franchise_rejected_at}}</p>
                                    </div> 
                                @endif
                            </div>
                        @endif
                        <hr color="black" >                        
                        <!--TABLE-->
                        <table  class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th style="text-align:center" height="10">Digits Code</th>
                                        <th style="text-align:center" height="10">UPC Code</th>
                                        <th style="text-align:center" height="10">Item Description</th>
                                        <th style="text-align:center" height="10">Brand</th>
                                        <th style="text-align:center" height="10">WH Category</th>
                                        <th style="text-align:center" height="10">Serial Number</th>
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
                                      <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                             
                                      <td style="text-align:center" height="10">
                                        @if($rowresult->serialize != 0 || $rowresult->serialize != '0')
                                            1
                                        @else
                                            {{$rowresult->quantity}}
                                        @endif
                                    </td>                    
                                </tr>
                            @endforeach
                                <tr>
                                        <td style="text-align:right" height="10" colspan="6"><label>Total Quantity:</label></td>
                                        <td style="text-align:center" height="10"><p>{{$row->total_quantity}}</p></td>   
                            
                                </tr>
                        </tbody>
                        </table>
                       <!-- <div class="row">
                            <label class="control-label col-md-2">Requestor Comment:</label>
                                <div class="col-md-4">
                                    @if($row->requestor_comments == null)
                                        ____________________
                                    @else 
                                        <p>{{$row->requestor_comments}}</p>
                                    @endif
                            </div>
                        </div>  -->

                        @if($row->approver_fra_comment != null)
                            <div class="row"> 
                                <label class="control-label col-md-3">Comment of Franchise Approver:</label>
                                <div class="col-md-9">
                                         <p>{{$row->approver_fra_comment}}</p>
                                </div>                            
                            </div>
                        @endif

                        <div class="row">
                            <label class="control-label col-md-2">Approver Comment:</label>
                            <div class="col-md-4">
                                @if($row->comments == null)
                                        ____________________
                                 @else 
                                    <p>{{$row->comments}}</p>
                                @endif
                            </div>  
                        </div>
                        
                        <hr color="black">
                        <div class="row">
                            <label class="control-label col-md-3">Comment:</label>
                            <div class='col-md-12'>
                                <textarea class="form-control" rows="7" name="hq_comment" id="hq_comment"></textarea>
                            </div>
                        </div>
                </div>
            </div>
            <div class='panel-footer'>  
               <div class="row"> 
                   <input type="hidden" value="{{$row->id}}" name="id">  
                   <div class='col-md-1'>
                       <input type='submit' class='btn btn-success' id="approved"  value='Approve' style="width:85px;"/>
                   </div>
                   <div class='col-md-1'>
                       <input type='submit' class='btn btn-danger' id="disapproved"  value='Reject' style="width:85px;"/>
                   </div>
               </div>  
            </div>
        </form>
    </div>
@endsection
@push('bottom')
<script type="text/javascript">

function preventBack(){
    window.history.forward();
}
 window.onunload = function(){
    null;
};
setTimeout("preventBack()", 0);

$("#approved").on('click',function(){
        var strconfirm = confirm("Are you sure you want to approve this pullout request?");
        if (strconfirm == true) {
            /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_requests/ApprovedRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );              
                        
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });*/
                  $("#button_type").val("approved");
                  return true;
        }else{
                  return false;
                  window.stop();
        }
      });
    $("#disapproved").on('click',function(){
        var strconfirm = confirm("Are you sure you want to reject this pullout request?");
        if (strconfirm == true) {
            /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_requests/DisapprovedRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                   
                        },
                        error: function( e ) {
                            console.log(e);
                        
                        }
                  });*/
                  $("#button_type").val("rejected");
                  return true;
        }else{
                  return false;
                  window.stop();
        }
      });
</script>
@endpush
