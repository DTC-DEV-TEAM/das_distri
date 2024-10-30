@extends('crudbooster::admin_template')

@section('content')
    @if (g('return_url'))
        <p class="noprint"><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @else
        <p class="noprint"><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i
                    class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @endif

    <style>
        fieldset.custom-sync {
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 0 1.0em 1.0em 1.0em !important;
            margin: 0 0 1.0em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        /* Overlay to cover the whole screen */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 75px;
            height: 75px;
            border: 15px solid rgba(43, 151, 253, 0.2);
            border-left-color: #008cdc;
            border-radius: 50%;
            animation: spin 0.5s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .valid {
            border: 1px solid green;
        }

        .invalid {
            border: 1px solid red;
        }
    </style>

@php
$status = CRUDBooster::me();
@endphp

    <div class='callout' id="sync-alert"
        style="width: 80%; margin:auto; border-radius: 10px; border-left: 5px solid #089e03ed; background-color:whitesmoke; box-shadow: 1px 7px 10px rgba(0, 0, 0, 0.2); display: none;">
        <h4 style="color:#089e03ed;"> <i class="glyphicon glyphicon-ok-sign" style="margin-right: 5px;"></i>
            Syncing Success</h4>
        <span style="color: #089e03ed; margin-left: 27px;">Based on your inputed date range there is/are <span
                id="no_sync"></span> Item/s synced
            successfully!</span> <br />
    </div>
    <br>

    <div class='panel panel-default' style="border-radius: 15px; width: 80%; margin:auto;">
        <div class='panel-heading' style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            DAS Item Master Custom Sync
        </div>
        <div class='panel-body'>

            <div class='callout'
                style="border-radius: 10px; border-left: 5px solid #00C0EF; background-color:whitesmoke; box-shadow: 1px 7px 10px rgba(0, 0, 0, 0.2);">
                <h4 style="color:#00C0EF;"> <i class="glyphicon glyphicon-bullhorn" style="margin-right: 5px;"></i> Welcome
                    to Item Master Custom Sync</h4>
                <span style="color: #00C0EF;">Before syncing dem item master, please read the Reminders below:</span> <br />
                <span style="color: #00C0EF;">* Syncing long date range (e.g. 1 or more months may take some/long time to
                    load the items.)</span> <br />
                <span style="color: #00C0EF;">* This is for specific syncing only (NOTE: we have a live/realtime syncing
                    every working hour. No need to manually sync here.)</span> <br />
            </div>

            <fieldset class="custom-sync">
                <div class="row" style="padding-top: 15px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="require control-label" style="font-size: 15px;">
                                <small style="color: red;">*</small> 
                                <strong>Date From</strong>
                                <small id="dateFrom_com" style="color: red; font-weight: 400;">
                                    <span id="dateFrom_coment1" style="display: none;"></span>
                                    <span id="dateFrom_coment2" style="display: none;"></span>
                                </small>
                            </label>
                            <input type="datetime-local" id="dateFrom" name="dateFrom" class='form-control'
                                style="border-radius: 7px;" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="require control-label" style="font-size: 15px;">
                                <small style="color: red;">*</small> 
                                <Strong>Date To</Strong>
                                <small id="dateTo_com" style="color: red; display: none;font-weight: 400;"></small>
                            </label>
                            <input type="datetime-local" id="dateTo" name="dateTo" class='form-control'
                                style="border-radius: 7px;" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default"
                            style="width:100%; border-radius:7px; margin: auto">Cancel </a>
                    </div>
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <button type="button" onclick="customSync()" class="btn btn-info"
                            style="width:100%; border-radius:7px; margin: auto">Sync <i class="fa fa-spinner fa-pulse fa-fw" style="display: none" id="sync_btn"></i></button>
                    </div>
                </div>
            </fieldset>

            <!-- Spinner -->
            <div class="spinner-overlay" id="spinner" style="display: none">
                <div class="spinner"></div>
            </div>

        </div>
    </div>

    <script>
        function customSync() {
            const dateFrom = $('#dateFrom').val();
            const dateTo = $('#dateTo').val();
            const dateFromObj = new Date(dateFrom);
            const dateToObj = new Date(dateTo);
            let isValid = true;

            if (dateFromObj > dateToObj) {
                $('#dateFrom').addClass('invalid');
                $('#dateFrom_coment1').html('<i class="fa fa-exclamation-circle" style="margin-left: 5px;"></i> Date From cannot be after date To!').show();
                isValid = false;
            } else {
                $('#dateFrom').removeClass('invalid');
                $('#dateFrom_coment1').hide();
            }
            // Validate dateFrom
            if (!dateFrom) {
                $('#dateFrom').addClass('invalid');
                $('#dateFrom_coment2').html('<i class="fa fa-exclamation-circle" style="margin-left: 5px;"></i> DateFrom is Required!').show();
                isValid = false;
            } else {
                $('#dateFrom').removeClass('invalid');
                $('#dateFrom_coment2').hide();
            }

            // Validate dateTo
            if (!dateTo) {
                $('#dateTo').addClass('invalid');
                $('#dateTo_com').html('<i class="fa fa-exclamation-circle" style="margin-left: 5px;"></i> DateTo is Required! ').show();
                isValid = false;
            } else {
                $('#dateTo').removeClass('invalid');
                $('#dateTo_com').hide();
            }

            if (!isValid) {
                return false;
            }

            $('#spinner').show();
            $('#sync_btn').show();

            $.ajax({
                url: '/admin/imfs/sync-item-created',
                type: 'POST',
                data: {
                    dateFrom: dateFrom,
                    dateTo: dateTo,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#spinner').hide();
                    $('#sync_btn').hide();

                    if (response.success) {
                        $('#no_sync').text(response.synced_items_count);
                        $('#sync-alert').show();
                        $('#error-alert').hide();

                        $('#dateFrom').val("");
                        $('#dateTo').val("");

                    } else {
                        alert('Error', 'An error occurred while syncing Item Master.', 'error');
                    }
                },
                error: function() {
                    $('#spinner').hide();
                    $('#sync_btn').hide();
                    alert('Error', 'An error occurred while processing the request.', 'error');
                }
            });
        }
    </script>
@endsection
