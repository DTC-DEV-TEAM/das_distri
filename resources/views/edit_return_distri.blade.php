<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<style>
    .input_div{
        margin-bottom: 15px;
    }
</style>
@section('content')
    <div class='panel panel-default'>
        <form method='post'enctype="multipart/form-data" action="{{ route('edit-return-ecoms', ['id' => $id]) }}">
            <div class='panel-heading'>Edit Form</div>
            <div class="panel-body">
                <h4>Distribution</h4>
                <hr>
                <div class="row input_div">     
                    <label class="control-label col-md-2">Return Status:</label>
                    <div class="col-md-4">
                        <select name="return_status" class="form-control" id="return_status">
                            <option value="">None selected</option>
                            @foreach ($return_statuses as $return_status)
                                <option value="{{ $return_status->id }}" {{ $item->returns_status == $return_status->id ? 'selected' : '' }}>
                                    {{ $return_status->warranty_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-2">Pos Replacement Reference:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->pos_replacement_ref }}' name='pos_replacement_ref' id='pos_replacement_ref' required  class='form-control'/>                
                    </div>
                </div>
                <div class="row">                           
                    <label class="control-label col-md-2">Negative Postive Invoice:</label>
                    <div class="col-md-4">
                        <input type='input' value='{{ $item->negative_positive_invoice }}' name='negative_positive_invoice' id='negative_positive_invoice' required  class='form-control'/>                
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