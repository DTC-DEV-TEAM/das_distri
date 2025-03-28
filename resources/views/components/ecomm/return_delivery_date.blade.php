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
                <div id="requestform" class='panel-body'>
                    <div>
                        
                    <div class="transaction_details_content">
                        <div class="transaction_details_flex">
                            <label class="control-label">{{ trans('message.form-label.return_delivery_date') }}</label>
                            <input type='input'  name='return_delivery_date' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
                        </div>
                    </div>
                    <br>
                    <table class="custom_table">
                        <tbody>
                            @if ($row->store_dropoff != null || $row->store_dropoff != "")
                            <tr>
                                <td>Pullout From:</td>
                                <td>{{$row->deliver_to}}</td>
                                <td>Deliver To:</td>
                                <td>{{$store_deliver_to->store_name}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>{{ trans('message.form-label.return_reference_no') }}</td>
                                <td>{{$row->return_reference_no}}</td>
                                <td>{{ trans('message.form-label.purchase_location') }}</td>
                                <td>{{$row->purchase_location}}</td>

                            </tr>
                            <tr>
                                {{-- {{ dd(trans('message.form-label.store_dropoff')) }} --}}
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
                                <td>{{ trans('message.form-label.customer_location') }}</td>
                                <td>{{$row->customer_location}}</td>
                                <td>{{ trans('message.form-label.mode_of_return') }}</td>
                                <td>{{$row->mode_of_return}}</td>
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
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>{{ trans('message.form-label.items_included') }}</td>
                                @if($row->items_included_others  != null)
                                <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                @else
                                    <td>{{$row->items_included}}</td>
                                @endif
                                <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                @if($row->verified_items_included  != null)
                                    @if($row->verified_items_included_others  != null)
                                            <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                        @else
                                            <td>{{$row->verified_items_included}}</td>
                                    @endif
                                @endif
                            </tr>
                        </tbody>
                    </table>

                    <hr/>

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
                            @if($row->returns_status_1 == 23)
                            <tr>
                                <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                <td>{{$row->scheduled_by}}</td>
                                <td>{{ trans('message.form-label.return_schedule') }}</td>
                                <td>{{$row->return_schedule}}</td>
                                <?php $date_return = $row->return_schedule;?>
                            </tr>
                            <br>
                            @endif
                        </tbody>
                    </table>

                    </div>
                </div>
                
                <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
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


var date_return_script = <?php echo json_encode($date_return); ?>;


function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);


    
    
    //var date_return_script1 = date_return_script;
    //var dt = new Date(date_return_script1).getTime();
    //var days = 1;
    //var newDate = new Date(dt + days * 24*60*60*1000);

    $( "#datepicker" ).datepicker( {
        
        dateFormat: 'yy-mm-dd',
        minDate :1

    } );

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