<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@section('content')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

<style>
    .table tbody tr td, .table thead tr th, .table{
        border: 1px solid #ddd;
    }
</style>

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
                    @include('components.chat-app', $comments_data)
                </div>
            </div>
        </div>
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="diagnose" id="diagnose">
                <div id="requestform" class='panel-body'>
                    <div> 
                            <div class="row"> 
                                <label class="control-label col-md-2"  style="margin-top:7px;">{{ trans('message.form-label.mode_of_refund') }}</label>
                                        <div class="col-md-4">
                     
                                          
                                            <select class="js-example-basic-single" name="mode_of_refund" id="mode_of_refund" required style="width:100%;height:35px;">
                                                    <option value="">-- Select Mode of Refund --</option>
                                                    @foreach($payments as $datas)   
                                                                    <option  value="{{$datas->mode_of_refund}}">{{$datas->mode_of_refund}}</option>
                                                    @endforeach
                                            </select>   
                                </div>
                                
                                <div  id="div_bank_name">
                                    <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_name') }}</label>
                                    <div class="col-md-4">
                                        <input type='input' name='bank_name' id="bank_name" class='form-control' autocomplete="off" maxlength="50" placeholder="Bank Name"  />                             
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row" id="div_bank_account_no"> 
                                <br>

                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_account_no') }}</label>
                                <div class="col-md-4">
                                    <input type='input' name='bank_account_no' id="bank_account_no" class='form-control' autocomplete="off" maxlength="50" placeholder="Bank Account#"  />                             
                                </div>
                                
                                
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_account_name') }}</label>
                                <div class="col-md-4">
                                    <input type='input' name='bank_account_name' id="bank_account_name" class='form-control' autocomplete="off" maxlength="50" placeholder="Bank Account Name"  />                             
                                </div>
                            </div>

                            <hr/>

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
                                        <td><{{$row->mode_of_payment}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <br>

                            <table class="custom_normal_table">
                                <tr>
                                    <td>{{ trans('message.form-label.items_included') }}</td>
                                    @if($row->items_included_others  != null)
                                            <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                        @else
                                            <td>{{$row->items_included}}</td>
                                    @endif
                                    <td>{{ trans('message.form-label.customer_location') }}</td>
                                    <td>{{$row->customer_location}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                    @if($row->verified_items_included_others  != null)
                                            <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</td>
                                        @else
                                            <td>{{$row->verified_items_included}}</td>
                                    @endif
                                </tr>
                            </table>
                            <br>
                            <table  class='table table-striped table-bordered'>
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
                                </tbody>
                            </table>


                            @if($row->diagnose != "REPLACE")
                                <hr/>

                                <table class="custom_normal_table">
                                    <tbody>
                                        <tr>
                                            <td>{{ trans('message.form-label.diagnosed_by') }}</td>
                                            <td>{{$row->diagnosed_by}}</td>
                                            <td>{{ trans('message.form-label.diagnosed_at') }}</td>
                                            <td>{{$row->level2_personnel_edited}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ trans('message.table.comments2') }}</td>
                                            <td>{{$row->diagnose_comments}}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                @else
                                <hr/>

                                <table class="custom_normal_table">
                                    <tbody>
                                        <tr>
                                            <td>{{ trans('message.form-label.diagnosed_by') }}</td>
                                            <td>{{$row->diagnosed_by}}</td>
                                            <td>{{ trans('message.form-label.diagnosed_at') }}</td>
                                            <td><{{$row->level2_personnel_edited}}/td>
                                        </tr>
                                        <tr>
                                            <td>{{ trans('message.table.comments2') }}</td>
                                            <td>{{$row->diagnose_comments}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif

                    </div>
                </div>
                <div class='panel-footer'>
                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                    <button class="btn btn-primary pull-right f-btn" type="button"><i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
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


function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}

function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);


$("#div_bank_name").hide();
$("#div_bank_account_no").hide();


$('#mode_of_refund').change(function(){
    if($('#mode_of_refund').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("BANK DEPOSIT")) {
        $("#div_bank_name").show();
        $("#div_bank_account_no").show();  
        $('#bank_name').attr("required", true);
        $('#bank_account_no').attr("required", true);
        $('#bank_account_name').attr("required", true);
    }else{
        $("#div_bank_name").hide();
        $("#div_bank_account_no").hide();
        
        $('#bank_name').attr("required", false);
        $('#bank_account_no').attr("required", false);
        $('#bank_account_name').attr("required", false);
        
        
        $('#bank_name').val("");
        $('#bank_account_no').val("");
        $('#bank_account_name').val("");
    }
});

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


$(document).ready(function(){
  $("myform").submit(function(){
        $('#btnSubmit').attr('disabled', true);
  });
});
</script>
@endpush 