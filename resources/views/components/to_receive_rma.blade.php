<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@section('content')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

<div class='panel panel-default'>
    <div class='panel-heading'>Details Form</div>
    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden"  name="diagnose" id="diagnose">
        <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $row->id }}">
        <div id="requestform" class='panel-body'>
            <div>
                @if ($row->returns_status_1 == 39 )
                <div class="row">
                  
                </div>
                <div class="row">
                    <div class="col-md-6">
                            
                    </div>

                    <label class="control-label col-md-2">Technicians</label>
                    <div class="col-md-4">
                        <select class="js-example-basic-single" name="technician" id="technician" required>
                            <option value="" selected disabled>Select Technician</option>
                          @foreach ($technicians as $technician)
                              <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <br>
                @endif   
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
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->purchase_location}}</p>
                    </div>

                    <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->store}}</p>
                    </div>
                </div>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_return') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->mode_of_return}}</p>
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
                        </div>
                    @endif
                    
                    @if ($row->branch_dropoff != null || $row->branch_dropoff != "")
                            <label class="control-label col-md-2">{{ trans('message.form-label.branch_dropoff') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->branch_dropoff}}</p>
                            </div>    
                    @endif  
                </div>  
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
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.purchase_date') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->purchase_date}}</p>
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
            </div>

            <hr>

            <div>
                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.form-label.tagged_by') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->tagged_by}}</p>
                    </div>
                    <label class="control-label col-md-2">{{ trans('message.form-label.tagged_at') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->level1_personnel_edited}}</p>
                    </div>
                </div>
    
                <div class="row"> 
                    <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                    <div class="col-md-4">
                        <p>{{$row->customer_location}}</p>
                    </div>
                </div> 
            </div>

            <hr>

            <div>
                {{-- Eccoms --}}
                @if(Request::segment(2) == 'to_receive_ecomm')
                    @if($row->transaction_type == 3)
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->scheduled_by}}</p>
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.dropoff_schedule') }}</label>
                            <div class="col-md-4">
                                    <p>{{$row->return_schedule}}</p>
                            </div>
                        </div>
                        @else
                
                        @if($row->mode_of_return == "STORE DROP-OFF")
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->scheduled_by}}</p>
                                </div>

                                @if($row->mode_of_return == "STORE DROP-OFF")
                                    <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.dropoff_schedule') }}</label>
                                    @else
                                    <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.return_schedule') }}</label>
                                @endif
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
                        @endif                                     
                    @endif 
                @endif

                @if(Request::segment(2) == 'to_receive_retail')
                    <div class="row">                           
                        <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                        <div class="col-md-4">
                            <p>{{$row->tagged_by}}</p>
                        </div>

                        <label class="control-label col-md-2">{{ trans('message.form-label.dropoff_schedule') }}</label>
                        <div class="col-md-4">
                                <p>{{$row->return_schedule}}</p>
                        </div>
                    </div>  
                @endif

                @if(Request::segment(2) == 'to_receive_distri')
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
                @endif

                <br>

                <table class='table table-striped table-bordered'>
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

                <div class="row">                           
                    <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                    <div class="col-md-10">
                        <p>{{$row->comments}}</p>
                    </div>
                </div>

            </div>
        </div>
        <div class='panel-footer'>          
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ $row->returns_status_1 == 37 ? trans('message.form.turnover') : trans('message.form.receive')  }}</button>

            @if ($row->returns_status_1 == 39)
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i>  Assign</button>
            @elseif ($row->returns_status_1 == 37)
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i> {{trans('message.form.turnover')}}</button>
            @else
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i> {{trans('message.form.receive')}}</button>
            @endif
        </div>
    </form>
</div>

<script>

    $(document).ready(function() {
        $('.js-example-basic-single').select2({ 
            width: '75%'
        });
    });

    function toTurnOver(id, table_name, module_mainpath){
        $.ajax({
            type: 'POST',
            url: '{{ route('turnover') }}',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                table_name: table_name,
            },
            success: function(res){
                if(res.success){
                    Swal.fire({
                    title: "INC Number",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                    html: res.success,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let inc_reference_number = "{{ route('custom_reference_number', ['#ref_num','#mainpath']) }}";
                        inc_reference_number = inc_reference_number.replace('#ref_num', res.success);
                        inc_reference_number = inc_reference_number.replace('#mainpath', module_mainpath);
                        // console.log(inc_reference_number);
                        location.assign(inc_reference_number);
                    }
                });
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    
    if("{{ $row->returns_status_1 == 37 }}") {

        $('.f-btn').on('click', function(){
            const id = $('#transaction_id').val();
            const module_mainpath = "{{ Request::segment(2) }}";
            let table_name = "{{ Request::segment(2) }}";

            if (table_name == 'to_receive_ecomm'){
                table_name = 'returns_header';
            }
            else if (table_name == 'to_receive_retail'){
                table_name = 'returns_header_retail';
            }
            else if(table_name == 'to_receive_distri'){
                table_name = 'returns_header_distribution';
            }

            Swal.fire({
                title: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true,
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    toTurnOver(id, table_name, module_mainpath);
                }
            });
        })
    }else{
        $('.f-btn').on('click', function(){
            const id = $('#transaction_id').val();
    
            Swal.fire({
                title: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true,
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnSubmit').click();
                }
            });
        })
    }
    
</script>

@endsection