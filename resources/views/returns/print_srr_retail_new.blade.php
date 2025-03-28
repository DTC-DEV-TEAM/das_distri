
@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
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
        Print Form
    </div>
    <div class='panel-body'>    
        <div id="printableArea"> 
            <table width="100%">
                    <tr>
                        <td colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="40%">
                                        <!-- <img src="{{asset("$store_logos->store_logo")}}"  style="align:middle;width:155px;height:30px;"> -->
                                        <img src="{{asset("img/logo-dw.png")}}"  style="align:middle;width:155px;height:30px;">
                                    </td>
                                    <td>
                                        
                                            <h4 align="left"  style="margin-top: 17px; margin-left:35px;"><strong>PULLOUT FORM</strong></h4> 
                                        
                                    </td>
                                </tr>     
                            </table>   
                            <hr color="black" >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY REQUESTOR</strong></label>
                        </td>
                    </tr>


                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Return Reference#:</strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->return_reference_no}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Order#:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->order_no}}</p>
                        </td>
                    </tr>  

                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout From:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$store_deliver_to->store_name}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Deliver To:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->deliver_to}}</p>
                        </td>                        
                    </tr>  

                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Purchase Date:</strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->purchase_date}}</p>
                        </td>
                        
                        
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Mode of Return:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->mode_of_return}}</p>
                        </td>  
                    </tr>  
                    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Created Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{date('Y-m-d', strtotime($row->created_at))}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong></strong></label>
                        </td>
                        <td>
                            <p></p>
                        </td>
                    </tr>  
                    
                    
<!--
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Purchase Location:</strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->purchase_location}}</p>
                        </td>
                    </tr>   -->
                    <!--
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>SOR#:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->sor_number}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Customer Location:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->customer_location}}</p>
                        </td>
                    </tr> -->
                    <!-- -->                                     
 


                    <tr>
                    <!--
                    <tr style="font-size: 13px;">
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>Customer Name:<strong></label>
                            </td>
                            <td width="40%">
                                <p>{{$row->customer_name}}</p>
                            </td>    
                            
   
                        </tr>
                    <tr>
                    -->
                    <td colspan="4">
                            <hr color="black" >
                    </td>
                    </tr>
                    
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Customer Location:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->customer_location}}</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="4">
                           
                            <label class="control-label col-md-12"><strong>RETURN ITEMS</strong></label>
                            <br/>
                            <table border="0" width="100%" style="text-align:center;border-collapse: collapse;font-size: 13px;">
                                <thead>
                                    <tr>
                                         <th style="text-align:center" height="10">Digits Code</th>
                                         <th style="text-align:center" height="10">UPC Code</th>
                                         <th style="text-align:center" height="10">Item Description</th>
                                         <th style="text-align:center" height="10">Brand</th>
                                         <th style="text-align:center" height="10">Serial#</th>
                                        <!-- <th style="text-align:center" height="10">Item Cost</th> -->
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
                                            <td height="10">{{$rowresult->serial_number}}</td>
                                           <!--  <td height="10">{{$rowresult->cost}}</td> -->
                                          <!--  <td height="10">{{$rowresult->category}}</td> -->
                                            <td height="10">{{$rowresult->quantity}}</td>
                                        </tr>
                                    @endforeach
                                        <!--<tr>
                                            <td style="text-align:right" height="10" colspan="6"><label style="margin-right: 8px;"><strong>Total Quantity:</strong></label></td>
                                            <td style="text-align:center" height="10"><p>{{$row->total_quantity}}</p></td>    
                                        </tr> -->
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        @foreach($resultlist as $rowresult)
                        <td width="20%">
                            <br>
                            <label class="control-label col-md-12"><strong>Problem Details:<strong></label>
                        </td>

                        <td width="40%">
                         
                           
                            
                                        @if($rowresult->problem_details_other  != null)
                                                <p style="margin-left:-25px; margin-top:20px;">{{$rowresult->problem_details}}, {{$rowresult->problem_details_other}}</p>
                                            @else
                                                 <p style="margin-left:-25px; margin-top:20px;">{{$rowresult->problem_details}}</p>
                                        @endif
                         </td>
                         @endforeach


                        @if($row->verified_items_included != null || $row->verified_items_included != "")
    
                                    <td width="20%">
                                         <br>
                                        <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                                    </td>
                                    <td>
                          
                                        
                                        @if($row->verified_items_included_others  != null)
                                                <p style="margin-left:-25px; margin-top:20px;">{{$row->verified_items_included}}, {{$row->verified_items_included_others}}</p>
                                            @else
                                                <p style="margin-left:-25px; margin-top:20px;">{{$row->verified_items_included}}</p>
                                        @endif
                                    </td>           
                                @else
                                    <td width="20%">
                                         <br>
                                        <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                                    </td>
                                    <td>
                          
                                        
                                        @if($row->items_included_others  != null)
                                                <p style="margin-left:-25px; margin-top:20px;">{{$row->items_included}}, {{$row->items_included_others}}</p>
                                            @else
                                                <p style="margin-left:-25px; margin-top:20px;">{{$row->items_included}}</p>
                                        @endif
                                    </td>              
                        
                        @endif




                    </tr>

                 
        
                    
                    <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY LOGISTICS</strong></label>
                        </td>                           
                    </tr>
                    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                          
                            <label class="control-label col-md-12"><strong>Pullout Date:<strong></label>
                        </td>
                        <td width="40%">
                                @if($row->return_schedule	 == null)
                                    ____________________
                                @else 
                                     <p>{{$row->return_schedule}}</p>
                                @endif   
                           

                        </td>                      
   
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Scheduled By:<strong></label>
                        </td>
                        <td>
                                @if($row->scheduled_by	 == null)
                                    ____________________
                                @else 
                                     <p>{{$row->scheduled_by}}</p>
                                @endif   

                        </td>  
                    </tr> 


                    <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                    </tr>


                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY STORE PERSONNEL</strong></label>
                        </td>                           
                    </tr>
    
    
    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                          
                            <label class="control-label col-md-12"><strong>Released Date:<strong></label>
                        </td>
                        <td width="40%">
                           
       
                                    ____________________
                    
                        </td>                      
    
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Released By:<strong></label>
                        </td>
                        <td>
                      
                                    ____________________
           
                        </td>  
                    </tr> 
    
                        <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                        </tr>
                        
                        
                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY CUSTOMER</strong></label>
                        </td>                           
                    </tr>
    
    
    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                          
                            <label class="control-label col-md-12"><strong>Customer:<strong></label>
                        </td>
                        <td width="40%">
                           
                                
                            <p>{{$row->customer_first_name}} {{$row->customer_last_name}}</p>        
                    
                        </td>                      
    
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Customer Signature:<strong></label>
                        </td>
                        <td>
                      
                                    ____________________
           
                        </td>  
                    </tr> 
    
                        <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                        </tr>    
                        

                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY RMA</strong></label>
                        </td>                           
                    </tr>



                    <tr style="font-size: 13px;">
                        <td width="20%">
                          
                            <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                        </td>
                        <td width="40%">
                           
                                @if($row->level3_personnel_edited	 == null)
                                    ____________________
                                @else 
                                    <p>{{ $row->level3_personnel_edited != null ? date('Y-m-d', strtotime($row->level2_personnel_edited)) : ""}}</p>
                                @endif
                        </td>                      
   
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Received By:<strong></label>
                        </td>
                        <td>
                                @if($row->diagnosed_by	 == null)
                                    ____________________
                                @else 
                                    <p>{{ $row->diagnosed_by }}</p>
                                @endif
                        </td>  
                    </tr> 


                    <tr>
                        <td colspan="4">
                                <hr color="black" >
                        </td>
                </tr>




                    <!--
                    <tr>
                            <td colspan="4">
                                    <label class="control-label col-md-12"><strong>FILLED BY CUSTOMER<strong></label>
                                    <br/>
                            </td>
                    </tr>
                    <tr style="font-size: 13px;">
                    
                            <td width="20%">
                                   
                                <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                            </td>
                            <td width="40%">
                              
                                    ____________________
                            </td>    
                            
                            <td width="20%">
                                
                                <label class="control-label col-md-12"><strong>Received By:<strong></label>
                            </td>
                            <td>
                            
                                    ____________________
                            </td>  
                    </tr> 
                  
                    <tr>
                    <td colspan="4">
                            <hr color="black" >
                    </td>
                    </tr>
                    -->
        
            </table> 
        </div>
        <table width="100%">
        <tr>
                <td width="20%">
                        <label class="control-label col-md-3">Comment:</label>
                </td>
                <td colspan="3">
                        <p>{{$row->comments}}</p>
                </td>
        </tr>
        </table>
  </div>
  <div class='panel-footer'>
    <form method='GET' id="myform" action="{{route('ReturnsSRRUpdateRTL')}}" enctype="multipart/form-data">
        
        <input type="hidden" value="{{$row->id}}" name="return_id">
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        
        <button class="btn btn-primary pull-right" type="submit" style="margin-left: 10px" disabled id="btnSubmit" > <i class="fa fa-check" ></i> Proceed</button>
        <button class="btn btn-primary pull-right" type="button" onclick="printDivision('printableArea', event)" > <i class="fa fa-print"></i> Print as PDF</button>
       
    </form>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        $("#btnSubmit").click(function(event) {
        if (document.querySelector("#myform").reportValidity()) {
                event.preventDefault(); 
                swal({
                    title: "Are you sure you want to approve this pull-out request?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#367fa9",
                    confirmButtonText: "Yes, proceed",
                    width: 450,
                    height: 200
                    }, function () {               
                        $('#myform').submit();
                });
            } else {
                event.preventDefault(); 
             }

        })


        function printDivision(divName ,event) {
            event.preventDefault();
            alert('Please print 2 copies!');
            var generator = window.open(",'printableArea,");
            var layertext = document.getElementById(divName);
            generator.document.write(layertext.innerHTML.replace("Print Me"));
            generator.document.close();
            generator.print();
            generator.close();

            swal({
            title: "Did you print/save the file?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#367fa9",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
            },
            function(isConfirm){
            if (isConfirm) {
                document.querySelector('.btn-primary[type="submit"]').removeAttribute('disabled');
            } else {
                document.querySelector('.btn-primary[type="submit"]').setAttribute('disabled', 'disabled');
            }
            });
            
        }               
    </script>
@endpush