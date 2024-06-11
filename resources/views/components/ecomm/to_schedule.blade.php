<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@include('plugins.plugins')
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

.transaction_details_content{
    display: flex;
    padding: 10px;
}

.transaction_details_flex{
    display: flex;
    align-items: center;
}

.transaction_details_flex label{
    width: 250px;
    margin-bottom: 0;
}

@media only screen and (max-width: 340px) {
    .transaction_details_flex{
        display: block;
    }
    .transaction_details_flex label{
        width: 100%;
    }
    .transaction_details_content{
        display: block;
    }
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
        

                                
                        @if($row->mode_of_return == "STORE DROP-OFF" && $row->returns_status_1 == 23)
                        <div class="transaction_details_content">
                            <div class="transaction_details_flex">
                                <label class="control-label">{{ trans('message.form-label.pickup_schedule') }}</label>
                                <input type='input'  name='pickup_schedule' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
                            </div>
                        </div>
                        @endif

                                   
                        @if($row->returns_status_1 == 22)
                        <div class="transaction_details_content">
                            <div class="transaction_details_flex">
                                @if($row->mode_of_return == "STORE DROP-OFF")
                                    <label class="control-label">{{ trans('message.form-label.dropoff_schedule') }}</label>
                                    @else
                                    <label class="control-label">{{ trans('message.form-label.return_schedule') }}</label>
                                @endif
                                <input type='input'  name='return_schedule' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />
                            </div>
                        </div>
                        @endif
                            
                        <?php   $Mode = $row->mode_of_return;  $Transaction = $row->transaction_type_id;  ?>
                        
                        @if($row->mode_of_return == "DOOR-TO-DOOR")
                            @if($row->transaction_type_id == "3")

                            <table class="custom_normal_table">
                                <tbody>
                                    <tr id="dr">
                                        <td>{{ trans('message.form-label.dr_number') }}</td>
                                        <td>
                                            <input type='input' name='dr_number' id="dr_number" class='form-control' autocomplete="off" maxlength="50"  onkeypress="return AvoidSpace(event)"  required placeholder="DR#" />
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            @endif 
                        @endif
                        
                        <br>
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
                                @if($row->bank_name  != null)
                                <tr>
                                    <td>{{ trans('message.form-label.bank_name') }}</td>
                                    <td>{{$row->bank_name}}</td>
                                    <td>{{ trans('message.form-label.bank_account_no') }}</td>
                                    <td>{{$row->bank_account_no}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ trans('message.form-label.items_included') }}</td>
                                    @if($row->items_included_others  != null)
                                            <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                        @else
                                            <td>{{$row->items_included}}</td>
                                    @endif
                                    @if($row->bank_account_name  != null)
                                    <td>{{ trans('message.form-label.bank_account_name') }}</td>
                                    <td>{{ $row->bank_account_name }}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>

                        <br/>

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
                                    
                                    <td style="text-align:center" height="10">{{$rowresult->problem_details}}
                                        @if($rowresult->problem_details_other != null)
                                            <br>
                                            {{$rowresult->problem_details_other}}
                                        @endif
                                    </td>
                                    <!--<td style="text-align:center" height="10">
                                        
                                        <select   class="js-example-basic-multiple" required name="problem_details[]" id="problem_details" multiple="multiple" style="width:100%;">
                                            @foreach($problem_details_list as $key=>$list)
                                                    @if(strpos($rowresult->problem_details, $list->problem_details) !== false)
                                                            <option selected value="{{$list->problem_details}}" >{{$list->problem_details}}</option>
                                                        @else
                                                            <option  value="{{$list->problem_details}}">{{$list->problem_details}}</option>
                                                    @endif
                                                    
                                            @endforeach
                        
                                        </select>

                                        @if($rowresult->problem_details_other != null)
                                            <br><br>
                                            <input type='input'  name='problem_details_other' id="problem_details_other" autocomplete="off" class='form-control' value="{{$rowresult->problem_details_other}}"/> 
                                        @endif
                                    </td> -->
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
                        <hr/>
                        
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

                        <br>
                        @endif
    
                    </div>
                </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

                @if($row->returns_status_1 == 22)
                    <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                @endif
                @if($row->mode_of_return == "STORE DROP-OFF" && $row->returns_status_1 == 23)
                        <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                @elseif($row->mode_of_return == "DOOR-TO-DOOR" &&   $row->returns_status_1 == 23)
                        <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.proceed') }}</button>
                @endif
                
                <button class="btn btn-danger pull-right" type="submit" id="cancel" style="margin-right:10px; width:135px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.void') }}</button>
            </div>
        </form>
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

var Mode = <?php echo json_encode($Mode); ?>;

var Transaction = <?php echo json_encode($Transaction); ?>;


$("#btnSubmit").on('click',function() {
    
    
    
    if(Mode == "DOOR-TO-DOOR") {  
        
        if(Transaction == "3") {  
            
            if($("#dr_number").val().includes("DR#")){
                
                if($("#dr_number").val().includes(" ")){
                    
                    signal = 0;
                    alert_message = 1;
                    
                }else if(text_length <= 4){
                    
                        signal = 0;
                        alert_message = 1;
                        
                }else{
                    
                    signal =1;
                    restriction = 0;
                    
                }
                
            }else{
                
                signal = 0;
                alert("Incorrect DR# format! e.g. DR#1001");
    
                return false;  
            }
            
        }
        
    }
    
    $("#remarks").val("SAVE");
    
    
});

$("#items_included_others").hide();
var items_others = <?php echo json_encode($other_items_included); ?>;



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