<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

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

.table tbody tr td, .table thead tr th, .table{
    border: 1px solid #ddd;
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
        <div class="message-pos">
            <div class="message-circ">
                <i class="fa fa-envelope" style="color: #fff; font-size: 20px;"></i>
            </div>
            <div class="chat-container">
                <div class="chat-content" style="display: none;">
                    <div class="hide-chat">
                        <i class="fa fa-close" style="color: #fff;"></i>
                    </div>
                    @include('components.ecomm.chat-app', $comments_data)
                </div>
            </div>
        </div>
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="remarks" id="remarks">
                <div id="requestform" class='panel-body'>
                    <div> 
                        <table class="custom_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.return_reference_no') }}</td>
                                    <td>{{$row->return_reference_no}}</td>
                                    <td>{{ trans('message.form-label.created_at') }}</td>
                                    <td>{{$row->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_location') }}</td>
                                    <td>{{$row->purchase_location}}</td>
                                    <td>{{ trans('message.form-label.store') }}</td>
                                    <td>{{$row->store}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.mode_of_return') }}</td>
                                    <td>{{$row->mode_of_return}}</td>
                                    @if ($row->branch != null || $row->branch != "")
                                    <td>{{ trans('message.form-label.branch') }}</td>
                                    <td>{{$row->branch}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                    <td>{{ trans('message.form-label.store_dropoff') }}</td>
                                    <td>{{$row->store_dropoff}}</td>
                                    @endif
                                    @if ($row->branch_dropoff != null || $row->branch_dropoff != "")
                                    <td>{{ trans('message.form-label.branch_dropoff') }}</td>
                                    <td>{{$row->branch_dropoff}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.customer_last_name') }}</td>
                                    <td>{{$row->customer_last_name}}</td>
                                    <td>{{ trans('message.form-label.customer_first_name') }}</td>
                                    <td>{{$row->customer_first_name}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.address') }}</td>
                                    <td>{{$row->address}}</td>
                                    <td>{{ trans('message.form-label.email_address') }}</td>
                                    <td>{{$row->email_address}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.contact_no') }}</td>
                                    <td>{{$row->contact_no}}</td>
                                    <td>{{ trans('message.form-label.order_no') }}</td>
                                    <td>{{$row->order_no}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_date') }}</td>
                                    <td>{{$row->purchase_date}}</td>
                                    <td>{{ trans('message.form-label.mode_of_payment') }}</td>
                                    <td>{{$row->mode_of_payment}}</td>
                                </tr>
                                <tr>
                                    @if ($row->bank_name != null || $row->bank_name != "")                    
                                    <td>{{ trans('message.form-label.bank_name') }}</td>
                                    <td>{{$row->bank_name}}</td>
                                    @endif
                                    @if ($row->bank_account_no != null || $row->bank_account_no != "")  
                                    <td>{{ trans('message.form-label.bank_account_no') }}</td>
                                    <td>{{$row->bank_account_no}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    @if ($row->bank_account_name != null || $row->bank_account_name != "")                        
                                    <td>{{ trans('message.form-label.bank_account_name') }}</td>
                                    <td>{{$row->bank_account_name}}</td>
                                    <td></td>
                                    <td></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.items_included') }}</td>
                                    @if($row->items_included_others  != null)
                                            <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                        @else
                                            <td>{{$row->items_included}}</td>
                                    @endif                                    
                                    <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                    <td>
                                        <select   class="js-example-basic-multiple" required name="verified_items_included[]" id="verified_items_included" multiple="multiple" style="width:100%;">
                                            @foreach($items_included_list as $key=>$list)
                                                    @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                            <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                        @else
                                                            <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                    @endif
                                            @endforeach
                                        </select>
                                        <?php $other_items_included = $row->items_included_others;?>
                                        <div id="verified_items_included_others">
                                            <br>
                                            <input type='input'  name='verified_items_included_others' id="" autocomplete="off" class='form-control' value="{{$row->items_included_others}}"/> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br>

                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.tagged_by') }}</td>
                                    <td>{{$row->tagged_by}}</td>
                                    <td>{{ trans('message.form-label.tagged_at') }}</td>
                                    <td>{{$row->level1_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.customer_location') }}</td>
                                    <td>{{$row->customer_location}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                            
                        <br>
                        <!--TABLE-->
                        <table  class='table table-striped table-bordered table-font'>
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    <th width="10%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th>
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
                                    <td style="text-align:center" height="10">{{$rowresult->cost}}</td>
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
                        
                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.table.comments1') }}</td>
                                    <td>{{$row->comments}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        @if($row->returns_status_1 == 23)
                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                    <td>{{$row->scheduled_by}}</td>
                                    <td>{{ trans('message.form-label.scheduled_at') }}</td>
                                    <td>{{$row->level2_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.return_schedule') }}</td>
                                    <td>{{$row->return_schedule}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        @endif

                    </div>
                </div>
                
                <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.receive') }}</button>
            </form>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right f-btn" type="button"><i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
            </div>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

function chatBox(){
    $('.hide-chat').on('click', function(){
        $(this).hide();
        $('.chat-content').hide();
    })

    $('.message-circ').on('click', function(){
        const scrollBody = $('.scroll-body');

        $('.hide-chat').show();
        $('.chat-content').show();

        scrollBody.ready(function() {
            scrollBody.animate({scrollTop: scrollBody.prop('scrollHeight')}, 1000)
            reloadInfo();
        });
        
        $('.type-message').focus();
    })
}

chatBox();

$(".f-btn").on('click', function(){

    const btnText = $(this).text();

    Swal.fire({
        title: `Are you sure you want to <span style="color: #3085D6">${btnText}</span> this transaction?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        reverseButtons: true,
        returnFocus: false,
        allowOutsideClick: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#btnSubmit').click();
        }
    });
})

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

var problem_others_field = <?php echo json_encode($other_problem_details); ?>;

if(problem_others_field == null || problem_others_field == ""){
        $('#problem_details_other').val("");
        $('#problem_details_other').hide();  
        $('#problem_details_other').attr("required", false);
}


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

$("#cancel").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Cancel this return request?");
        if (strconfirm == true) {
                $('#datepicker').attr("required", false);
                $("#remarks").val("CANCEL");
                return true;
        }else{
                return false;
                window.stop();
        }
});

$("#btnSubmit").on('click',function() {
    $("#remarks").val("SAVE");
});

$("#items_included_others").hide();

var items_others = <?php echo json_encode($other_items_included); ?>;

var verified_others_field = <?php echo json_encode($other_items_included); ?>;


if(verified_others_field == null || verified_others_field == ""){
        $('#verified_items_included_others').val("");
        $('#verified_items_included_others').hide();  
        $('#verified_items_included_others').attr("required", false);
}



function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

$( "#datepicker" ).datepicker( { minDate: '1', dateFormat: 'yy-mm-dd' } );

$('.js-example-basic-multiple').select2();
$(".js-example-basic-multiple").select2({
     theme: "classic"
});



$(document).ready(function(){
  $("myform").submit(function(){
        $('#btnSubmit').attr('disabled', true);
  });
});

</script>
@endpush