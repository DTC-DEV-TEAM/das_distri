<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@section('content')

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
        <div id="requestform" class='panel-body'>
            <div>   
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

            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.receive') }}</button>
        </div>
    </form>
</div>

@endsection