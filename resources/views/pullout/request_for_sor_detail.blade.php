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
                            @if($row->dr_number != null)
                            <label class="control-label col-md-2">DR#:</label>
                                <div class="col-md-4">
                                        <p>{{$row->dr_number}}</p>
                                </div>
                            @endif
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
                        @if($row->online_transaction == 1)
                                <div class="row"> 
                                        @if($row->privilege_id == 22)
                                                        <label class="control-label col-md-2">Customer Name:</label>
                                                        <div class="col-md-4">
                                                                @if($row->middle_initial == null)
                                                                                <p>{{$row1->first_name}} {{$row1->last_name}}</p>
                                                                        @else
                                                                                <p>{{$row1->first_name}} {{$row1->middle_initial}} {{$row1->last_name}}</p> 
                                                                @endif
                                                        </div> 
                                                        <label class="control-label col-md-2">Address:</label>
                                                        <div class="col-md-4">
                                                                        <p>{{$row1->address}}</p>
                                                        </div> 
                                                @else
                                                        <label class="control-label col-md-2">Online Customer:</label>
                                                        <div class="col-md-4">
                                                            @if($row->other_online_customer == null)
                                                                <p>{{$row->online_customer}}</p>
                                                                @else
                                                                <p>{{$row->online_customer}} : {{$row->other_online_customer}} </p>
                                                            @endif
                                                        </div> 

                                                        <label class="control-label col-md-2">Customer Name:</label>
                                                        <div class="col-md-4">
                                                                @if($row->middle_initial == null)
                                                                                <p>{{$row1->first_name}} {{$row1->last_name}}</p>
                                                                        @else
                                                                                <p>{{$row1->first_name}} {{$row1->middle_initial}} {{$row1->last_name}}</p> 
                                                                @endif
                                                        </div> 
                                        @endif
                                </div>
                        @endif
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
                        @if($row->online_transaction == 1)
                            @if($row->privilege_id != 22)
                                <div class="row"> 
                                        <label class="control-label col-md-2">Transacted By:</label>
                                        <div class="col-md-4">
                                                <p>{{$row->online_personnel}}</p>
                                        </div> 
                                         <label class="control-label col-md-2">Transacted Date:</label>
                                        <div class="col-md-4">
                                                 <p>{{$row->onl_transacted_at}}</p>
                                        </div>   
                                </div>
                            @endif
                         @endif
                        @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                        <hr color="black" >                        
                        <!--TABLE-->
                        <div class="row"> 
                            <!--@if ($row->pullout_type == "rma") 
                                <label class="control-label col-md-2">RMA Specialist:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div> 
                            @else
                                <label class="control-label col-md-2">SIM Specialist:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div>                              
                            @endif-->
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
                        @if($row->sopersonnel != null)
                            <div class="row"> 
                                <label class="control-label col-md-2">SO# By:</label>
                                <div class="col-md-4">
                                    <p>{{$row->sopersonnel}}</p>
                                </div>       
                                <label class="control-label col-md-2">SO# Date:</label>
                                <div class="col-md-4">
                                            <p>{{$row->so_at}}</p>
                                </div>                            
                            </div>
                            <div class="row"> 
                                <label class="control-label col-md-2">SO#:</label>
                                <div class="col-md-4">
                                            <p>{{$row->so_number}}</p>
                                </div>                             
                            </div>
                        @endif
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
                            <label class="control-label col-md-2">Scheduled By:</label>
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
        </form>
    </div>
@endsection
@push('bottom')
<script type="text/javascript">
    $("#approved").on('click',function(){
        var strconfirm = confirm("Are you sure you want to approve this pullout request?");
        if (strconfirm == true) {
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_requests/InWarrantyRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );              
                        
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
        }else{
                  return false;
                  window.stop();
        }
      });
    $("#disapproved").on('click',function(){
        var strconfirm = confirm("Are you sure you want to reject this pullout request?");
        if (strconfirm == true) {
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_requests/VoidWarrantyRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
        }else{
                  return false;
                  window.stop();
        }
      });
</script>
@endpush