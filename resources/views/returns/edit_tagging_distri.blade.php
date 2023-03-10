<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
.pic-container {
  width: 1350px;
  margin: 0 auto;
  white-space: nowrap;
}
.pic-row {
  /* As wide as it needs to be */
  width: 1350px;
  
  overflow: auto;
}
.pic-row a {
  clear: left;
  display: block;
}

#deliver_to{
    width:100%; 
    height: 33.97px;
    border-radius: 5px;
    border-color: #d2d6de;
}
</style>
@endpush
@section('content')
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
            <input type="hidden"  name="remarks" id="remarks">
                <div id="requestform" class='panel-body'>
                    <div> 
                        <div class="row">                           
                            <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.customer_location') }}</label>
                            <div class="col-md-5">
                                <select class="js-example-basic-single" name="customer_location" id="customer_location" required style="width:100%">
                                                <option value="" disabled>-- Select Customer Location Name --</option>
                                        @foreach($store_list as $datas)    
                                                <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <br>                             
                        {{-- @if($Location == 1)  --}}
                        <div id="locations"> 
                            <div class="row">                           
                                <label class="control-label col-md-2"  style="margin-top:4px;" >Service Center Location:</label>
                                <div class="col-md-5">
                                    <select class="js-example-basic-single" name="deliver_to" id="deliver_to" required style="width:100%">
                                            <option value="" selected id="">-- Select Service Center Location --</option>
                                            @foreach($SCLocation as $datas)    

                                                <option  value="{{$datas->sc_location_name}}">{{$datas->sc_location_name}}</option>
                                                    
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                        </div>
                        <input id="rmawh" type="hidden" name="deliver_to" value="WAREHOUSE.RMA.DEP">
                        <div class="row"> 
                            <label class="require control-label col-md-2">{{ trans('message.form-label.via') }}</label>

                            @foreach($via as $data)


                                @if($data->id == 1)
                                            <div class="col-md-5">
                                                <label class="radio-inline control-label col-md-5" ><input type="radio" required checked  class="via_class" name="via_id" value="{{$data->id}}" >{{$data->via_name}}</label>
                                                <br>
                                            </div>
                                    @else
                                            <div class="col-md-5">
                                                <label class="radio-inline control-label col-md-5"><input type="radio" required  class="via_class" name="via_id" value="{{$data->id}}" >{{$data->via_name}}</label>
                                                <br>
                                            </div>
                                @endif

                             @endforeach
                        </div>
                        <br>      

                        <div id="carried"> 

                            <div class="row"> 

                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.carried_by') }}</label>
                                <div class="col-md-4">
                                    <input type='input' name='carried_by' id="carried_by" class='form-control' autocomplete="off"  placeholder="Hand Carried By"  required/>                             
                                </div>


                            </div>

                        <br/>

                        </div>

                            <div class="row"> 
                                <label class="require control-label col-md-2">{{ trans('message.form-label.warranty_status') }}</label>

                                @foreach($warranty_status as $data)

                                    @if($data->warranty_name =="IN WARRANTY")
                                                <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-5" ><input type="radio" required checked    name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                </div>
                                        @else
                                                <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-5"><input type="radio" required  name="warranty_status_val" value="{{$data->warranty_name}}" >{{$data->warranty_name}}</label>
                                                    <br>
                                                </div>
                                    @endif

                                 @endforeach
                            </div>
                            <br>
                            <div class="row"> 
                                <label class="require control-label col-md-2">{{ trans('message.form-label.transaction_type_id') }}</label>
                                
                                           <!-- <select class="js-example-basic-single" name="transaction_type_id" id="transaction_type_id" required style="width:100%">
                                                           
                                                    @foreach($transaction_type as $datas)    

                                                        @if($datas->id == 2)
                                                                <option  value="{{$datas->id}}" selected >{{$datas->transaction_type_name}}</option>
                                                            @else
                                                                <option  value="{{$datas->id}}">{{$datas->transaction_type_name}}</option>
                                                        @endif
        
                                                            
                                                            
                                                    @endforeach
                                            </select> -->
                                            
                                @foreach($transaction_type as $data)

                                    @if($data->id == 5)
                                                <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-12" ><input type="radio" class="transactionradio"  required checked  name="transaction_type_id" id="transaction_type_id" value="{{$data->id}}" >{{$data->transaction_type_name}}</label>
                                                    <br>
                                                </div>
                                        @else
                                                <div class="col-md-5">
                                                    <label class="radio-inline control-label col-md-12"><input type="radio"  class="transactionradio"  required  name="transaction_type_id" id="transaction_type_id" value="{{$data->id}}" >{{$data->transaction_type_name}}</label>
                                                    <br>
                                                </div>
                                    @endif

                                 @endforeach
                                    
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
                            <div class="row">                           
                                <label class="control-label col-md-2" style="margin-top:4px;">{{ trans('message.form-label.purchase_location') }}</label>
                                <div class="col-md-4">

                                    <!-- <p>{{$row->purchase_location}}</p> -->

                                    <select class="js-example-basic-single" name="purchase_location" id="purchase_location"  required style="width:100%">
                                     
                                            @foreach($purchaselocation as $datas)    
                                                @if( $datas->channel_name == $row->purchase_location)
                                                            <option selected value="{{$datas->channel_name}}">{{$datas->channel_name}}</option>
                                                    @else
                                                            <option  value="{{$datas->channel_name}}">{{$datas->channel_name}}</option>
                                                @endif

                                            @endforeach
                                    </select>  
                                </div>
    
                                <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.store') }}</label>
                                <div class="col-md-4">
                                    <!-- <p>{{$row->store}}</p> -->

                                    <select class="js-example-basic-single" name="store" id="store" onchange="showCustomerLocation()" required style="width:100%">
                                        @foreach($store_front_end as $datas)    
                                            @if( $datas->store_name == $row->store)
                                                        <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                @else
                                                        <option  value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                            @endif

                                        @endforeach
                                    </select>  

                                </div>
                            </div>
                            <br>
                            <!-- 2r -->
                           
                            <div class="row">                           



                                @if ($row->branch != null || $row->branch != "")
                                    <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.branch') }}</label>
                                    <div class="col-md-4">
                                        <!--<p> {{$row->branch}}</p> -->

                                        <select class="js-example-basic-single" name="branch" id="branch" required style="width:100%" onchange="branchChange()">
                                            @foreach($branch as $datas)    
                                                @if( $datas->branch_id == $row->branch)
                                                            <option selected value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                    @else
                                                            <option  value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                                @endif

                                            @endforeach
                                        </select>  
                                    </div>    
                                 @endif      

                                 <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.mode_of_return') }}</label>
                                 <div class="col-md-4">
                                    <!-- <p>{{$row->mode_of_return}}</p> -->

                                     <select class="js-example-basic-single" name="mode_of_return" id="mode_of_return" required style="width:100%">
                                       
                                            @if(  $row->mode_of_return == "STORE DROP-OFF")
                                                  
                                                        <option  selected value="STORE DROP-OFF">Store Drop-Off</option>
                                                @else
                                                        <option   value="STORE DROP-OFF">Store Drop-Off</option>
                                            @endif

                                    
                                    </select>  
                                 </div>
                            </div>      
                             <hr/>
                            <div class="row">   
                                @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                    <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.store_dropoff') }}</label>
                                    <div class="col-md-4">
                                    <!-- <p>{{$row->store_dropoff}}</p> -->
                                            <select class="js-example-basic-single" name="store_dropoff" id="store_dropoff" onchange="showBranch()"  required style="width:100%">
                                                @foreach($store_drop_off as $datas)    
                                                    @if( $datas->store_name == $row->store_dropoff)
                                                                <option selected value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                        @else
                                                                <option  value="{{$datas->store_name}}">{{$datas->store_name}}</option>
                                                    @endif
        
                                                @endforeach
                                            </select> 
                                    </div>
                                @endif
                    
                    
                                <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.branch_dropoff') }}</label>
                                <div class="col-md-4">
                                    {{-- {{$row->branch_dropoff}} --}}
                                    
                                    <select class="js-example-basic-single" name="branch_dropoff" id="branch_dropoff"  style="width:100%">
                                            
                                        @foreach($branch_dropoff as $datas)    
                                            @if( $datas->branch_id == $row->branch_dropoff)
                                                    <option selected value="{{$row->branch_dropoff}}">{{$row->branch_dropoff}}</option>
                                                @else
                                                    <option  value="{{$datas->branch_id}}">{{$datas->branch_id}}</option>
                                            @endif
                                        @endforeach
                  
                                    </select> 
                                </div>    
                    
                            </div>    
                            <br>
                            <!-- 3r -->
                            <div class="row">                           
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.customer_last_name') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->customer_last_name}}</p> -->
                                    <input type='input' value="{{$row->customer_last_name}}" name='customer_last_name' id="customer_last_name" placeholder="{{ trans('message.form-label.customer_last_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>      
                                </div>
    
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.customer_first_name') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->customer_first_name}}</p> -->
                                    <input type='input' value="{{$row->customer_first_name}}" name='customer_first_name' id="customer_first_name" placeholder="{{ trans('message.form-label.customer_first_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>   
                                </div>
                            </div>
                            <br>
                            <!-- 4r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.address') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->address}}</p> -->
                                    <textarea placeholder="{{ trans('message.form-label.address') }} ..." rows="3" class="form-control" name="address" id="address" required>{{$row->address}}</textarea>
                                </div>
    
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.email_address') }}</label>
                                <div class="col-md-4">
                                    <input type='input' value="{{$row->email_address}}" name='email_address' id="email_address" placeholder="{{ trans('message.form-label.email_address') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                    <!--<p>{{$row->email_address}}</p> -->
                                </div>
                            </div>
                            <br/>
                            <!-- 5r -->
                            <div class="row">                           
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.contact_no') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->contact_no}}</p> -->
                                    <input type='input' value="{{$row->contact_no}}" name='contact_no' id="contact_no" placeholder="{{ trans('message.form-label.contact_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                </div>
    
                                <label class="control-label col-md-2"  style="margin-top:7px;">{{ trans('message.form-label.order_no') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->order_no}}</p> -->
                                    <input type='input' value="{{$row->order_no}}" name='order_no' id="order_no" placeholder="{{ trans('message.form-label.order_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/> 
                                </div>
                            </div>
                            <br>
                            <!-- 6r -->
                            <div class="row">                           
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.purchase_date') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->purchase_date}}</p> -->
                                    <input type='input'  name='purchase_date' id="datepicker" value="{{$row->purchase_date}}" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" /> 
                                </div>
    
                                <label class="control-label col-md-2" style="visibility: hidden;">{{ trans('message.form-label.mode_of_payment') }}</label>
                                <div class="col-md-4" style="visibility: hidden;">
                                    <!--<p>{{$row->mode_of_payment}}</p> -->
                                    <!--
                                    <select class="js-example-basic-single" name="mode_of_payment" id="mode_of_payment" required style="width:100%">
                                            <option value="">-- Select Original Mode of Payment --</option>
                                        @foreach($payments as $datas)    
                                            @if( $datas->payment_name == $row->mode_of_payment)
                                                        <option selected value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                                @else
                                                        <option  value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>  -->
                                    
                                    <select   class="js-example-basic-multiple" required name="mode_of_payment[]" id="mode_of_payment" multiple="multiple" style="width:100%;">
                                        <option value="NULL" selected></option>
                                        @foreach($payments as $key=>$list)
                                                @if(strpos($row->mode_of_payment, $list->payment_name) !== false)
                                                        <option selected value="{{$list->payment_name}}" >{{$list->payment_name}}</option>
                                                    @else
                                                        <option  value="{{$list->payment_name}}">{{$list->payment_name}}</option>
                                                @endif
                                                
                                        @endforeach
                  
                                    </select>                                    
                                    
                                    
                                </div>
                            </div>       
                            <br>                    
                            <!-- 7r -->
                            <div class="row">      
                                @if($row->mode_of_refund != null || $row->mode_of_refund != "")
                                    <label class="control-label col-md-2"  style="margin-top:4px;">{{ trans('message.form-label.mode_of_refund') }}</label>
                                    <div class="col-md-4">
                 
                                      
                                        <select class="js-example-basic-single" name="mode_of_refund" id="mode_of_refund" required style="width:100%">
                                                <option value="">-- Select Mode of Refund --</option>
                                                @foreach($payments as $datas)    
                                                    @if( $datas->payment_name == $row->mode_of_refund)
                                                                <option selected value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                                        @else
                                                                <option  value="{{$datas->payment_name}}">{{$datas->payment_name}}</option>
                                                    @endif

                                                @endforeach
                                        </select>   
                                    </div>
                                    @else
                                    
                                @endif

                                @if ($row->bank_name != null || $row->bank_name != "")
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_name') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->bank_name}}</p> -->
                                    <input type='input' value="{{$row->bank_name}}" name='bank_name' id="bank_name" placeholder="{{ trans('message.form-label.bank_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                </div>
                                @endif

                            </div>
                            <br>
                            <!-- 8r -->
                            <div class="row">  
                                @if ($row->bank_account_no != null || $row->bank_account_no != "")
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_account_no') }}</label>
                                <div class="col-md-4">
                                    <!--<p>{{$row->bank_account_no}}</p> -->
                                    <input type='input' value="{{$row->bank_account_no}}" name='bank_account_no' id="bank_account_no" placeholder="{{ trans('message.form-label.bank_account_no') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                </div>
                                @endif
                                @if ($row->bank_account_name != null || $row->bank_account_name != "")
                                <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.bank_account_name') }}</label>
                                <div class="col-md-4">
                                    <!-- <p>{{$row->bank_account_name}}</p> -->
                                    <input type='input' value="{{$row->bank_account_name}}" name='bank_account_name' id="bank_account_name" placeholder="{{ trans('message.form-label.bank_account_name') }}"  required class='form-control' autocomplete="off"  maxlength="50"/>
                                </div>
                                @endif

                            </div>
                            {{-- <br> --}}
                            {{-- <div class="row">
                                <label class="control-label col-md-2"  style="margin-top:6px;">{{ trans('message.form-label.items_included') }}</label>
                                <div class="col-md-4">
                                        @if($row->items_included_others  != null)
                                                <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                            @else
                                                <p>{{$row->items_included}}</p>
                                        @endif
                                </div>
                            </div> --}}
                            {{-- <br> --}}
                            <div class="row">
                                <label class="control-label col-md-2"  style="margin-top:6px;">{{ trans('message.form-label.items_included') }}</label>
                                <div class="col-md-4">
                                    <select   class="js-example-basic-multiple" required name="items_included[]" id="items_included" multiple="multiple" style="width:100%;">
                                        @foreach($items_included_list as $key=>$list)
                                                @if(strpos($row->items_included, $list->items_description_included) !== false)
                                                        <option selected value="{{$list->items_description_included}}" >{{$list->items_description_included}}</option>
                                                    @else
                                                        <option  value="{{$list->items_description_included}}">{{$list->items_description_included}}</option>
                                                @endif
                                                
                                        @endforeach
                  
                                    </select>
                                        <?php $other_items_included = $row->items_included_others;?>
                                        <br><br>
                                        <input type='input'  name='items_included_others' id="items_included_others" autocomplete="off" class='form-control' value="{{$row->items_included_others}}" placeholder="OTHERS"/> 


                                </div>
                         
                            </div>

                            <!--
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
                            </div> -->
                            <br>
                            <!--TABLE-->
                            <!--<div class="table-responsive">
                                <div class="pic-container">
                                    <div class="pic-row"> -->
                                        <!--
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
                                        </table> -->

                                        <table  class='table table-striped table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center" height="10">{{ trans('message.table.front_end_item_code') }}</th>
                                                    <th style="text-align:center" height="10">{{ trans('message.table.front_end_item_description') }}</th>
                                                    <th style="text-align:center" height="10">{{ trans('message.table.front_end_brand') }}</th>
                                                    {{-- <th style="text-align:center" height="10">{{ trans('message.table.front_end_cost') }}</th> --}}
                                                    <th style="text-align:center" height="10">{{ trans('message.table.front_end_serial_number') }}</th>
                                                    <!--<th style="text-align:center" height="10">{{ trans('message.table.front_end_items_included') }}</th>-->
                                                    <th style="text-align:center" height="10">{{ trans('message.table.front_end_problem_details') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resultlist as $rowresult)
                                                <tr>
                                                    <?php $stack_serials = $rowresult->serial_number;?>
                                                    <?php $stack_problem_details = $rowresult->problem_details;?>
                                                    <?php $stack_problem_details_other = $rowresult->problem_details_other;?>
                                                    <?php $stack_cost = $rowresult->cost;?>
                                                    <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                                    <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                                    <td style="text-align:center" height="10" id="return_brand">{{$rowresult->brand}}</td>
                                                    {{-- <td style="text-align:center" height="10">{{$rowresult->cost}}</td> --}}
                                                    <td style="text-align:center" height="10">{{$rowresult->serial_number}}</td>
                                                     <!--<td style="text-align:center" height="10">{{$rowresult->items_included}}</td>-->
                                                    <td style="text-align:center" height="10">{{$rowresult->problem_details}}
                                                        @if($rowresult->problem_details_other != null)
                                                            <br>
                                                            {{$rowresult->problem_details_other}}
                                                        @endif
                                                    </td>                    
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    
                                                </tr>                        
                                            </tbody>
                                        </table>   


                                        <hr/>
                                        <div class="row">                           
                                            <label class="control-label col-md-2" style="color: #3C8DBC; font-size:15px;">{{ trans('message.form-label.suggested_item') }}</label>
                                            
                                        </div>

                                                        
                                        @if($ItemCount == 1 || $ItemCount == 2  || $ItemCount == 0 )
                                                    <div style="width: 100%; height: auto; overflow:auto;">
                                            @else
                                                    <div style="width: 100%; height: 150px; overflow:auto;">
                                        @endif
                                            <table  class='table table-striped table-bordered'>
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" height="10" style="color: #3C8DBC;">{{ trans('message.table.front_end_item_code') }}</th>
                                                        <th style="text-align:center" height="10" style="color: #3C8DBC;">{{ trans('message.table.front_end_item_description') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($ItemResult as $rowresult)
                                                    <tr>

                                                        <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                                        <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                                                    
                                                    </tr>
                                                @endforeach
                                                    <tr>
                                                        
                                                    </tr>                        
                                                </tbody>
                                            </table>   
                                        </div>
                                            
                                       

                                        <hr />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('message.form-label.add_item') }}</label>
                                                    <input class="form-control auto" style="width:420px;" placeholder="Search Item" id="search">
                                                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" style="display: none; top: 60px; left: 15px; width: 520px;">
                                                        <li>Loading...</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="box-header text-center">
                                                <h3 class="box-title"><b>{{ trans('message.form-label.return_items') }}</b></h3>
                                                </div>
                                                            <div class="box-body no-padding">
                                                                <div class="table-responsive">
                                                                    <div class="pic-container">
                                                                        <div class="pic-row">
                                                                            <table class="table table-bordered" id="pullout-items">
                                                                                <tbody>
                                                                                    <tr class="tbl_header_color dynamicRows">
                                                                                        <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                                                                        <th width="10%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                                                                        <th width="30%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                                                                        {{-- <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th> --}}
                                                                                        <th width="10%" class="text-center">{{ trans('message.table.brand') }}</th>
                                                                                        <th width="10%" class="text-center">{{ trans('message.table.serial_no') }}</th>
                                                                                        <th width="10%" class="text-center">{{ trans('message.table.problem_details') }}</th>
                                                                                        <th width="5%" class="text-center">{{ trans('message.table.quantity') }}</th>
                                                                                        <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                                                                    </tr>
                                                                                    <!--
                                                                                    <tr class="tableInfo">
                                                                                        <td colspan="7" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                                                                        <td align="left" colspan="1">
                                                                                            <input type='number' name="total_quantity" class="form-control text-center" id="totalQuantity" readonly></td>
                                                                                        </td>
                                                                                        <td colspan="1"></td>
                                                                                    </tr>
                                                                                -->
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                            
                                                                </div>
                                                                <br>
                                                            </div>
                                            </div>
                                      
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ trans('message.table.note') }}</label>
                                                    <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="requestor_comments" required></textarea>
                                                </div>
                                            </div>
                                     
                                        </div>
                                    <!--</div>
                                </div>
                            </div>-->         
                            <!--
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                                <div class="col-md-10">
                                    <p>{{$row->comments}}</p>
                                </div>
                            </div>
                            -->
                    </div>
                </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>

                <button class="btn btn-danger pull-right" type="submit" id="cancel" style="margin-right:10px; width:135px;"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.void') }}</button>
            </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

$('#carried').hide();
$('#carried_by').removeAttr('required');

var Location = <?php echo json_encode($Location); ?>;

$('.via_class').change(function(){
    var value = $(this).val();
    transactionvalue  = $(this).val();
    if(value == 2){
        $('#carried').show();
        $('#carried_by').attr('required', 'required');
    }else{
        $('#carried').hide();
        $('#carried_by').removeAttr('required');
    }
});

$(document).ready(function() {

        $('.js-example-basic-single').select2();
        var return_brand = $("#return_brand").text();
        if(return_brand == 'APPLE' || return_brand == 'BEATS'){
            $('#locations').show()
            $('#rmawh').remove()
        }else{
            $('#locations').remove()
            // WAREHOUSE.RMA.DEP
        }
});
function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);
$( "#datepicker" ).datepicker( {  maxDate: '0',  dateFormat: 'yy-mm-dd'  } );
$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});
$('.js-example-basic-multiple').select2();
$(".js-example-basic-multiple").select2({
     theme: "classic"
});
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
$('#mode_of_refund').change(function(){
    if($('#mode_of_refund').val() != null){
        var items = $(this).val();
    }else{
        var items = "";
     }
    if(items.includes("BANK DEPOSIT")) {
        $('#div_bank_name').show();
        $('#div_bank_account_no').show();
        $('#div_bank_account_name').show();
        $('#bank_name').attr("required", true);
        $('#bank_account_no').attr("required", true);
        $('#bank_account_name').attr("required", true);
        //$('#items_included_others').show();
        //$('#items_included_others').attr("required", true);
    }else{
        $('#div_bank_name').hide();
        $('#div_bank_account_no').hide();
        $('#div_bank_account_name').hide();
        $('#bank_name').attr("required", false);
        $('#bank_account_no').attr("required", false);
        $('#bank_account_name').attr("required", false);
        //$('#items_included_others').val("");
        //$('#items_included_others').hide();  
        //$('#items_included_others').attr("required", false);
    }
});
function showCustomerLocation(){
        var store_backend = document.getElementById("store").value;
        var purchase_location = document.getElementById("purchase_location").value; 
        $.ajax
        ({ 
            url: '{{ url('admin/distri_for_verification/backend_stores') }}',
            type: "POST",
            data: {
                'store_backend': store_backend,
                'purchase_location': purchase_location,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
         
                var i;
                var showData = [];
                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                 
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }
                jQuery("#branch").html(showData);  
            }
        });
    }
    

function branchChange(){
        var brand_change = document.getElementById("branch").value;
        var store_front = document.getElementById("store").value;
    
        $.ajax
        ({ 
            url: '{{ url('admin/distri_for_verification/branch_change') }}',
            type: "POST",
            data: {
                'brand_change': brand_change,
                'store_front': store_front,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose Customer Location here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].store_name+"'>"+result[i].store_name+"</option>";
                }
                jQuery("#customer_location").html(showData);  
            }
        });
    }


    function showBranch()
    {
        var drop_off_store = document.getElementById("store_dropoff").value;
        var location = document.getElementById("purchase_location").value;
        $.ajax
        ({ 
            url: '{{ url('admin/retail_for_verification/branch_drop_off') }}',
            type: "POST",
            data: {
                'drop_off_store': drop_off_store,
                'location': location,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
            
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }

                jQuery("#branch_dropoff").html(showData); 
            }
        });
    }
$("#cancel").on('click',function() {
    var strconfirm = confirm("Are you sure you want to Cancel this return request?");
        if (strconfirm == true) {
                $("#remarks").val("CANCEL");
                return true;
        }else{
                return false;
                window.stop();
        }
});
//cut
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}
$("#btnSubmit").on('click',function() {
    
        var rowCount = $('#pullout-items tr').length;
       // var quantityReservable = $('.item_quantity').val();
        var itemCost = $('.cost_item').val();
       // var totalQty = $("#totalQuantity").val();
        var signal = 0;
        var alert_message = 0;
        var error_qty = 0;   
   
        if(rowCount <= 1){
            alert("Please put an item!"); 
            return false;
        }else{
            $("form").submit(function(){
                $('#btnSubmit').attr('disabled', true);
            }); 
        }
        
        if(itemCost == '' || itemCost == null || itemCost < 0 ){
            // alert("Please put item cost!");
            return true;
        }else{
            $("form").submit(function(){
                $('#btnSubmit').attr('disabled', true);
            }); 
            $("#remarks").val("SAVE");
        }
});
    
$(document).ready(function() {
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});
 
function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}
var id_qty = 0;
var stack = [];
var token = $("#token").val();
var myStr = '';
var xx_len = 0;
var restriction = 0;
var blank = 0;
var execute = 0;
var button_asc = 0;
var temp = '';
var problem_loop = 0;
var stack_serials = <?php echo json_encode($stack_serials); ?>;
var stack_problem_details = <?php echo json_encode($stack_problem_details); ?>;
var stack_problem_details_other = <?php echo json_encode($stack_problem_details_other); ?>;
var stack_cost = <?php echo json_encode($stack_cost); ?>;
$(document).ready(function(){
    $(function(){
        $("#search").autocomplete({
            source: function (request, response) {
            $.ajax({
                url: "{{ route('scheduling.item.search') }}",
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "search": request.term
                },
                success: function (data) {
                    var rowCount = $('#pullout-items tr').length;
             
                    if(rowCount == 1){
                            if(restriction == 0){
                                myStr = data.sample;   
                                if (data.status_no == 1) {
                                    $("#val_item").html();
                                    var data = data.items;
                                    $('#ui-id-2').css('display', 'none');
                                    response($.map(data, function (item) {
                                        return {
                                            id: item.id,
                                            stock_code: item.digits_code,
                                            stock_upc: item.upc_code,
                                            value: item.item_description,
                                            stock_brand: item.brand,
                                            stock_category: item.category,
                                            stock_current_srp: item.current_srp
                                        }
                                    }));
                                } else {
                                    $('.ui-menu-item').remove();
                                    $('.addedLi').remove();
                                    $("#ui-id-2").append($("<li class='addedLi'>").text(data.message));
                                    var searchVal = $("#search").val();
                                    if (searchVal.length > 0) {
                                        $("#ui-id-2").css('display', 'block');
                                    } else {
                                        $("#ui-id-2").css('display', 'none');
                                    }
                                }
                        }else{
                            alert("Please fill out the problem details!");
                            $("#search").val("");

                        }
                    }else{
                        $("#search").val("");
                        alert("Only 1 item allowed!");

                    }
                }
            })
        },
        select: function (event, ui) {
            var e = ui.item;
            if (e.id) {
                if (!in_array(e.stock_code, stack)) {
                    //temp = $("#temp_reference1").val();
                    button_asc++;
                    problem_loop++;
                    stack.push(e.stock_code);                    
                        var new_row = '<tr class="nr" id="rowid' + e.id + '">' +
                            '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + e.stock_code + '"></td>' +
                            '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + e.stock_upc + '"></td>' +
                            '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + e.value + '"></td>' +
                            // '<td><input class="form-control text-center  cost_item" type="number" name="cost[]" value="'+stack_cost+'" min="0" max="9999999999" step="any" onKeyPress="if(this.value.length==10) return false;" oninput="validity.valid||(value=0);"></td>' +
                            '<td><input class="form-control text-center" type="text" name="brand[]" readonly value="' +e.stock_brand + '"></td>' +
                            '<td><input class="form-control text-center" type="text" name="serial_no[]"  value="' +stack_serials+'"></td>' +
                            '<td><select class="js-example-basic-multiple" required name="' + e.stock_code + '[]" id="problem_details_'+ e.id +'" multiple="multiple" style="width:100%"></select><br><br><input class="form-control text-center" type="text" name="problem_details_other[]" id="problem_details_other_'+ e.id +'"></td>'+
                            '<td><input class="form-control text-center no_units item_quantity"   readonly  data-id="'+e.stock_code +''+button_asc+''+temp+'" data-rate="' + e.stock_price + '" type="number" min="0" max="99999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="'+e.stock_code +'" name="quantity[]" value="1"><input  type="hidden" name="serialize[]" id="serialize_'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ><input  type="hidden" name="line_id[]"  value="'+e.stock_code +''+button_asc+''+temp+'" readonly ><input  type="hidden" name="visible_qty[]" id="'+e.stock_code +''+button_asc+''+temp+'" value="1" readonly ></td>' +
                            '<td class="text-center"><button id="'+e.stock_code +''+button_asc+''+temp+'" onclick="reply_click1(this.id)" class="btn btn-xs btn-danger delete_item" style="width:60px;height:30px;font-size: 11px;text-align: center;">REMOVE</button></td>' +
                            '<input type="hidden" name="category[]" readonly value="' +e.stock_category + '">' +
                            '</tr>';
                    $(new_row).insertAfter($('table tr.dynamicRows:last'));
                    $('.js-example-basic-multiple').select2();
                    $(".js-example-basic-multiple").select2({
                    theme: "classic"
                    });
             
                    var array_problem_details = stack_problem_details.split(", ");
                    var strArray = myStr.split(",");
                    for(var x=0; x < strArray.length; x++){
                        if(array_problem_details.includes(strArray[x])){
              
                            $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'" selected>'+strArray[x]+'</option>');
                        }else{
                            $('#problem_details_'+e.id).append('<option value="'+strArray[x]+'">'+strArray[x]+'</option>');
                        }
                        
                  }
                  if(array_problem_details.includes("OTHERS")){
                    $('#problem_details_other_'+e.id).show();
                    $('#problem_details_other_'+e.id).val(stack_problem_details_other);
                  }else{
                    $('#problem_details_other_'+e.id).hide();
                  }
                  
                  $('#problem_details_'+e.id).change(function(){
                        if($('#problem_details_'+e.id).val() != null){
                            var arrx = $(this).val();
                            execute = 0;
                        }else{
                            var arrx = "";
                            execute++;
                        }
                        var s = arrx;
                        if(s.includes("OTHERS")) {
                            $('#problem_details_other_'+e.id).show();
                            $('#problem_details_other_'+e.id).attr('required', 'required');
                        }else{
                            $('#problem_details_other_'+e.id).val("");
                            $('#problem_details_other_'+e.id).hide();  
                            $('#problem_details_other_'+e.id).removeAttr('required');
                        }
                    })
                    if($('#problem_details_'+e.id).val() == null || $('#problem_details_'+e.id).val() == ''){
                        execute++;
                    }
                    blank++;
                    // Calculate total quantiy
                    //var subTotalQuantity = calculateTotalQuantity();
                    //$("#totalQuantity").val(subTotalQuantity);
                   // $('.tableInfo').show();
                } else {
                    if(!related_items.includes(e.stock_code)){
                        $('#' + e.stock_code).val(function (i, oldval) {
                            return ++oldval;
                        });
                        var temp_qty = $('#'+ e.stock_code).attr("data-id");
                        var q = $('#' +e.stock_code).val();
                        var r = $("#rate_id_" + e.id).val();
                        $('#amount_' + e.id).val(function (i, amount) {
                            if (q != 0) {
                                var itemPrice = (q * r);
                                return itemPrice;
                            } else {
                                return 0;
                            }
                        });
                        $('#'+temp_qty).val(q);
                        //var subTotalQuantity = calculateTotalQuantity();
                        //$("#totalQuantity").val(subTotalQuantity);
                    }else{
                        alert("You can not add this item!");
                    }
                }
                $(this).val('');
                $('#val_item').html('');
                return false;
            }
        },
        minLength: 1,
        autoFocus: true
        });
    });
});
$(document).ready(function () {
  //$('.tableInfo').hide();
});
// Delete item row
$(document).ready(function (e) {
  $('#pullout-items').on('click', '.delete_item', function () {
    problem_loop = problem_loop - 1;
    var  v = $(this).attr("id").substr(0, 8);
    stack = jQuery.grep(stack, function (value) {
      return value != v;
    });
    $(this).closest("tr").remove();
    //var subTotalQuantity = calculateTotalQuantity();
    //$("#totalQuantity").val(subTotalQuantity);
    execute = 0;
    for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
    }
     div_container1 = [];
  });
});
$(document).on('keyup', '.no_units', function (ev) {
    $('#'+ $(this).attr("data-id")).val(this.value);
    //$('.tableInfo').show();
    //var totalQuantity = calculateTotalQuantity();
    //$("#totalQuantity").val(totalQuantity);
});
$("#").on('keyup', function () {
  var searchVal = $("#search").val();
  if (searchVal.length > 0) {
    $("#ui-id-2").css('display', 'block');
  } else {
    $("#ui-id-2").css('display', 'none');
  }
});
function calculateTotalQuantity() {
  var totalQuantity = 0;
  $('.item_quantity').each(function () {
    totalQuantity += parseInt($(this).val());
  });
  return totalQuantity;
}
</script>
@endpush