@extends('crudbooster::admin_template')
@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <div class='panel panel-default'>
    <div class='panel-heading'>
            <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <form>
                            <input type='submit' class='btn btn-primary' id="save" onclick="printDivision('printableArea')" value='Print as PDF'/>
                        </form>
                    </div>
            </div>        
    </div>
    <div class='panel-body'>    
        <div id="printableArea"> 
            <table width="100%">
                    <tr>
                        <td colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="33%">
                                        <img src="{{asset("$store_logos->store_logo")}}"  style="align:middle;width:155px;height:30px;">
                                    </td>
                                    <td>
                                        <h4 align="left" style="margin-top: 17px;"><strong>MERCHANDISING PULLOUT FORM</strong></h4> 
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
                            <p>{{$row->requestorlevel}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Created Date:</strong></label>
                        </td>
                        <td>
                            <p>{{date('Y-m-d', strtotime($row->created_at))}}</p>
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        <td colspan="1"> 
                            <label class="control-label col-md-12"><strong>Requested Date:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->pull_out_schedule_date}}</p>
                            
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>MP#:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->reference}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>ST#/Ref#:</strong></label>
                        </td>
                        <td>
                 

                            @if($row->st_number_pull_out == null)
                                    ____________________
                            @else 

                         					<p>{{$row->st_number_pull_out}}</p>
                         

                            @endif

                        </td>
                    </tr>  
                    @if($row->pullout_type == 'rma')
                    <tr style="font-size: 13px;">
                            <td width="20%" colspan="1">
                                <label class="control-label col-md-12"><strong>SROF#:<strong></label>
                            </td>
                            <td colspan="3">
                                <p>{{$row->srof_number}}</p>
                            </td>
                    </tr>
                    @endif
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout From:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->store_name}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Deliver To:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->stores_deliver_to}}</p>
                        </td>                        
                    </tr>   
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout Reason:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->reason_name}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout Via:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->path_name}}</p>
                        </td>                        
                    </tr>
                    @if($row->hand_carry_by != null)
                        <tr style="font-size: 13px;">
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>Hand Carried By:<strong></label>
                            </td>
                            <td width="40%">
                                @if($row->hand_carry_by == null)
                                    ____________________
                                @else
                                    <p>{{$row->hand_carry_by}}</p>
                                @endif 
                              
                            </td>                     
                        </tr>
                    @endif
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Approver:<strong></label>
                        </td>
                        <td width="40%">
                            @if($row->approverlevel == null)
                                ____________________
                            @else
                                <p>{{$row->approverlevel}}</p>
                            @endif 
                          
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Approved Date:</strong></label>
                        </td>
                        <td>
                            @if($row->approved_at_level1 == null)
                                ____________________
                            @else
                            <p>{{date('Y-m-d', strtotime($row->approved_at_level1))}}</p>
                            @endif 
                            
                            
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
                                <!--@if($row->pullout_type == "rma") 
                                <label class="control-label col-md-12"><strong>FILLED BY RETURN MERCHANDISE AUTHORIZATION</strong></label>
                                @else
                                    <label class="control-label col-md-12"><strong>FILLED BY SYSTEMS DATA MANAGEMENT</strong></label>
                                @endif-->
                                <label class="control-label col-md-12"><strong>FILLED BY SYSTEMS DATA MANAGEMENT</strong></label>
                        </td>      
                    </tr>
                    <tr style="font-size: 13px;">
                       <!-- @if($row->pullout_type == "rma") 
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>RMA Specialist:<strong></label>
                            </td>
                            <td width="40%">
                                <p>{{$row->simlevel}}</p>
                            </td>
                        @else
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>SDM Specialist:<strong></label>
                            </td>
                            <td width="40%">
                                <p>{{$row->simlevel}}</p>
                            </td>                       
                        @endif -->
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>SDM Specialist:<strong></label>
                        </td>
                        <td width="40%">
                            @if($row->simlevel == null)
                                    ____________________
                             @else 
                                <p>{{$row->simlevel}}</p>
                            @endif
                        </td>    

                        <td width="20%">
                            <label class="control-label col-md-12"><strong>SOR/MOR Date:</strong></label>
                        </td>
                        <td>
                            @if($row->approved_at_level2 == null)
                                ____________________
                            @else
                                <p>{{date('Y-m-d', strtotime($row->approved_at_level2))}}</p>
                            @endif 
                        </td>                        
                    </tr>
                    <tr style="font-size: 13px;">
                        <td colspan="1"> 
                            <label class="control-label col-md-12"><strong>SOR#/MOR#:</strong></label>
                        </td>
                        <td>
                            @if($row->sor_mor_number == null)
                                ____________________
                            @else 
                            <p>{{$row->sor_mor_number}}</p>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="4">
                            <br>
                            <table border="1" width="100%" style="text-align:center;border-collapse: collapse;">
                                <thead>
                                    <tr>
                                         <th style="text-align:center;font-size: 13px;" height="10">Digits Code</th>
                                         <th style="text-align:center;font-size: 13px;" height="10">UPC Code</th>
                                         <th style="text-align:center;font-size: 13px;" height="10">Item Description</th>
                                         <th style="text-align:center;font-size: 13px;" height="10">Brand</th>
                                         <th style="text-align:center;font-size: 13px;" height="10">WH Category</th>
                                         <th style="text-align:center;font-size: 13px;" height="10">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultlist as $rowresult)
                                        <tr>
                                            <td height="10" style="font-size: 13px;">{{$rowresult->digits_code}}</td>
                                            <td height="10" style="text-align:left !important;font-size: 13px;">{{$rowresult->upc_code}}</td>
                                            <td height="10" style="text-align:left !important;font-size: 13px;">{{$rowresult->item_description}}</td>
                                            <td height="10" style="text-align:left !important;font-size: 13px;">{{$rowresult->brand}}</td>
                                            <td height="10" style="text-align:left !important;font-size: 13px;">{{$rowresult->category}}</td>
                                            <td height="10" style="font-size: 13px;">{{$rowresult->quantity}}</td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            <td style="text-align:right;font-size: 13px;" height="10" colspan="5"><label>Total Quantity:</label></td>
                                            <td style="text-align:center;font-size: 13px;" height="10"><p>{{$row->total_quantity}}</p></td>    
                                        </tr>
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                    @if($row->path_name == "LOGISTICS")
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
                            
                            @if($row->approved_at_level3 == null)
                                ____________________
                            @else
                            <p>{{date('Y-m-d', strtotime($row->approved_at_level3))}}</p>
                            @endif 
                         
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Scheduled By:</strong></label>
                        </td>
                        <td>
                            @if($row->logiscticslevel == null)
                                 ____________________
                            @else
                                <p>{{$row->logiscticslevel}}</p>
                            @endif 
                            
                        </td>                        
                    </tr>
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            
                        </td>
                        <td width="40%">
          
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received By:</strong></label>
                        </td>
                        <td>
                               @if($row->logistics_personnel_received_by == null)
                                    ____________________
                               @else
                                   <p>{{$row->logistics_personnel_received_by}}</p>
                               @endif   

                        </td>                        
                    </tr>
                    @if($row->logistics_personnel_received_by == null)
                    <tr style="font-size: 13px;">
                            <td width="20%">
               
                            </td>
                            <td width="40%">
     
                            </td>
                            <td width="20%">
                              
                            </td>
                            <td>
                                <p style="font-size: 7px;">&nbsp;&nbsp;&nbsp;&nbsp;(SIGNATURE OVER PRINTED NAME)</p>
                            </td>
                    </tr>
                    @endif
                    @endif
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
                            @if($row->released_date == null)
                                ____________________
                            @else
                            <p>{{$row->released_date}}</p>
                            @endif
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Released By:</strong></label>
                        </td>
                        <td>
                            @if($row->released_by == null)
                                ____________________
                            @else
                            <p>{{$row->released_by}}</p>
                            @endif 
                        </td>
                    </tr>
                    @if($row->released_by == null)
                    <tr style="font-size: 13px;">
                            <td width="20%">
               
                            </td>
                            <td width="40%">
     
                            </td>
                            <td width="20%">
                              
                            </td>
                            <td>
                                <p style="font-size: 7px;">&nbsp;&nbsp;&nbsp;&nbsp;(SIGNATURE OVER PRINTED NAME)</p>
                            </td>
                    </tr>
                    @endif
                    @if($row->transaction_type_name == "STORE TO STORE") 
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                        </td>
                        <td width="40%">
                                @if($row->received_at == null)
                                    ____________________
                                @else
                                    <p>{{ $row->received_at != null ? date('Y-m-d', strtotime($row->received_at)): "" }}</p>
                                @endif 
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received By:</strong></label>
                        </td>
                        <td>
                            @if($row->received_by == null)
                                ____________________
                            @else
                                <p>{{$row->received_by}}</p> 
                            @endif 
                        </td>
                    </tr>
                    @if($row->received_by == null)
                    <tr style="font-size: 13px;">
                            <td width="20%">
               
                            </td>
                            <td width="40%">
     
                            </td>
                            <td width="20%">
                              
                            </td>
                            <td>
                                <p style="font-size: 7px;">&nbsp;&nbsp;&nbsp;&nbsp;(SIGNATURE OVER PRINTED NAME)</p>
                            </td>
                    </tr>
                    @endif
                    @endif
                    @if($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                    <tr>
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY WAREHOUSE</strong></label>
                        </td>        
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>WRF#:<strong></label>
                        </td>
                        <td width="40%">
                            @if($row->wrf_number == null)
                                ____________________
                            @else
                                <p>{{$row->wrf_number}}</p>
                            @endif 
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Scanned By:</strong></label>
                        </td>
                        <td>
                            @if($row->scanned_by == null)
                                ____________________
                            @else
                                <p>{{$row->scanned_by}}</p>
                            @endif 
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>WRF Date:</strong></label> 
                        </td>
                        <td colspan="3">
                                @if($row->wrf_date == null)
                                    ____________________
                                @else
                                    <p>{{ $row->wrf_date != null ? date('Y-m-d', strtotime($row->wrf_date)) : ""}}</p>
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
                              
                                    <label class="control-label col-md-12"><strong>FILLED BY W-SDM</strong></label>
                                
                        </td>     
                    </tr>
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                        </td>
                        <td width="40%">
                                @if($row->received_at == null)
                                    ____________________
                                @else
                                    <p>{{ $row->received_at != null ? date('Y-m-d', strtotime($row->received_at)): "" }}</p>
                                @endif 
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received By:</strong></label>
                        </td>
                        <td>
                            @if($row->received_by == null)
                                ____________________
                            @else
                                <p>{{$row->received_by}}</p> 
                            @endif 
                        </td>
                    </tr> 
                    @endif
                    @if($row->pullout_status == "CANCELLED") 
                    <tr>
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr> 
                    <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY IC PERSONNEL</strong></label>
                    </td>     
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout Status:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->pullout_status}}</p> 
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Cancelled By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->iclevel}}</p> 
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="20%" colspan="1">
                            <label class="control-label col-md-12"><strong>Cancelled At:<strong></label>
                        </td>
                        <td width="40%" colspan="3">
                            <p>{{$row->cancelled_at}}</p> 
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="20%" colspan="1">
                            <label class="control-label col-md-12"><strong>Comment:<strong></label>
                        </td>
                        <td colspan="3">
                            <p>{{$row->comments3}}</p> 
                        </td>
                    </tr> 
                    @endif
                    @if($row->revise_at != null) 
                    <tr>
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr> 
                    <td colspan="4">
                            <label class="control-label col-md-12"><strong>FILLED BY IC PERSONNEL</strong></label>
                    </td>  
                    <tr style="font-size: 13px;">
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>Revised ST#/Ref#:<strong></label>
                            </td>
                            <td width="40%">
                                <p>{{$row->revise_st_number_pull_out}}</p> 
                            </td>
                            <td width="20%">
                                <label class="control-label col-md-12"><strong>Revised SOR#/MOR#:</strong></label>
                            </td>
                            <td>
                                <p>{{$row->revise_sor_mor_number}}</p> 
                            </td>
                    </tr>    
                    <tr style="font-size: 13px;">
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Revise By:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->iclevelrevise}}</p> 
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Revise Date:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->revise_at}}</p> 
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="20%" colspan="1">
                            <label class="control-label col-md-12"><strong>Comment:<strong></label>
                        </td>
                        <td colspan="3">
                            <p>{{$row->comments4}}</p> 
                        </td>
                    </tr> 
                    @endif
                    <tr>
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr> 
                <table border="1" width="100%" style="border-collapse: collapse;font-size: 10px;">
                            <thead>
                                    <tr>
                                         <th style="text-align:center" height="10" width="20%">Policy</th>
                                         <th style="text-align:center" height="10">SCENARIO</th>
                                    </tr>
                            </thead>
                            <tbody>                                       
                                        <tr>
                                                <td height="10" style="text-align:center" >NO PULLOUT FORM, NO PULLOUT</td>
                                                <td height="10" style="text-align:justify" >If the Logistics personnel picks up the pullout without the MPF (pullout form), the store personnel shall reject the pullout.</td>
                                        </tr>
                                        <tr>
                                                <td height="10" style="text-align:center" >NO MATCH, NO PULLOUT</td>
                                                <td height="10" style="text-align:justify" >If the contents of the MPF does not match the physical items' barcodes, the Logistics personnel shall reject the pullout.</td>
                                        </tr>
                                        <tr>
                                                <td height="10" style="text-align:center" >NO PACKAGING, NO PULLOUT</td>
                                                <td height="10" style="text-align:justify" >If an item has no packaging, it may not be pulled out, unless it is accompanied with a memo signed by the SBU head.</td>
                                        </tr>
                                        <tr>
                                                <td height="10" style="text-align:center" >NO ITEM, NO PULLOUT</td>
                                                <td height="10" style="text-align:justify" >If the package has no item inside, the Logistics personnel shall reject the pullout.</td>
                                        </tr>
                            </tbody>
                </table>                                   
            </table>  
    </div>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript"> 
        function printDivision(divName) {
            alert('Please print 3 copies!');
            var generator = window.open(",'printableArea,");
            var layertext = document.getElementById(divName);
            generator.document.write(layertext.innerHTML.replace("Print Me"));
            generator.document.close();
            generator.print();
            generator.close();

        }                
    </script>
@endpush