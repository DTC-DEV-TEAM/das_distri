<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<style>
    .panel-heading{
        background: #F5F5F5;
    }
    .input_div{
        margin-bottom: 15px;
    }
    .display_custom_table{
        padding: 15px !important;
        overflow-x: auto;
    }
    .custom_table{
        width: 100%;
        border: 1px solid #DDDDDD;
        background: #f5f5f5cc;
    }
    .custom_table tr td {
        border: 1px solid #DDDDDD;
        padding: 10px
    }
    .table_label{
        font-weight: bold;
    }
    
</style>
@section('content')
    <div class='panel panel-default'>
        <form method='POST' action="{{ route('edit-return-retail', ['id' => $id]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class='panel-heading'>Edit Form</div>
            <div class="panel-body">
                <div class="row display_custom_table">
                    <table class='custom_table'>
                        <tbody>
                            <tr>
                                <td class="table_label">Return Reference#:</td>
                                <td>{{ $item->return_reference_no }}</td>
                                <td class="table_label">Created Date:</td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                            <tr>
                                <td class="table_label">Purchase Location:</td>
                                <td>{{ $item->purchase_location }}</td>
                                <td class="table_label">Store:</td>
                                <td>{{ $item->store }}</td>
                            </tr>
                            <tr>
                                <td class="table_label">Customer Last Name:	</td>
                                <td>{{ $item->customer_last_name }}</td>
                                <td class="table_label">Customer First Name:</td>
                                <td>{{ $item->customer_first_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row input_div">     
                    <label class="control-label col-md-2">Frontend Status:</label>
                    <div class="col-md-4">
                        <select name="return_status" class="form-control" id="return_status" required>
                            <option value="">None selected</option>
                            @foreach ($return_statuses as $return_status)
                                <option value="{{ $return_status->id }}" {{ $item->returns_status == $return_status->id ? 'selected' : '' }}>
                                    {{ $return_status->warranty_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-2">Backend Status:</label>
                    <div class="col-md-4">
                        <select name="return_status_1" class="form-control" id="return_status_1" required>
                            <option value="">None selected</option>
                            @foreach ($return_statuses as $return_status)
                                <option value="{{ $return_status->id }}" {{ $item->returns_status_1 == $return_status->id ? 'selected' : '' }}>
                                    {{ $return_status->warranty_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">                           
                    <label class="control-label col-md-2">Negative Postive Invoice:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->negative_positive_invoice }}' name='negative_positive_invoice' id='negative_positive_invoice' required  class='form-control'/>                
                    </div>
                    <label class="control-label col-md-2">Address:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->address }}' name='address' id='address' required  class='form-control'/>                
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class="control-label col-md-2">Customer Location:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->customer_location }}' name='customer_location' id='customer_location' required  class='form-control'/>                
                    </div>
                    <label class="control-label col-md-2">Pos Replacement Reference:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->pos_replacement_ref }}' name='pos_replacement_ref' id='pos_replacement_ref' required  class='form-control'/>                
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class="control-label col-md-2">Order No.:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->order_no }}' name='order_no' id='order_no' required  class='form-control'/>                
                    </div>
                    <label class="control-label col-md-2">Diagnose:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->diagnose }}' name='diagnose' id='diagnose' required  class='form-control'/>                
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class="control-label col-md-2">Warranty Status:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->item_warranty_status }}' name='item_warranty_status' id='item_warranty_status' required  class='form-control'/>                
                    </div>
                </div>
                <br>
            </div>
            <div class="box-footer">
                <div class='pull-right'>
                    <input type='submit' class='btn btn-primary' name='submit' value='Save'/>
                </div>
            </div><!-- /.box-footer-->
        </form>
    </div>



<script>
    $(document).ready(function(){
					

        })

</script>
    
@endsection