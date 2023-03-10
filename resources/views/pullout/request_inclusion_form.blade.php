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
                <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <form method='' id="myform" action="">
                                <input type="hidden" value="{{$row->id}}" name="id">  
                                <input type="hidden" value="{{$row->status_level1}}" name="status">  
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
                                    <td width="40%">
                                        <img src="{{asset("$store_logos->store_logo")}}"  style="align:middle;width:155px;height:30px;">
                                    </td>
                                    <td>
                                        <h4 align="left"  style="margin-top: 17px;"><strong>INCLUSION FORM</strong></h4> 
                                    </td>
                                </tr>     
                            </table>   
                            <hr color="black" >
                        </td>
                    </tr>
        
                    <tr style="font-size: 13px;">
                        <td width="23%">
                            <label class="control-label col-md-12"><strong>MP#:<strong></label>
                        </td>
                        <td width="35%">
                            <p>{{$row->reference}}</p>
                        </td>
                        <td width="23%">
                            <label class="control-label col-md-12"><strong>SROF#:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->srof_number}}</p>
                        </td>
                    </tr>  
                 
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
                            <label class="control-label col-md-12"><strong>Transacted Date:<strong></label>
                        </td>
                        <td width="40%">
                            <p>{{ $row->approved_at_level5 != null ? date('m-d-Y', strtotime($row->approved_at_level5)): "" }}</p>
                        </td>
                        <td width="20%">
                            <label class="control-label col-md-12"><strong>Transacted By:</strong></label>
                        </td>
                        <td>
                            <p>{{$row->received_by}}</p> 
                        </td>                        
                    </tr> 
                    <tr style="font-size: 13px;">
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
                    <tr>
                        <td colspan="4">
                           
                            <label class="control-label col-md-12"><strong>ITEMS INCLUSION</strong></label>
                            <br>
                            <table border="1" width="100%" style="text-align:center;border-collapse: collapse;font-size: 13px;">
                                <thead>
                                    <tr>
                                         <th style="text-align:left" height="10">Item</th>
                                         <th style="text-align:center" height="10">Quantity</th>
                                   
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach(explode(',', $srof_row->items_inclusion) as $info)
                                        <tr>
                                            <td  style="text-align:left" height="10">{{$info}}</td>
                                            <td  style="text-align:center" height="10">                                       
                                                    1
                                            </td>
                                        </tr>
                                    @endforeach
                                
                                </tbody>
                            </table> 
                        </td>
                    </tr>    
                    @if($srof_row->items_inclusion_other != null)
                    <tr style="font-size: 13px;">
                        <td width="22%">
                            <label class="control-label col-md-12"><strong>OTHERS INCLUSION:<strong></label>
                        </td>
                        <td colspan="5">
                            <p>{{$srof_row->items_inclusion_other}}</p>
                        </td>                    
                    </tr> 
                    @endif
            </table> 
        </div>
        </table>
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        $("#savedata").on('click',function(){
        //var strconfirm = confirm("Are you sure you want to approve this pull-out request?");
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/pullout_stw/ReceiveRequest') }}',
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