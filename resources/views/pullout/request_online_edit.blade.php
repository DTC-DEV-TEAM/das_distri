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
            <div id="requestform" class='panel-body'>
             <div> 
                <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <div class="row">  
                                        @if($list == 1)
                                                <label class="control-label col-md-2">Online Customer:</label>
                                                <div class="col-md-5">
                                                        <select class="js-example-basic-single" name="online_customer" id="online_customer" required style="width:100%">
                                                                        <option value="">-- Select Customer Name --</option>
                                                                @foreach($online_list as $datas)    
                                                                        <option value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                                @endforeach
                                                        </select>
                                                </div>
                                                @else
                                                <label class="control-label col-md-2">Distribution Customer:</label>
                                                <div class="col-md-5">
                                                        <select class="js-example-basic-single" name="online_customer" id="online_customer" required style="width:100%">
                                                                        <option value="">-- Select Customer Name --</option>
                                                                @foreach($online_list as $datas)    
                                                                        <option value="{{$datas->online_customer_name}}">{{$datas->online_customer_name}}</option>
                                                                @endforeach
                                                        </select>
                                                </div>
                                        @endif
                        </div>
                     
                        <div class="row" id="show">
                                <br>
                                <label class="control-label col-md-2"></label>
                                <div class="col-md-5">
                                                <input type='input'  name='other_online_customer' id="other_online_customer" autocomplete="off" class='form-control' maxlength="50" placeholder="OTHER ONLINE CUSTOMER" style="height:40px;" /> 
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
                                     <p>{{$row->path_name}}

                                     </p>
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
                        
                        @if($row->pullout_status == 'REJECTED')
                                @if($row->approver_franchise_at != null)
                                        <div class="row"> 
                                                <label class="control-label col-md-2">Franchise Approver:</label>
                                                <div class="col-md-4">
                                                        <p>{{$row->approver_franchise}}</p>
                                                </div>
                                                <label class="control-label col-md-2">Franchise Approved Date:</label>
                                                <div class="col-md-4">
                                                        <p>{{$row->approver_franchise_at}}</p>
                                                </div> 
                                        </div>
                                @endif
                                @if($row->approver_franchise_rejected_at != null)
                                        <div class="row"> 
                                                <label class="control-label col-md-2">Franchise Approver:</label>
                                                <div class="col-md-4">
                                                        <p>{{$row->approver_franchise}}</p>
                                                </div>
                                                <label class="control-label col-md-2">Franchise Rejected Date:</label>
                                                <div class="col-md-4">
                                                        <p>{{$row->approver_franchise_rejected_at}}</p>
                                                </div> 
  
                                        </div>
                                @endif
                                @if($row->rejected_at_level1 != null)     
                                        <div class="row"> 
                                                <label class="control-label col-md-2">Rejected By:</label>
                                                <div class="col-md-4">
                                                        <p>{{$row->rejectedlevel}}</p>
                                                </div> 
                                                 <label class="control-label col-md-2">Rejected Date:</label>
                                                <div class="col-md-4">
                                                         <p>{{$row->rejected_at_level1}}</p>
                                                </div>                            
                                        </div>
                                @endif
                        @else
                            @if($row->approver_franchise_at != null)
                                <div class="row"> 
                                        <label class="control-label col-md-2">Franchise Approver:</label>
                                        <div class="col-md-4">
                                                <p>{{$row->approver_franchise}}</p>
                                        </div>
                                        <label class="control-label col-md-2">Franchise Approved Date:</label>
                                        <div class="col-md-4">
                                                <p>{{$row->approver_franchise_at}}</p>
                                        </div> 
                                </div>
                            @endif
                            @if($row->approved_at_level1 != null)
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
                            @endif
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
                        <div class="row">
                                <label class="control-label col-md-2">Requestor Comment:</label>
                                    <div class="col-md-4">
                                        @if($row->requestor_comments == null)
                                            ____________________
                                        @else 
                                            <p>{{$row->requestor_comments}}</p>
                                        @endif
                                </div>
                        </div>
                        @if($row->approver_fra_comment != null || $row->approver_fra_comment != "")
                                <div class="row"> 
                                    <label class="control-label col-md-3">Comment of Franchise Approver:</label>
                                    <div class="col-md-9">
                                             <p>{{$row->approver_fra_comment}}</p>
                                    </div>                            
                                </div>
                         @endif
                        <hr color="black">
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
                </div>
                <div class='panel-footer'>           
                        <input type='submit' class='btn btn-primary' id="save_button" value='Save'/>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('bottom')
<script type="text/javascript">
$(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('#show').hide();  

$('#online_customer').change(function(){
    if($('#online_customer').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#show').show();
        $('#other_online_customer').attr("required", true);
    }else{
        $('#other_online_customer').val("");
        $('#show').hide();  
        $('#other_online_customer').attr("required", false);
    }
});

});

</script>
@endpush 
