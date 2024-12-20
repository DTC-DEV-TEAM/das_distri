<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
.pic-container {
  width: 1350px;
  margin: 0 auto;
  white-space: nowrap;
}

.pic-row {
  /* As wide as it needs to be */
  width: 1350px;
  
  overflow: auto;
}

.pic-row a {
  clear: left;
  display: block;
}
</style>
@endpush
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
            <input type="hidden"  name="remarks" id="remarks" value="SAVE">
            <input type="hidden"  name="diagnose" id="diagnose">
            <input type="hidden" name="mode_of_payment" id="mode_of_payment" value{{ $row->mode_of_payment }}>
                <div id="requestform" class='panel-body'>
                    <div> 

                            
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.return_reference_no') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->return_reference_no}}</p>
                                <input type="hidden" name="return_reference" value="{{ $row->return_reference_no }}"> 
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.created_at') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->created_at}}</p>
                            </div>
                        </div>
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->purchase_location}}</p>
                                <input type="hidden" name="purchase_location" id="purchase_location" value="{{ $row->purchase_location }}">
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->store}}</p>
                                <input type="hidden" name="store" id="store" value="{{ $row->store }}">
                            </div>
                        </div>
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_return') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->mode_of_return}}</p>
                                <input type="hidden" name="mode_of_return" id="mode_of_return" value="{{ $row->mode_of_return }}">
                            </div>


                            @if ($row->branch != null || $row->branch != "")
                                <label class="control-label col-md-2">{{ trans('message.form-label.branch') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->branch}}</p>
                                </div>    
                             @endif    
                        </div>     
                        <div class="row">   
                            @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                <label class="control-label col-md-2">{{ trans('message.form-label.store_dropoff') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->store_dropoff}}</p>
                                    <input type="hidden" name="store_dropoff" id="store_dropoff" value="{{ $row->store_dropoff }}">
                                </div>
                            @endif
                            
                            @if ($row->branch_dropoff != null || $row->branch_dropoff != "")
                                    <label class="control-label col-md-2">{{ trans('message.form-label.branch_dropoff') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->branch_dropoff}}</p>
                                        <input type="hidden" name="branch_dropoff" id="branch_dropoff" value="{{ $row->branch_dropoff }}" >
                                    </div>    
                             @endif  
                        </div>   
                        <!-- 3r -->
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.customer_last_name') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->customer_last_name}}</p>
                                <input type="hidden" name="customer_last_name" id="customer_last_name" value="{{ $row->customer_last_name }}">
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.customer_first_name') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->customer_first_name}}</p>
                                <input type="hidden" name="customer_first_name" id="customer_first_name" value="{{ $row->customer_first_name }}">
                            </div>
                        </div>
                        <!-- 4r -->
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.address') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->address}}</p>
                                <input type="hidden" name="address" id="address" value="{{ $row->address }}">
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.email_address') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->email_address}}</p>
                                <input type="hidden" name="email_address" id="email_address" value="{{ $row->email_address }}">
                            </div>
                        </div>
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.contact_no') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->contact_no}}</p>
                                <input type="hidden" name="contact_no" id="contact_no" value="{{ $row->contact_no }}">
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.order_no') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->order_no}}</p>
                                <input type="hidden" name="order_no" id="order_no" value="{{ $row->order_no }}">
                            </div>
                        </div>
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.received_date') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->purchase_date}}</p>
                                <input type="hidden" name="purchase_date" id="purchase_date" value="{{ $row->purchase_date }}">

                            </div>
                            
                            <label class="control-label col-md-2">{{ trans('message.form-label.items_included') }}</label>
                            <div class="col-md-4">
                                    
                                @if($row->mode_of_return =="STORE DROP-OFF")
                                
                                           @if($row->transaction_type == 3)
                                           
                                                    @if($row->items_included_others  != null)
                                                            <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                                        @else
                                                            <p>{{$row->items_included}}</p>
                                                    @endif
                                           
                                           
                                                @else
                                                
                                                    @if($row->verified_items_included_others  != null)
                                                            <p>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                                        @else
                                                            <p>{{$row->verified_items_included}}</p>
                                                    @endif
                                           @endif

                                                
                                                
                                        @else   
                                                @if($row->items_included_others  != null)
                                                        <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                                    @else
                                                        <p>{{$row->items_included}}</p>
                                                @endif
                                @endif      
                                
                            </div>
                            
                        </div>
                        <div class="row">
                            <label class="control-label col-md-2" style="visibility: hidden">{{ trans('message.form-label.mode_of_payment') }}</label>
                            <div class="col-md-4" style="visibility: hidden">
                                {{$row->mode_of_payment}}
                            </div>
                            <label class="control-label col-md-2"  style="margin-top:6px;">{{ trans('message.form-label.verified_items_included') }}</label>
                            <div class="col-md-4">
                                <select   class="js-example-basic-multiple" required name="items_included[]" id="items_included" multiple="multiple" style="width:100%;">
                                    @foreach($items_included_list as $key=>$list)
                                            @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                    <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                @else
                                                    <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                            @endif
                                            
                                    @endforeach
                
                                </select>
                                    <?php $other_items_included = $row->items_included_others;?>
                                    <br><br>
                                    <input type='input'  name='items_included_others' id="items_included_others" autocomplete="off" class='form-control' value="{{$row->items_included_others}}"/> 
                                
                            </div>
                        </div>
                            {{-- Edited Data 414 --}}
                            <hr>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.tagged_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->tagged_by}}</p>
                                    <input type="hidden" name="closed_by" id="closed_by" value="{{ $row->tagged_by }}">
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.tagged_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level1_personnel_edited}}</p>
                                    <input type="hidden" name="level7_personnel_edited" value="{{ $row->level1_personnel_edited }}">
                                </div>
                            </div>
                            <br>
                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                    <input type="hidden" name="customer_location" id="customer_location" value="{{ $row->customer_location }}">
                                </div>
                            </div> 
                            <hr>
                            {{-- <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->tagged_by}}</p>
                                </div>
                                
                                <label class="control-label col-md-2">{{ trans('message.form-label.pickup_schedule') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->return_schedule}}</p>
                                </div>
                            </div> --}}
                        
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
                                            {{-- <td style="text-align:center; visibility: hidden;" height="10">{{$rowresult->cost}}</td> --}}
                                            <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                            <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                                            {{-- <td style="text-align:center" height="10">{{$rowresult->problem_details}}</td> --}}
                                            <td style="text-align:center" height="10">
                                    
                                                <select class="js-example-basic-multiple" required name="problem_details[]" id="problem_details" multiple="multiple" style="width:100%;">
                                                    @foreach($problem_details_list as $key=>$list)
                                                            @if(strpos($rowresult->problem_details, $list->problem_details) !== false)
                                                                    <option selected value="{{$list->problem_details}}" >{{$list->problem_details}}</option>
                                                                @else
                                                                    <option  value="{{$list->problem_details}}">{{$list->problem_details}}</option>
                                                            @endif
                                                            
                                                    @endforeach
                            
                                                </select>
                
                                            
                                            </td>
                                            <td style="text-align:center" height="10">{{$rowresult->quantity}}</td>

                                            <input type="hidden" name="digits_code" id="digits_code" value="{{ $rowresult->digits_code }}">
                                            <input type="hidden" name="upc_code" id="upc_code" value="{{ $rowresult->upc_code }}">
                                            <input type="hidden" name="item_description" id="item_description" value="{{ $rowresult->item_description }}">
                                            <input type="hidden" name="cost" id="cost" value="{{ $rowresult->cost }}">
                                            <input type="hidden" name="brand" id="brand" value="{{ $rowresult->brand }}">
                                            <input type="hidden" name="serial_number" id="serial_number" value="{{ $rowresult->serial_number }}">
                                            {{-- <input type="hidden" name="problem_details" id="problem_detailse" value="{{ $rowresult->problem_details }}"> --}}
                                            <input type="hidden" name="quantity" id="quantity" value="{{ $rowresult->quantity }}">
                                            <input type="hidden" name="category" id="category" value="{{ $rowresult->category }}">
                                            <input type="hidden" name="problem_details_other" id="problem_details_other" value="{{ $rowresult->problem_details_other }}">
                                            <input type="hidden" name="serialize" id="serialize" value="{{ $rowresult->serialize }}">
                                            <input type="hidden" name="line_id" id="line_id" value="{{ $rowresult->line_id }}">
                                            
                     
                                        </tr>
                                    @endforeach
     
                                </tbody>
                            </table>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                                <div class="col-md-10">
                                    <p>{{$row->comments}}</p>
                                    <input type="hidden" name="requestor_comments" id="requestor_comments" value="{{ $row->comments }}">
                                </div>
                            </div>
                            
                    </div>
                </div>
                <div class='panel-footer'>

                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.receive') }}</button>

                </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function() {
        $('.js-example-basic-single').select2();

});



function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

$( "#datepicker" ).datepicker( {  maxDate: '0',  dateFormat: 'yy-mm-dd'  } );

$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});


$('.js-example-basic-multiple').select2();
$(".js-example-basic-multiple").select2({
     theme: "classic"
});

$("#items_included_others").hide();

var verified_others_field = <?php echo json_encode($other_items_included); ?>;

if(verified_others_field == null || verified_others_field == ""){
        $('#items_included_others').val("");
        $('#items_included_others').hide();  
        $('#items_included_others').attr("required", false);
}

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

$('#mode_of_refund').change(function(){
    if($('#mode_of_refund').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("BANK DEPOSIT")) {
        $('#div_bank_name').show();
        $('#div_bank_account_no').show();
        $('#div_bank_account_name').show();

        $('#bank_name').attr("required", true);
        $('#bank_account_no').attr("required", true);
        $('#bank_account_name').attr("required", true);
        //$('#items_included_others').show();
        //$('#items_included_others').attr("required", true);
    }else{
        $('#div_bank_name').hide();
        $('#div_bank_account_no').hide();
        $('#div_bank_account_name').hide();

        $('#bank_name').attr("required", false);
        $('#bank_account_no').attr("required", false);
        $('#bank_account_name').attr("required", false);
        //$('#items_included_others').val("");
        //$('#items_included_others').hide();  
        //$('#items_included_others').attr("required", false);
    }
});

function showCustomerLocation(){
        var store_backend = document.getElementById("store").value;
        var purchase_location = document.getElementById("purchase_location").value; 

        $.ajax
        ({ 
            url: '{{ url('admin/retail_for_verification/backend_stores') }}',
            type: "POST",
            data: {
                'store_backend': store_backend,
                'purchase_location': purchase_location,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
         
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                 
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }
                jQuery("#branch").html(showData);  
            }
        });
    }
    
    
    
function branchChange(){
        var brand_change = document.getElementById("branch_dropoff").value;
        var store_front = document.getElementById("store_dropoff").value;
            
        $.ajax
        ({ 
            url: '{{ url('admin/retail_for_verification/branch_change') }}',
            type: "POST",
            data: {
                'brand_change': brand_change,
                'store_front': store_front,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
           
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose Customer Location here...</option>";
                for (i = 0; i < result.length; ++i) {
                 
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].store_name+"'>"+result[i].store_name+"</option>";
                }
                jQuery("#customer_location").html(showData);  
            }
        });
    }    

    function showBranch()
    {
        var drop_off_store = document.getElementById("store_dropoff").value;
        //var location = document.getElementById("channels").value;
        $.ajax
        ({ 
            url: '{{ url('admin/retail_for_verification/branch_drop_off') }}',
            type: "POST",
            data: {
                'drop_off_store': drop_off_store,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
            
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }

                jQuery("#branch_dropoff").html(showData); 
            }
        });
    }

$("#cancel").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Cancel this return request?");
        if (strconfirm == true) {
                $("#remarks").val("CANCEL");
                return true;
        }else{
                return false;
                window.stop();
        }
});

//cut
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}



$("#btnSubmit").on('click',function() {
    // Edited Data
    //     var rowCount = $('#pullout-items tr').length;
    //    // var quantityReservable = $('.item_quantity').val();
    //     var itemCost = $('.cost_item').val();
    //    // var totalQty = $("#totalQuantity").val();
    //     var signal = 0;
    //     var alert_message = 0;
    //     var error_qty = 0;   
   
    //     if(rowCount <= 1){
    //         alert("Please put an item123!"); 
    //         return true;
    //     }else{
    //         $("form").submit(function(){
    //             $('#btnSubmit').attr('disabled', true);
    //         }); 
    //     }
        
    //     if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
    //         alert("Please put item cost!");
    //         return false;
    //     }else{
    //         $("form").submit(function(){
    //             $('#btnSubmit').attr('disabled', true);
    //         }); 

    //         $("#remarks").val("SAVE");
    //     }
});
    
$(document).ready(function() {
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
 
function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

var id_qty = 0;
var stack = [];
var token = $("#token").val();
var myStr = '';
var xx_len = 0;
var restriction = 0;
var blank = 0;
var execute = 0;
var button_asc = 0;
var temp = '';
var problem_loop = 0;
var stack_serials = <?php echo json_encode($stack_serials); ?>;
var stack_problem_details = <?php echo json_encode($stack_problem_details); ?>;
var stack_problem_details_other = <?php echo json_encode($stack_problem_details_other); ?>;
var stack_cost = <?php echo json_encode($stack_cost); ?>;
$(document).ready(function(){
    $(function(){
        $("#search").autocomplete({
            source: function (request, response) {
            $.ajax({
                url: "{{ route('scheduling.item.search') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "search": request.term
                },
                success: function (data) {
                    var rowCount = $('#pullout-items tr').length;
             
                    if(rowCount == 1){
                            if(restriction == 0){
                                myStr = data.sample;   
                                if (data.status_no == 1) {
                                    $("#val_item").html();
                                    var data = data.items;
                                    $('#ui-id-2').css('display', 'none');
                                    response($.map(data, function (item) {
                                        return {
                                            id: item.id,
                                            stock_code: item.digits_code,
                                            stock_upc: item.upc_code,
                                            value: item.item_description,
                                            stock_brand: item.brand,
                                            stock_category: item.category,
                                            stock_current_srp: item.current_srp
                                        }
                                    }));
                                } else {
                                    $('.ui-menu-item').remove();
                                    $('.addedLi').remove();
                                    $("#ui-id-2").append($("<li class='addedLi'>").text(data.message));
                                    var searchVal = $("#search").val();
                                    if (searchVal.length > 0) {
                                        $("#ui-id-2").css('display', 'block');
                                    } else {
                                        $("#ui-id-2").css('display', 'none');
                                    }
                                }
                        }else{
                            alert("Please fill out the problem details!");
                            $("#search").val("");
                        }

                    }else{
                        $("#search").val("");
                        alert("Only 1 item allowed!");
                        
                    }

                }
            })
        },
        select: function (event, ui) {
            var e = ui.item;
            if (e.id) {
                if (!in_array(e.stock_code, stack)) {
                    //temp = $("#temp_reference1").val();
                    button_asc++;
                    problem_loop++;
                    stack.push(e.stock_code);                    
                        var new_row = '<tr class="nr" id="rowid' + e.id + '">' +
                                '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + e.stock_code + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + e.stock_upc + '"></td>' +
                                '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + e.value + '"></td>' +
                                '<td><input class="form-control text-center  cost_item" type="number" name="cost[]" value="'+stack_cost+'" min="0" max="999999" step="any" onKeyPress="if(this.value.length==7) return false;" oninput="validity.valid||(value=0);"></td>' +
                                '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="serial_no[]"  value="' +stack_serials+'"></td>' +
                                '<td><select class="js-example-basic-multiple" required name="' + e.stock_code + '[]" id="problem_details_'+ e.id +'" multiple="multiple" style="width:100%"></select><br><br><input class="form-control text-center" type="text" name="problem_details_other[]" id="problem_details_other_'+ e.id +'"></td>'+
                                '<td><input class="form-control text-center no_units item_quantity"   readonly  data-id="'+e.stock_code +''+button_asc+''+temp+'" data-rate="' + e.stock_price + '" type="number" min="0" max="99999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="'+e.stock_code +'" name="quantity[]" value="1"><input  type="hidden" name="serialize[]" id="serialize_'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ><input  type="hidden" name="line_id[]"  value="'+e.stock_code +''+button_asc+''+temp+'" readonly ><input  type="hidden" name="visible_qty[]" id="'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ></td>' +
                                '<td class="text-center"><button id="'+e.stock_code +''+button_asc+''+temp+'" onclick="reply_click1(this.id)" class="btn btn-xs btn-danger delete_item" style="width:60px;height:30px;font-size: 11px;text-align: center;">REMOVE</button></td>' +
                                '<input type="hidden" name="category[]" readonly value="' +e.stock_category + '">' +
                                '</tr>';
                    $(new_row).insertAfter($('table tr.dynamicRows:last'));
                    $('.js-example-basic-multiple').select2();
                    $(".js-example-basic-multiple").select2({
                    theme: "classic"
                    });

           
                  var array_problem_details = stack_problem_details.split(", ");
                  var strArray = myStr.split(",");
                  for(var x=0; x < strArray.length; x++){
                        if(array_problem_details.includes(strArray[x])){
              
                            $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'" selected>'+strArray[x]+'</option>');
                        }else{
                            $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'">'+strArray[x]+'</option>');
                        }
                        
                  }
                  if(array_problem_details.includes("OTHERS")){
                    $('#problem_details_other_'+e.id).show();
                    $('#problem_details_other_'+e.id).val(stack_problem_details_other);
                  }else{
                    $('#problem_details_other_'+e.id).hide();
                  }
                  
                  $('#problem_details_'+e.id).change(function(){
                        if($('#problem_details_'+e.id).val() != null){
                            var arrx = $(this).val();
                            execute = 0;
                        }else{
                            var arrx = "";
                            execute++;
                        }
                        var s = arrx;
                        if(s.includes("OTHERS")) {
                            $('#problem_details_other_'+e.id).show();
                            $('#problem_details_other_'+e.id).attr('required', 'required');
                        }else{
                            $('#problem_details_other_'+e.id).val("");
                            $('#problem_details_other_'+e.id).hide();  
                            $('#problem_details_other_'+e.id).removeAttr('required');
                        }
                    })
                    if($('#problem_details_'+e.id).val() == null || $('#problem_details_'+e.id).val() == ''){
                        execute++;
                    }
                    blank++;
                    // Calculate total quantiy
                    //var subTotalQuantity = calculateTotalQuantity();
                    //$("#totalQuantity").val(subTotalQuantity);
                   // $('.tableInfo').show();

                } else {
                    if(!related_items.includes(e.stock_code)){
                        $('#' + e.stock_code).val(function (i, oldval) {
                            return ++oldval;
                        });
                        var temp_qty = $('#'+ e.stock_code).attr("data-id");
                        var q = $('#' +e.stock_code).val();
                        var r = $("#rate_id_" + e.id).val();
                        $('#amount_' + e.id).val(function (i, amount) {
                            if (q != 0) {
                                var itemPrice = (q * r);
                                return itemPrice;
                            } else {
                                return 0;
                            }
                        });
                        $('#'+temp_qty).val(q);
                        //var subTotalQuantity = calculateTotalQuantity();
                        //$("#totalQuantity").val(subTotalQuantity);
                    }else{
                        alert("You can not add this item!");
                    }
                }
                $(this).val('');
                $('#val_item').html('');
                return false;
            }
        },
        minLength: 1,
        autoFocus: true
        });
    });
});

$(document).ready(function () {
  //$('.tableInfo').hide();
});



// Delete item row
$(document).ready(function (e) {
  $('#pullout-items').on('click', '.delete_item', function () {
    problem_loop = problem_loop - 1;
    var  v = $(this).attr("id").substr(0, 8);
    stack = jQuery.grep(stack, function (value) {
      return value != v;
    });

    $(this).closest("tr").remove();
    //var subTotalQuantity = calculateTotalQuantity();
    //$("#totalQuantity").val(subTotalQuantity);
    execute = 0;

    for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
    }
     div_container1 = [];
  });
});

$(document).on('keyup', '.no_units', function (ev) {
    $('#'+ $(this).attr("data-id")).val(this.value);
    //$('.tableInfo').show();
    //var totalQuantity = calculateTotalQuantity();
    //$("#totalQuantity").val(totalQuantity);

});



$("#").on('keyup', function () {
  var searchVal = $("#search").val();
  if (searchVal.length > 0) {
    $("#ui-id-2").css('display', 'block');
  } else {
    $("#ui-id-2").css('display', 'none');
  }
});

function calculateTotalQuantity() {
  var totalQuantity = 0;
  $('.item_quantity').each(function () {
    totalQuantity += parseInt($(this).val());
  });
  return totalQuantity;
}



$("#btnSubmitRepair").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Repair this return request?");
        if (strconfirm == true) {
            
            var rowCount = $('#pullout-items tr').length;
               // var quantityReservable = $('.item_quantity').val();
                var itemCost = $('.cost_item').val();
               // var totalQty = $("#totalQuantity").val();
                var signal = 0;
                var alert_message = 0;
                var error_qty = 0;   
           
                if(rowCount <= 1){
                    alert("Please put an item!"); 
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
                }
                
                if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
                    alert("Please put item cost!");
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
        
                    $("#remarks").val("SAVE");
                }
                
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
            
                var rowCount = $('#pullout-items tr').length;
               // var quantityReservable = $('.item_quantity').val();
                var itemCost = $('.cost_item').val();
               // var totalQty = $("#totalQuantity").val();
                var signal = 0;
                var alert_message = 0;
                var error_qty = 0;   
           
                if(rowCount <= 1){
                    alert("Please put an item!"); 
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
                }
                
                if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
                    alert("Please put item cost!");
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
        
                    $("#remarks").val("SAVE");
                }
                
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
                var rowCount = $('#pullout-items tr').length;
               // var quantityReservable = $('.item_quantity').val();
                var itemCost = $('.cost_item').val();
               // var totalQty = $("#totalQuantity").val();
                var signal = 0;
                var alert_message = 0;
                var error_qty = 0;   
           
                if(rowCount <= 1){
                    alert("Please put an item!"); 
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
                }
                
                if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
                    alert("Please put item cost!");
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
        
                    $("#remarks").val("SAVE");
                } 
                
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
        
                var rowCount = $('#pullout-items tr').length;
               // var quantityReservable = $('.item_quantity').val();
                var itemCost = $('.cost_item').val();
               // var totalQty = $("#totalQuantity").val();
                var signal = 0;
                var alert_message = 0;
                var error_qty = 0;   
           
                if(rowCount <= 1){
                    alert("Please put an item!"); 
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
                }
                
                if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
                    alert("Please put item cost!");
                    return false;
                }else{
                    $("form").submit(function(){
                        $('#btnSubmit').attr('disabled', true);
                    }); 
        
                    $("#remarks").val("SAVE");
                }
                
            
            
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
