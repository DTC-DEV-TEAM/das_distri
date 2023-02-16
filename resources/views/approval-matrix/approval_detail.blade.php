@extends('crudbooster::admin_template')
@section('content')

<div class='panel panel-default'>
    <div class='panel-heading'>Approval Matrix Details</div>
        <div class='panel-body'>
            <div class="box-body" id="parent-form-area">
                <div class="table-responsive">
                    <table id="table-detail" class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="25%"><b>Privilege Name</b></td>
                                <td width="75%">{{ $approval_details->privilege_name }}</td>
                            </tr>
                            <tr>
                                <td width="25%"><b>Approver Name</b></td>
                                <td width="75%">{{ $approval_details->cms_user_name }}</td>
                            </tr>
                            <tr>
                                <td width="25%"><b>Channel</b></td>
                                <td width="75%">{{ $approval_details->channel_name }}</td>
                            </tr>
                            <tr>
                                <td width="25%"><b>Store List</b></td>
                                <td width="75%">
                                    @foreach ($store_lists as $store)
                                        <span stye="display: block;" class="label label-info"> {{ $store }}</span><br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td width="25%"><b>Status</b></td>
                                <td width="75%">{{ $approval_details->status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

@endsection