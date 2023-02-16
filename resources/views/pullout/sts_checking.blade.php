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
                                        <p>{{ $row->wrf_date != null ? date('m-d-Y', strtotime($row->wrf_date)) : ""}}</p>
                                </div> 
                        </div>-->
                        <hr color="black" > 
                        <div class="row"> 
                                <label class="control-label col-md-2">Received Date:</label>
                                <div class="col-md-4">
                                        <p>{{ $row->received_at != null ? date('m-d-Y', strtotime($row->received_at)): "" }}</p>
                                </div> 
                                <label class="control-label col-md-2">Received By:</label>
                                <div class="col-md-4">
                                        <p>{{$row->received_by}}</p> 
                                </div>                            
                        </div>
                      
                        <hr color="black" > 
                            <label class="require control-label">*Receiving Type:</label>
                                <div class="row">   
                                    @foreach($ReceivingType as $data)
                                        <div class="col-md-6">
                                            <label class="radio-inline control-label col-md-5"><input type="radio" id="sts_receiving_type"  name="sts_receiving_type" value="{{$data->receiving_description}}" >{{$data->receiving_description}}</label>
                                        </div>
                                    @endforeach
                                </div> 
                        <hr color="black" > 
                        <div class="row">
                                <label class="control-label col-md-2">Comment:</label>
                                <div class='col-md-12'>
                                    <textarea class="form-control" rows="7" name="comments2" id="comments2" required></textarea>
                                </div>
                        </div>
                </div>
            </div>
            <div class='panel-footer'>           
                    <div class="row"> 
                            <input type="hidden" value="{{$row->id}}" name="id">  
                            <div class='col-md-2'>
                                <input type='submit' class='btn btn-success' id="approved" onclick="printDiv('requestform')" value='For Replacement' style="width:170px;"/>
                            </div>
                            <div class='col-md-2'>
                                <input type='submit' class='btn btn-danger' id="disapproved" onclick="disapprovedform('requestform')" value='Void' style="width:170px;"/>
                            </div>
                            <div class='col-md-2'>
                                <input type='submit' class='btn btn-danger' id="repaired" onclick="disapprovedform('requestform')" value='For Repair' style="width:170px;"/>
                            </div>
                            <div class='col-md-2'>
                                <input type='submit' class='btn btn-danger' id="cancel" onclick="disapprovedform('requestform')" value='Cancel Repair' style="width:170px;"/>
                            </div>
                    </div>  
            </div>
        </form>
    </div>
@endsection

@push('bottom')
<script type="text/javascript">

document.getElementById("sts_receiving_type").required = true;

jQuery("#sts_receiving_type").attr('checked', true);

var $radio = $("input:radio");

$radio.change(function(){
    var type = $('input[name=sts_receiving_type]:checked').val();
    if(type == "PICK-UP AT SERVICE CENTER"){
        document.getElementById("approved").disabled = true;
    }else{
        document.getElementById("approved").disabled = false;
    }
});

function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

    $("#approved").on('click',function(){
        var type = $('input[name=sts_receiving_type]:checked').val();
        var strconfirm = confirm("Are you sure you want to replace this pullout request with "+type+" receiving type?");
       // var strconfirm = confirm("Are you sure you want to replace this pullout request?");
        if (strconfirm == true) {
            if($("#comments2").val() == ""){
              alert("Please put a comment!");
            }else{
                var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_receiving_store/InWarrantyRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );              
                        
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
            }
        }else{
                  return false;
                  window.stop();
        }
      });
    $("#disapproved").on('click',function(){
        var type = $('input[name=sts_receiving_type]:checked').val();
        var strconfirm = confirm("Are you sure you want to void this pullout request with "+type+" receiving type?");
       // var strconfirm = confirm("Are you sure you want to void this pullout request?");
        if (strconfirm == true) {
          if($("#comments2").val() == ""){
              alert("Please put a comment!");
            }else{
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_receiving_store/VoidWarrantyRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
            }
        }else{
                  return false;
                  window.stop();
        }
      });
      $("#repaired").on('click',function(){
        var type = $('input[name=sts_receiving_type]:checked').val();          
       var strconfirm = confirm("Are you sure you want to repair this pullout request with "+type+" receiving type?");
       //var strconfirm = confirm("Are you sure you want to repair this pullout request?");
        if (strconfirm == true) {
          if($("#comments2").val() == ""){
              alert("Please put a comment!");
            }else{
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_receiving_store/RepairedRequest') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
            }
        }else{
                  return false;
                  window.stop();
        }
      });
      $("#cancel").on('click',function(){
        var type = $('input[name=sts_receiving_type]:checked').val();         
        var strconfirm = confirm("Are you sure you want to cancel repair this pullout request with "+type+" receiving type?");
        //var strconfirm = confirm("Are you sure you want to cancel repair this pullout request?");
        if (strconfirm == true) {
          if($("#comments2").val() == ""){
              alert("Please put a comment!");
            }else{
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_receiving_store/CancelRepairRequest') }}',
                        data: data,
                        success: function( rsponse ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
            }
        }else{
                  return false;
                  window.stop();
        }
      });
</script>
@endpush