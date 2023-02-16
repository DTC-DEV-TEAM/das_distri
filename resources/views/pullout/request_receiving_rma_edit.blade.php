<!-- First, extends to the CRUDBooster Layout -->
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
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <!-- Your html goes here -->
  <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
            <div class='panel-body'>
             <div id="printableArea"> 
                <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <h5 style="color:red;"><strong>Store Personnel</strong></h5>
                        <div class="row"> 
                                <div class='col-md-6'>    
                                    <label class="control-label">Released Date:</label> 
                                    <input type='input' name='released_date' id="datepicker" onkeydown="return false"   required  autocomplete="off"  class='form-control' placeholder="yyyy-mm-dd" />                     
                                </div>
                                <div class='col-md-6'>    
                                    <label class="control-label">Released By:</label> 
                                    <input type='text' name='released_by' id="released_by" class='form-control' required maxlength="50" autocomplete="off" placeholder="First Name Last Name" />                              
                                </div>
                        </div>
                        @if($row->path_name == "LOGISTICS")
                        <br>
                        <h5 style="color:red;"><strong>Logistics Personnel</strong></h5>
                        <div class="row"> 
                                <div class='col-md-6'>    
                                    <label class="control-label">Received By:</label> 
                                    <input type='text' name='logistics_personnel_received_by' id="logistics_personnel_received_by" class='form-control' required maxlength="50" autocomplete="off" placeholder="First Name Last Name" />                              
                                </div>
                        </div>
                        @endif
                        <hr color="black" >
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
                                    <p>{{$row->st_number_pull_out}}</p>
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
                        <div class="row"> 
                            <label class="control-label col-md-2">Date Purchased:</label>
                            <div class="col-md-4">
                                     @if($srof_detials->date_purchased == null)
                                        ____________________
                                     @else 
                                        <p>{{$srof_detials->date_purchased}}</p>
                                    @endif
                            </div> 
                            @if($row->path_name == "REQUESTOR")
                                <label class="control-label col-md-2">Hand Carried By:</label>
                                <div class="col-md-4">
                                         <p>{{$row->hand_carry_by}}</p>
                                </div>    
                            @endif                         
                        </div> 
                        <div class="row"> 
                            <label class="control-label col-md-2">Approver:</label>
                            <div class="col-md-4">
                                    <p>{{$row->approverlevel}}</p>
                            </div> 
                             <label class="control-label col-md-2">Approved Date:</label>
                            <div class="col-md-4">
                                     <p>{{$row->approved_at_level1}}</p>
                            </div>                            
                        </div>
                        @if ($row->transaction_type_name == "STORE TO WAREHOUSE" || $row->transaction_type_name == "DEPARTMENT TO WAREHOUSE") 
                        <hr color="black" >                        
                        <div class="row"> 
                            <!--@if ($row->pullout_type == "rma") 
                                <label class="control-label col-md-2">RMA:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div> 
                            @else
                                <label class="control-label col-md-2">SIM:</label>
                                <div class="col-md-4">
                                    <p>{{$row->simlevel}}</p>
                                </div>                              
                            @endif-->
                            <label class="control-label col-md-2">SDM Specialist:</label>
                            <div class="col-md-4">
                        
                                @if($row->simlevel == null)
                                        ____________________
                                    @else
                                        <p>{{$row->simlevel}}</p>
                                @endif
                            </div> 
                            <label class="control-label col-md-2">SOR/MOR Date:</label>
                            <div class="col-md-4">
                                    @if($row->approved_at_level2 == null)
                                            ____________________
                                        @else
                                            <p>{{$row->approved_at_level2}}</p>
                                    @endif
                                        
                            </div>                            
                        </div>
                        <div class="row"> 
                            <label class="control-label col-md-2">SOR#/MOR#:</label>
                            <div class="col-md-4">
                                    @if($row->sor_mor_number == null)
                                            ____________________
                                        @else
                                            <p>{{$row->sor_mor_number}}</p>
                                    @endif
                            </div>                             
                        </div>
                        <!--TABLE-->
                        <br>
                        <table  class='table table-striped table-bordered'>
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
                                      <td style="text-align:center" height="10">{{$rowresult->digits_code}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->upc_code}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->item_description}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->brand}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->category}}</td>
                                      <td style="text-align:center" height="10">{{$rowresult->quantity}}</td>                    
                                </tr>
                            @endforeach
                                        <tr>
                                            <td style="text-align:right" height="10" colspan="5"><label>Total Quantity:</label></td>
                                            <td style="text-align:center" height="10"><p>{{$row->total_quantity}}</p></td>    
                                        </tr>
                        </tbody>
                        </table>
                        @endif
                        <hr color="black" > 
                        <div class="row"> 
                            <label class="control-label col-md-2">Pullout Date:</label>
                            <div class="col-md-4">
                                    <p>{{$row->approved_at_level3}}</p>
                            </div> 
                            <label class="control-label col-md-2">Approved By:</label>
                            <div class="col-md-4">
                                    <p>{{$row->logiscticslevel}}</p>
                            </div>                            
                        </div> 
                        @if($row->srof_image != NULL)
                            @if($privilegename == "Approver" || $privilegename == "RMA" || $privilegename == "Super Administrator" || $privilegename == "Admin")
                                <hr color="black" >
                                <h5 style="text-align:left;margin-left: 17px;"><strong>ITEM IMAGES:</strong></h5>
                                <div class="slideshow-container">
                                    @foreach(explode('|', $row->srof_image) as $info)
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
            </div>
            <div class='panel-footer'>           
                    <input type='submit' class='btn btn-primary' value='Save'/>
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

  $( "#datepicker" ).datepicker( { maxDate: 0, dateFormat: 'yy-mm-dd' } );

$(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
 
});
$('#released_by').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z. ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else
        {
        e.preventDefault();
        alert('Please Enter Alphabet');
        return false;
        }
});

$('#logistics_personnel_received_by').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z. ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else
        {
        e.preventDefault();
        alert('Please Enter Alphabet');
        return false;
        }
});

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
</script>
@endpush