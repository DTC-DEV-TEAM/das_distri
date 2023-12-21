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
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div id="requestform" class='panel-body'>
                    <div>

                        <div class="transaction_details_content">
                            <div class="transaction_details_flex">
                                <label class="control-label" for="">{{ trans('message.form-label.return_delivery_date') }}</label>
                                <input type='input'  name='return_delivery_date' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
                            </div>
                        </div>
                        <br/>
                        <table class="custom_table">
                            <tbody>
                                <tr>
                                    <td>Pullout From:</td>
                                    <td>{{$row->deliver_to}}</td>
                                    <td>Deliver To:</td>
                                    <td>{{$store_deliver_to->store_name}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.return_reference_no') }}</td>
                                    <td>{{$row->return_reference_no}}</td>
                                    @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                    <td>{{ trans('message.form-label.store_dropoff') }}</td>
                                    <td>{{$row->store_dropoff}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_location') }}</td>
                                    <td>{{$row->purchase_location}}</td>
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
                                    <td>{{ trans('message.form-label.contact_no') }}</td>
                                    <td>{{$row->contact_no}}</td>
                                    <td>{{ trans('message.form-label.email_address') }}</td>
                                    <td>{{$row->email_address}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.address') }}</td>
                                    <td>{{$row->address}}</td>
                                    <td>{{ trans('message.form-label.order_no') }}</td>
                                    <td>{{$row->order_no}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.purchase_date') }}</td>
                                    <td>{{$row->purchase_date}}</td>
                                    <td>{{ trans('message.form-label.mode_of_payment') }}</td>
                                    <td>{{$row->mode_of_payment}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br> 
                        <table class="custom_normal_table">
                            <tr>
                                <td>{{ trans('message.form-label.items_included') }}</td>
                                @if($row->items_included_others  != null)
                                        <td>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                    @else
                                        <td>{{$row->items_included}}</td>
                                @endif
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>{{ trans('message.form-label.verified_items_included') }}</td>
                                @if($row->verified_items_included_others  != null)
                                        <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</td>
                                    @else
                                        <td>{{$row->verified_items_included}}</td>
                                @endif
                                <td></td>
                            </tr>
                        </table>                      
                            

                        @if($row->level7_personnel == null)
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.created_by') }}</td>
                                        <td>{{$row->created_by}}</td>
                                        <td>{{ trans('message.form-label.created_at') }}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif

                        <br>

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

    function preventBack() {
        window.history.forward();
    }

    window.onunload = function() {
        null;
    };

    setTimeout("preventBack()", 0);

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

    $( "#datepicker" ).datepicker( { minDate: '1', dateFormat: 'yy-mm-dd' } );


    $(document).ready(function(){
        $("myform").submit(function(){
                $('#btnSubmit').attr('disabled', true);
        });
    });

</script>
@endpush