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
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="diagnose" id="diagnose">
                <div id="requestform" class='panel-body'>
                    <div> 
                    
                        <div class="row" style="background-color: #3C8DBC; height:50px;"> 
                            <div class="col-md-6" style="margin-top: 10px; color: white; font-size: 20px;">
                                    
                                    <span >Please Upload Proof of Refund here: </span><a style="margin-top: 2px; color: white;" href="https://drive.google.com/drive/folders/1bx6o1nNMTXpFlzT0u0iCe3Xzik28NLoa?usp=sharing" target="_blank">&nbsp;<i class="fa fa-cloud-upload fa-lg" ></i></a>
                               
                            </div>
                        
                        </div>
                        <br>

                        <div class="row"> 
                            <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.refunded_date') }}</label>
                            <div class="col-md-4">
                                    <input type='input'  name='refunded_date' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
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
                                    <td>{{$row->mode_of_payment}}</td>
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
                            </tbody>
                        </table>
                        
                        @if($row->scheduled_by != null || $row->scheduled_by != "")
                        <hr/>

                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                    <td>{{$row->scheduled_by}}</td>
                                    <td>{{ trans('message.form-label.scheduled_at') }}</td>
                                    <td>{{$row->level1_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.return_schedule1') }}</td>
                                    <td>{{$row->return_schedule}}</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif

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
                        
                        <hr/>

                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.diagnosed_by') }}</td>
                                    <td>{{$row->diagnosed_by}}</td>
                                    <td>{{ trans('message.form-label.diagnosed_at') }}</td>
                                    <td>{{$row->level3_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.table.comments2') }}</td>
                                    <td>{{$row->diagnose_comments}}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if($row->diagnose == "REFUND")
                        <hr/>
                        
                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.created_crf_by') }}</td>
                                    <td>{{$row->created_crf_by}}</td>
                                    <td>{{ trans('message.form-label.created_crf_date') }}</td>
                                    <td>{{$row->level8_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.bank_account_name') }}</td>
                                    <td>{{$row->bank_account_name}}</td>
                                    <td>{{ trans('message.form-label.bank_account_no') }}</td>
                                    <td>{{$row->bank_account_no}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.bank_name') }}</td>
                                    <td>{{$row->bank_name}}</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                            
                        <hr/>

                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.printed_by') }}</td>
                                    <td>{{$row->printed_by}}</td>
                                    <td>{{ trans('message.form-label.printed_at') }}</td>
                                    <td>{{$row->level4_personnel_edited}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <hr/>

                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.transacted_by') }}</td>
                                    <td>{{$row->transacted_by}}</td>
                                    <td>{{ trans('message.form-label.transacted_at') }}</td>
                                    <td>{{$row->level4_personnel_edited}}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if($row->sor_number != null || $row->sor_number != "")
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.sor_number') }}</td>
                                        <td>{{$row->sor_number}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class='panel-footer'>
                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.close') }}</button>
                    <button class="btn btn-primary pull-right f-btn" type="button"><i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                </div>
        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

    $( "#datepicker" ).datepicker( {  maxDate: '0',  dateFormat: 'yy-mm-dd'  } );

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