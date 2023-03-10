<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

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
            <input type="hidden"  name="diagnose" id="diagnose">
                <div id="requestform" class='panel-body'>
                    <div> 
                            <?php   $transaction  == $row->transaction_type; ?>
                            
                            <?php   $deliver  == $row->deliver_to; ?>

                            <div class="row"> 
                                <label class="control-label col-md-2" style="margin-top:7px;">DR#:</label>
                                <div class="col-md-4" style="display:">
                                    <input oninput="this.value = this.value.toUpperCase()" type='input' name='dr_number' id="pos_crf_number" class='form-control' autocomplete="off" maxlength="50" placeholder="DR#" onkeypress="return AvoidSpace(event)" required/>                             
                                </div>
                               
                            </div>
                            <br>
                            <div class="row"> 
                                <label class="control-label col-md-4 text-warning ">Note: Email SDM to SO replacement item with complete details. <br> Customer information &#8594 view button in module.</label>
                            </div>
                            <br>
                            <div class="row"> 
                                <label class="control-label col-md-4 text-success">SDM will provide DR#</label>
                            </div>
                            
                            <hr/> 

                            {{-- @foreach($resultlist as $rowresult)

                                @if ($rowresult->brand == 'APPLE' || $rowresult->brand == 'BEATS')
                                        <div class="row"> 
                                            <label class="control-label col-md-2" style="margin-top:7px;">CRF#:</label>
                                            <div class="col-md-4">
                                                <input oninput="this.value = this.value.toUpperCase()" type='input' name='crf_number' id="pos_crf_number" class='form-control' autocomplete="off" maxlength="50" placeholder="CRF#" onkeypress="return AvoidSpace(event)" required/>                             
                                            </div>
                                        </div>
                                        <hr/> 
                                    @else
                                        <div class="row"> 
                                            <label class="control-label col-md-2" style="margin-top:7px;">SOR#:</label>
                                            <div class="col-md-4">
                                                <input oninput="this.value = this.value.toUpperCase()" type='input' name='sor_number' id="pos_crf_number" class='form-control' autocomplete="off" maxlength="50" placeholder="SOR#" onkeypress="return AvoidSpace(event)" required/>                             
                                            </div>
                                        </div>
                                        <hr/> 
                                @endif --}}

                            {{-- @endforeach --}}

                            {{-- @if ($row->transaction_type == 0 )
                            
                            @if ( $row->sor_number != null ||  $row->sor_number != "" )
                            
                                    <div class="row"> 
                                        <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.sor_number') }}</label>
                                        <div class="col-md-4">
                                            <input type='input' value="{{$row->sor_number}}" name='sor_number' id="sor_number" class='form-control' autocomplete="off" maxlength="50" placeholder="SOR#" onkeypress="return AvoidSpace(event)"  required/>                             
                                        </div>
                                    </div>
                                @else
                                
                                   <div class="row"> 
                                        <label class="control-label col-md-2" style="margin-top:7px;">{{ trans('message.form-label.sor_number') }}</label>
                                        <div class="col-md-4">
                                            <input type='input' name='sor_number' id="sor_number" class='form-control' autocomplete="off" maxlength="50" placeholder="SOR#" onkeypress="return AvoidSpace(event)"  required/>                             
                                        </div>
                                    </div>
                                    
                            @endif
                            
                            <hr/>
                        @endif --}}

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
                                                    {{-- <th width="10%" class="text-center">{{ trans('message.table.cost') }}</th> --}}
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
                                                    {{-- <td style="text-align:center" height="10">{{$rowresult->cost}}</td> --}}
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
                            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>
                        @else
                            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-circle-o" ></i> {{ trans('message.form.proceed') }}</button>
                    @endif
                </div>

        </form>
</div>
@endsection

@push('bottom')
<script type="text/javascript">
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

$("#btnSubmit").on('click',function() {
        var signal = 0;
        var alert_message = 0;
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


       if(signal != 0){
            return true;   
        }else{
            if(alert_message == 1){
                alert("Incorrect SOR# format! e.g. SOR#1001");
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