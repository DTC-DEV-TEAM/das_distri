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
                                        <img src="{{asset('img/logo_digits.png')}}" style="align:middle;width:155px;height:25px;">
                                    </td>
                                    <td>
                                        <h4 align="left"><strong>MERCHANDISING PULLOUT FORM</strong></h4> 
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
                    <tr>
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
                            <p>{{date('m-d-Y', strtotime($row->created_at))}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"> 
                            <label class="control-label col-md-12"><strong>Requested Date:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->pull_out_schedule_date}}</p>
                            
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
                            <label class="control-label col-md-12"><strong>ST#/Ref#:</strong></label>
                        </td>
                        <td>
                                    @if($row->revise_st_number_pull_out != null)
            
                                                    <p>{{$row->revise_st_number_pull_out}}</p>
                        			@else
                        
                         					<p>{{$row->st_number_pull_out}}</p>
                         
                        			@endif
                        </td>
                    </tr>  
                    @if($row->pullout_type == 'rma')
                    <tr>
                            <td width="20%" colspan="1">
                                <label class="control-label col-md-12"><strong>SROF#:<strong></label>
                            </td>
                            <td colspan="3">
                                <p>{{$row->srof_number}}</p>
                            </td>
                    </tr>
                    @endif
                    <tr>
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
                    <tr>
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
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Approver:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->approverlevel}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Approved Date:</strong></label>
                        </td>
                        <td>
                            <p>{{date('m-d-Y', strtotime($row->approved_at_level1))}}</p>
                        </td>                        
                    </tr>
                    @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                    <tr>
                        <td colspan="4">
                            <hr color="black" >
                        </td>
                    </tr> 
                    <tr>
                        <td colspan="4">
                                <label class="control-label col-md-12"><strong>FILLED BY SYSTEMS DATA MANAGEMENT</strong></label>
                        </td>      
                    </tr>
                    <tr>
                        @if ($row->pullout_type == "rma") 
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
                        @endif
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>SOR/MOR Date:</strong></label>
                        </td>
                        <td>
                            <p>{{date('m-d-Y', strtotime($row->approved_at_level2))}}</p>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="1"> 
                            <label class="control-label col-md-12"><strong>SOR#/MOR#:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->sor_mor_number}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <br>
                            <table border="1" width="100%" style="text-align:center;border-collapse: collapse;font-size: 14px;">
                                <thead>
                                    <tr>
                                         <th style="text-align:center" height="10">Digits Code</th>
                                         <th style="text-align:center" height="10">UPC Code</th>
                                         <th style="text-align:center" height="10">Item Description</th>
                                         <th style="text-align:center" height="10">Brand</th>
                                         <th style="text-align:center" height="10">WH Category</th>
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
                                            <td height="10">{{$rowresult->category}}</td>
                                            <td height="10">{{$rowresult->quantity}}</td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            <td style="text-align:right" height="10" colspan="5"><label>Total Quantity:</label></td>
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
                            <label class="control-label col-md-12"><strong>FILLED BY LOGISTICS</strong></label>
                        </td>                           
                    </tr>
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Pullout Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{date('m-d-Y', strtotime($row->approved_at_level3))}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Approved By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->logiscticslevel}}</p>
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
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Released Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->released_date}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Released By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->released_by}}</p>
                        </td>
                    </tr>  
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
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>WRF#:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{$row->wrf_number}}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Scanned By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->scanned_by}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>WRF Date:</strong></label> 
                        </td>
                        <td colspan="3">
                            <p>{{ $row->wrf_date != null ? date('m-d-Y', strtotime($row->wrf_date)) : ""}}</p>
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
                    <tr>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{ $row->received_at != null ? date('m-d-Y', strtotime($row->received_at)): "" }}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Received By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->received_by}}</p> 
                        </td>
                    </tr>                                    
            </table>  
    </div>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        function printDivision(divName) {
         var generator = window.open(",'printableArea,");
         var layertext = document.getElementById(divName);
         generator.document.write(layertext.innerHTML.replace("Print Me"));
         generator.document.close();
         generator.print();
         generator.close();
        }                
    </script>
@endpush