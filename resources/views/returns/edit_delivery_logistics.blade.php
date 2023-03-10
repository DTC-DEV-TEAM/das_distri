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
                <div id="requestform" class='panel-body'>
                    <div> 

                                         <div class="row"> 
                                            <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.return_delivery_date') }}</label>
                                            <div class="col-md-4">
                                                    <input type='input'  name='return_delivery_date' id="datepicker" onkeydown="return false" required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                        
                                            </div>
                                        </div>
                            <hr/>
                            @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                <div class="row">                           
                                    <label class="control-label col-md-2">Pullout From:</label>
                                    <div class="col-md-4">
                                       <p>{{$row->deliver_to}}</p>
                                    </div>
                                 
                                    <label class="control-label col-md-2">Deliver To:</label>
                                            <div class="col-md-4">
                                                <p>{{$store_deliver_to->store_name}}</p>
                                    </div>
                                 
                                </div>
                            @endif
                                

                            <!-- 1r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.return_reference_no') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->return_reference_no}}</p>
                                </div>
                                @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                        <label class="control-label col-md-2">{{ trans('message.form-label.store_dropoff') }}</label>
                                        <div class="col-md-4">
                                            <p>{{$row->store_dropoff}}</p>
                                        </div>
                                @endif
                            </div>
                            
                            <!-- 2r -->
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.purchase_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->purchase_location}}</p>
                                </div>
                                
                                @if ($row->branch_dropoff != null || $row->branch_dropoff != "")
                                                <label class="control-label col-md-2">{{ trans('message.form-label.branch_dropoff') }}</label>
                                                <div class="col-md-4">
                                                    <p>{{$row->branch_dropoff}}</p>
                                                </div>    
                                @endif  
    
    <!--
                                <label class="control-label col-md-2">{{ trans('message.form-label.store') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->store}}</p>
                                </div> -->
                            </div>
                            <!-- 2r -->
                            <div class="row">           
                            
                                <label class="control-label col-md-2">{{ trans('message.form-label.customer_location') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->customer_location}}</p>
                                </div>
                                
                                <label class="control-label col-md-2">{{ trans('message.form-label.mode_of_return') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->mode_of_return}}</p>
                                </div>
    
                            <!--
                                @if ($row->branch != null || $row->branch != "")
                                    <label class="control-label col-md-2">{{ trans('message.form-label.branch') }}</label>
                                    <div class="col-md-4">
                                        <p>{{$row->branch}}</p>
                                    </div>    
                                 @endif     -->
                            </div>      
                            
                            <div class="row">   
                            <!--
                                        @if ($row->store_dropoff != null || $row->store_dropoff != "")
                                            <label class="control-label col-md-2">{{ trans('message.form-label.store_dropoff') }}</label>
                                            <div class="col-md-4">
                                                <p>{{$row->store_dropoff}}</p>
                                            </div>
                                        @endif -->
                                        
           
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

                                    @if($row->items_included_others  != null)
                                            <p>{{$row->items_included}}, {{$row->items_included_others}}</p>
                                        @else
                                            <p>{{$row->items_included}}</p>
                                    @endif
                                   
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
                            <div class="row">   
                                <!--
                                <label class="control-label col-md-2">{{ trans('message.form-label.bank_account_name') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->bank_account_name}}</p>
                                </div> -->
                                 <div class="col-md-6">
                                </div>
                             
                            </div>

                            <div class="row">                           
                                
                                <div class="col-md-6">
                                 
                                </div>
                                
                            @if($row->verified_items_included  != null)
                                <label class="control-label col-md-2">{{ trans('message.form-label.verified_items_included') }}</label>
                                <div class="col-md-4">

                                    @if($row->verified_items_included_others  != null)
                                            <p>{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                        @else
                                            <p>{{$row->verified_items_included}}</p>
                                 
                                    @endif
                                </div>      
                                @endif
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
                            
                            <br>
                            <!--TABLE-->
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

                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.table.comments1') }}</label>
                                <div class="col-md-10">
                                    <p>{{$row->comments}}</p>
                                </div>
                            </div>

                            @if($row->returns_status_1 == 23)
                            <hr/>
                            <div class="row">                           
                                <label class="control-label col-md-2">{{ trans('message.form-label.scheduled_by') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->scheduled_by}}</p>
                                </div>
                                <label class="control-label col-md-2">{{ trans('message.form-label.return_schedule') }}</label>
                                <div class="col-md-4">
                                    <p>{{$row->return_schedule}}</p>
                                    <?php $date_return = $row->return_schedule;?>
                                </div>
                            </div>
                            <br>
                            @endif
                           <!--
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
                            </div>   -->
                            <!--
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
                                                                            <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th>
                                                                            <th width="10%" class="text-center">{{ trans('message.table.brand') }}</th>
                                                                            <th width="10%" class="text-center">{{ trans('message.table.serial_no') }}</th>
                                                                            <th width="10%" class="text-center">{{ trans('message.table.problem_details') }}</th>
                                                                            <th width="5%" class="text-center">{{ trans('message.table.quantity') }}</th>
                                                                            <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
                                                                        </tr>
                                                           
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
                                        <textarea placeholder="{{ trans('message.table.comments') }} ..." rows="3" class="form-control" name="requestor_comments"></textarea>
                                    </div>
                                </div>
                         
                            </div>   
                            -->
                    </div>
                </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
             
            </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">

var date_return_script = <?php echo json_encode($date_return); ?>;


function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);


    
    
    //var date_return_script1 = date_return_script;
    //var dt = new Date(date_return_script1).getTime();
    //var days = 1;
    //var newDate = new Date(dt + days * 24*60*60*1000);

    $( "#datepicker" ).datepicker( {
        
        dateFormat: 'yy-mm-dd',
        minDate :1

    } );

$('.js-example-basic-multiple').select2();
$(".js-example-basic-multiple").select2({
     theme: "classic"
});



$(document).ready(function(){
  $("myform").submit(function(){
        $('#btnSubmit').attr('disabled', true);
  });
});


</script>
@endpush