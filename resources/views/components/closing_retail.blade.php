<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@section('content')

@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

<style>
	#swal_table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 20px;
	}

	#swal_table th,
	#swal_table td {
		border: 1px solid #ddd;
		padding: 8px;
		text-align: center;
	}

	@media (max-width: 767px) {
		#swal_table {
			overflow-x: auto;
			white-space: nowrap;
		}

		#swal_table th,
		#swal_table td {
			white-space: nowrap;
		}
	}

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
            <input type="hidden"  name="closing" id="closing">
                <div id="requestform" class='panel-body'>
                    <div> 
                            @if($row->received_by != null && $row->diagnose == "REPLACE")

                                
                                <div class="row" style="background-color: #3C8DBC;">                           
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 25px;">{{ trans('message.table.comments3') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 25px; color: white; margin-top: 5px;">{{$row->notes}}</p>
                                    </div>
                                    
                                </div>

                                
                                <div class="row" style="background-color: #3C8DBC;">                           
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 20px;">{{ trans('message.form-label.checked_by') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 20px; color: white; margin-top: 5px;">{{$row->received_by}}</p>
                                    </div>
                                    <label class="control-label col-md-2" style="margin-top: 5px; color: white; font-size: 20px;">{{ trans('message.form-label.checked_date') }}</label>
                                    <div class="col-md-4">
                                        <p style="font-size: 20px; color: white; margin-top: 5px;">{{$row->level6_personnel_edited}}</p>
                                    </div>
                                </div>
                                <hr/>
                            @endif
                            <!--
                            <div class="row" style="background-color: #3C8DBC; height:50px;"> 
                                <div class="col-md-6" style="margin-top: 10px; color: white; font-size: 20px;">
                                        
                                        <span >Please Upload Return Form here: </span><a style="margin-top: 2px; color: white;" href="https://drive.google.com/drive/folders/174H74xguMR9rwig12YBIx7jSkXxSEQk9?usp=sharing" target="_blank">&nbsp;<i class="fa fa-cloud-upload fa-lg" ></i></a>
                                   
                                </div>
                            
                            </div> 
                              
                            <hr/>-->
                            
                            @if($row->diagnose == "REPLACE")
                            <div class="row" id="replacement">        
                                <div class="col-md-2" style="min-height: 35px; max-height: 35px; display: flex; align-items: center;">
                                        <label class="control-label">{{ trans('message.form-label.negative_positive_invoice') }}</label>
                                    </div>                         
                                    <div class="col-md-4">
                                        <input type='input' name='negative_positive_invoice' id="negative_positive_invoice" class='form-control' autocomplete="off" maxlength="50" placeholder="INV#" onkeypress="return AvoidSpace(event)" />                             
                                    </div>
                                </div> 
                                <br>
                                <div class="row" id="replacement1">        
                                    <div class="col-md-2" style="min-height: 35px; display: flex; align-items: center;">
                                        <label class="control-label">{{ trans('message.form-label.pos_replacement_ref') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type='input' name='pos_replacement_ref' id="pos_replacement_ref" class='form-control' autocomplete="off" maxlength="50"  onkeypress="return AvoidSpace(event)"  placeholder="REP#" />                             
                                    </div>
                                    
                                    {{-- <div class="col-md-6">
                                        <br>
                                        <p style="color:red;"><label style="color:black;">Notes:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*PLEASE CREATE POS REPLACEMENT STOCK ADJUSTMENT TRANSACTION. </p>
                                    </div> --}}
                                    <div class="col-md-6" style="min-height: 35px; display: flex; align-items: center;">
                                        <label class="control-label">Notes: <span style="color: red;">*PLEASE CREATE POS REPLACEMENT STOCK ADJUSTMENT TRANSACTION.</span> </label>
                                    </div>
                                </div>
                                
                                <hr/>
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

                            

                            @if($row->scheduled_by != null  || $row->scheduled_by != "")
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
                                        <td>{{$row->level2_personnel_edited}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.table.comments2') }}</td>
                                        <td>{{$row->diagnose_comments}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            @if($row->diagnose == "REPLACE")
                                <hr/>
                                
                                <table class="custom_normal_table">
                                    <tbody>
                                        <tr>
                                            <td>{{ trans('message.form-label.transacted_by') }}</td>
                                            <td>{{$row->transacted_by}}</td>
                                            <td>{{ trans('message.form-label.transacted_at') }}</td>
                                            <td>{{$row->level3_personnel_edited}}</td>
                                        </tr>
                                        @if($row->sor_number != null || $row->sor_number != "")
                                        <tr>
                                            <td>{{ trans('message.form-label.sor_number') }}</td>
                                            <td>{{$row->sor_number}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @endif 


                            @if($row->returns_status_1 != 20)
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.printed_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->printed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.printed_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level3_personnel_edited}}</p>
                                </div>
                            </div>
                            @endif
                            <!--
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level6_personnel_edited}}</p>
                                </div>
                            </div>
                            -->

                    </div>
                </div>
                <div class='panel-footer'>
                    <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                    
                    <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.close') }}</button>
                    <button class="btn btn-primary pull-right f-btn" type="button"><i class="fa fa-save" ></i> {{ trans('message.form.close') }}</button>
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

    function validationNpiPos(){

        let validation = false;

        let invStartsWith = false;
        let repStartsWith = false;
        let invNumberLength = false;
        let repNumberLength = false;

        const invNumber = $('#negative_positive_invoice').val();
        const repNumber = $('#pos_replacement_ref').val();
        const borderColor = {border: '2px solid #FE4A49'};
        const removeBorderColor = {border: ''};

        invStartsWith = invNumber.startsWith('INV#') ? true : false;
        invNumberLength = invNumber.length >= 8 ? true : false;

        repStartsWith = repNumber.startsWith('REP#') ? true : false;
        repNumberLength = repNumber.length >= 8 ? true : false;

        if ((!invStartsWith && !repStartsWith) || (!invNumberLength && !repNumberLength)){
            $('#negative_positive_invoice').css(borderColor);
            $('#pos_replacement_ref').css(borderColor);
        }else{
            $('#pos_replacement_ref').css('border', '');
            $('#negative_positive_invoice').css('border', '');
        }

        if(!invStartsWith){
            Swal.fire({
                title: "Incorrect Negative/Positive Invoice format! e.g. INV#1001",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#negative_positive_invoice').css(borderColor);
                    $('#negative_positive_invoice').focus();
                }
            })
        }else if(!invNumberLength){
            Swal.fire({
                title: "Please make sure the input is at least 8 characters long in INV#",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#negative_positive_invoice').css(borderColor);
                    $('#negative_positive_invoice').focus();
                }
            })
        }else if(!repStartsWith){
            Swal.fire({
                title: "Incorrect POS Replacement Ref# format! e.g. REP#1001",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#pos_replacement_ref').css(borderColor);
                    $('#pos_replacement_ref').focus();
                }
            })
        }else if(!repNumberLength){
            Swal.fire({
                title: "Please make sure the input is at least 8 characters long in REP#",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#pos_replacement_ref').css(borderColor);
                    $('#pos_replacement_ref').focus();
                }
            })
        }

        if (invStartsWith && repStartsWith && invNumberLength && repNumberLength) {
            validation = true;
        }else{
            validation = false;
        }

        return validation;
    }

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


    $("#check").on('click',function() {
        var strconfirm = confirm("Are you sure you want to check with SDM?");
            if (strconfirm == true) {
                $("#closing").val("Check with SDM");
                return true;
            }else{
                return false;
                window.stop();
            }
    });

	
    // Swal
    $(".f-btn").on('click', function(){

		const invNumber = $('#negative_positive_invoice').val();
        const repNumber = $('#pos_replacement_ref').val();
		
		const wrapper = $('<div>');
		const table = $('<table id="swal_table">');
		const thead = $('<thead>');
		const tbody = $('<tbody>');
		const tr = $('<tr>'); // Create a table row

		// Use th (table header) for the headers
		tr.append($('<th>').text('INV Number'), $('<th>').text('REP Number'));

		thead.append(tr);

		// Create another row for the data
		const dataRow = $('<tr style="color: #2C78C1; font-weight: 600;">').append($('<td>').text(invNumber), $('<td>').text(repNumber));
		tbody.append(dataRow);

		table.append(thead, tbody);

		let p = $('<p style="color: red;">');

		if (invNumber == '' || repNumber == ''){
			p.text('Please fill up required fields');
		} else {
			p.text('');
		}

		wrapper.append(table, p);

        Swal.fire({
            title: "Are you sure you want to close this transaction?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            // html: wrapper,
            reverseButtons: true,
            returnFocus: false,
            allowOutsideClick: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSubmit').click();
            }
        });
    })

    $('#btnSubmit').on('click', function(event){

        event.preventDefault();

        let validated = false;

        if("{{ CrudBooster::myPrivilegeName() == 'Super Administrator' }}"){
            validated = validationNpiPos();
        }else{
            validated = true;
        }

        if(validated){
            $("#closing").val("Close");
            $('#myform').submit();
        }
    })

    // $("#btnSubmit").on('click',function(event) {

    // });

    $(document).ready(function(){
        $("myform").submit(function(){
                $('#btnSubmit').attr('disabled', true);
        });
    });

</script>
@endpush 