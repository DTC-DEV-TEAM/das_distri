<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

<style type="text/css">  

    .pic-container {
        width: 1350px;
        margin: 0 auto;
        white-space: nowrap;
    }

    .pic-row {
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
    <div class='panel panel-default' style="position: relative">
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
            <input type="hidden"  name="remarks" id="remarks">
                <div id="requestform" class='panel-body'>
                    <div> 
                        <table class="custom_normal_table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.customer_location') }}</td>
                                    <td >
                                        <select class=" form-control" name="customer_location" id="customer_location" required>
                                            @foreach($store_list as $datas)    
                                                <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @if($Location == 1) 
                                <tr>
                                    <td>Service Center Location:</td>
                                    <td>
                                        <select class="form-control" name="deliver_to" id="deliver_to" required>
                                            <option value="" disabled>-- Select Service Center Location --</option>
                                            @foreach($SCLocation as $datas)    
                                                <option  value="{{$datas->sc_location_name}}">{{$datas->sc_location_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ trans('message.form-label.via') }}</td>
                                    @foreach($via as $data)
                                        @if($data->id == 1)
                                            <td>
                                                <label class="radio-inline control-label " ><input type="radio" required class="via_class" name="via_id" value="{{$data->id}}" >{{$data->via_name}}</label>
                                            </td>
                                            @else
                                            <td>
                                                <label class="radio-inline control-label "><input type="radio" required class="via_class" name="via_id" value="{{$data->id}}" >{{$data->via_name}}</label>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr id="carried">
                                    <td>{{ trans('message.form-label.carried_by') }}</td>
                                    <td>
                                        <input type='input' name='carried_by' id="carried_by" class='form-control' autocomplete="off"  placeholder="Hand Carried By"  required/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.warranty_status') }}</td>
                                    @foreach($warranty_status as $data)
                                        @if($data->warranty_name =="IN WARRANTY")
                                                <td>
                                                    <label class="radio-inline control-label" ><input type="radio" required name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                </td>
                                            @else
                                                <td>
                                                    <label class="radio-inline control-label "><input type="radio" required name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                </div>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.transaction_type_id') }}</td>
                                    @foreach($transaction_type as $data)
                                        @if($data->id == 2)
                                            <td>
                                                <label class="radio-inline control-label" ><input type="radio" class="transactionradio" required name="transaction_type_id" id="transaction_type_id" value="{{$data->id}}" >{{$data->transaction_type_name}}</label>
                                                <br>
                                            </td>
                                            @else
                                            <td>
                                                <label class="radio-inline control-label"><input type="radio" class="transactionradio" required name="transaction_type_id" id="transaction_type_id" value="{{$data->id}}" >{{$data->transaction_type_name}}</label>
                                                <br>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr id="replacement">
                                    <td>{{ trans('message.form-label.negative_positive_invoice') }}</td>
                                    <td>
                                        <input type='input' name='negative_positive_invoice' id="negative_positive_invoice" class='form-control' autocomplete="off" maxlength="50" placeholder="INV#" onkeypress="return AvoidSpace(event)"  required/>
                                    </td>
                                </tr>
                                <tr id="replacement1">
                                    <td>{{ trans('message.form-label.pos_replacement_ref') }}</td>
                                    <td>
                                        <input type='input' name='pos_replacement_ref' id="pos_replacement_ref" class='form-control' autocomplete="off" maxlength="50"  onkeypress="return AvoidSpace(event)"  required placeholder="REP#" />                             
                                    </td>
                                    <td>
                                        <span style="font-weight: bold;;">Notes:</span> <span style="color: red;">*PLEASE CREATE POS REPLACEMENT STOCK ADJUSTMENT TRANSACTION.</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 

                        <br>
                            
                        <table class="custom_table">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.return_reference_no') }}</td>
                                    <td>{{$row->return_reference_no}}</td>
                                    <td>{{ trans('message.form-label.created_at') }}</td>
                                    <td>{{$row->created_at}}</td>  
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_location') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="purchase_location" id="purchase_location"  required style="width:100%">
                                        
                                            @foreach($purchaselocation as $datas)    
                                                @if( $datas->channel_name == $row->purchase_location)
                                                            <option selected value="{{$datas->channel_name}}">{{$datas->channel_name}}</option>
                                                    @else
                                                            <option  value="{{$datas->channel_name}}">{{$datas->channel_name}}</option>
                                                @endif

                                            @endforeach
                                        </select>  
                                    </td>
                                    <td>{{ trans('message.form-label.store') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="store" id="store" onchange="showCustomerLocation()" required style="width:100%">
                                            @foreach($store_front_end as $datas)    
                                                @if( $datas->store_name == $row->store)
                                                            <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                    @else
                                                            <option  value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                @endif

                                            @endforeach
                                        </select>  
                                    </td>
                                </tr>
                                <tr>
                                    @if ($row->branch != null || $row->branch != "")
                                    <td>{{ trans('message.form-label.branch') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="branch" id="branch" required style="width:100%">
                                            @foreach($branch as $datas)    
                                                @if( $datas->branch_id == $row->branch)
                                                            <option selected value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                    @else
                                                            <option  value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                @endif
                                            @endforeach
                                        </select> 
                                    </td>
                                    @endif
                                    <td>{{ trans('message.form-label.mode_of_return') }}</td>  
                                    <td>
                                        <select class="js-example-basic-single" name="mode_of_return" id="mode_of_return" required style="width:100%">
                                            @if(  $row->mode_of_return == "STORE DROP-OFF")
                                                <option selected value="STORE DROP-OFF">Store Drop-Off</option>
                                                @else
                                                    <option value="STORE DROP-OFF">Store Drop-Off</option>
                                            @endif
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                    <td>{{ trans('message.form-label.store_dropoff') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="store_dropoff" id="store_dropoff" onchange="showBranch()"  required style="width:100%">
                                            @foreach($store_drop_off as $datas)    
                                                @if( $datas->store_name == $row->store_dropoff)
                                                    <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                    @else
                                                        <option  value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                @endif
                                            @endforeach
                                        </select> 
                                    </td>
                                    @endif
                                    <td>{{ trans('message.form-label.branch_dropoff') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="branch_dropoff" id="branch_dropoff"  style="width:100%" onchange="branchChange()">
                                            @foreach($branch_dropoff as $datas)    
                                                @if( $datas->branch_id == $row->branch_dropoff)
                                                            <option selected value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                    @else
                                                            <option  value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                @endif
        
                                            @endforeach
                                        </select> 
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br>

                        <table class="custom_table">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.customer_last_name') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->customer_last_name}}" name='customer_last_name' id="customer_last_name" placeholder="{{ trans('message.form-label.customer_last_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                    </td>
                                    <td>{{ trans('message.form-label.customer_first_name') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->customer_first_name}}" name='customer_first_name' id="customer_first_name" placeholder="{{ trans('message.form-label.customer_first_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                    </td>  
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.address') }}</td>
                                    <td>
                                        <textarea placeholder="{{ trans('message.form-label.address') }} ..." rows="3" class="form-control" name="address" id="address" required>{{$row->address}}</textarea>
                                    </td>
                                    <td>{{ trans('message.form-label.email_address') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->email_address}}" name='email_address' id="email_address" placeholder="{{ trans('message.form-label.email_address') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.contact_no') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->contact_no}}" name='contact_no' id="contact_no" placeholder="{{ trans('message.form-label.contact_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                    </td>
                                    <td>{{ trans('message.form-label.order_no') }}</td>  
                                    <td>
                                        <input type='input' value="{{$row->order_no}}" name='order_no' id="order_no" placeholder="{{ trans('message.form-label.order_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_date') }}</td>
                                    <td>
                                        <input type='input'  name='purchase_date' id="datepicker" value="{{$row->purchase_date}}" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" /> 
                                    </td>
                                    <td>{{ trans('message.form-label.mode_of_payment') }}</td>
                                    <td>
                                        <select class="js-example-basic-multiple" required name="mode_of_payment[]" id="mode_of_payment" multiple="multiple" style="width:100%;">
                                            @foreach($payments as $key=>$list)
                                                @if(strpos($row->mode_of_payment, $list->payment_name) !== false)
                                                    <option selected value="{{$list->payment_name}}" >{{$list->payment_name}}</option>
                                                    @else
                                                        <option  value="{{$list->payment_name}}">{{$list->payment_name}}</option>
                                                @endif
                                            @endforeach
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    @if($row->mode_of_refund != null || $row->mode_of_refund != "")
                                    <td>{{ trans('message.form-label.mode_of_refund') }}</td>
                                    <td>
                                        <select class="js-example-basic-single" name="mode_of_refund" id="mode_of_refund" required style="width:100%">
                                            <option value="">-- Select Mode of Refund --</option>
                                            @foreach($payments as $datas)    
                                                @if( $datas->payment_name == $row->mode_of_refund)
                                                    <option selected value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                                    @else
                                                        <option  value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>   
                                    </td>
                                    @endif
                                    @if ($row->bank_name != null || $row->bank_name != "")
                                    <td>{{ trans('message.form-label.bank_name') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->bank_name}}" name='bank_name' id="bank_name" placeholder="{{ trans('message.form-label.bank_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                    </td>
                                    @endif
                                </tr>
                                <tr>
                                    @if ($row->bank_account_no != null || $row->bank_account_no != "")
                                    <td>{{ trans('message.form-label.bank_account_no') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->bank_account_no}}" name='bank_account_no' id="bank_account_no" placeholder="{{ trans('message.form-label.bank_account_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                    </td>
                                    @endif
                                    @if ($row->bank_account_name != null || $row->bank_account_name != "")
                                    <td>{{ trans('message.form-label.bank_account_name') }}</td>
                                    <td>
                                        <input type='input' value="{{$row->bank_account_name}}" name='bank_account_name' id="bank_account_name" placeholder="{{ trans('message.form-label.bank_account_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                    </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>

                        <br>

                        <table class="custom_normal_table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.items_included') }}</td>
                                    @if($row->items_included_others  != null)
                                    <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                    @else
                                    <td>{{$row->items_included}}</td>
                                    @endif
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                    <td>
                                        <select class="js-example-basic-multiple" required name="items_included[]" id="items_included" multiple="multiple" style="width:100%;">
                                            @foreach($items_included_list as $key=>$list)
                                                    @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                            <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                        @else
                                                            <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                    @endif
                                                    
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php $other_items_included = $row->items_included_others;?></td>
                                    <td>
                                        <input type='input'  name='items_included_others' id="items_included_others" autocomplete="off" class='form-control' value="{{$row->items_included_others}}" placeholder="OTHERS"/> 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>

                        <div style="overflow: auto;">
                            <table class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_item_code') }}</th>
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_item_description') }}</th>
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_brand') }}</th>
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_cost') }}</th>
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_serial_number') }}</th>
                                        <!--<th style="text-align:center" height="10">{{ trans('message.table.front_end_items_included') }}</th>-->
                                        <th style="text-align:center" height="10">{{ trans('message.table.front_end_problem_details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @if (!empty($resultlist)) 
                                        @foreach($resultlist as $rowresult)
                                        <tr>
                                            <?php $stack_serials = $rowresult->serial_number;?>
                                            <?php $stack_problem_details = $rowresult->problem_details;?>
                                            <?php $stack_problem_details_other = $rowresult->problem_details_other;?>
                                            <?php $stack_cost = $rowresult->cost;?>
                                            <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                            <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                            <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                            <td style="text-align:center" height="10">{{$rowresult->cost}}</td>
                                            <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                                                <!--<td style="text-align:center" height="10">{{$rowresult->items_included}}</td>-->
                                            <td style="text-align:center" height="10">{{$rowresult->problem_details}}
                                                @if($rowresult->problem_details_other != null)
                                                    <br>
                                                    {{$rowresult->problem_details_other}}
                                                @endif
                                            </td>                    
                                        </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        
                                    </tr>                        
                                </tbody>
                            </table>   
                        </div>
                                            
                        <hr />

                        <table class="custom_normal_table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.add_item') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input class="form-control auto" style="max-width:420px;" placeholder="Search Item" id="search">
                                            <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; max-width: 520px;">
                                                <li>Loading...</li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-header text-center">
                                <h3 class="box-title"><b>{{ trans('message.form-label.return_items') }}</b></h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <div class="pic-container">
                                            <div class="pic-row">
                                                <table class="table table-bordered" id="pullout-items">
                                                    <tbody>
                                                        <tr class="tbl_header_color dynamicRows">
                                                            <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                                            <th width="10%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                                            <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                            <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th>
                                                            <th width="10%" class="text-center">{{ trans('message.table.brand') }}</th>
                                                            <th width="10%" class="text-center">{{ trans('message.table.serial_no') }}</th>
                                                            <th width="10%" class="text-center">{{ trans('message.table.problem_details') }}</th>
                                                            <th width="5%" class="text-center">{{ trans('message.table.quantity') }}</th>
                                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                                        </tr>
                                                        <!--
                                                        <tr class="tableInfo">
                                                            <td colspan="7" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                                            <td align="left" colspan="1">
                                                                <input type='number' name="total_quantity" class="form-control text-center" id="totalQuantity" readonly></td>
                                                            </td>
                                                            <td colspan="1"></td>
                                                        </tr>
                                                    -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                
                                    </div>
                                    <br>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('message.table.note') }}</label>
                                    <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3"  wrap="soft" class="form-control" name="requestor_comments"></textarea>
                                </div>
                            </div>
                        
                        </div>

                    </div>
                </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>

                <button class="btn btn-danger pull-right" type="submit" id="cancel" style="margin-right:10px; width:135px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.void') }}</button>

            </div>

        </form>

</div>
@endsection

@push('bottom')
<script type="text/javascript">

$('#carried').hide();
$('#carried_by').removeAttr('required');

$('.via_class').change(function(){
    var value = $(this).val();
    transactionvalue  = $(this).val();
    if(value == 2){
        $('#carried').show();
        $('#carried_by').attr('required', 'required');
    }else{
        $('#carried').hide();
        $('#carried_by').removeAttr('required');
    }
});


$(document).ready(function() {
    $('.js-example-basic-single').select2({
        dropdownAutoWidth: true,
        width: '100%'
    });
});

//$('#locations').hide();
//$('#deliver_to').attr("required", false);

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


$(".js-example-basic-multiple").select2({
     theme: "classic"
});

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

$('#replacement, #replacement1').hide();

$('#negative_positive_invoice, #pos_replacement_ref').removeAttr('required');

var transactionvalue = 2;
$('.transactionradio').change(function(){
    var value = $(this).val();
    transactionvalue  = $(this).val();
    if(value == 1){
        $('#replacement, #replacement1').show();
        $('#negative_positive_invoice, #pos_replacement_ref').attr('required', 'required');
    }else{
        $('#replacement, #replacement1').hide();
        $('#negative_positive_invoice, #pos_replacement_ref').removeAttr('required');
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
        var location = document.getElementById("purchase_location").value;
        $.ajax
        ({ 
            url: '{{ url('admin/retail_for_verification/branch_drop_off') }}',
            type: "POST",
            data: {
                'drop_off_store': drop_off_store,
                'location': location,                
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
    
        var rowCount = $('#pullout-items tr').length;
       // var quantityReservable = $('.item_quantity').val();
        var itemCost = $('.cost_item').val();
       // var totalQty = $("#totalQuantity").val();
        var signal = 0;
        var alert_message = 0;
        var error_qty = 0;   
        var text_length = $("#negative_positive_invoice").val().length;

        if(transactionvalue == 1){

            if($("#negative_positive_invoice").val().includes("INV#")){
                
                if($("#negative_positive_invoice").val().includes(" ")){
                    signal = 0;
                    alert_message = 1;
                    Swal.fire({
                        title: "Incorrect Negative/Positive Invoice format! e.g. INV#1001 remove space",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        returnFocus: false,
                        allowOutsideClick: true
                    });
                    return false;
                }else if(text_length <= 4){
                        signal = 0;
                        alert_message = 1;
                        Swal.fire({
                            title: "Incorrect Negative/Positive Invoice format! e.g. INV#1001",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            allowOutsideClick: true
                        })
                        return false;
                }else{
                    signal =    1;
                }
            }
            else if(!$("#negative_positive_invoice").val().includes("INV#")){
                signal = 0;
                alert_message = 1;
                Swal.fire({
                    title: "Incorrect Negative/Positive Invoice format! e.g. INV#1001",
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                    allowOutsideClick: true
                })
                return false;
            }
            // else{
            //     signal = 0;
            //     alert_message = 1;
            //     Swal.fire({
            //         title: "Incorrect Negative/Positive Invoice format! e.g. INV#1001",
            //         icon: 'warning',
            //         showCancelButton: false,
            //         confirmButtonColor: '#3085d6',
            //         confirmButtonText: 'Ok',
            //         returnFocus: false,
            //         allowOutsideClick: true
            //     })
            //     return false;
            // }
            
            
            
            if($("#pos_replacement_ref").val().includes("REP#") ){
                
                if($("#pos_replacement_ref").val().includes(" ")){
                    signal = 0;
                    alert_message = 1;
                    Swal.fire({
                        title: "Incorrect POS Replacement Ref# format! e.g. REP#1001",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        returnFocus: false,
                        allowOutsideClick: true
                    });
                    return false;
                }else if(text_length <= 4){
                        signal = 0;
                        alert_message = 1;
                        Swal.fire({
                            title: "Incorrect POS Replacement Ref# format! e.g. REP#1001",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            allowOutsideClick: true
                        })
                        return false;
                }else{
                    signal =    1;
                }
                
            }
            else if(!$("#pos_replacement_ref").val().includes("INV#")){
                signal = 0;
                alert_message = 1;
                Swal.fire({
                    title: "Incorrect POS Replacement Ref# format! e.g. REP#1001",
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                    allowOutsideClick: true
                })
                return false;
            }
            // else{
            //     signal = 0;
            //     alert_message = 1;
            //     Swal.fire({
            //         title: "Incorrect POS Replacement Ref# format! e.g. REP#1001",
            //         icon: 'warning',
            //         showCancelButton: false,
            //         confirmButtonColor: '#3085d6',
            //         confirmButtonText: 'Ok',
            //         returnFocus: false,
            //         allowOutsideClick: true
            //     })
            //     return false;
            // }

        }
        
        if(rowCount <= 1){
            Swal.fire({
                title: "Please put an item!",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#search').focus();
                }
            })
            return false;
        }else{
            $("form").submit(function(){
                $('#btnSubmit').attr('disabled', true);
            }); 
        }
        
        if(itemCost == '' || itemCost == null || itemCost < 0 ){
            Swal.fire({
                title: "Please put item cost!",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: true
            })
            return false;
        }else{
            $("form").submit(function(){
                $('#btnSubmit').attr('disabled', true);
            }); 

            $("#remarks").val("SAVE");
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
                            Swal.fire({
                                title: "Please fill out the problem details!",
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                returnFocus: false,
                                allowOutsideClick: true
                            })
                            $("#search").val("");
                        }

                    }else{
                        $("#search").val("");
                        Swal.fire({
                            title: "Only 1 item allowed!",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            allowOutsideClick: true
                        })                        
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
                                '<td><input class="form-control text-center  cost_item" type="number" name="cost[]" value="'+stack_cost+'" min="0" max="9999999999" step="any" onKeyPress="if(this.value.length==10) return false;" oninput="validity.valid||(value=0);"></td>' +
                                '<td><input class="form-control text-center" type="text" name="brand[]" id="brand'+ e.id +'"  readonly value="' +e.stock_brand + '"></td>' +
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
                    
                    if($('#brand'+e.id).val() == "APPLE" || $('#brand'+e.id).val() == "BEATS"){
                        //$('#locations').show();
                        //$('#deliver_to').attr("required", true);
                    }

                   
                if(stack_problem_details != null){
                    

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

                }else{

                        var strArray = myStr.split(",");
                        for(var x=0; x < strArray.length; x++){
                         
                                    $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'">'+strArray[x]+'</option>');
                              
                                
                        }

                        $('#problem_details_other_'+e.id).hide();
                        



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
                        Swal.fire({
                            title: "You can't add this item!",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            allowOutsideClick: true
                        })
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
