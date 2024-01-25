<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@include('plugins.plugins')
<link rel="stylesheet" href="{{ asset('css/sweet_alert_size.css') }}">
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
                @include('components.distribution.chat-app', $comments_data)
            </div>
        </div>
    </div>
    <div id="requestform" class='panel-body'>
        <div>
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
                        <td>Diagnose</td>
                        <td>{{ $row->diagnose }}</td>
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
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif

                <br>
                <!--TABLE-->
                <!--<div class="table-responsive">
                    <div class="pic-container">
                        <div class="pic-row"> -->
                            <table class='table table-striped table-bordered table-font'>
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
                            <td>{{ trans('message.form-label.printed_by') }}</td>
                            <td>{{$row->printed_by}}</td>
                            <td>{{ trans('message.form-label.printed_at') }}</td>
                            <td>{{$row->level3_personnel_edited}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @if($row->diagnose == "REFUND")
                <hr/>
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>{{ trans('message.form-label.transacted_by') }}</td>
                            <td>{{$row->transacted_by}}</td>
                            <td>{{ trans('message.form-label.transacted_at') }}</td>
                            <td>{{$row->level4_personnel_edited}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('message.form-label.sor_number') }}</td>
                            <td>{{$row->sor_number}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @if($row->diagnose == "REFUND")
                <hr/>
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>{{ trans('message.form-label.closed_by') }}</td>
                            <td>{{$row->closed_by}}</td>
                            <td>{{ trans('message.form-label.closed_at') }}</td>
                            <td>{{$row->level5_personnel_edited}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('message.form-label.refunded_date') }}</td>
                            <td>{{$row->refunded_date}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @elseif($row->diagnose == "REPLACE")    
                @if($row->transaction_type ==  3)
                    <hr/>
                    <table class="custom_normal_table">
                        <tbody>
                            <tr>
                                <td>{{ trans('message.form-label.closed_by') }}</td>
                                <td>{{$row->transacted_by}}</td>
                                <td>{{ trans('message.form-label.closed_at') }}</td>
                                <td>{{$row->level4_personnel_edited}}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                        <hr/>
                        <table class="custom_normal_table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('message.form-label.checked_by') }}</td>
                                    <td>{{$row->transacted_by}}</td>
                                    <td>{{ trans('message.form-label.checked_date') }}</td>
                                    <td>{{$row->level4_personnel_edited}}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('message.form-label.negative_positive_invoice') }}</td>
                                    <td>{{$row->negative_positive_invoice}}</td>
                                    <td>{{ trans('message.form-label.pos_replacement_ref') }}</td>
                                    <td>{{$row->pos_replacement_ref}}</td>
                                </tr>
                            </tbody>
                        </table>
                @endif
            @else
                <hr/>
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>{{ trans('message.form-label.closed_by') }}</td>
                            <td>{{$row->transacted_by}}</td>
                            <td>{{ trans('message.form-label.closed_at') }}</td>
                            <td>{{$row->level4_personnel_edited}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if($row->date_adjusted != null || $row->date_adjusted != "")  
                <hr/>
                <table class="custom_normal_table">
                    <tbody>
                        <tr>
                            <td>{{ trans('message.form-label.closed_by') }}</td>
                            <td>{{$row->closed_by}}</td>
                            <td>{{ trans('message.form-label.closed_at') }}</td>
                            <td>{{$row->level5_personnel_edited}}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('message.form-label.date_adjusted') }}</td>
                            <td>{{$row->date_adjusted}}</td>
                            <td>{{ trans('message.form-label.stock_adj_ref_no') }}</td>
                            <td>{{$row->stock_adj_ref_no}}</td>
                        </tr>
                    </tbody>
                </table>
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