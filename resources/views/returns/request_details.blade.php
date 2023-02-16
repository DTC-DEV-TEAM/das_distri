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
        <div id="requestform" class='panel-body'>
            <div> 
                <!-- 1r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.return_reference_no') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->return_reference_no }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.created_at') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->CreatedDate }}</p>
                    </div>
                </div>
                <!-- 2r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->purchase_location }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->store }}</p>
                    </div>
                </div>
                <!-- 3r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.customer_last_name') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->customer_last_name }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.customer_first_name') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->customer_first_name }}</p>
                    </div>
                </div>
                <!-- 4r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.address') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->address }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.email_address') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->email_address }}</p>
                    </div>
                </div>
                <!-- 5r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.contact_no') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->contact_no }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.order_no') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->order_no }}</p>
                    </div>
                </div>
                <!-- 6r -->
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.purchase_date') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->purchase_date }}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_payment') }}</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->mode_of_payment }}</p>
                    </div>
                </div>                           
                <!-- 7r -->
                <div class="row">                       
                    <label class="control-label col-md-2">{{ trans('message.form-label.items_included') }}</label>
                    <div class="col-md-4">
                        @if(!empty($data['return_request']->ItemsIncludedOthers))
                            <p>{{ $data['return_request']->ItemsIncluded }}, {{ $data['return_request']->ItemsIncludedOthers }}</p>
                        @else
                            <p>{{ $data['return_request']->ItemsIncluded }}</p>
                        @endif
                    </div>

                    <label class="control-label col-md-2">Customer Location</label>
                    <div class="col-md-4">
                        <p>{{ $data['return_request']->customer_location }}</p>
                    </div>
                </div>

                <div class="row">
                    <label class="control-label col-md-2">{{ trans('message.table.comments') }}:</label>
                    <div class="col-md-4">
                        <p>{{$data['return_request']->comments}}</p>
                    </div>
                </div>
                <br>
                <!--TABLE-->
                <table  class='table table-striped table-bordered'>
                    <tbody>
                        @foreach($resultlist as $rowresult)
                        <tr>
                            <?php $stack_serials = $rowresult->serial_number;?>
                            <?php $stack_problem_details = $rowresult->problem_details;?>
                            <?php $stack_problem_details_other = $rowresult->problem_details_other;?>
                            <?php $stack_cost = $rowresult->cost;?>
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>                    
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-header text-center">
                            <h3 class="box-title"><b>{{ trans('message.form-label.return_items') }}</b></h3>
                        </div>
                        <div class="box-body no-padding">
                            <div class="table-responsive">
                                <div class="pic-container">
                                    <div class="pic-row">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr class="tbl_header_color">
                                                    <th class="text-center">{{ trans('message.table.digits_code') }}</th>
                                                    <th class="text-center">{{ trans('message.table.upc_code') }}</th>
                                                    <th class="text-center">{{ trans('message.table.item_description') }}</th>
                                                    <th class="text-center">{{ trans('message.table.cost') }}</th>
                                                    <th class="text-center">{{ trans('message.table.brand') }}</th>
                                                    <th class="text-center">{{ trans('message.table.serial_no') }}</th>
                                                    <th class="text-center">{{ trans('message.table.problem_details') }}</th>
                                                    <th class="text-center">{{ trans('message.table.quantity') }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">{{$data['return_request']->digits_code}}</td>
                                                    <td class="text-center">{{$data['return_request']->upc_code}}</td>
                                                    <td class="text-center"> {{$data['return_request']->item_description}}</td>
                                                    <td class="text-center">{{$data['return_request']->cost}}</td>
                                                    <td class="text-center"> {{$data['return_request']->brand}}</td>
                                                    <td class="text-center"> {{$data['return_request']->serial_number}}</td>
                                                    <td class="text-center"> 
                                                        <?php $problem_details = explode(",", $data['return_request']->problem_details); ?>
                                                        @foreach($problem_details as $problem_detail)
                                                            {{$problem_detail}} <br>
                                                        @endforeach
                                                        <br>
                                                        @if(!empty($data['return_request']->problem_details_other))
                                                                {{$data['return_request']->problem_details_other}}
                                                        @endif
                                                    </td>
                                                    <td class="text-center"> {{$data['return_request']->quantity}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-primary pull-right">Back</a>
            </div>
        </div>
        <div class='panel-footer'>
            <!-- <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button> -->
        </div>
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

//cut
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}

$("#btnSubmit").on('click',function() {
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
        }
        
        if(itemCost == '' || itemCost == null || itemCost < 0 || itemCost == 0){
            alert("Please put item cost!");
            return false;
        }
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
                                '<td><input class="form-control text-center  cost_item" type="number" name="cost[]" value="'+stack_cost+'" min="0" max="9999" step="any" onKeyPress="if(this.value.length==7) return false;" oninput="validity.valid||(value=0);"></td>' +
                                '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="serial_no[]"  value="' +stack_serials+'"></td>' +
                                '<td><select class="js-example-basic-multiple" required name="' + e.stock_code + '[]" id="problem_details_'+ e.id +'" multiple="multiple" style="width:100%"></select><br><br><input class="form-control text-center" type="text" name="problem_details_other[]" id="problem_details_other_'+ e.id +'"></td>'+
                                '<td><input class="form-control text-center no_units item_quantity"   readonly  data-id="'+e.stock_code +''+button_asc+''+temp+'" data-rate="' + e.stock_price + '" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="'+e.stock_code +'" name="quantity[]" value="1"><input  type="hidden" name="serialize[]" id="serialize_'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ><input  type="hidden" name="line_id[]"  value="'+e.stock_code +''+button_asc+''+temp+'" readonly ><input  type="hidden" name="visible_qty[]" id="'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ></td>' +
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
</script>
@endpush