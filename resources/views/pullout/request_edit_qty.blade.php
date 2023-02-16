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
            <div id="requestform" class='panel-body'>
             <div> 
                <form action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}' method="POST" id="pulloutForm" enctype="multipart/form-data">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                    <input type="hidden" value="{{ $temp_reference }}" name="temp_reference1" id="temp_reference1">
                        <div class="row">                           
                            <label class="control-label col-md-2">Requestor:</label>
                            <div class="col-md-4">
                                    <p>{{$row->requestorlevel}}</p>
                            </div>
 
                            <label class="control-label col-md-2">Created Date:</label>
                            <div class="col-md-4">
                                    <p>{{$row->created_at}}</p>
                            </div>
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">Requested Date for Pullout:</label>
                            <div class="col-md-4">
                                    <p>{{$row->pull_out_schedule_date}}</p>
                            </div>
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">MP#:</label>
                            <div class="col-md-4">
                                    <p>{{$row->reference}}</p>
                            </div> 
                                <label class="control-label col-md-2">ST#/Ref#:</label>
                                <div class="col-md-4">
                                    @if($row->st_number_pull_out == null)
                                        ____________________
                                    @else 
                                    		@if($row->revise_st_number_pull_out != null)
            
                                                    <p>{{$row->revise_st_number_pull_out}}</p>
                                			@else
                                
                                 					<p>{{$row->st_number_pull_out}}</p>
                                 
                                			@endif
                                    @endif
                                </div>
                                                         
                        </div>
                        @if ($row->pullout_type == 'rma')
                        <div class="row">
                                <label class="control-label col-md-2">SROF#:</label>
                                 <div class="col-md-4">
                                        <p>{{$row->srof_number}}</p>
                                </div>
                        </div>
                        @endif
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout From:</label>
                            <div class="col-md-4">
                                    <p>{{$row->store_name}}</p>
                            </div> 
                            <label class="control-label col-md-2">Deliver To:</label>
                            <div class="col-md-4">
                                    <p>{{$row->stores_deliver_to}}</p>
                            </div>                            
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout Reason:</label>
                            <div class="col-md-4">
                                    <p>{{$row->reason_name}}</p>
                            </div> 
                            <label class="control-label col-md-2">Pullout Via:</label>
                            <div class="col-md-4">
                                     <p>{{$row->path_name}}</p>
                            </div>                          
                        </div>
                        @if($row->path_name == "REQUESTOR")
                        <div class="row"> 
                            <label class="control-label col-md-2">Hand Carried By:</label>
                            <div class="col-md-4">
                                     <p>{{$row->hand_carry_by}}</p>
                            </div>                            
                        </div>
                        @endif                        
                        @if ($row->pullout_status != 'PENDING')
                            @if($row->pullout_status == 'REJECTED')
                            <div class="row"> 
                                    <label class="control-label col-md-2">Rejected By:</label>
                                    <div class="col-md-4">
                                            <p>{{$row->rejectedlevel}}</p>
                                    </div> 
                                     <label class="control-label col-md-2">Rejected Date:</label>
                                    <div class="col-md-4">
                                             <p>{{$row->rejected_at_level1}}</p>
                                    </div>                            
                            </div>
                            @else
                            <div class="row"> 
                                    <label class="control-label col-md-2">Approver:</label>
                                    <div class="col-md-4">
                                            <p>{{$row->approverlevel}}</p>
                                    </div> 
                                     <label class="control-label col-md-2">Approved Date:</label>
                                    <div class="col-md-4">
                                             <p>{{ $row->approved_at_level1}}</p>
                                    </div>                            
                            </div>
                        @endif
                        @endif

                        <hr color="black" >                        
                        <!--TABLE-->
                        <table  class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th style="text-align:center" height="10">Digits Code</th>
                                        <th style="text-align:center" height="10">UPC Code</th>
                                        <th style="text-align:center" height="10">Item Description</th>
                                        <th style="text-align:center" height="10">Brand</th>
                                        <!--<th style="text-align:center" height="10">WH Category</th>-->
                                        <th style="text-align:center" height="10">Qty</th>
                                        <th style="text-align:center" height="10">Action</th>
                                     </tr>
                                </thead>
                        <tbody>
                            @foreach($resultlist as $rowresult)
                                <tr>
                                      <td style="text-align:center" height="10">{{$rowresult->digits_code}}
                                            <input  type="hidden" name="digits_code[]" value="{{$rowresult->digits_code}}" readonly >
                                    </td>
                                      <td style="text-align:center" height="10">{{$rowresult->upc_code}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                     <!--<td style="text-align:center" height="10">{{$rowresult->category}}</td> -->
                                      <td style="text-align:center;width:80px;" height="10">
                                       
                                        @if($rowresult->serialize != 0)
                                                @php($count= 0)
                                                <input class="form-control text-center no_units item_quantity" style="width:80px;" data-id="b_id{{$rowresult->digits_code}}{{ $temp_reference }}" readonly data-rate="" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="{{$rowresult->digits_code}}" name="quantity[]" value="{{$rowresult->quantity}}">
                                                <input  type="hidden" name="serialize[]" id="serialize_{{$rowresult->digits_code}}{{ $temp_reference }}" value="{{$rowresult->serialize}}" readonly >
                                                <input  type="hidden" name="data_checker[]" id="data_checker_{{$rowresult->digits_code}}{{ $temp_reference }}" value="1" readonly >
                                                
                                                <input  type="hidden" name="old_line_id[]"  value="{{$rowresult->line_id}}" readonly >

                                                @foreach($serial_no as $seriallist)
                                                    @php($count++)
                                                    <input  type="hidden" id="serial_no_b_id{{$seriallist->digits_code}}{{ $temp_reference }}{{$count}}"  value="{{$seriallist->serial_number}}" readonly >
                                                    
                                                    @if($seriallist->serialize == 0)
                                                        @php($count = 0)
                                                    @else    
                                                        @if($count == $seriallist->quantity)
                                                            @php($count = 0)
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                <input class="form-control text-center no_units item_quantity" style="width:80px;" data-id="b_id{{$rowresult->digits_code}}{{ $temp_reference }}" data-rate="" type="number" min="0" max="9999" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid||(value=0);" id="{{$rowresult->digits_code}}" name="quantity[]" value="{{$rowresult->quantity}}">
                                                <input  type="hidden" name="serialize[]" id="serialize_{{$rowresult->digits_code}}{{ $temp_reference }}" value="0" readonly >

                                                <input  type="hidden" name="data_checker[]" id="data_checker_{{$rowresult->digits_code}}{{ $temp_reference }}" value="0" readonly >
                                        
                                                <input  type="hidden" name="old_line_id[]"  value="" readonly >
                                        @endif 
                                        
                                       
                                        <input  type="hidden" name="remember_qty_{{$rowresult->digits_code}}" id="remember_qty_{{$rowresult->digits_code}}" value="{{$rowresult->quantity}}" readonly >
                                      
                                        <input  type="hidden" name="line_id[]"  value="{{$rowresult->digits_code}}{{ $temp_reference }}" readonly >
                                       
                                    </td>   
                                    <td style="text-align:center;width:80px;" height="10">     
                                        <button type="button" id="{{$rowresult->digits_code}}{{ $temp_reference }}" onclick="reply_click1(this.id)" class="btn btn-xs btn-danger delete_item" style="width:60px;height:30px;font-size: 11px;text-align: center;">CLEAR</button><br><br>
                                        <button type="button" style="width:60px;height:30px;font-size: 11px;text-align: center;" class="btn btn-success" id="b_id{{$rowresult->digits_code}}{{ $temp_reference }}" tabindex="-1" data-toggle="modal" data-target="#excel-upload-serial"  onclick="reply_click(this.id)" >SN</button>
                                    </td> 
                                </tr>
                            @endforeach
                                <tr>
                                        <td style="text-align:right" height="10" colspan="4"><label>Total Quantity:</label></td>
                                        <td style="text-align:center;width:80px;" height="10">
                                                <input type="number" name="total_quantity" class="form-control text-center" style="width:80px;" id="totalQuantity" readonly value="{{$row->total_quantity}}" >
                                        </td>    
                                        <td style="text-align:center;width:80px;" height="10">
                                        </td>   
                                </tr>
                        </tbody>
                        </table>

                        @if($row->requestor_comments != null)
                            <div class="row">
                                <label class="control-label col-md-2">Requestor Comment:</label>
                                    <div class="col-md-4">
                                        @if($row->requestor_comments == null)
                                            ____________________
                                        @else 
                                            <p>{{$row->requestor_comments}}</p>
                                        @endif
                                </div>
                            </div>
                        @endif

                        @if ($row->comments != null)

                        <div class="row">
                            <label class="control-label col-md-2">Approver Comment:</label>
                            <div class="col-md-4">
                                @if($row->comments == null)
                                        ____________________
                                 @else 
                                    <p>{{$row->comments}}</p>
                                @endif
                            </div>  
                        </div>
                        @endif

                        @if($row->hq_comment != null)
                            <div class="row">
                                <label class="control-label col-md-2">HQ Approval Comment:</label>
                                    <div class="col-md-4">
                                        @if($row->hq_comment == null)
                                            ____________________
                                        @else 
                                            <p>{{$row->hq_comment}}</p>
                                        @endif
                                    </div>
                            </div>

                        @endif

                        @if ($row->pullout_status == 'REJECTED')
                        <br>
                        <div class="row">
                            <div class='col-md-12'>
                                <h4 style="color:red;"><strong>Please void ST# if request is rejected.</strong></h4>
                            </div>
                        </div>
                        @endif
                </div>
            </div>
            <div class='panel-footer'>           
                    <input type='submit' class='btn btn-primary'  id="btnSubmit" value='Save'/>
            </div>
        </form>
    </div>  
    
    <!-- Modal -->
    <div class="modal fade" id="excel-upload-serial" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Serial</h4>
            </div>
            <form id="myform" method="" >
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div class="modal-body">
                        <div id="screens"> 
                                <input type="hidden" value="{{ $temp_reference }}" name="temp_reference" id="temp_reference">
                                <input type="hidden"  name="button_ids" id="button_ids">
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" id="save_serial" value="Save">
                    <button type="button" class="btn btn-default" id="close_serial" data-dismiss="modal" onclick="removeElements()">Close</button>
                </div>
            
            </form>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
<script type="text/javascript">

$("#btnSubmit").on('click',function() {
    var quantityReservable = $('.item_quantity').val();
    var error_qty = 0;
    $('.item_quantity').each(function () {
            var quantityReservable1 = $(this).val();
            
            if(quantityReservable1 < 0 || quantityReservable1 == 0){
                //return false;
                error_qty++;
            }
            if(quantityReservable1 == ''){
                //return false;
                error_qty++;
            }
            if(quantityReservable1 == null){
                //return false;
                error_qty++;
            }
            
    });

    if(error_qty == 0){
        return true; 
    }else{
        alert("Please put an item quantity!"); 
        return false;  
    }
});

function calculateTotalQuantity() {
  var totalQuantity = 0;
  $('.item_quantity').each(function () {
    totalQuantity += parseInt($(this).val());
  });
  return totalQuantity;
}

$(document).on('keyup', '.no_units', function (ev) {
    //$('.tableInfo').show();
    var totalQuantity = calculateTotalQuantity();
    $("#totalQuantity").val(totalQuantity);

});


var count_of_id = 0;
var div_container = ''; 
var div_container1 =[]; 
var qty_current = [];
var related_items = [];
var qty_current1 = 0;
var count_validation = 0;
var axxxx = 0;
var fields = 0;
var disable = 0;
var count_null_values = '';
var old_value = 0;
var serialize_value = 0;
var start= 0;
var current_field = 0;
var upload_items = 0;
var remember_field = [];
var serial_restriction = []; //new
function reply_click1(clicked_id){

        div_container = "b_id"+clicked_id;
        current_field = $("#b_id"+clicked_id).val();
        if(current_field == ""){
            current_field = 1;
        }

        $("#button_ids").val("b_id"+clicked_id);
        var data = $('#myform').serialize();
        $.ajax({
             type: 'GET',
             url: '{{ url('admin/pullout_headquarters/SoftDeleteSerail') }}',
             data: data,
             success: function( response ){
                 console.log(response);
             },
             error: function( e ) {
                 console.log(e);
             }             
        });

    for (iz = 0; iz <=current_field; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
    }

    for( var i = 0; i < div_container1.length; i++){ 
        if ( div_container1[i] === div_container) {
            div_container1.splice(i, current_field); 
        }
    }
    var counter_field = 0;
    var sequence_loop = remember_field.length;
 
    for( var y = 0; y < sequence_loop; y++){ 
        counter_field++;
        for (var ix = remember_field.length - 1; ix >= 0; ix--) {
            if (remember_field[ix] == div_container+counter_field) remember_field.splice(ix, 1);
        }
    }

    $("#"+clicked_id.substr(0, 8)).prop("readonly", false);

    var qty = $("#remember_qty_"+clicked_id.substr(0, 8)).val();
    $("#serialize_"+clicked_id).val(0)
    $("#data_checker_"+clicked_id).val(0)
    $("#"+clicked_id.substr(0, 8)).val(qty);
}

function reply_click(clicked_id){
 
    //alert($("#serial_no_"+clicked_id+1).val());

    if(upload_items == 0){
      serialize_plug =  clicked_id;
      serialize_value = clicked_id;
      $("#button_ids").val(clicked_id);
      div_container = clicked_id;
      current_field = $("#"+clicked_id.substr(4, 8)).val();
        if(current_field == ""){
            current_field = 1;
        } 
        
      count_null_values = current_field;

      //remember_qty.push(clicked_id.substr(4, 8)+current_field);

      if(div_container1.includes(clicked_id)){

        current_field = $("#"+clicked_id.substr(4, 8)).val();
        if(current_field == ""){
            current_field = 1;
        }
        qty_current1 = $("#"+clicked_id).val()+div_container;
        fields = document.getElementById("screens").childElementCount;
            for (iz = 0; iz <=current_field; iz++) { 
                var child = $('#second'+div_container+iz);
                child.show();
            }

        for (ii = 0; ii <= count_null_values; ii++) { 
            if(ii == 0){
                 ii = 1;
             }
             var x_var =  $("#serial_number_"+ii+clicked_id).val();
            if(x_var == null || x_var == ''){
                disable++;
            }else{
                disable = 0;
            }
        }

        if(disable == 0){
            $("#save_serial").attr("disabled", true);  
        }else{
            $("#save_serial").attr("disabled", false); 
        }

        if(!in_array(clicked_id+current_field, remember_field)){
  
            start = current_field;
            //alert(start);
            axxxx = $("#"+clicked_id).val();
            qty_current.push(axxxx+div_container);
            var ch = document.getElementById("screens");
            var  dcode = clicked_id.substr(4, 8);
            for (i = start; i <=current_field; i++){
                count_of_id++;
                count_validation++;
                if(i == 0){
                 i = 1;
                }
                document.querySelector('#screens').insertAdjacentHTML(
                    'afterbegin',
                    `
                    <div id="second`+clicked_id+i+`">
                    <label>Serial`+i+` #:</label>
                    <input type="text" name="serial_number[]`+i+`" class="form-control"  required  id="serial_number_`+i+``+clicked_id+`" autofocus placeholder="Serial`+i+` #" />
                    <input type="hidden" name="code[]"  id="code"`+i+` value="`+dcode+`"  />
                    <br>
                    </div>
                    `      
                )
                $("#serial_number_"+i+clicked_id).focusin();
            } 
            $("#save_serial").attr("disabled", false); 
            remember_field.push(clicked_id+current_field);  
        }
      }else{ 
        axxxx = $("#"+clicked_id).val();
        current_field = $("#"+clicked_id.substr(4, 8)).val();
        if(current_field == ""){
            current_field = 1;
        }
        
        //alert($("#serialize_"+clicked_id.substr(4, 100)).val());
        if($("#serialize_"+clicked_id.substr(4, 100)).val() != 0){
            qty_current.push(axxxx+div_container);
            var ch = document.getElementById("screens");        
            var  dcode = clicked_id.substr(4, 8);   
            for (i = 0; i <=current_field; i++) {
                count_of_id++;
                count_validation++;
                if(i == 0){
                 i = 1;
                }
            document.querySelector('#screens').insertAdjacentHTML(
            'afterbegin',
            `
            <div id="second`+clicked_id+i+`">
            <label>Serial`+i+` #:</label>
            <input value="`+$("#serial_no_"+clicked_id+i).val()+`" type="text" name="serial_number[]`+i+`" class="form-control"  required  id="serial_number_`+i+``+clicked_id+`" autofocus readonly  placeholder="Serial`+i+` #" />
            <input type="hidden" name="code[]"  id="code"`+i+` value="`+dcode+`"  />
            <br>
            </div>
            `      
            )
            $("#serial_number_"+i+clicked_id).focus();
            }
           // document.getElementById("save_serial").disabled = true; 
            $("#save_serial").attr("disabled", true);  
        }else{
            qty_current.push(axxxx+div_container);
            var ch = document.getElementById("screens");        
            var  dcode = clicked_id.substr(4, 8);   
            for (i = 0; i <=current_field; i++) {
                count_of_id++;
                count_validation++;
                if(i == 0){
                 i = 1;
                }
            document.querySelector('#screens').insertAdjacentHTML(
            'afterbegin',
            `
            <div id="second`+clicked_id+i+`">
            <label>Serial`+i+` #:</label>
            <input type="text" name="serial_number[]`+i+`" class="form-control"  required  id="serial_number_`+i+``+clicked_id+`" autofocus  placeholder="Serial`+i+` #" />
            <input type="hidden" name="code[]"  id="code"`+i+` value="`+dcode+`"  />
            <br>
            </div>
            `      
            )
            $("#serial_number_"+i+clicked_id).focus();
            } 
            $("#save_serial").attr("disabled", false);  
        }


        //$("#save_serial").attr("disabled", false);   
        remember_field.push(clicked_id+current_field);      
      }
    }else{

      document.getElementById("save_serial").disabled = true;
      serialize_plug =  clicked_id;
      serialize_value = clicked_id;
      index_variable = $("#sn_index"+clicked_id.substr(4, 100)).val();
      count_variable = $("#sn_count"+clicked_id.substr(4, 100)).val();
      $("#button_ids").val(clicked_id);
      div_container = clicked_id;
      current_field = $("#"+clicked_id).val();
        if(current_field == ""){
            current_field = 1;
        } 
      count_null_values = current_field;
        axxxx = $("#"+clicked_id).val();
        current_field = $("#"+clicked_id).val();
        if(current_field == ""){
            current_field = 1;
        }
        qty_current.push(axxxx+div_container);
        var ch = document.getElementById("screens");        
        var  dcode = clicked_id.substr(4, 8);  
        var array_search = 0;
        var serial_count = 0;
        var serial_count1 = 0;
        var serial_code = "";
        var i = 0;
        for (i = 0; i <=current_field; i++){
            count_of_id++;
            count_validation++;
            if(i == 0){
             i = 1;
            }
            serial_count = count_variable;

            if(serial_count == 0){
                serial_count = 1;
            }
        
            if(serial_count != serial_count1){
                array_search = index_variable++;
                serial_count1++;
            }

            if(upload_serials[0][array_search] == null){
                serial_code = "";
            }else{
                serial_code = upload_serials[0][array_search];
            }
                
                document.querySelector('#screens').insertAdjacentHTML(
                'afterbegin',
                `
                <div id="second`+clicked_id+i+`">
                <label>Serial`+i+` #:</label>
                <input type="text" name="serial_number[]`+i+`" class="form-control" required  value="`+serial_code+`"  readonly id="serial_number_`+i+``+clicked_id+`" autofocus  placeholder="Serial`+i+` #" />
                <input type="hidden" name="code[]"  id="code"`+i+` value="`+dcode+`"  />
                <br>
                </div>
                `      
                )
                $("#serial_number_"+i+clicked_id).focus();
            }

            //$("#save_serial").attr("disabled", false);   
            remember_field.push(clicked_id+current_field);      
      
    }

  }
  
  function removeElements() {
    $('#error-message').hide();
    if(div_container1.includes(div_container)){
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.hide();
        }
    }else{
        for (iz = 0; iz <=count_of_id; iz++) { 
        var child = $('#second'+div_container+iz);
        child.remove();
        }
    }
}

var add = 0;
var permission = 0;
$("#save_serial").on('click',function(){
        var strconfirm = confirm("Are you sure you want to save this serials?");
        if (strconfirm == true){
            var data = $('#myform').serialize();
                for (ii = 0; ii <= count_null_values; ii++) { 
                    if(ii == 0){
                        ii = 1;
                    }
                   var x_var =  $('#serial_number_'+ii+serialize_value).val();
                   if(x_var == null || x_var == ''){
                        permission++
                    }else{
                        permission = 0;
                    }            
                } 
                if(permission == 0){
                $("#save_serial").attr("disabled", true);
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_headquarters/AddSerail') }}',
                        data: data,
                        success: function(data){
                           if(data['serail_restriction'] != 'available'){
                                $('#error-message').show();
                                var myJSON =  JSON.stringify(data['serail_restriction']);
                                var newStr = myJSON.replace(/'"]}/g, "");
                                var newStr1 = newStr.replace(/key 'serial_number/g, "Serial Number!");
                                document.getElementById("p1").innerHTML = newStr1.substr(28,200);
                           }else{

                                //alert(serialize_plug.substr(4, 100));               
                                if($('#serialize_'+serialize_plug.substr(4, 100)).val() == 0){
                                    $('#serialize_'+serialize_plug.substr(4, 100)).val(1); //new
                                }
                                document.getElementById("close_serial").disabled = false;
                                $('#error-message').hide();
                               // $("#serialize_"+serialize_value).val("1");
                                $('#excel-upload-serial').modal('hide');
                                div_container1.push(div_container);
                                related_items.push(div_container.substr(0, 8));
                                for (iz = 0; iz <=count_of_id; iz++){ 
                                     var child = $('#second'+div_container+iz);
                                     child.hide();
                                     $("#serial_number_"+iz+serialize_value).attr("disabled", true);
                                 }
                                 console.log(data);
                                 $('#'+serialize_plug.substr(4, 100)).prop("readonly", true);
                                 
                                 $("#"+serialize_plug.substr(4, 8)).prop("readonly", true);

                                 if($('#serialize_'+serialize_plug.substr(4, 100)).val() == 2){
                                    serial_restriction.push(serialize_plug); 
                                 }
                           }
                        },
                        error: function( e ) {
                            console.log(e);
                        }                      
                  });
                }else{
                    alert("Please fill out serial number!");
                    permission= 0;
                }
                  return false;
                  window.stop();
        }else{
                  return false;
                  window.stop();
        }
});
</script>
@endpush