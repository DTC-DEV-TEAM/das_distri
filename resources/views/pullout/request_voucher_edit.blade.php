
@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
#image_preview {
    display: none;
}
</style>
@endpush
@section('content')
<!-- link -->
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <div class='panel panel-default'>
    <div class='panel-heading'>

    </div>
    <div class='panel-body'>    
            <form method='POST' id="myform" action="{{route('pulloutstw.item.store')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$row->id}}" name="id">  
            <table width="100%">
                    <tr>
                            <td width="17%">
                                <label class="control-label col-md-12"><strong>Return Form Upload:<strong></label>
                            </td>
                            <td colspan="3">
                                <input type="file" name="image" id="image" class="image" style="width:250px;" required accept="image/*">
                                <div id="image_preview">
                                    <img src="#" id="image-preview" style="width:650px;height:300px;" /><br />
                                    <a id="image_remove" href="#">Remove</a>
                                </div>
      
                            </td>
                    </tr>  
                    <tr>    
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr>
            </table>
        <div id="printableArea"> 
            <table width="100%">
                <tr>
                    <td colspan="4">
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <img src="{{asset("$store_logos->store_logo")}}"  style="align:middle;width:155px;height:30px;">
                                </td>
                                <td>
                                    <h4 align="left"  style="margin-top: 17px;"><strong>RETURN FORM</strong></h4> 
                                </td>
                            </tr>     
                        </table>   
                        <hr color="black" >
                    </td>
                </tr>
        
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>MP#:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->reference}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>SROF#:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->srof_number}}</p>
                        </td>
                    </tr>  
                 
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout From:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->stores_deliver_to}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Deliver To:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->store_name}}</p>
                        </td>                        
                    </tr>   
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Transacted Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{ $row->rejected_at_level5 != null ? date('m-d-Y', strtotime($row->rejected_at_level5)): "" }}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Transacted By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->received_by}}</p> 
                        </td>                        
                    </tr> 
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Warranty Status:<strong></label>
                        </td>
                        <td width="40%">
                            <p><label style="color:red">{{$row->pullout_status}}</label></p>
                        </td>    
                        
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout Reason:<strong></label>
                        </td>
                        <td>
                            <p>{{$row->reason_name}}</p>
                        </td>  
                    </tr>
                    <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                    </tr>
                    @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                    <tr>
                        <td colspan="4">
                           
                            <label class="control-label col-md-12"><strong>PULLOUT ITEMS</strong></label>
                            <br>
                            <table border="0" width="100%" style="text-align:center;border-collapse: collapse;font-size: 14px;">
                                <thead>
                                    <tr>
                                         <th style="text-align:center" height="10">Digits Code</th>
                                         <th style="text-align:center" height="10">UPC Code</th>
                                         <th style="text-align:center" height="10">Item Description</th>
                                         <th style="text-align:center" height="10">Brand</th>
                                        <!-- <th style="text-align:center" height="10">WH Category</th> -->
                                         <th style="text-align:center" height="10">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultlist as $rowresult)
                                        <tr>
                                            <td height="10">{{$rowresult->digits_code}}</td>
                                            <td height="10">{{$rowresult->upc_code}}</td>
                                            <td height="10">{{$rowresult->item_description}}</td>
                                            <td height="10">{{$rowresult->brand}}</td>
                                          <!--  <td height="10">{{$rowresult->category}}</td> -->
                                            <td height="10">{{$rowresult->quantity}}</td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            <td style="text-align:right" height="10" colspan="4"><label><strong>Total Quantity:</strong></label></td>
                                            <td style="text-align:center" height="10"><p>{{$row->total_quantity}}</p></td>    
                                        </tr>
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                    @endif
                    <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                    </tr>
                    <tr>
                            <td colspan="4">
                                    <label class="control-label col-md-12"><strong>FILLED BY CUSTOMER<strong></label>
                            </td>
                    </tr>
                    <tr>
                    
                            <td width="20%">
                                    <br>
                                <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                            </td>
                            <td width="40%">
                                    <br>
                                    ____________________
                            </td>    
                            
                            <td width="20%">
                                    <br>
                                <label class="control-label col-md-12"><strong>Received By:<strong></label>
                            </td>
                            <td>
                                    <br>
                                    ____________________
                            </td>  
                        </tr>
                    <tr>
                    <td colspan="4">
                            <hr color="black" >
                    </td>
                    </tr>
        
            </table> 
        </div>
        <table width="100%">
        <tr>
                <td width="20%">
                        <label class="control-label col-md-3">Comment:</label>
                </td>
                <td colspan="3">
                        <p>{{$row->comments2}}</p>
                </td>
        </tr>
        <tr>
                    
            <td width="20%">
                    <br>
                <label class="control-label col-md-12"><strong>Return Date:<strong></label>
            </td>
            <td width="40%">
                    <br>
                    <p>{{$row->return_schedule}}</p>
            </td>    
            
            <td width="20%">
                    <br>
                <label class="control-label col-md-12"><strong>Scheduled By:<strong></label>
            </td>
            <td>
                    <br>
                    <p>{{$row->returnlevel}}</p>
            </td>  
        </tr>
        </table>
    </div>
        <div class='panel-footer'>           
                <input type='submit' class='btn btn-primary' value='Save' name="savedata"/>
        </div>
    </form>
  </div>
@endsection
@push('bottom')
<script type="text/javascript">
function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);

    jQuery( document ).delegate('#image', 'change', function() {
    ext = jQuery(this).val().split('.').pop().toLowerCase();
    if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        resetFormElement(jQuery(this));
        window.alert('Not an image!');
    } else {
        var reader = new FileReader();
        var image_holder = jQuery("#"+jQuery(this).attr('class')+"-preview");
        image_holder.empty();

        reader.onload = function (e) {
            jQuery(image_holder).attr('src', e.target.result);
        }

        reader.readAsDataURL((this).files[0]);
        jQuery('#image_preview').slideDown();
        jQuery(this).slideUp();
    }
});

jQuery('#image_preview a').bind('click', function () {
    resetFormElement(jQuery('#image'));
    jQuery('#image').slideDown();
    jQuery(this).parent().slideUp();
    return false;
});

function resetFormElement(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

    </script>
@endpush