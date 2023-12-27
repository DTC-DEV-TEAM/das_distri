<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

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

<div class="sk-chase-position" style="display: none;">
    <div class="sk-chase">
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
    </div>
    <div class="sk-chase-text">
        <p>Please wait, system is on process...</p>
    </div>
</div>

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
                @include('components.distribution.chat-app', $comments_data)
            </div>
        </div>
    </div>
    <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden"  name="diagnose" id="diagnose">
        <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $row->id }}">
        <div id="requestform" class='panel-body'>
            <div>
                @if ($row->returns_status_1 == 39 )
                <div class="row">
                    <div class="col-md-6">
                            
                    </div>
                    <label class="control-label col-md-1" style="margin-top: 5px;">Technicians</label>
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
                            <td>{{ trans('message.form-label.items_included') }}</td>
                            @if($row->mode_of_return =="STORE DROP-OFF")
                                @if($row->transaction_type == 3)
                                    @if($row->items_included_others  != null)
                                        <td>{{$row->items_included}}, {{$row->items_included_others}}</ptd>
                                    @else
                                        <td>{{$row->items_included}}</td>
                                    @endif
                                    @else
                                    @if($row->verified_items_included_others  != null)
                                            <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</td>
                                        @else
                                            <td>{{$row->verified_items_included}}</td>
                                    @endif
                                @endif
                                @else   
                                @if($row->items_included_others  != null)
                                        <td>{{$row->items_included}}, {{$row->items_included_others}}</td>
                                    @else
                                        <td>{{$row->items_included}}</td>
                                @endif
                            @endif 
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div>
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
            </div>

            <hr>

            <div>
                {{-- Eccoms --}}
                @if(Request::segment(2) == 'to_receive_ecomm')
                    @if($row->transaction_type == 3)
                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                    <td>{{$row->scheduled_by}}</td>
                                    <td>{{ trans('message.form-label.dropoff_schedule') }}</td>
                                    <td>{{$row->return_schedule}}</td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                
                        @if($row->mode_of_return == "STORE DROP-OFF")

                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                        <td>{{$row->scheduled_by}}</td>
                                        @if($row->mode_of_return == "STORE DROP-OFF")
                                            <td>{{ trans('message.form-label.dropoff_schedule') }}</td>
                                            @else
                                            <td>{{ trans('message.form-label.return_schedule') }}</td>
                                        @endif
                                        <td>{{$row->return_schedule}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="custom_normal_table">
                                <tr>
                                    <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                    <td>{{$row->scheduled_by_logistics}}</td>
                                    <td>{{ trans('message.form-label.pickup_schedule') }}</td>
                                    <td>{{$row->pickup_schedule}}</td>
                                </tr>
                            </table>
                        @endif                                     
                    @endif 
                @endif

                @if(Request::segment(2) == 'to_receive_retail')
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                <td>{{$row->tagged_by}}</td>
                                <td>{{ trans('message.form-label.dropoff_schedule') }}</td>
                                <td>{{$row->return_schedule}}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                @if(Request::segment(2) == 'to_receive_distri')
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                <td>{{$row->closed_by}}</td>
                                <td>{{ trans('message.form-label.dropoff_schedule') }}</td>
                                <td>{{$row->return_schedule}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                <td>{{$row->scheduled_by_logistics}}</td>
                                <td>{{ trans('message.form-label.pickup_schedule') }}</td>
                                <td>{{$row->pickup_schedule}}</td>
                            </tr>
                        </tbody>
                    </table>
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
            @elseif ($row->returns_status_1 == 40)
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i>  Ongoing Testing</button>
            @elseif ($row->returns_status_1 == 37)
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i> {{trans('message.form.turnover')}}</button>
            @else
            <button class="btn btn-primary pull-right f-btn" type="button" id="btnSubmit"> <i class="fa fa-save" ></i> {{trans('message.form.receive')}}</button>
            @endif
        </div>
    </form>
</div>

<script>

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
                    $('.sk-chase-position').hide();

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
                $('.sk-chase-position').hide();
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
                    $('.sk-chase-position').show();
                }
            });
        })
    }else{
        $('.f-btn').on('click', function(){

            const moduleMainpath = "{{ Request::segment(2) }}";

            const digitItems = ({!! json_encode($resultlist) !!});
            const clickedText = $(this).text();

            const referenceNumber = '{{$row->return_reference_no}}';
            // const digitsCode = 

            let wrapper = $('<div>');
            const table = $('<table id="swal_table">');
            const thead = $('<thead>');
            const tbody = $('<tbody>');
            const tr = $('<tr>');
            const title = $('<p class="text-center text-bold" style="font-size: 20px;">').text(`REFERENCE #: ${referenceNumber}`);
            const footerDescription = $('<p class="text-center text-bold" style="color: #EC5766;">').text(`Please make sure to print a barcode`);

            // Use th (table header) for the headers
            tr.append($('<th>').text('Digits Code'), $('<th>').text('Item Description'));

            thead.append(tr);

            // Digit Item
            digitItems.forEach((item, index) => {

                let dataRow = $('<tr style="color: #2C78C1; font-weight: 600;">').append($('<td>').text(item.digits_code), $('<td>').text(item.item_description));
                tbody.append(dataRow);
    
                table.append(thead, tbody);
    
            });

            if (moduleMainpath == 'to_receive_retail' || moduleMainpath == 'to_receive_distri' || moduleMainpath == 'to_receive_ecomm'){
                wrapper.append(title, table, footerDescription);
            }else{
                wrapper = '';
            }



            const id = $('#transaction_id').val();
    
            Swal.fire({
                title: `Are you sure?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true,
                returnFocus: false,
                html: wrapper,
                allowOutsideClick: false,
                width: '550',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnSubmit').click();
                }
            });
        })
    }
    
</script>

@endsection