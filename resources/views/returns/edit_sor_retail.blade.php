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
                        <?php   $transaction  == $row->transaction_type; ?>
                        <?php   $deliver  == $row->deliver_to; ?>
        
                        @if ($row->transaction_type == 0 )
                        <div class="row"> 
                            <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.sor_number') }}</label>
                            <div class="col-md-4">
                                <input type='input' name='sor_number' id="sor_number" class='form-control' autocomplete="off" maxlength="50" placeholder="SOR#" onkeypress="return AvoidSpace(event)"  required/>                             
                            </div>
                        </div>
                        <hr/>
                        @endif
                            
                        @if ($row->mode_of_return == "STORE DROP-OFF" )
                            @if ($row->deliver_to != "WAREHOUSE.RMA.DEP" )
                            <div class="row"> 
                                    <label class="control-label col-md-2" style="margin-top:7px;">POS CRF#:</label>
                                    <div class="col-md-4">
                                        <input type='input' name='pos_crf_number' id="pos_crf_number" class='form-control' autocomplete="off" maxlength="50" placeholder="CRF#" onkeypress="return AvoidSpace(event)"  required/>                             
                                    </div>
                                </div>
                                <hr/>
                                
                            @endif
                        @endif
                            

                        <!-- 1r -->
                        <div class="row">                           
                            <label class="control-label col-md-2">{{ trans('message.form-label.return_reference_no') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->return_reference_no}}</p>
                            </div>

                            <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                            <div class="col-md-4">
                                <p>{{$row->customer_location}}</p>
                            </div>
                            
                        </div>

                        <br>
                            <!--TABLE-->
                            <!--<div class="table-responsive">
                                <div class="pic-container">
                                    <div class="pic-row"> -->
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
                                    <!--</div>
                                </div>
                            </div>-->         
                            
                            
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
                    @if ($row->transaction_type == 0 )
                            <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                        @else
                            <button class="btn btn-primary pull-right hide" type="submit" id="btnSubmit"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.proceed') }}</button>
                    @endif
                    <button class="btn btn-primary pull-right f-btn" type="button"><i class="fa {{ $row->transaction_type == 0 ? 'fa-save' : 'fa-circle-o'}}" ></i> {{ $row->transaction_type == 0 ? trans('message.form.save') : trans('message.form.proceed') }}</button>
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

    const transaction = parseInt('{{ $row->transaction_type }}');
    const deliver = "{{ $row->deliver_to }}";
    const modeOfReturn = "{{ $row->mode_of_return }}"
    
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

    $("#btnSubmit").on('click',function() {
        
            var signal = 0;
            var alert_message = 0;

            if(transaction == 0){
                
                var text_length = $("#sor_number").val().length;
                
                if($("#sor_number").val().includes("SOR#")){
                    
                    if($("#sor_number").val().includes(" ")){
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
                    alert_message = 1;
                }
                
            }
            
            if(modeOfReturn == "STORE DROP-OFF"){
                if(deliver != "WAREHOUSE.RMA.DEP"){
                
                    if($("#pos_crf_number").val().includes("CRF#")){
                            
                        if($("#pos_crf_number").val().includes(" ")){
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
                        // alert("Incorrect POS CRF# format! e.g. CRF#1001");
                        Swal.fire({
                            title: "Incorrect POS CRF# format! e.g. CRF#1001",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#pos_crf_number').css({border: '2px solid #FE4A49'});
                                $('#pos_crf_number').focus();
                            }
                        })
                        return false;  
                    }  
                }
            }

            if(signal != 0){
                return true;   
            }else{
                if(alert_message == 1){
                    // alert("Incorrect SOR# format! e.g. SOR#1001");
                    Swal.fire({
                        title: "Incorrect SOR# format! e.g. SOR#1001",
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        returnFocus: false,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#sor_number').css({border: '2px solid #FE4A49'});
                            $('#sor_number').focus();
                        }
                    })
                }
                return false;  
            }
            
    });

    $(document).ready(function(){
    $("myform").submit(function(){
            $('#btnSubmit').attr('disabled', true);
    });
    });

</script>
@endpush 