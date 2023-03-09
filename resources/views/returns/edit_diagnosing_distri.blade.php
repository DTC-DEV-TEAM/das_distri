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
            <input type="hidden"  name="diagnose" id="diagnose">
                <div id="requestform" class='panel-body'>
                    <div> 
                            <div class="row"> 
                                <label class="require control-label col-md-2">{{ trans('message.form-label.warranty_status') }}</label>

                                @foreach($warranty_status as $data)

                            
                                            @if($data->warranty_name == $row->warranty_status)
                                                    <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-5" ><input type="radio" required checked    name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                    </div>
                                     
                                        
                                            @else
                                                <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-5"><input type="radio" required  name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                </div>
                                             @endif

                                 @endforeach
                            </div>
                            
                            <hr/>
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
                                
                                <!--
                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_location}}</p>
                                </div>
                                -->

                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>

                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_return') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_return}}</p>
                                </div>

    
                                <!--
                                <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->store}}</p>
                                </div> -->
                            </div>

                            <!-- 2r -->
                             <!--
                            <div class="row">                           
                        
                               
                                @if ($row->branch != null || $row->branch != "")
                                    <label class="control-label col-md-2">{{ trans('message.form-label.branch') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->branch}}</p>
                                    </div>    
                                 @endif    
                            </div>      
                             -->
                             <!--
                            <div class="row">   
                                    
                                        @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                            <label class="control-label col-md-2">{{ trans('message.form-label.store_dropoff') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->store_dropoff}}</p>
                                            </div>
                                        @endif
                                        
                                        @if ($row->branch_dropoff != null || $row->branch_dropoff != "")
                                                <label class="control-label col-md-2">{{ trans('message.form-label.branch_dropoff') }}</label>
                                                <div class="col-md-4">
                                                    <p>{{$row->branch_dropoff}}</p>
                                                </div>    
                                         @endif  
                            </div> 
                            -->                           
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
                           <!-- <div class="row">                           

                              
                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_payment') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_payment}}</p>
                                </div> 
                            </div>  -->                         
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

                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_date') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_date}}</p>
                                </div>

                                <label class="control-label col-md-2">{{ trans('message.form-label.items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->items_included_others  != null)
                                            <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                        @else
                                            <p>{{$row->items_included}}</p>
                                    @endif
                                   
                                </div>
                            </div>
                            <div class="row">  

                                <div class="col-md-6">

                                </div>

                                    <label class="control-label col-md-2">{{ trans('message.form-label.verified_items_included') }}</label>
                                    <div class="col-md-3">

                                        <select   class="js-example-basic-multiple" required name="verified_items_included[]" id="verified_items_included" multiple="multiple" style="width:100%;">
                                            @foreach($items_included_list as $key=>$list)
                                                    @if(strpos($row->verified_items_included, $list->items_description_included) !== false)
                                                            <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                        @else
                                                            <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                    @endif
                                                    
                                            @endforeach
                    
                                        </select>
                                            <?php $other_items_included = $row->items_included_others;?>
                                            <br><br>
                                            <?php $other_verified_items_included = $row->verified_items_included_others;?>
                                            
                                            <input type='input'  name='verified_items_included_others' id="verified_items_included_others" autocomplete="off" class='form-control' value="{{$row->verified_items_included_others}}"/> 
                                         
                                    
                            
                                    </div>
                            </div>
                 <!--
                            <hr/>
                            
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
                            -->
                            <hr>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.tagged_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->closed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.tagged_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level7_personnel_edited}}</p>
                                </div>
                            </div>

                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                            </div> 

                            <hr/>
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                    <div class="col-md-4">
                                         <p>{{$row->closed_by}}</p>
                                    </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.dropoff_schedule') }}</label>
                                <div class="col-md-4">
                                        <p>{{$row->return_schedule}}</p>
                                </div>
                                </div>                                             
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->scheduled_by_logistics}}</p>
                                    </div>
                                    
                                    <label class="control-label col-md-2">{{ trans('message.form-label.pickup_schedule') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->pickup_schedule}}</p>
                                    </div>
                                </div>
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
                                                    {{-- <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th> --}}
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
                                                    {{-- <td style="text-align:center" height="10">{{$rowresult->cost}}</td> --}}
                                                    <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                                    <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                                                    <td style="text-align:center" height="10">
                                            
                                                        <select   class="js-example-basic-multiple" required name="problem_details[]" id="problem_details" multiple="multiple" style="width:100%;">
                                                            @foreach($problem_details_list as $key=>$list)
                                                                    @if(strpos($rowresult->problem_details, $list->problem_details) !== false)
                                                                            <option selected value="{{$list->problem_details}}" >{{$list->problem_details}}</option>
                                                                        @else
                                                                            <option  value="{{$list->problem_details}}">{{$list->problem_details}}</option>
                                                                    @endif
                                                                    
                                                            @endforeach
                                      
                                                        </select>
                                                            <?php $other_problem_details = $rowresult->problem_details_other;?>
                                                            <br><br>
                                                            <input type='input'  name='problem_details_other' id="problem_details_other" autocomplete="off" class='form-control' value="{{$rowresult->problem_details_other}}"/> 
                                                       
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
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->rma_name}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_at_sc}}</p>
                                </div>
                            </div>
                            <div class="row"> 
                               
                                    <label class="control-label col-md-2">{{ trans('message.table.comments2') }}</label>
                                    <div class="col-md-10">
                                    <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="diagnose_comments" id="diagnose_comments"></textarea>
                                    </div>
                            </div>
                    </div>
                </div>
            <div class='panel-footer'>

                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                
               {{-- <button class="btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button> --}}
                
                <button class="btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
             
                <button class="btn btn-success pull-right" type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>

                <button class="btn btn-success pull-right" type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>


            </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

$('.js-example-basic-multiple').select2();
$(".js-example-basic-multiple").select2({
     theme: "classic"
});


var verified_others_field = <?php echo json_encode($other_verified_items_included); ?>;
var problem_others_field = <?php echo json_encode($other_problem_details); ?>;

if(verified_others_field == null || verified_others_field == ""){
        $('#verified_items_included_others').val("");
        $('#verified_items_included_others').hide();  
        $('#verified_items_included_others').attr("required", false);
}


if(problem_others_field == null || problem_others_field == ""){
        $('#problem_details_other').val("");
        $('#problem_details_other').hide();  
        $('#problem_details_other').attr("required", false);
}

$("#items_included_others").hide();



$('#items_included').change(function(){
    if($('#items_included').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#items_included_others').show();
        $('#items_included_others').attr("required", true);
    }else{
        $('#items_included_others').val("");
        $('#items_included_others').hide();  
        $('#items_included_others').attr("required", false);
    }
});



$('#verified_items_included').change(function(){
    if($('#verified_items_included').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {

        $('#verified_items_included_others').show();
        $('#verified_items_included_others').attr("required", true);
    }else{
        $('#verified_items_included_others').val("");
        $('#verified_items_included_others').hide();  
        $('#verified_items_included_others').attr("required", false);
    }
});

$('#problem_details').change(function(){
    if($('#problem_details').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("OTHERS")) {
        $('#problem_details_other').show();
        $('#problem_details_other').attr("required", true);
    }else{
        $('#problem_details_other').val("");
        $('#problem_details_other').hide();  
        $('#problem_details_other').attr("required", false);
    }
});

$("#btnSubmitRepair").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Repair this return request?");
        if (strconfirm == true) {
          $("#diagnose").val("Repair");
          if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
              alert("Please put a comment!");
              return false;
              window.stop();
            }else{
             /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/rma_level2_request/VoidWarrantyRequestRMALVL2') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;*/
            }
        }else{
                  return false;
                  window.stop();
        }
});

$("#btnSubmitReject").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Reject this return request?");
        if (strconfirm == true) {
          $("#diagnose").val("Reject");
          if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
              alert("Please put a comment!");
              return false;
              window.stop();
            }else{
             /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/rma_level2_request/VoidWarrantyRequestRMALVL2') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;*/
            }
        }else{
                  return false;
                  window.stop();
        }
});

$("#btnSubmitRefund").on('click',function() {
    var strconfirm = confirm("Please contact your RMA head for special approval.\n\nAre you sure you want to Refund this return request?");
        if (strconfirm == true) {
          $("#diagnose").val("Refund");
          if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
              alert("Please put a comment!");
              return false;
              window.stop();
            }else{
             /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/rma_level2_request/VoidWarrantyRequestRMALVL2') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;*/
            }
        }else{
                  return false;
                  window.stop();
        }
});

$("#btnSubmitReplace").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Replace this return request?");
        if (strconfirm == true) {
          $("#diagnose").val("Replace");
          if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
              alert("Please put a comment!");
              return false;
              window.stop();
            }else{
             /*var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/rma_level2_request/VoidWarrantyRequestRMALVL2') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;*/
            }
        }else{
                  return false;
                  window.stop();
        }
});

</script>
@endpush 