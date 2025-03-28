
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
                                       <!-- <img src="{{asset("img/logo-dw.png")}}"  style="align:middle;width:155px;height:30px;"> -->
                                    </td>
                                    <td>
                                        <h4 align="left"  style="margin-top: 17px; margin-left:-55px;"><strong>CUSTOMER REFUND FORM</strong></h4> 
                                    </td>
                                </tr>     
                            </table>   
                            <hr color="black" >
                        </td>
                    </tr>
        
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Return Reference#:<strong></label>
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
                    <!--
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout From:<strong></label>
                        </td>
                        <td width="40%">
                           <p>WAREHOUSE.RMA.DEP</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Deliver To:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->store}}</p>
                        </td>                        
                    </tr>   -->
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Diagnosed Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{ $row->level3_personnel_edited != null ? date('m-d-Y', strtotime($row->level3_personnel_edited)): "" }}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Diagnosed By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->rma_person}}</p>
                        </td>                        
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Warranty Status:<strong></label>
                        </td>
                        <td width="40%">
                            <p><label style="color:red">{{$row->diagnose}}</label></p>
                        </td>    
                        
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Customer Name:<strong></label>
                        </td>
                        <td>
                            <p>{{$row->customer_first_name}} {{$row->customer_last_name}}</p>
                        </td>  
                    </tr>

                    <tr style="font-size: 13px;">
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>Customer Location:<strong></label>
                            </td>
                            <td width="40%">
                                <p>{{$row->customer_location}}</p>
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
                                         <th style="text-align:center" height="10">Item Cost</th>
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
                                            <td height="10">{{$rowresult->cost}}</td>
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
                    <tr>
                            <td colspan="4">
                                    <hr color="black" >
                            </td>
                    </tr>
                    <tr >
                            <td width="20%" colspan="4">

                                    <label class="control-label col-md-12"><strong>BANK DETAILS</strong></label>
                                    <br/>
                            </td>
                    </tr>
                    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Mode of Refund:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->mode_of_refund}}</p>
                        </td>    
                    
                        
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Bank Name:<strong></label>
                        </td>
                        <td>
                           @if($row->bank_name != null || $row->bank_name != "")
                                             <p>{{$row->bank_name}}</p>
                                    @else
                                            ____________________
                           @endif
                               
                        </td>  

                    </tr>

                    <tr style="font-size: 13px;">
                        <td width="20%">
                          
                            <label class="control-label col-md-12"><strong>Bank Account Name:<strong></label>
                        </td>
                        <td width="40%">
                           
                               
                            @if($row->bank_account_name != null || $row->bank_account_name != "")
                                             <p>{{$row->bank_account_name}}</p>
                                    @else
                                            ____________________
                           @endif
                        </td>                      
   
                        <td width="20%">
                             
                            <label class="control-label col-md-12"><strong>Bank Account#:<strong></label>
                        </td>
                        <td>
                           
                                
                            @if($row->bank_account_no != null || $row->bank_account_no != "")
                                             <p>{{$row->bank_account_no}}</p>
                                    @else
                                            ____________________
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
                        <p>{{$row->diagnose_comments}}</p>
                </td>
        </tr>
        </table>
  </div>
  <div class='panel-footer'>
    <form method='' id="myform" action="">
        
        <input type="hidden" value="{{$row->id}}" name="return_id">
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        
        @if($row->returns_status_1 == 7)
                <button class="btn btn-primary pull-right" type="submit" id="printCRF" onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print as PDF</button>
            @else
                <button class="btn btn-primary pull-right" type="submit" id="print"    onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print as PDF</button>
        @endif
       
    </form>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        $("#printCRF").on('click',function(){
        //var strconfirm = confirm("Are you sure you want to approve this pull-out request?");
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/returns_crf/CRFUpdateStatus') }}',
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