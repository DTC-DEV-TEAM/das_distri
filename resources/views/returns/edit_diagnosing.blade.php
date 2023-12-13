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
        <form method='post' id="myform" action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden"  name="diagnose" id="diagnose">
            <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $row->id }}">
                <div id="requestform" class='panel-body'>
                    <div>   
                    
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
                            
                            <hr/>
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

                            <!-- 2r -->
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
                                
                                <!--
                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_payment') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_payment}}</p>
                                </div> -->
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
                               
               
                            </div>
                            -->
                            <div class="row">  

                                <div class="col-md-6">

                                </div>
                                        
                                    <label class="control-label col-md-2">{{ trans('message.form-label.verified_items_included') }}</label>
                                    <div class="col-md-3">

                                       @if ($row->returns_status_1 == '38')
                                            @if($row->verified_items_included_others  != null)
                                            <p>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                            @else
                                                <p>{{$row->verified_items_included}}</p>
                                            @endif
                                        @else
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

                                    
                                        @endif


                                     
                            
                                    </div>
                            </div>
                            <hr/>

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

                            @if($row->transaction_type == 3)
                                                <hr/>
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
                                            
                                                    <!-- @if($row->mode_of_return == "STORE DROP-OFF") -->
                                                                <hr/>
                                                                
                                                                        <hr/>
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
                        
                                                           <!-- @else -->
                                            
                                                    <!-- @endif      -->                                      
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
                            
                        
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                                <div class="col-md-10">
                                    <p>{{$row->comments}}</p>
                                </div>
                            </div>

                            
                            @if (CRUDBooster::myPrivilegeName() == 'Service Center')
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_item_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_at_rma_sc}}</p>
                                </div>
                            </div>
                            @else
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->turnover_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->rma_receiver_date_received}}</p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">Turnover By:</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_item_by}}</p>
                                </div>
                                <label class="control-label col-md-2">Turnover Date:</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_at_rma_sc}}</p>
                                </div>
                            </div>
                            @endif
                         
                            @if ($row->returns_status_1 == '38')
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">Diagnosed By:</label>
                                <div class="col-md-4">
                                    <p>{{$row->diagnosed_by}}</p>
                                </div>
                                <label class="control-label col-md-2">Diagnosed Date:</label>
                                <div class="col-md-4">
                                    <p>{{$row->level3_personnel_edited}}</p>
                                </div>
                            </div>
                            @endif

                            {{-- <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->turnover_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->rma_receiver_date_received}}</p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">Turnover By:</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_item_by}}</p>
                                </div>
                                <label class="control-label col-md-2">Turnover Date:</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_at_rma_sc}}</p>
                                </div>
                            </div>
                            @if ($row->returns_status_1 == '38')
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">Diagnosed By:</label>
                                <div class="col-md-4">
                                    <p>{{$row->diagnose_by_technician}}</p>
                                </div>
                                <label class="control-label col-md-2">Diagnosed Date:</label>
                                <div class="col-md-4">
                                    <p>{{$row->rma_specialist_date_received}}</p>
                                </div>
                            </div>
                            @endif --}}
                            {{-- <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_by_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_item_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.received_at_rma_sc') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->received_at_rma_sc}}</p>
                                </div>
                            </div> --}}

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
                                        let inc_reference_number = "{{ route('fwc_custom_reference_number', ['#ref_num','#mainpath']) }}";
                                        inc_reference_number = inc_reference_number.replace('#ref_num', res.success);
                                        inc_reference_number = inc_reference_number.replace('#mainpath', moduleMainpath);
                                        // console.log(inc_reference_number);
                                        location.assign(inc_reference_number);
                                    }
                                });
                            }
                        },
                        error: function(err){

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