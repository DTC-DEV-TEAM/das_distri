<!DOCTYPE html>
<html>
<head>
    <title>Return Request</title>
</head>



<body>
    <style>
        * {
            box-sizing: border-box;
        }
        .column {
            float: left;
            width: 35%;
            padding: 10px;
            height: 300px;
        }
        .column2 {
            float: left;
            width: 15%;
            padding: 10px;
            height: 300px;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
            width: 100%;
            margin: 0 100px;
        }
        td, th {
            border: 1px solid #999;
            padding: 0.5rem;
            text-align: center;
        }
    </style>

    <h3>{{ $details['title'] }}</h3>
    <p>Please check your email for a copy of your request. A representative will reach out to you in a few days to process your concern.</p>
    <p>{{ $details['body'] }} <b>{{ $details['all_details']->return_reference_no }}</b></p>

    <br><br>
<div class="row" style="margin: 0 100px;">
    <div class="row" style="text-align: center;"> 
        <h4>Details Form</h4>
    </div>
    <!-- 1r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.return_reference_no') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->return_reference_no }}
            </span>
        </div>

        <div class="column2">     
            <label>{{ trans('message.form-label.created_at') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->CreatedDate }}
            </span>
        </div>
    </div>

    <!-- 2r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.purchase_location') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->purchase_location }}
            </span>
        </div>
        
        <div class="column2">     
            <label>{{ trans('message.form-label.store') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->store }}
            </span>
        </div>
    </div>

    <!-- 3r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.customer_last_name') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->customer_last_name }}
            </span>
        </div>
        
        <div class="column2">     
            <label>{{ trans('message.form-label.customer_first_name') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->customer_first_name }}
            </span>
        </div>
    </div>

    <!-- 4r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.address') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->address }}
            </span>
        </div>
        
        <div class="column2">     
            <label>{{ trans('message.form-label.email_address') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->email_address }}
            </span>
        </div>
    </div>

    <!-- 5r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.contact_no') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->contact_no }}
            </span>
        </div>
        
        <div class="column2">     
            <label>{{ trans('message.form-label.order_no') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->order_no }}
            </span>
        </div>
    </div>

    <!-- 6r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.purchase_date') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->purchase_date }}
            </span>
        </div>
        
        <div class="column2">     
            <label>{{ trans('message.form-label.mode_of_payment') }}</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->mode_of_payment }}
            </span>
        </div>
    </div>

    <!-- 7r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.form-label.items_included') }}</label>
        </div>
        <div class="column">
            <span>
                @if(!empty($details['all_details']->ItemsIncludedOthers))
                    {{ $details['all_details']->ItemsIncluded }}, {{ $details['all_details']->ItemsIncludedOthers }}
                @else
                    {{ $details['all_details']->ItemsIncluded }}
                @endif
            </span>
        </div>
        
        <div class="column2">     
            <label>Customer Location</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->customer_location }}
            </span>
        </div>
    </div>
                       
    <!-- 8r -->
    <div class="row"> 
        <div class="column2">                           
            <label>{{ trans('message.table.comments') }}:</label>
        </div>
        <div class="column">
            <span>
                {{ $details['all_details']->comments }}
            </span>
        </div>
    </div>
    
    <br><br>
    <hr/> 

    <div class="row" style="text-align: center;"> 
        <h4>{{ trans('message.form-label.return_items') }}</h4>
    </div>

    <table class="table table-striped">
        <tbody>
            <tr class="tbl_header_color">
                <th class="text-center">{{ trans('message.table.digits_code') }}</th>
                <th class="text-center">{{ trans('message.table.upc_code') }}</th>
                <th class="text-center">{{ trans('message.table.item_description') }}</th>
                <th class="text-center">{{ trans('message.table.cost') }}</th>
                <th class="text-center">{{ trans('message.table.brand') }}</th>
                <th class="text-center">{{ trans('message.table.serial_no') }}</th>
                <th class="text-center">{{ trans('message.table.problem_details') }}</th>
                <th class="text-center">{{ trans('message.table.quantity') }}</th>
            </tr>
            <tr>
                <td class="text-center">{{ $details['all_details']->digits_code }}</td>
                <td class="text-center">{{ $details['all_details']->upc_code }}</td>
                <td class="text-center">{{ $details['all_details']->item_description }}</td>
                <td class="text-center">{{ $details['all_details']->cost }}</td>
                <td class="text-center">{{ $details['all_details']->brand }}</td>
                <td class="text-center">{{ $details['all_details']->serial_number }}</td>
                <td class="text-center"> 
                    <?php $problem_details = explode(",", $details['all_details']->problem_details); ?>
                    @foreach($problem_details as $problem_detail)
                        {{$problem_detail}} <br>
                    @endforeach
                    <br>
                    @if(!empty($details['all_details']->problem_details_other))
                            {{ $details['all_details']->problem_details_other }}
                    @endif
                </td>
                <td class="text-center">{{ $details['all_details']->quantity }}</td>
            </tr>
        </tbody>
    </table>
</div>
       
</body>
</html>