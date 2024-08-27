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
                    @include('components.ecomm.chat-app', $comments_data)
                </div>
            </div>
        </div>
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="diagnose" id="diagnose">
            <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $row->id }}">
                <div id="requestform" class='panel-body'>
                    <div>   
                        <br>
                            <div class="row"> 
                                <label class="require control-label col-md-2">{{ trans('message.form-label.warranty_status') }}</label>

                                @if ($row->returns_status_1 == "38") 
                                <div class="col-md-4">
                                    <p>{{$row->warranty_status}}</p>
                                </div>
                                @else
                                @foreach($warranty_status as $data)
                                <div class="col-md-2">
                                    <label class="radio-inline control-label col-md-12">
                                        <input type="radio" required
                                               {{ $data->warranty_name == $row->warranty_status ? 'checked' : '' }}
                                               name="warranty_status_val" value="{{ $data->warranty_name }}">
                                        {{ $data->warranty_name }}
                                    </label>
                                    <br>
                                </div>
                                @endforeach
                                
                                @endif
                                
                                @if (CRUDBooster::myPrivilegeName() != 'Service Center' && $row->transaction_type_id != 3)
                                    <label class="col-md-2" for="case_status">Case Status:</label>
                                    <select class="col-md-2 js-example-basic-single" id="case_status" name="case_status">
                                    @foreach ($case_status as $case_status_name)
                                        <option value="{{ $case_status_name }}" @if ($case_status_name == $row->case_status) selected @endif>
                                            {{ $case_status_name }}
                                        </option>
                                    @endforeach
                                    </select>
                                @endif
                            </div>
                            <hr>
                            <div class="row">                           
                                @if (CRUDBooster::myPrivilegeName() != 'Service Center')
                                <label class="control-label col-md-2">Transaction Type:</label>
                                <div class="col-md-4">
                                    <p>{{$row->transaction_type_name}}</p>
                                </div>
                                @if (CRUDBooster::myPrivilegeName() == 'Tech Lead')
                                <label class="control-label col-md-2">Technician Assigned:</label>
                                <div class="col-md-4">
                                    <p>{{$row->technician_assigned}}</p>
                                </div>
                                @endif
                                @endif
                            </div>
                            <br>
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
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <br>

                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.items_included') }}</td>
                                        @if($row->mode_of_return =="STORE DROP-OFF")
                                
                                            @if($row->transaction_type == 3)
                                            
                                                    @if($row->items_included_others  != null)
                                                            <td>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                                        @else
                                                            <td>{{$row->items_included}}</p>
                                                    @endif
                                            
                                            
                                                @else
                                                
                                                    @if($row->verified_items_included_others  != null)
                                                            <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
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
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('message.form-label.verified_items_included') }}</td>

                                        @if ($row->returns_status_1 == '38')
                                            @if($row->verified_items_included_others  != null)
                                                <td>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</td>
                                            @else
                                                <td>{{$row->verified_items_included}}</td>
                                            @endif
                                        @else
                                        <td>
                                            <select   class="js-example-basic-multiple" required name="verified_items_included[]" id="verified_items_included" multiple="multiple" style="width:100%;">
                                                
                                                @if($row->mode_of_return =="STORE DROP-OFF")
                                                            
                                                            
                                                            @if($row->transaction_type == 3)
                                                                        @foreach($items_included_list as $key=>$list)
                                                                            @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                                                    <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                                                @else
                                                                                    <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                                            @endif
                                                                
                                                                        @endforeach                                                        
                                                                    @else
                                                                        @foreach($items_included_list as $key=>$list)
                                                                            @if(strpos($row->verified_items_included, $list->items_description_included) !== false)
                                                                                    <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                                                @else
                                                                                    <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                                            @endif
                                                                
                                                                        @endforeach
                                                            @endif

                                                        @else
                                                                @foreach($items_included_list as $key=>$list)
                                                                    @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                                            <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                                        @else
                                                                            <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                                    @endif
                                                        
                                                                @endforeach
                                                @endif
                                            </select>
                                                <?php $other_items_included = $row->items_included_others;?>
                                                <br><br>
                                                <input type='input'  name='verified_items_included_others' id="verified_items_included_others" autocomplete="off" class='form-control' value="{{$row->items_included_others}}"/> 
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

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
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

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
                                <hr/>
                            @else
                            <!-- @if($row->mode_of_return == "STORE DROP-OFF") -->
                                    <hr/>
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
                                            <tr>
                                                <td>{{ trans('message.form-label.scheduled_by') }}</td>
                                                <td>{{$row->scheduled_by_logistics}}</td>
                                                <td>{{ trans('message.form-label.pickup_schedule') }}</td>
                                                <td>{{$row->pickup_schedule}}</td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <!-- @else -->
                    
                            <!-- @endif     = -->                                      
                            @endif                
                          
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
                                                    @if ($row->returns_status_1 == '38')
                                                    <td style="text-align:center" height="10">{{$rowresult->problem_details}}, <br><br><span style="font-weight: bold;"> {{ $rowresult->problem_details_other }} </span></td>
                                                    @else
                                                    <td style="text-align:center" height="10">
                                            
                                                        <select   class="js-example-basic-multiple" required name="problem_details[]" id="problem_details" multiple="multiple" style="width:100%;">
                                                            @foreach($problem_details_list as $key=>$list)
                                                                    @if(strpos($rowresult->problem_details, $list->problem_details) !== false)
                                                                            <option selected value="{{$list->problem_details}}" >{{$list->problem_details}}</option>
                                                                        @else
                                                                            <option  value="{{$list->problem_details}}">{{$list->problem_details}}</option>
                                                                    @endif
                                                                    
                                                            @endforeach
                                      
                                                        </select>
                                                            <?php $other_problem_details = $rowresult->problem_details_other;?>
                                                            <br><br>
                                                            <input type='input'  name='problem_details_other' id="problem_details_other" autocomplete="off" class='form-control' value="{{$rowresult->problem_details_other}}"/> 
                                                            
                                                    </td>
                                                          @endif
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
                            
                            @if (CRUDBooster::myPrivilegeName() == 'Service Center')
                            <hr/>
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.received_by_rma_sc') }}</td>
                                        <td>{{$row->received_item_by}}</td>
                                        <td>{{ trans('message.form-label.received_at_rma_sc') }}</td>
                                        <td>{{$row->received_at_rma_sc}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @else
                            <hr/>
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('message.form-label.received_by_rma_sc') }}</td>
                                        <td>{{$row->turnover_by}}</td>
                                        <td>{{ trans('message.form-label.received_at_rma_sc') }}</td>
                                        <td>{{$row->rma_receiver_date_received}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>Turnover By:</td>
                                        <td>{{$row->received_item_by}}</td>
                                        <td>Turnover Date:</td>
                                        <td>{{$row->received_at_rma_sc}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @endif

                            @if ($row->returns_status_1 == '38')
                            <hr/>
                            <table class="custom_normal_table">
                                <tbody>
                                    <tr>
                                        <td>Diagnosed By:</td>
                                        <td>{{$row->diagnosed_by}}</td>
                                        <td>Diagnosed Date:</td>
                                        <td>{{$row->level3_personnel_edited}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif

                            <hr/>
                            <div class="row"> 
                            
                                <label class="control-label col-md-2">{{ trans('message.table.comments2') }}</label>
                                <div class="col-md-10">
                                    <textarea {{ $row->returns_status_1 == '38' ? 'disabled' : '' }} placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="diagnose_comments" id="diagnose_comments">{{ $row->diagnose_comments }}</textarea>
                                    </div>
                            </div>
                    </div>
                </div>
            <div class='panel-footer'>
                
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                
                @if($row->returns_status_1 == '5')
                @if (CRUDBooster::myPrivilegeName() == 'Service Center')
                    @if ($row->transaction_type_id == 3)
                        <button class="btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                        <button class="btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                        <button class="btn btn-success pull-right"  type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                        <button class="btn btn-success pull-right"  type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                    @else
                        <button class="btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>  
                        <button class="btn btn-success pull-right" type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                        <button class="btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                        <button class="btn btn-success pull-right"  type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                    @endif
                @else
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitDone" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Test Done</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitSave" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Save</button>
                @endif
            @else
                @if ($row->transaction_type_id == 3)
                    <button class="btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                    <button class="btn btn-success pull-right"  type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                    <button class="btn btn-success pull-right"  type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                @else
                    <button class="bottom-btn btn btn-danger  pull-right btn-sbmt disabled" type="submit" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                    <button class="bottom-btn btn btn-success pull-right btn-sbmt disabled" type="submit" style="margin-right:10px; width:100px;" > <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                    <button class="bottom-btn btn btn-success pull-right btn-sbmt disabled"  type="submit" style="margin-right:10px; width:100px;" > <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                    <button class="bottom-btn btn btn-success pull-right btn-sbmt disabled"  type="submit" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                    <button class="bottom-btn btn btn-success pull-right disabled" type="submit" id="btnSSR" style="margin-right:10px; width:160px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Service Center Repair</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitSave" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Save</button>
                @endif
            @endif
                {{-- @if($row->transaction_type_id == 3 && CRUDBooster::myPrivilegeName() != 'RMA Technician')
                    <button class="btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                    <button class="btn btn-success pull-right"  type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;" disabled> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                    <button class="btn btn-success pull-right"  type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                @elseif (CRUDBooster::myPrivilegeName() == 'RMA Technician')
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitDone" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Test Done</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitSave" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Save</button>
                @elseif ($row->case_status !== 'Closed'  && CRUDBooster::myPrivilegeName() != 'Service Center')
                    <button class="bottom-btn btn btn-danger pull-right disabled" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                    <button class="bottom-btn btn btn-success pull-right disabled" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;" > <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                    <button class="bottom-btn btn btn-success pull-right disabled"  type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;" > <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                    <button class="bottom-btn btn btn-success pull-right disabled"  type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                    
                    @if ($row->case_status != 'Pending Supplier')
                        <button class="bottom-btn btn btn-success pull-right disabled" type="submit" id="btnSSR" style="margin-right:10px; width:160px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Service Center Repair</button>
                    @else
                        <button class="bottom-btn btn btn-success pull-right" type="submit" id="btnSSR" style="margin-right:10px; width:160px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Service Center Repair</button>
                    @endif
                        @if (CRUDBooster::myPrivilegeName() != 'RMA Specialist')
                        <button class="btn btn-success pull-right" type="submit" id="btnSubmitDone" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Test Done</button>
                        @endif
                        <button class="btn btn-success pull-right" type="submit" id="btnSubmitSave" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Save</button>
                @else
                    <button class="bottom-btn btn btn-danger pull-right" type="submit" id="btnSubmitRefund" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.refund') }}</button>
                    <button class="bottom-btn btn btn-success pull-right" type="submit" id="btnSubmitReject" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.reject') }}</button>
                    <button class="bottom-btn btn btn-success pull-right" type="submit" id="btnSubmitRepair" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.repair') }}</button>
                    <button class="bottom-btn btn btn-success pull-right" type="submit" id="btnSubmitReplace" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.replace') }}</button>
                    

                    @if (CRUDBooster::myPrivilegeName() != 'Service Center')
                    <button class="bottom-btn btn btn-success pull-right" type="submit" id="btnSSR" style="margin-right:10px; width:160px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Service Center Repair</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitDone" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Test Done</button>
                    <button class="btn btn-success pull-right" type="submit" id="btnSubmitSave" style="margin-right:10px; width:100px;"> <i class="fa fa-circle-o" style="margin-right:4px;" ></i>Save</button>
                    
                    
                    @endif

                @endif --}}
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

    $('.js-example-basic-multiple').select2();
    $(".js-example-basic-multiple").select2({
        theme: "classic"
    });

    $(document).ready(function() {

        $('.js-example-basic-single').select2();

        if ($('#case_status').val() == 'Closed'){
            $('.btn-sbmt').removeClass('disabled');
            $('#btnSSR').removeClass('disabled');
        }else if ($('#case_status').val() == 'Pending Supplier'){
            $('#btnSSR').removeClass('disabled');
        }else{
            $('.btn-sbmt').addClass('disabled');
            $('#btnSSR').addClass('disabled');
        }

        $('#case_status').on('change', function(){
            const value = $(this).val();
            if (value === 'Closed') {
                $('.btn-sbmt').removeClass('disabled');
                $('#btnSSR').removeClass('disabled');
            }else if (value === 'Pending Supplier') {
                $('.btn-sbmt').addClass('disabled', true);
                $('#btnSSR').removeClass('disabled', false);
            }else {
                $('.btn-sbmt').addClass('disabled', true);
                $('#btnSSR').addClass('disabled', true);
            }
        });
    });

    var verified_others_field = <?php echo json_encode($other_items_included); ?>;

    var problem_others_field = <?php echo json_encode($other_problem_details); ?>;


    if(problem_others_field == null || problem_others_field == ""){
        $('#problem_details_other').val("");
        $('#problem_details_other').hide();  
        $('#problem_details_other').attr("required", false);
    }


    if(verified_others_field == null || verified_others_field == ""){
        $('#verified_items_included_others').val("");
        $('#verified_items_included_others').hide();  
        $('#verified_items_included_others').attr("required", false);
    }

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

    $('#verified_items_included').change(function(){
        if($('#verified_items_included').val() != null){
            var items = $(this).val();
        }else{
            var items = "";
        }
        if(items.includes("OTHERS")) {
            $('#verified_items_included_others').show();
            $('#verified_items_included_others').attr("required", true);
        }else{
            $('#verified_items_included_others').val("");
            $('#verified_items_included_others').hide();  
            $('#verified_items_included_others').attr("required", false);
        }
    });

    $('#problem_details').change(function(){
        if($('#problem_details').val() != null){
            var items = $(this).val();
        }else{
            var items = "";
        }
        if(items.includes("OTHERS")) {
            $('#problem_details_other').show();
            $('#problem_details_other').attr("required", true);
        }else{
            $('#problem_details_other').val("");
            $('#problem_details_other').hide();  
            $('#problem_details_other').attr("required", false);
        }
    });


    $("#btnSubmitSave").on('click',function() {
        $("#diagnose").val("Save");
    });

    $("#btnSubmitDone").on('click',function() {
        var strconfirm = confirm("Are you sure you want to Test Done this return request?");
        if (strconfirm == true) {
            $("#diagnose").val("Test Done");
            if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
                alert("Please put a comment!");
                return false;
                window.stop();
            }
        }else{
            return false;
            window.stop();
        }
    });

    $("#btnSSR").on('click', function(){
        const isDisabled = $(this).hasClass('disabled');
        if(isDisabled){
            alert('Case Status shoud be Pending Supplier')
            event.preventDefault();
            return;
        }
        $("#diagnose").val("PrintSSR");
    })

    // Custom BTN
    $('.btn-sbmt').on('click', function(event){
        
        let clickedText = $(this).text().trim();
        let clickedDescription = clickedText == 'Refund' ? `<span style="color: red;">${clickedText}</span>` : `<span style="color: green;">${clickedText}</span>`;
        let btnDisabled = $(this).hasClass('disabled');
        let diagnose = clickedText == 'Service Center Repair' ? 'PrintSSR' : clickedText;

        const id = $('#transaction_id').val();
        const moduleMainpath = "{{ Request::segment(2) }}";
        const caseStatus = $('#case_status').val();

        let tableName = "{{ Request::segment(2) }}";

        if (tableName == 'returns_diagnosing'){
            tableName = 'returns_header';
        }
        else if (tableName == 'retail_return_diagnosing'){
            tableName = 'returns_header_retail';
        }
        else if(tableName == 'distri_return_diagnosing'){
            tableName = 'returns_header_distribution';
        }

        event.preventDefault();
        
        if (btnDisabled){
            Swal.fire({
                title: `Case Status should be "<span style='color: orange;'>CLOSED</span>"`,
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                allowOutsideClick: true,
            });
        }
        else{
            Swal.fire({
                title: `Are you sure you want to ${clickedDescription} this return request?`,
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
                    $('.sk-chase-position').show();
                    $("#diagnose").val(diagnose);

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('for_warranty_claim') }}',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            table_name: tableName,
                            diagnose: diagnose,
                            case_status: caseStatus
                        },
                        success: function(res){
                            if(res.success){
                                Swal.fire({
                                    title: "RMA Number",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok',
                                    returnFocus: false,
                                    html: res.success,
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('.sk-chase-position').hide();
                                        let inc_reference_number = "{{ route('fwc_custom_reference_number', ['#id','#diagnose','#ref_num','#mainpath']) }}";
                                        inc_reference_number = inc_reference_number.replace('#id', id);
                                        inc_reference_number = inc_reference_number.replace('#diagnose', diagnose);
                                        inc_reference_number = inc_reference_number.replace('#ref_num', res.success);
                                        inc_reference_number = inc_reference_number.replace('#mainpath', moduleMainpath);
                                        // console.log(inc_reference_number);
                                        location.assign(inc_reference_number);
                                    }
                                });
                            }
                        },
                        error: function(err){
                            $('.sk-chase-position').hide();
                            console.log(err)
                        }
                    });
                }
            });
        }
    });

    $("#btnSubmitRepair").on('click',function() {
        const isDisabled = $(this).hasClass('disabled');
        if (isDisabled) {
            alert('Case Status should be Closed');
            event.preventDefault();
            return;
        }
        var strconfirm = confirm("Are you sure you want to Repair this return request?");
            if (strconfirm == true) {
                $("#diagnose").val("Repair");
                if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
                    alert("Please put a comment!");
                    return false;
                    window.stop();
                }
            }else{
                return false;
                window.stop();
            }
    });

    $("#btnSubmitReject").on('click',function() {
        const isDisabled = $(this).hasClass('disabled');
        if (isDisabled) {
            alert('Case Status should be Closed');
            event.preventDefault();
            return;
        }
        var strconfirm = confirm("Are you sure you want to Reject this return request?");
            if (strconfirm == true) {
                $("#diagnose").val("Reject");
                if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
                    alert("Please put a comment!");
                    return false;
                    window.stop();
                }
            }else{
                return false;
                window.stop();
            }
    });

    $("#btnSubmitRefund").on('click',function() {
        const isDisabled = $(this).hasClass('disabled');
        if (isDisabled) {
            alert('Case Status should be Closed');
            event.preventDefault();
            return;
        }
        var strconfirm = confirm("Are you sure you want to Refund this return request?");
            if (strconfirm == true) {
                $("#diagnose").val("Refund");
                if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
                    alert("Please put a comment!");
                    return false;
                    window.stop();
                }
            }else{
                return false;
                window.stop();
            }
    });

    $("#btnSubmitReplace").on('click',function() {
        const isDisabled = $(this).hasClass('disabled');
        if (isDisabled) {
            alert('Case Status should be Closed');
            event.preventDefault();
            return;
        }
        var strconfirm = confirm("Are you sure you want to Replace this return request?");
            if (strconfirm == true) {
                $("#diagnose").val("Replace");
                if($("#diagnose_comments").val() == "" || $("#diagnose_comments").val() == null){
                    alert("Please put a comment!");
                    return false;
                    window.stop();
                }
            }else{
                return false;
                window.stop();
            }
    });

</script>
@endpush 