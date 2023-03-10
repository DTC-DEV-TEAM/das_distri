
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
                            <label class="control-label col-md-12"><strong>Requestor:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->customer_first_name}} {{$row->customer_last_name}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Created Date:</strong></label>
                        </td>
                        <td>
                            <p>{{date('Y-m-d', strtotime($row->created_at))}}</p>
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
                            <label class="control-label col-md-12"><strong>Purchase Date:</strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->purchase_date}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Mode of Payment:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->mode_of_payment}}</p>
                        </td>
                    </tr>  

                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Purchase Location:</strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->purchase_location}}</p>
                        </td>
                        
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Mode of Return:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->mode_of_return}}</p>
                        </td>  
                    </tr>  
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
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout From:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->customer_location}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Deliver To:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->deliver_to}}</p>
                        </td>                        
                    </tr>   
                    
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
                                          <!--   <th style="text-align:center" height="10">Item Cost</th> -->
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
                                           <!-- <td height="10">{{$rowresult->cost}}</td> -->
                                          <!--  <td height="10">{{$rowresult->category}}</td> -->
                                            <td height="10">{{$rowresult->quantity}}</td>
                                        </tr>
                                    @endforeach

                               
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



                        @if($row->mode_of_return =="STORE DROP-OFF")
                                    <td width="20%">
                                            <br>
                                        <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                                    </td>
                                    <td>
                                        <p style="margin-left:-25px; margin-top:20px;">{{$row->verified_items_included}}</p>
                                    </td>   
                                @else
                                    <td width="20%">
                                            <br>
                                        <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                                    </td>
                                    <td>
                                        <p style="margin-left:-25px; margin-top:20px;">{{$row->items_included}}</p>
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
                                @if($row->pickup_schedule	 == null)
                                    ____________________
                                @else 
                                     <p>{{$row->pickup_schedule}}</p>
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
    <form method='' id="myform" action="">
        
        <input type="hidden" value="{{$row->id}}" name="return_id">
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        
        @if( $row->returns_status_1 == 19 )
                <button class="btn btn-primary pull-right" type="submit" id="printPulloutForm" onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print as PDF</button>
            @else
                <button class="btn btn-primary pull-right" type="submit" id="print"    onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print as PDF</button>
        @endif
       
    </form>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        $("#printPulloutForm").on('click',function(){
        //var strconfirm = confirm("Are you sure you want to approve this pull-out request?");
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/scheduling/ReturnPulloutUpdateONLDTD') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );              
                        
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                  return true;
        });



        function printDivision(divName) {
         alert('Please print 2 copies!');
         var generator = window.open(",'printableArea,");
         var layertext = document.getElementById(divName);
         generator.document.write(layertext.innerHTML.replace("Print Me"));
         generator.document.close();
         generator.print();
         generator.close();
        }                
    </script>
@endpush