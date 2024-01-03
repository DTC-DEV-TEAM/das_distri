<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">

<style>
    .table tbody tr td, .table thead tr th, .table{
        border: 1px solid #ddd;
    }
</style>

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
                    @include('components.chat-app', $comments_data)
                </div>
            </div>
        </div>
                <div id="requestform" class='panel-body'>
                    <div> 
                            <!-- 1r -->
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
                            <!-- 2r -->
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
                            <!-- 3r -->
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
                            <!-- 4r -->
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
                            <!-- 5r -->
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
                            <!-- 6r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_date') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_date}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_payment') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_payment}}</p>
                                </div>
                            </div>                           
                            <!-- 7r -->
                            <!--
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_name}}</p>
                                </div>
    
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_account_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_account_no}}</p>
                                </div>
                            </div> -->
                            <!-- 8r -->
                            <!--
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_account_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_account_name}}</p>
                                </div>
                            -->
                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->items_included_others  != null)
                                            <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                        @else
                                            <p>{{$row->items_included}}</p>
                                    @endif
                                   
                                </div>

                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                            </div>

                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.verified_items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->verified_items_included_others  != null)
                                            <p>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                        @else
                                            <p>{{$row->verified_items_included}}</p>
                                    @endif
                                   
                                </div>

                            </div>
                            <div class="row">                           
                                <label class="control-label col-md-2">Transaction Type:</label>
                                <div class="col-md-4">
                                    <p>{{$row->transaction_type_name}}</p>
                                </div>
                             
                            </div>
                            
                            @if($row->scheduled_by != null || $row->scheduled_by != "")  
                                <hr/>
    
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->scheduled_by}}</p>
                                    </div>
                                    <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_at') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->level1_personnel_edited}}</p>
                                    </div>
                                </div>
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.return_schedule1') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->return_schedule}}</p>
                                    </div>
                                </div>
                            @endif
                            <!--
                            <div class="row"> 
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                                
                                <label class="control-label col-md-2">{{ trans('message.form-label.sor_number') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->sor_number}}</p>
                                </div>
                            </div> -->
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

                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.diagnosed_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->diagnosed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.diagnosed_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level2_personnel_edited}}</p>
                                </div>
                            </div>

                            <div class="row"> 
                               
                                <label class="control-label col-md-2">{{ trans('message.table.comments2') }}</label>
                                    <div class="col-md-10">
                                        <p>{{$row->diagnose_comments}}</p>
                                    </div>
                            </div>
                            @if($row->diagnose != "REPLACE")
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
                            @if($row->diagnose == "REFUND")
                                    <hr/>
                                    <div class="row">                           
                                        <label class="control-label col-md-2">{{ trans('message.form-label.transacted_by') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->transacted_by}}</p>
                                        </div>
                                        <label class="control-label col-md-2">{{ trans('message.form-label.transacted_at') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->level4_personnel_edited}}</p>
                                        </div>
                                    </div>
                                    <div class="row">                              
                                        <label class="control-label col-md-2">{{ trans('message.form-label.sor_number') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->sor_number}}</p>
                                        </div>
                                    </div>
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
                            @endif
                            @if($row->diagnose == "REFUND")
                                <hr/>
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->closed_by}}</p>
                                    </div>
                                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_at') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->level5_personnel_edited}}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="control-label col-md-2">{{ trans('message.form-label.refunded_date') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->refunded_date}}</p>
                                    </div>
                                </div>                                
                            @elseif($row->diagnose == "REPLACE")    
                                @if($row->transaction_type ==  3)
                                        <hr/>
                                        
                                    
                                        <div class="row">                           
                                            <label class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->transacted_by}}</p>
                                            </div>
                                            <label class="control-label col-md-2">{{ trans('message.form-label.closed_at') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->level4_personnel_edited}}</p>
                                            </div>
                                        </div>
                                        

                                    @else
                                        <hr/>
                                        <div class="row">                           
                                            <label class="control-label col-md-2">{{ trans('message.form-label.checked_by') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->transacted_by}}</p>
                                            </div>
                                            <label class="control-label col-md-2">{{ trans('message.form-label.checked_date') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->level4_personnel_edited}}</p>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row">                           
                                            <label class="control-label col-md-2">{{ trans('message.form-label.negative_positive_invoice') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->negative_positive_invoice}}</p>
                                            </div>
                                            <label class="control-label col-md-2">{{ trans('message.form-label.pos_replacement_ref') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->pos_replacement_ref}}</p>
                                            </div>
                                        </div>
                                @endif

                            @else
                                <hr/>
                                <div class="row">                           
                                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->transacted_by}}</p>
                                    </div>
                                    <label class="control-label col-md-2">{{ trans('message.form-label.closed_at') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->level4_personnel_edited}}</p>
                                    </div>
                                </div>
                            @endif

                    
                            @if($row->date_adjusted != null || $row->date_adjusted != "")  
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.closed_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->closed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.closed_at') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->level5_personnel_edited}}</p>
                                </div>
                            </div>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.date_adjusted') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->date_adjusted}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.stock_adj_ref_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->stock_adj_ref_no}}</p>
                                </div>
                            </div>
                            @endif
                            
                            
                            
                    </div>
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
</script>
@endpush 