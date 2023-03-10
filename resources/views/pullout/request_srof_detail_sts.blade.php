@extends('crudbooster::admin_template')
@section('content')
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
                                    @if($row->store_name == null)
                                    ____________
                                    @else
                                    <p>{{$row->store_name}}</p>
                                    @endif 
                            </td>
                    </tr>  
                    <tr>
                        <td colspan="6">
                            <hr color="black" >
                        </td>
                    </tr> 
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16")
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
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16")
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
                            <table border="1" width="100%" style="text-align:center;border-collapse: collapse">
                                <thead>
                                    <tr style="font-size: 12px;">
                                         <th style="text-align:center;" height="10">Digits Code</th>
                                         <th style="text-align:center;" height="10">Item Description</th>
                                         <th style="text-align:center;" height="10">Problem Details</th>
                                         <!--<th style="text-align:center" height="10">Problem Details Others</th>-->
                                         <th style="text-align:center;" height="10">Serial Number</th>
                                         <th style="text-align:center;" height="10">Qty</th>
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
                    @if($totals->reasons_id == "3" || $totals->reasons_id == "16")
                    <tr style="font-size: 13px;">
                        <td colspan="1">
                        
                            <label class="control-label col-md-12"><strong>Items Included:<strong></label>
                        </td>
                        <td colspan="5">
                           
                            @if($row->items_included == null)
                            ____________
                            @else
                            <p>{{$row->items_included}} @if($row->other_items_included != null) :  @endif {{$row->other_items_included}}</p>
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
                                            <td height="10" style="text-align:justify;font-size: 7px;" >
                                                    <p>1. Services. Beyond the Box will service your Apple product as described and conformed to by you with the estimated charges shown on the Service Repair plus any applicable tax, unless such charges are revised with your prior oral or written consent. Unless otherwise stated, Beyond the Box will provide repair or replacement services to address a defect in the materials or workmanship of a product. Service is not available for issues caused by failure of or incompatibilities with any software or data residing or recorded on your product.</p>
                                                    <p>2. Coverage. When AppleCare service plan covers your product as described in the official Apple website or your valid proof of purchase from Beyond the Box or an Apple Authorized Reseller, Beyond the Box will service your product under its corresponding terms and conditions. If the Apple product is outside of warranty coverage, Beyond the Box will service your product in a competent and professional manner, and will provide you a cost estimate of any charges for labor, diagnostic, and parts subject to your approval prior to performing the service. You can check for the warranty coverage of your product from https://checkcoverage.apple.com.</p>
                                                    <p>3. Parts and Labor. In servicing your product, Beyond the Box may use parts or products that are new or refurbished and equivalent to new in performance and reliability, as authorized by Apple. Beyond the Box will retain and send back to Apple the replaced part or product that is exchanged during service, and the replacement part or product will become your property.</p>
                                                    <p>4. Service Exclusions and Diagnostic Fee. Beyond the Box may charge you a diagnostic fee as described in the Service Repair, if Beyond the Box inspects your product and determines that
                                                    (i) your product does not require service
                                                    (ii) your product has failed due to or has incompatibilities with software or data residing or recorded on your product
                                                    (iii) service is required due to the failure of parts that are neither supplied by Apple nor Apple-branded
                                                    (iv) additional labor or parts are required that were not specified in the original estimated charges and you do not agree to authorize service based on Beyond the Box’s revised estimated charges
                                                    (v) service cannot be performed because the serial number has been altered, defaced or removed, or the product has failed due to accident, abuse, liquid spill or submersion, neglect, misuse (including faulty installation, repair, or maintenance by anyone other than Beyond the Box or an Apple Authorized Service Provider), unauthorized modification, extreme environment (including extreme temperature or humidity), extreme physical or electrical stress or interference, fluctuation or surges of electrical power, lightning, static electricity, fire, acts of God, or other external causes (“Service Exclusions”). Beyond the Box will return your product to you without servicing it, and may charge you the Diagnostic Fee.</p>
                                                    <p>5. Customer’s Responsibility
                                                    <br>
                                                    a. Customer Data. It is your responsibility to backup all existing data, software, and programs, and to erase all existing data before receiving services. Beyond the Box is not responsible for loss, recovery, or compromise of data, programs or loss of use of equipment arising out of the services provided by Beyond the Box. You represent that your product does not contain illegal files or data.
                                                    <br>
                                                    b. Abandoned Property. Upon completion of the service, Beyond the Box will notify you immediately to settle the remaining fees and claim the Apple product at the same place where it was received. If no acknowledgement or response is received, Beyond the Box will attempt to contact you again using all details provided. If you do not provide an acknowledgement, settle the remaining fees, nor claim the product within sixty (60) days after the first notification, Beyond the Box will post a storage fee of Php700 for the next seven (7) days, and an additional Php250 per day thereafter – charged on top of your total amount due. Unless you provide alternative instructions, Beyond the Box will notify you that it considers your product to be abandoned when it reaches 120 days. Beyond the Box will send notice to all contact details you furnished when you authorized service. In the event that your product is abandoned, Beyond the Box may dispose of your product in accordance with applicable provisions of law, and, specifically, may sell your product at a private or public sale to pay for any outstanding service performed. Beyond the Box reserves its statutory and any other lawful liens for unpaid charges.
                                                    <br>
                                                    c. Information on Service. During the service process, you must provide a clear, concise description of the issue that is affecting your product, so that Beyond the Box understands and may replicate the issue. You must also provide full access to the product by disabling or resetting any security password. If you fail to give the necessary information, Beyond the Box may reset the security password and use the same for the repair, maintenance or upgrade of the product.
                                                    <br>
                                                    d. Disclosure of Unauthorized Modifications. During the service process, you must notify Beyond the Box of any unauthorized modifications, or any repairs or replacements not performed by Beyond the Box or an Apple Authorized Service Provider (“AASP”), that have been made to your product. Beyond the Box will not be responsible for any damage to the product that occurs during the service process that is a result of any unauthorized modifications or repairs or replacements not performed by Beyond the Box or an AASP. If damage results, Beyond the Box will seek your authorization for any additional costs for completing service even if the product is covered by warranty or an AppleCare service plan. If you decline authorization, Beyond the Box may return your product unrepaired in the damaged condition without any responsibility.</p>
                                                    <p>6. Prices and Payment. Beyond the Box may provide you with a written cost estimate on the onset of the service, or revise the cost estimates during the service process. Beyond the Box endeavors to offer you competitive prices on current Apple products and services. Unless specified otherwise, the estimated amount will include all parts, labor, and diagnostic fees required for the repair or replacement of the part or product SERVICE TERMS & CONDITIONS</p>
                                                    <p>7. Payment Methods. For service requiring partial payment to proceed with repair or replacement of part or product, Beyond the Box allows you to settle the charges using credit card, check order, or cash through direct payment to Beyond the Box or via bank deposit, subject to verification and any bank clearance policies that may delay the processing of payment. Beyond the Box will resume service once approval of cost estimates has been given and partial payment received. The full payment is due in exchange of returning the product to you at the end of the service process. Except as described in the Warranty clause below, Beyond the Box does not offer refunds for Service Repairs.</p>
                                                    <p>8. Service Warranty. For all Service Repairs, Beyond the Box warrants that (1) services performed will conform to their description for ninety (90) days from the date of payment receipt, (2) except for batteries described in the subsection below, all parts or products used in service will be free from defects in materials and workmanship for ninety (90) days from the date of payment receipt, and (3) batteries installed as part of Apple’s battery replacement service for Apple portable Mac computers will be free from defects in materials and workmanship for one year from the date of service. If non-conforming service is provided or a defect arises in a replacement part or product during the applicable warranty period, Beyond the Box will at its option, either (a) re-perform services to conform to their description (b) repair or replace the part or product, using parts or products that are new or equivalent to new in performance and reliability, or (c) refund the sums paid to Beyond the Box for service.</p>                                                

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
                         <td  colspan="1">
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
                             <label class="control-label col-md-12"><strong>Encoded By:</strong></label>
                         </td>
                         <td colspan="1">
                             @if($row->encoded_by == null)
                             ____________
                             @else
                             <p>{{$row->encoded_by}}</p>
                             @endif 
                         </td>
                    </tr>
                    <tr style="font-size: 13px;">
                         <td  colspan="1">
                                <label class="control-label col-md-12"><strong>Received By:<strong></label>
                            </td>
                            <td  colspan="3">
                                @if($row->received_by == null)
                                ____________
                                @else
                                <p>{{$row->received_by}}</p>
                                @endif 
                            </td>
                            <td colspan="1">
                                <label class="control-label col-md-12"><strong>Ref Service Repair#:</strong></label>
                            </td>
                            <td colspan="1">
                                @if($row->reference_service_repair_number == null)
                                ____________
                                @else
                                <p>{{$row->reference_service_repair_number}}</p>
                                @endif 
                            </td>
                    </tr>
                       @endif
            </table>  
    </div>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
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