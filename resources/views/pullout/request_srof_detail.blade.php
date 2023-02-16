@extends('crudbooster::admin_template')
@section('content')
@push('head')
<style type="text/css">  
* {box-sizing: border-box}
.mySlides1, .mySlides2 {display: none}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 800px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: black;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a grey background color */
.prev:hover, .next:hover {
  background-color: #f1f1f1;
  color: black;
}
</style>
@endpush
<!-- link -->
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <div class='panel panel-default'>
    <div class='panel-heading'>
            <div class="row">
                    <div class="col-sm-12 col-xs-12">
                            <form method='' id="myform" action="">
                                 <input type='submit' class='btn btn-primary' id="print" name="print" onclick="printDivision('printableArea')" value='Print as PDF'/>   
                            </form>
                    </div>
            </div>     
    </div>
    <div class='panel-body'>    
        <div id="printableArea"> 
            <table width="100%">
                    <tr>
                        <td colspan="6">
                            <table width="100%">
                                <tr>
                                    <td width="33%">
                                        <img src="{{asset("$store_logos->store_logo")}}"  style="align:middle;width:155px;height:30px;">
                                    </td>
                                    <td>
                                        <h4 align="left" style="margin-top: 17px;"><strong>SERVICE REPAIR ORDER FORM</strong></h4> 
                                    </td>
                                </tr>     
                            </table>   
                            <hr color="black" >
                        </td>
                    </tr>

                    <tr style="font-size: 13px;">
                        <td width="15%">
                                <label class="control-label col-md-12"><strong>SROF#:</strong></label>
                        </td>
                        <td width="20%">
                                <p>{{$row->srof_number}}</p>
                        </td>
                        <td width="15%">

                        </td>
                        <td width="15%">

                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>ST#:</strong></label>
                        </td>
                        <td width="20%">
                             @if($totals->st_number_pull_out == null)
                                ____________
                            @else
                                    @if($totals->revise_st_number_pull_out != null)
            
                                            <p>{{$totals->revise_st_number_pull_out}}</p>
                        			@else
                        
                         					<p>{{$totals->st_number_pull_out}}</p>
                         
                        			@endif
                            @endif
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                                <label class="control-label col-md-12"><strong>Store Name:</strong></label>
                        </td>
                        <td colspan="5">
                                @if($totals->store_name == null)
                                ____________
                                @else
                                <p>{{$totals->store_name}}</p>
                                @endif 
                        </td>
                    </tr> 
                    <tr>
                        <td colspan="6">
                            <hr color="black" >
                        </td>
                    </tr> 
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16" || $totals->reasons_id == "24")
                    <tr>
                        <td colspan="6">
                            <label class="control-label col-md-12"><strong>CUSTOMER INFORMATION</strong></label>
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>First Name:<strong></label>
                        </td>
                        <td width="20%">
                            <p>{{$row->first_name}}</p>
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Middle Initial:</strong></label>
                        </td>
                        <td width="15%">
                            @if($row->middle_initial == null)
                            ____________
                            @else
                            <p>{{$row->middle_initial}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Last Name:</strong></label>
                        </td>
                        <td width="20%">
                            <p>{{$row->last_name}}</p>
                        </td>
                    </tr>   
                    
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                            <label class="control-label col-md-12"><strong>Address:<strong></label>
                        </td>
                        <td colspan="3">
                            @if($row->address == null)
                            ____________
                            @else
                            <p>{{$row->address}}</p>
                            @endif 
                        </td>
                        <td colspan="1">
                            <label class="control-label col-md-12"><strong>Email Address:</strong></label>
                        </td>
                        <td colspan="1">
                            @if($row->email_address == null)
                            ____________
                            @else
                            <p>{{$row->email_address}}</p>
                            @endif 
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Work#:<strong></label>
                        </td>
                        <td width="20%">
                            @if($row->work_number == null)
                            ____________
                            @else
                            <p>{{$row->work_number}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Home#:</strong></label>
                        </td>
                        <td width="15%">
                            @if($row->home_number == null)
                            ____________
                            @else
                            <p>{{$row->home_number}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Mobile#:</strong></label>
                        </td>
                        <td width="20%">
                            @if($row->mobile_number == null)
                            ____________
                            @else
                            <p>{{$row->mobile_number}}</p>
                            @endif 
                        </td>
                    </tr> 
                    <tr style="font-size: 13px;">
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Date Received:<strong></label>
                        </td>
                        <td width="20%">
                            @if($row->date_received == null)
                            ____________
                            @else
                            <p>{{ $row->date_received != null ? date('Y-m-d', strtotime($row->date_received)) : ""}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Time Received:</strong></label>
                        </td>
                        <td width="15%">
                            @if($row->time_received == null)
                            ____________
                            @else
                            <p>{{date('g:i A', strtotime($row->time_received))}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Company:</strong></label>
                        </td>
                        <td width="20%">
                            @if($row->company_store == null)
                                ____________
                            @else
                            <p>{{$row->company_store}}</p>
                            @endif 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <hr color="black" >
                        </td>
                    </tr>  
                    @endif
                    <tr>
                        <td colspan="6">
                            <label class="control-label col-md-12"><strong>UNIT INFORMATION</strong></label>
                        </td>
                    </tr>
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16" ||  $totals->reasons_id == "24")
                    <tr style="font-size: 13px;">
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Date Purchased:<strong></label>
                        </td>
                        <td width="20%">
                            @if($row->date_purchased == null)
                            ____________
                            @else
                            <p>{{$row->date_purchased}}</p>
                            @endif 
                        </td>
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Invoice#:</strong></label>
                        </td>
                        <td width="15%">
                            @if($row->rs_invoice_number == null)
                            ____________
                            @else
                            <p>{{$row->rs_invoice_number}}</p>
                            @endif 
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="6">
                            <br>
                            <table border="1" width="100%" style="text-align:center;border-collapse: collapse;">
                                <thead>
                                    <tr style="font-size: 12px;">
                                         <th style="text-align:center" height="10">Digits Code</th>
                                         <th style="text-align:center" height="10">Item Description</th>
                                         <th style="text-align:center" height="10">Problem Details</th>
                                         <!--<th style="text-align:center" height="10">Problem Details Others</th>-->
                                         <th style="text-align:center" height="10">Serial Number</th>
                                         <th style="text-align:center" height="10">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resultlist as $rowresult)
                                        <tr style="font-size: 11px;">
                                            <td height="10">{{$rowresult->digits_code}}</td>
                                            <td height="10" style="text-align:left !important;">{{$rowresult->item_description}}</td>
                                            <td height="10" style="text-align:center !important;">{{$rowresult->problem_details}}
                                                <br>
                                                        {{$rowresult->problem_details_other}}
                                            </td>
                                            <td height="10" style="text-align:left !important;">
                                                @if($rowresult->serialize == 1 || $rowresult->serialize == '1')
                                                        {{$rowresult->serial_number}}
                                                @endif
                                                
                                            </td>
                                            <td height="10">
                                                    @if($rowresult->serialize == 1 || $rowresult->serialize == '1')
                                                                    1
                                                        @else
                                                            {{$rowresult->quantity}}
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                        <tr style="font-size: 11px;">
                                            <td style="text-align:right" height="10" colspan="4"><label>Total Quantity:</label></td>
                                            <td style="text-align:center" height="10"><p>{{$totals->total_quantity}}</p></td>    
                                        </tr>
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                    @if($totals->reasons_id == "22" || $totals->reasons_id == "23")
                    <tr style="font-size: 13px;">
                        <td width="15%">
                            <label class="control-label col-md-12"><strong>Date Opened:<strong></label>
                        </td>
                        <td width="20%">
                            @if($row->created_at == null)
                            ____________
                            @else
                            <p>{{date('Y-m-d', strtotime($row->created_at))}}</p>
                            @endif 
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                        
                            <label class="control-label col-md-12"><strong>Additional Notes:<strong></label>
                        </td>
                        <td colspan="5">
                           
                            @if($totals->requestor_comments == null)
                            ____________
                            @else
                            <p>{{$totals->requestor_comments}}</p>
                            @endif 
                        </td>
                    </tr>
                    @endif
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16" || $totals->reasons_id == "24")
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                        
                            <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                        </td>
                        <td colspan="5">
                           
                            @if($row->items_included == null)
                            ____________
                            @else
                            <p>{{$row->items_included}}  @if($row->other_items_included != null) :  @endif  {{$row->other_items_included}}</p>
                            @endif 
                        </td>
                    </tr>  
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                        
                            <label class="control-label col-md-12"><strong>Additional Notes:<strong></label>
                        </td>
                        <td colspan="5">
                           
                            @if($totals->requestor_comments == null)
                            ____________
                            @else
                            <p>{{$totals->requestor_comments}}</p>
                            @endif 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <hr color="black" >
                        </td>
                    </tr>  
                    <tr>
                        <td colspan="6">
                            <label class="control-label col-md-12"><strong>SERVICE DETAILS</strong></label>
                        </td>
                    </tr>
                    <tr style="font-size: 13px;">
                            <td colspan="1">
                                <label class="control-label col-md-12"><strong>Action Taken:<strong></label>
                            </td>
                            <td colspan="2">
                                @if($row->action_taken_type == null)
                                ____________
                                @else
                                <p>{{$row->action_taken_type}} </p>
                                @endif 
                            </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <hr color="black" >
                        </td>
                    </tr>  
                    <tr>
                        <td colspan="6">
                            <br>
                            <table border="1" width="100%" style="border-collapse: collapse;">
                                <thead>
                                    <tr>
                                         <th style="text-align:center;font-size: 12px;" height="10" colspan="1">COMPANY GUIDELINES</th>

                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td height="10" style="text-align:left;font-size: 10px;" >
                                                @if($totals->rma_level == "level1")
                                                <p>
                                                    This will serve as the proof of the customer's return.<br>
                                                    The warranty of the product above is based on the original purchase date.
                                                </p>
                                                @else
                                                <p>
                                                    <b>1.Disclaimer Statement</b>
                                                    <br>
                                                    a. The company will not be held responsible for any loss of any data or inability to recover data.
                                                    <br>
                                                    b. Items received will be updated after 10 working days from turn over to Store.
                                                    <br>
                                                    <b>2.Checklist</b>
                                                    <br>
                                                    a. Customer must present Proof of Purchase.
                                                    <br>
                                                    b. The Company reserves the right to any changes in servicing policies without prior notice.
                                                   <br> 
                                                   <b>3.Collection</b>
                                                    <br>
                                                    a. All goods are collected for checking only. The Company reserves the right to reject any servicing, to repair and to replace.
                                                    <br>
                                                    b. The Company reserves the rights to dispose unclaimed items after 90 days from date of notification (verbal/ written).
                                                    <br>
                                                    c. The Company reserves the right to keep and properly dispose all replaced spare parts and defective items once processed.
                                                    <br>
                                                    d. The customer is prohibited to request for the replaced spare parts and defective items after repair or replacement for whatever purpose.
                                                    <br>
                                                    <b>4.Payment</b>
                                                    <br>
                                                    a. All service repair / replacement charges are payable in cash only upon pick up of item.
                                                </p>
                                                @endif
                                            </td>
                                        </tr>
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                    <tr>
                            <td colspan="6">
                                <hr color="black" >
                            </td>
                    </tr>  
                    <tr style="font-size: 13px;">
                         <td colspan="1">
                             <label class="control-label col-md-12"><strong>Prepared By:<strong></label>
                         </td>
                         <td colspan="3">
                             @if($row->prepared_by == null)
                             ____________
                             @else
                             <p>{{$row->prepared_by}}</p>
                             @endif 
                         </td>
                         <td colspan="1">
                             <label class="control-label col-md-12"><strong>Checked By:</strong></label>
                         </td>
                         <td colspan="1">
                             @if($row->checked_by == null)
                             ____________
                             @else
                             <p>{{$row->checked_by}}</p>
                             @endif 
                         </td>
                    </tr>
                    <tr style="font-size: 13px;">
                         <td colspan="1">
                             <label class="control-label col-md-12"><strong>Received By:</strong></label>
                         </td>
                         <td colspan="5">
                             @if($row->received_by == null)
                             ____________
                             @else
                             <p>{{$row->received_by}}</p>
                             @endif 
                         </td>
                    </tr>

                    
                    @endif
            </table>  
    </div>
    @if($totals->srof_image != NULL)
            @if($privilegename == "Approver" || $privilegename == "RMA" || $privilegename == "Logistics" || $privilegename == "Super Administrator" || $privilegename == "Admin")
                <hr color="black" >
                    <h5 style="text-align:left;margin-left: 17px;"><strong>ITEM IMAGES:</strong></h5>
                        <div class="slideshow-container">
                            @foreach(explode('|', $totals->srof_image) as $info)
                                <div class="mySlides1">
                                    <img src="{{asset("$info")}}" style="width:100%;height:400px">
                                </div>
                            @endforeach
                            
                            <a class="prev" onclick="plusSlides(-1, 0)">&#10094;</a>
                            <a class="next" onclick="plusSlides(1, 0)">&#10095;</a>
                        </div>
            @endif 
    @endif

  </div>
@endsection
@push('bottom')
    <script type="text/javascript">

        var slideIndex = [1,1];
        var slideId = ["mySlides1", "mySlides2"]
        showSlides(1, 0);
        showSlides(1, 1);

        function plusSlides(n, no) {
          showSlides(slideIndex[no] += n, no);
        }

        function showSlides(n, no) {
          var i;
          var x = document.getElementsByClassName(slideId[no]);
          if (n > x.length) {slideIndex[no] = 1}    
          if (n < 1) {slideIndex[no] = x.length}
          for (i = 0; i < x.length; i++) {
             x[i].style.display = "none";  
          }
          x[slideIndex[no]-1].style.display = "block";  
        }

        function printDivision(divName) {
            //alert('Please print 2 copies!');
            var generator = window.open(",'printableArea,");
            var layertext = document.getElementById(divName);
            generator.document.write(layertext.innerHTML.replace("Print Me"));
            generator.document.close();
            generator.print();
            generator.close();
        }                
    </script>
@endpush