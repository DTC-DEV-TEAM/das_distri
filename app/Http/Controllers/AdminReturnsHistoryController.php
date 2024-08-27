<?php namespace App\Http\Controllers;

use Session;
//use Request;
use DB;
use CRUDBooster;
use App\ReturnsStatus;
use App\ReturnsHeader;
use App\ReturnsBody;
use App\ReturnsSerials;
use App\ProblemDetails;
use App\Stores;
use App\Channel;
use App\ShipBackStatus;
use App\ClaimedStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use App\StoresFrontEnd;

	class AdminReturnsHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			//$this->request = $request;
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "customer_last_name";
			$this->limit = "20";
			$this->orderby = "history_status,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "returns_header";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Brand","name"=>"id", 'callback'=>function($row){
				
				$id = $row->id;

				$brand = DB::table('returns_body_item')->where('returns_header_id', $id)->orderBy('id', 'desc')->first()->brand;

				return $brand;
			}];
			$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
			$this->col[] = ["label"=>"Last Chat", "name"=>"id", 'callback'=>function($row){
				$img_url = asset("chat_img/$row->last_image");
				;
				$str = '';
				
				$str .= "<div class='sender_name'>$row->sender_name</div>";
				$str .= "<div class='time_ago' datetime='$row->date_send'>$row->date_send</div>";
				
				if ($row->last_message) {
					// Truncate the message if it's longer than 150 characters
					$truncatedMessage = strlen($row->last_message) > 41 ? substr($row->last_message, 0, 41) . '...' : $row->last_message;
					$str .= "<div class='text-msg'>$truncatedMessage</div>";
				}
				if($row->last_image){
					$str .= "<div class='last_msg'><img src='$img_url'></div>";
				}
				if($row->sender_name){
					return $str;
				}else{
					return '<div class="no-message">No messages available at the moment.</div>';
				}
			}];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			if(CRUDBooster::myPrivilegeName() == "Aftersales"){ 
				$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Logistics"){ 
				$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Ecomm Ops"){ 
				//$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Customer","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(in_array(CRUDBooster::myPrivilegeName(), ['RMA Inbound', 'Tech Lead', 'RMA Technician', 'RMA Specialist'])){ 
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				//$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				//$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				//$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				//$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				//$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Ship Back Status","name"=>"ship_back_status" ,"join"=>"status_ship_back,ship_back_status_name"];
				$this->col[] = ["label"=>"Claimed Status","name"=>"claimed_status" ,"join"=>"status_claimed,claimed_status_name"];
				$this->col[] = ["label"=>"Credit Memo#","name"=>"credit_memo_number"];

				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				//$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				//$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				//$this->col[] = ["label"=>"Branch","name"=>"branch"];
				$this->col[] = ["label"=>"Store Drop-Off","name"=>"store_dropoff"];
				$this->col[] = ["label"=>"Branch Drop-Off","name"=>"branch_dropoff"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				//$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				//$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				//$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				//$this->col[] = ["label"=>"Ship Back Status","name"=>"ship_back_status" ,"join"=>"status_ship_back,ship_back_status_name"];
				//$this->col[] = ["label"=>"Claimed Status","name"=>"claimed_status" ,"join"=>"status_claimed,claimed_status_name"];
				//$this->col[] = ["label"=>"Credit Memo#","name"=>"credit_memo_number"];

				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Accounting"){ 
				$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "SDM"){ 

			}else{
				$this->col[] = ["label"=>"Id","name"=>"id","visible"=>false];
				$this->col[] = ["label"=>"Closed Date","name"=>"level5_personnel_edited"];
				$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}

			
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Returns Status','name'=>'returns_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Returns Status 1','name'=>'returns_status_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Schedule','name'=>'return_schedule','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Reference No','name'=>'return_reference_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Location','name'=>'purchase_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store','name'=>'store','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Last Name','name'=>'customer_last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer First Name','name'=>'customer_first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Contact No','name'=>'contact_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Order No','name'=>'order_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Date','name'=>'purchase_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Payment','name'=>'mode_of_payment','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Name','name'=>'bank_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account No','name'=>'bank_account_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account Name','name'=>'bank_account_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included','name'=>'items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included Others','name'=>'items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Comments','name'=>'comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sor No','name'=>'sor_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Total Quantity','name'=>'total_quantity','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level1 Personnel','name'=>'level1_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level1 Personnel Edited','name'=>'level1_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level2 Personnel','name'=>'level2_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level2 Personnel Edited','name'=>'level2_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level3 Personnel','name'=>'level3_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level3 Personnel Edited','name'=>'level3_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level4 Personnel','name'=>'level4_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level4 Personnel Edited','name'=>'level4_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level5 Personnel','name'=>'level5_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level5 Personnel Edited','name'=>'level5_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level6 Personnel','name'=>'level6_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level6 Personnel Edited','name'=>'level6_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level7 Personnel','name'=>'level7_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level7 Personnel Edited','name'=>'level7_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Returns Status","name"=>"returns_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Returns Status 1","name"=>"returns_status_1","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Return Schedule","name"=>"return_schedule","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Return Reference No","name"=>"return_reference_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purchase Location","name"=>"purchase_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Store","name"=>"store","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer Last Name","name"=>"customer_last_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer First Name","name"=>"customer_first_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Address","name"=>"address","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Email Address","name"=>"email_address","type"=>"email","required"=>TRUE,"validation"=>"required|min:1|max:255|email|unique:returns_header","placeholder"=>"Please enter a valid email address"];
			//$this->form[] = ["label"=>"Contact No","name"=>"contact_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Order No","name"=>"order_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purchase Date","name"=>"purchase_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Name","name"=>"bank_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Account No","name"=>"bank_account_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Account Name","name"=>"bank_account_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Items Included","name"=>"items_included","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Items Included Others","name"=>"items_included_others","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Comments","name"=>"comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Customer Location","name"=>"customer_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sor No","name"=>"sor_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total Quantity","name"=>"total_quantity","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level1 Personnel","name"=>"level1_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level1 Personnel Edited","name"=>"level1_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level2 Personnel","name"=>"level2_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level2 Personnel Edited","name"=>"level2_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level3 Personnel Edited","name"=>"level3_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level4 Personnel","name"=>"level4_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level4 Personnel Edited","name"=>"level4_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level5 Personnel","name"=>"level5_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level5 Personnel Edited","name"=>"level5_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level6 Personnel","name"=>"level6_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level6 Personnel Edited","name"=>"level6_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Level7 Personnel","name"=>"level7_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level7 Personnel Edited","name"=>"level7_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
			$this->addaction = array();
			$refund_complete = 			ReturnsStatus::where('id','11')->value('id');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('id');			
			
			if(CRUDBooster::myPrivilegeName() == "RMA"){
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsHistoryEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $refund_complete or [returns_status_1] == $return_invalid or [returns_status_1] == $repair_complete"];
			}
			
			if(CRUDBooster::myPrivilegeName() == "Admin Ops"){
			    $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('EditHistoryEcomm/[id]'),'icon'=>'fa fa-pencil'];
			}
			
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsHistoryDetail/[id]'),'icon'=>'fa fa-eye', 'color'=>'none'];

			if(CRUDBooster::myPrivilegeName() == 'Super Administrator'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsHistoryEditEcomm/[id]'),'icon'=>'fa fa-pencil', 'color'=>'none'];
			}

			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrint/[id]'),'icon'=>'fa fa-print', 'color'=>'none', "showIf"=>"[diagnose] != 'REFUND' and [level3_personnel] != null"];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsCRFPrint/[id]'),'icon'=>'fa fa-print', 'color'=>'none', "showIf"=>"[diagnose] == 'REFUND' and [level3_personnel] != null"];
			/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();
			if(CRUDBooster::isUpdate())
	        {
				if(CRUDBooster::myPrivilegeName() == "Admin Ops" ||  CRUDBooster::isSuperadmin() ){ 

						$this->button_selected[] = ['label'=>'Void',
													'icon'=>'fa fa-times-circle',
													'name'=>'void'];
						$this->button_selected[] = ['label'=>'Replace to Refund (CRF)',
													'icon'=>'fa fa-times-circle',
													'name'=>'to_create_crf'];
													
		
				}else{
				    
			
				    
				    if(CRUDBooster::myName() == "Joelan Delota" ||
				       CRUDBooster::myName() == "Jan Franz Josef Sevilla"){
				        						$this->button_selected[] = ['label'=>'Void',
													'icon'=>'fa fa-times-circle',
													'name'=>'void'];
				    }
				    
				}

	        }
			

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();

	
				if(CRUDBooster::getCurrentMethod() == 'getIndex'){
        				$this->index_button[] = ["title"=>"Export Returns",
        				"label"=>"Export Returns",
        				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export_return_history_ecomm').'?'.urldecode(http_build_query(@$_GET))];
				}
			


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;

            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
			$this->load_js[] = "https://unpkg.com/timeago.js/dist/timeago.min.js";
			$this->load_js[] = asset("js/time_ago.js");
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
			$this->load_css[] = asset('css/last_message.css');
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

			if($button_name == 'void') {

				ReturnsHeader::whereIn('id',$id_selected)->update([
					'returns_status_1'=> 28,
					'returns_status'=> 28
				]);

				
					
				DB::connection('mysql_front_end')
				->statement("update returns_header set returns_status_1 = 28,
								returns_status = 28
								where id  in  ('$id_selected')");
	
				DB::disconnect('mysql_front_end');
				
				

			}
			if($button_name == 'to_create_crf'){
			    ReturnsHeader::whereIn('id',$id_selected)->update([
					'returns_status_1'=> 25,
					'returns_status'=> 25,
					'diagnose'=>'REFUND'
				]);
			}
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here

			// Chatbox
			$query->leftJoin('ecomm_last_comments', 'ecomm_last_comments.returns_header_id', 'returns_header.id')
			->leftJoin('chat_ecomms', 'chat_ecomms.id', 'ecomm_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chat_ecomms.created_by')
			->addSelect('chat_ecomms.message as last_message',
				'chat_ecomms.file_name as last_image',
				'sender.name as sender_name',
				'chat_ecomms.created_at as date_send'
			);

			if(CRUDBooster::myPrivilegeName() == "Aftersales" || CRUDBooster::myPrivilegeName() == "Ecomm Ops"){ 
	            $query->whereNotNull('returns_status_1')
				->where('transaction_type','!=', 2);
				
                
			}elseif(CRUDBooster::myPrivilegeName() == "Logistics"){ 

				$query->whereNotIn('returns_status_1',  [
					ReturnsStatus::TO_SCHEDULE_LOGISTICS, 
					ReturnsStatus::RETURN_DELIVERY_DATE
				])
				->where('transaction_type','!=', 2);

			}elseif(CRUDBooster::myPrivilegeName() == "Retail Ops"){ 

				$query->whereNotNull('received_by')
				->where('transaction_type','!=', 2);
				
			}elseif(CRUDBooster::myPrivilegeName() == "Store Ops"){ 

				$storeList = self::getStoreList();

				$query->whereNotNull('received_by')
				->where('returns_status_1','!=', ReturnsStatus::TO_PRINT_PF)
				->where('transaction_type','!=', 2)
				->whereIn('returns_header.stores_id', $storeList);
			
				
			}elseif(in_array(CRUDBooster::myPrivilegeName(), ['RMA Inbound', 'Tech Lead', 'RMA Technician', 'RMA Specialist'])){ 

				$query->whereNotNull('returns_status_1');

			}elseif(CRUDBooster::myPrivilegeName() == "Service Center"){ 

				$storeList = self::getStoreList();

				$query->where(function ($query) use ($storeList) {
					$query->whereNotIn('returns_status_1', [
						ReturnsStatus::TO_DIAGNOSE,
						ReturnsStatus::TO_SOR,
						ReturnsStatus::TO_PRINT_SSR
					])
					->where('transaction_type', 1)
					->whereNotNull('diagnose')
					->whereIn('returns_header.stores_id', $storeList);
				})
				->orWhere(function ($query) use ($storeList) {
					$query->whereNotIn('returns_status_1', [
						ReturnsStatus::TO_RECEIVE,
						ReturnsStatus::TO_PRINT_SSR,
						ReturnsStatus::TO_SOR
					])
					->where('transaction_type', 3)
					->whereNotNull('diagnose')
					->whereIn('returns_header.stores_id', $storeList);
					
				});

			
			}elseif(CRUDBooster::myPrivilegeName() == "Accounting"){ 

				$query->whereNotIn('returns_status_1', [
					ReturnsStatus::TO_PRINT_CRF, 
					ReturnsStatus::REFUND_IN_PROCESS
				])
				->where('transaction_type','!=', 2)
				->where('diagnose', "REFUND");

			}elseif(CRUDBooster::myPrivilegeName() == "SDM"){ 

				$query->where('returns_status_1','!=',	ReturnsStatus::TO_SOR)
				->where('transaction_type','!=', 2)
				->where('diagnose', "REFUND");
			}else{
				$query->whereNotNull('returns_status_1'); 
			}
			
			$query->orderBy('created_at', 'desc'); 
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
			//Your code here

			
			$requested = 				ReturnsStatus::where('id','1')->value('warranty_status');
			$to_indicate_store = 		ReturnsStatus::where('id','3')->value('warranty_status');
			$to_diagnose = 				ReturnsStatus::where('id','5')->value('warranty_status');
			$to_print_crf = 			ReturnsStatus::where('id','7')->value('warranty_status');
			$to_sor = 					ReturnsStatus::where('id','9')->value('warranty_status');
			$to_receive_sor = 			ReturnsStatus::where('id','10')->value('warranty_status');
			$refund_in_process = 		ReturnsStatus::where('id','8')->value('warranty_status');
			$refund_complete = 			ReturnsStatus::where('id','11')->value('warranty_status');
			$to_print_return_form = 	ReturnsStatus::where('id','13')->value('warranty_status');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('warranty_status');
			$to_ship_back = 			ReturnsStatus::where('id','14')->value('warranty_status');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('warranty_status');
			$to_schedule = 				ReturnsStatus::where('id','18')->value('warranty_status');
			$to_schedule_aftersales = 	ReturnsStatus::where('id','22')->value('warranty_status');
			$to_schedule_logistics = 	ReturnsStatus::where('id','23')->value('warranty_status');
			$cancelled = 	  			ReturnsStatus::where('id','28')->value('warranty_status');
			$to_receive = 				ReturnsStatus::where('id','29')->value('warranty_status');
            $to_print_pf = 				ReturnsStatus::where('id','19')->value('warranty_status');
            $received = 	            ReturnsStatus::where('id','31')->value('warranty_status');
            $to_receive  = 	            ReturnsStatus::where('id','29')->value('warranty_status');      
            $return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
            $to_print_srr  =            ReturnsStatus::where('id','32')->value('warranty_status');
            
            $for_replacement = 	  		ReturnsStatus::where('id','20')->value('warranty_status');
            $replacement_complete = 	ReturnsStatus::where('id','21')->value('warranty_status');

			$to_receive_rma = 			ReturnsStatus::where('id','34')->value('warranty_status');
			$to_receive_sc = 			ReturnsStatus::where('id','35')->value('warranty_status');
			$to_turnover = 				ReturnsStatus::where('id','37')->value('warranty_status');
			$to_for_action = 			ReturnsStatus::where('id','38')->value('warranty_status');
			$to_ongoing_testing = 		ReturnsStatus::where('id','40')->value('warranty_status');

			$to_create_crf = 			ReturnsStatus::where('id','25')->value('warranty_status');

			$closed_date = "";

			//dd($column_index);

			if($column_index == 6){
				$row_id = ReturnsHeader::where('return_reference_no', $column_value)->first();
			
				if($row_id->diagnose == "REFUND"){
						$closed_date = $row_id->level7_personnel_edited;
				}else{
						$closed_date = $row_id->level5_personnel_edited;
				}

				if($column_index == 3){
					$column_value = $closed_date;
				}

				
			//	dd($row_id->id);
			}		


                      
			if($column_index == 3){
			
				if($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_indicate_store){
					$column_value = '<span class="label label-warning">'.$to_indicate_store.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_print_pf){
					$column_value = '<span class="label label-warning">'.$to_print_pf.'</span>';
			
				}elseif($column_value == $to_print_crf){
					$column_value = '<span class="label label-warning">'.$to_print_crf.'</span>';
			
				}elseif($column_value == $to_sor){
					$column_value = '<span class="label label-warning">'.$to_sor.'</span>';
			
				}elseif($column_value == $to_receive_sor){
					$column_value = '<span class="label label-warning">'.$to_receive_sor.'</span>';
			
				}elseif($column_value == $refund_in_process){
					$column_value = '<span class="label label-warning">'.$refund_in_process.'</span>';
			
				}elseif($column_value == $refund_complete){
					$column_value = '<span class="label label-success">'.$refund_complete.'</span>';
			
				}elseif($column_value == $to_print_return_form){
					$column_value = '<span class="label label-warning">'.$to_print_return_form.'</span>';
			
				}elseif($column_value == $to_ship_back){
					$column_value = '<span class="label label-warning">'.$to_ship_back.'</span>';
			
				}elseif($column_value == $return_invalid){
					$column_value = '<span class="label label-success">'.$return_invalid.'</span>';
			
				}elseif($column_value == $repair_complete){
					$column_value = '<span class="label label-success">'.$repair_complete.'</span>';
			
				}elseif($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $to_schedule_aftersales){
					$column_value = '<span class="label label-warning">'.$to_schedule_aftersales.'</span>';
			
				}elseif($column_value == $to_schedule_logistics){
					$column_value = '<span class="label label-warning">'.$to_schedule_logistics.'</span>';
			
				}elseif($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
			
				}elseif($column_value == $to_receive){
					$column_value = '<span class="label label-warning">'.$to_receive.'</span>';
			
				}elseif($column_value == $received){
					$column_value = '<span class="label label-success">'.$received.'</span>';
			
				}elseif($column_value == $to_receive){
					$column_value = '<span class="label label-warning">'.$to_receive.'</span>';
			
				}elseif($column_value == $return_delivery_date){
					$column_value = '<span class="label label-warning">'.$return_delivery_date.'</span>';
			
				}elseif($column_value == $to_print_srr){
					$column_value = '<span class="label label-warning">'.$to_print_srr.'</span>';
			
				}elseif($column_value == $for_replacement){
					$column_value = '<span class="label label-warning">'.$for_replacement.'</span>';
			
				}elseif($column_value == $replacement_complete){
					$column_value = '<span class="label label-success">'.$replacement_complete.'</span>';
			
				}elseif($column_value == $to_receive_rma){
					$column_value = '<span class="label label-warning">'.$to_receive_rma.'</span>';
			
				}elseif($column_value == $to_receive_sc){
					$column_value = '<span class="label label-warning">'.$to_receive_sc.'</span>';
			
				}elseif($column_value == $to_create_crf){
					$column_value = '<span class="label label-warning">'.$to_create_crf.'</span>';
			
				}
				elseif($column_value == $to_turnover){
					$column_value = '<span class="label label-warning">'.$to_turnover.'</span>';
			
				}
				elseif($column_value == $to_for_action){
					$column_value = '<span class="label label-warning">'.$to_for_action.'</span>';
			
				}elseif($column_value == $to_ongoing_testing){
					$column_value = '<span class="label label-warning">'.$to_ongoing_testing.'</span>';
			
				}
			}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here


			$returns_fields = Input::all();
			$field_1 		= $returns_fields['ship_back_status'];
			$field_2 		= $returns_fields['claimed_status'];
			$field_3 		= $returns_fields['credit_memo_number'];

			$postdata['ship_back_status'] = 	$field_1;
			$postdata['claimed_status'] = 		$field_2;
			$postdata['credit_memo_number'] = 	$field_3;
			$postdata['rma_edited_by'] = 		CRUDBooster::myId();
			$postdata['rma_edited_at']=			date('Y-m-d H:i:s');
			
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been edited successfully!"), 'success');
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function ReturnsHistoryEditEcomm($id)
		{
			$data = [];
			$data['id'] = $id; 
			$return_statuses = DB::table('warranty_statuses')
				->orderBy('warranty_status', 'asc')
				->get();
			$item = DB::table('returns_header')
				->where('returns_header.id', $id)
				->leftJoin('warranty_statuses', 'warranty_statuses.id', '=' , 'returns_header.returns_status_1')
				->select(
					'returns_header.*',
					'warranty_statuses.warranty_status',
					'returns_header.warranty_status as item_warranty_status'
				)
				->get()
				->first();
			$data['item'] = $item;
			$data['return_statuses'] = $return_statuses;
			$this->cbview('edit_return_ecoms', $data);
		}
		public function updateReturnEcoms(Request $request, $id)
		{
			DB::table('returns_header')
				->where('id', $id)
				->update([
					'returns_status' => $request->input('return_status'),
					'returns_status_1' => $request->input('return_status_1'),
					'pos_replacement_ref' => $request->input('pos_replacement_ref'),
					'negative_positive_invoice' => $request->input('negative_positive_invoice'),
					'address' => $request->input('address'),
					'customer_location' => $request->input('customer_location'),
					'order_no' => $request->input('order_no'),
					'diagnose' => $request->input('diagnose'),
					'warranty_status' => $request->input('item_warranty_status'),
				]);
				
				CRUDBooster::redirect(CRUDBooster::mainpath(), sprintf("Edited Successfully!"),"success");
		}


		//By the way, you can still create your own method in here... :) 
		public function ReturnsHistoryDetail($id)
		{
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }  

			$data = array();
			//$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')																		
			->leftjoin('transaction_type', 'returns_header.transaction_type_id', '=', 'transaction_type.id')
			->leftJoin('via', 'returns_header.via_id', 'via.id')
			->select(
			'returns_header.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'transaction_type.transaction_type_name',
			'closed.name as closed_by',
			'via.via_name as via_id'					
			)
			->where('returns_header.id',$id)->first();

				
            if($data['row']->returns_status_1 == 1){
     			$data['resultlist'] = ReturnsBody::
    			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
    			->select(
    			'returns_body_item.*',
    			'returns_serial.*'					
    			)
    			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();                            
            }else{
     			$data['resultlist'] = ReturnsBody::
    			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
    			->select(
    			'returns_body_item.*',
    			'returns_serial.*'					
    			)
    			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();               
            }

			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();

			$data['comments_data'] = (new ChatController)->getCommentsEcomm($id);
			
			$this->cbView("returns.history_view", $data);
		}

		public function ReturnsReturnFormPrint($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level1_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')																		
			->select(
			'returns_header.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by'							
			)
			->where('returns_header.id',$id)->first();



			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_return_form", $data);
		}


		public function ReturnsCRFPrint($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')		
			leftjoin('cms_users as rma_personnel', 'returns_header.level3_personnel','=', 'rma_personnel.id')			
			->select(
			'returns_header.*',
			'rma_personnel.name as rma_person'			
			)
			->where('returns_header.id',$id)->first();



			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.print_crf", $data);
		}
   

		public function ReturnsHistoryEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			//$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
			)
			->where('returns_header.id',$id)->first();



			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			

			$data['ClaimedStatus'] = ClaimedStatus::all();


			$data['ShipBackStatus'] = ShipBackStatus::all();


			$this->cbView("returns.history_edit", $data);
		}



		public function importPage() {
		    
	    	$data['page_title'] = 'Upload Retail';

	    	return view('ecomm_upload',$data);
	    	
	    }
	    
	    public function importExcel(Request $request) {
	        
	        ini_set('max_execution_time', 0);
			ini_set('memory_limit',"-1");

			
			
	    	$insert = array();
	    	$data_saved = false;

			$file = $request->file('import_file');
			
			$validator = \Validator::make(
				[
					'file' => $file,
					'extension' => strtolower($file->getClientOriginalExtension()),
				],
				[
					'file' => 'required',
					'extension' => 'required|in:xlsx',
				]
			);

			if ($validator->fails()) {
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Invalid Format."), 'danger');
			}
			
			
			if ($request->hasFile('import_file')) {
			    
			    
				$cnt_fail = 0;
				$item_counter = 0;
				$path = $request->file('import_file')->getRealPath();
				
				$up_option = $request->input('upload_option');

				$data = Excel::load($path, function ($reader) {
				})->get();
		
				$dataSTExcel = Excel::load($path, function($reader) {
					$reader->noHeading()->all();			
				})->skip(1)->get();
				
		
			

				if($cnt_fail == 0) {

						foreach ($data as $key => $value) {

							//$Store = DB::table('stores')->where('store_name', $value->customer_location)->first();
							
							/*if($value->brand == "APPLE"){
								$transactionID = 1;
							}else{
								$transactionID = 0;
							}*/



							$Search = DB::table('returns_header')->where('return_reference_no', $value->return_reference)->first();

							
							$Status = DB::table('warranty_statuses')->where('warranty_status', $value->return_status)->first();

							$Items = DB::table('digits_imfs')->where('digits_code', $value->digits_code)->first(); 

							$Category = DB::table('warehouse_category')->where('id', $Items->warehouse_category_id)->first();  


							//dd($Category->wh_category_description);

							//dd($Status->id);


							if($Search->transaction_type == 2 ){


								//$closed_personnel = $orderRow->verified_by;
								//$closed_date = 		$orderRow->level1_personnel_edited;

								if($Search->level1_personnel == 0  ){

									DB::table('returns_header')->where('return_reference_no', $value->return_reference)
									->update([
	
										'level1_personnel' =>  		  DB::table('cms_users')->where('name', $value->verified_by)->value('id')
	
									]);

								}


								if($Search->level3_personnel == 0  ){

									DB::table('returns_header')->where('return_reference_no', $value->return_reference)
									->update([
	
									
										'level3_personnel' =>  		  DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
	
									]);

								}

								if($Search->level4_personnel == 0  ){

									DB::table('returns_header')->where('return_reference_no', $value->return_reference)
									->update([
	 
										'level4_personnel' =>  		   DB::table('cms_users')->where('name', $value->printed_by)->value('id')
	
									]);

								}

								if($Search->level1_personnel == 0  ){

									DB::table('returns_header')->where('return_reference_no', $value->return_reference)
									->update([
	
								
										'level1_personnel' =>  		    DB::table('cms_users')->where('name', $value->closed_by)->value('id')
										//'level1_personnel_edited' =>  		$value->closed_date
	
									]);


								}

								
							}else{
	
									if($Search->diagnose == "REFUND"){
										//$transacted_personnel = $orderRow->transacted_by;
										//$transacted_date = 		$orderRow->level5_personnel_edited;
										//$closed_personnel = 	$orderRow->closed_by;
										//$closed_date = 			$orderRow->level7_personnel_edited;


										if($Search->level1_personnel == 0  ){

											DB::table('returns_header')->where('return_reference_no', $value->return_reference)
											->update([
	
												'level1_personnel' =>  	 DB::table('cms_users')->where('name', $value->verified_by)->value('id')
												
	
											]);
										}


										if($Search->level3_personnel == 0  ){

											DB::table('returns_header')->where('return_reference_no', $value->return_reference)
											->update([
	
											
												'level3_personnel' =>  		    DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
	
											]);
										}


										if($Search->level4_personnel == 0  ){

											DB::table('returns_header')->where('return_reference_no', $value->return_reference)
											->update([
	
									
												'level4_personnel' =>  		DB::table('cms_users')->where('name', $value->printed_by)->value('id')
	
											]);
										}

										if($Search->level5_personnel == 0  ){

											DB::table('returns_header')->where('return_reference_no', $value->return_reference)
											->update([
	
										
												'level5_personnel' =>  		     DB::table('cms_users')->where('name', $value->sor_by)->value('id')
											
	
											]);
										}


										if($Search->level7_personnel == 0  ){

											DB::table('returns_header')->where('return_reference_no', $value->return_reference)
											->update([
	
									
												'level7_personnel' =>  		  DB::table('cms_users')->where('name', $value->closed_by)->value('id')
	
											]);

										}


									}else{
										//$transacted_personnel = "";
										//$transacted_date = "";
										//$closed_personnel = $orderRow->transacted_by;
										//$closed_date = 		$orderRow->level5_personnel_edited;

									
											if($Search->level1_personnel == 0  ){

												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level1_personnel' =>  	  DB::table('cms_users')->where('name', $value->verified_by)->value('id')
		
												]);

												
											}
											
											if($Search->level3_personnel == 0  ){


												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
												
													'level3_personnel' =>  	DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
		
												]);
	

											}

											if($Search->level4_personnel == 0  ){


												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level4_personnel' =>  		   DB::table('cms_users')->where('name', $value->printed_by)->value('id')
		
												]);
												

											}

											if($Search->level5_personnel == 0  ){


												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level5_personnel' =>  		   DB::table('cms_users')->where('name', $value->closed_by)->value('id')
		
												]);

											}

										

									}
									
									if($Search->mode_of_return == "STORE DROP-OFF"){

											$scheduled_by = 	$orderRow->scheduled_logistics_by;
											$scheduled_date =	$orderRow->level8_personnel_edited;


											if($Search->level8_personnel == 0  ){

												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
	
													'level8_personnel' =>  		    DB::table('cms_users')->where('name', $value->scheduled_by)->value('id')
													//'level8_personnel_edited' =>  		$value->scheduled_date

												]);

											}

											

									}else{
									
											if($Search->level2_personnel == 0  ){

												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level2_personnel' =>  		    DB::table('cms_users')->where('name', $value->scheduled_by)->value('id')
													//'level2_personnel_edited' =>  		$value->scheduled_date
	
												]);

											}

										
									}


							
							}
				

							
							/*if (!empty($Search)) {

									DB::table('returns_header')->where('return_reference_no', $value->return_reference)
									->update([

										'diagnose' =>  		    $value->diagnose,
										//'created_at' =>  		$value->created_date,

										//'returns_status' =>  		$Status->id,
										//'returns_status_1' =>  		$Status->id,
										
										//'return_reference_no' =>  		$value->return_reference_no,
										//'purchase_location' =>  		$value->purchase_location,
										//'customer_last_name' =>  		$value->customer_last_name,
										//'customer_first_name' =>  		$value->customer_first_name,
										//'address' =>  		        $value->address,
										//'email_address' =>  		$value->email_address,
										//'contact_no' =>  		$value->contact,
										//'order_no' =>  		$value->order,
										'purchase_date' =>  		$value->purchase_date,

							
										
										//'bank_name' =>  		$value->bank_name,
										//'bank_account_no' =>  		$value->bank_account,
										//'bank_account_name' =>  		$value->bank_account_name,


										'items_included' =>  		$value->items_included,
										'items_included_others' =>  		$value->items_included_others,

										'verified_items_included' =>  		$value->verified_items_included,
										'verified_items_included_others' =>  		$value->verified_items_included_others,

										'customer_location' =>  		$value->customer_location,
										'deliver_to' =>  		$value->deliver_to,

										'return_schedule' =>  		$value->return_schedule,
										'pickup_schedule' =>  		$value->pickup_schedule,

										'refunded_date' =>  		$value->refunded_date,

										//'date_adjusted' =>  		$value->date_adjusted,
										//'stock_adj_ref_no' =>  		$value->stock_adjusted_ref,

										'sor_number' =>  		$value->sor_number,
										//'negative_positive_invoice' =>  		$value->negative_positive_invoice,
										//'pos_replacement_ref' =>  		$value->pos_replacement_ref,

										'warranty_status' =>  		$value->warranty_status,
										'ship_back_status' =>  		$value->ship_back_status,
										'claimed_status' =>  		$value->claimed_status,
										'credit_memo_number' =>  	$value->credit_memo,

										'comments' =>  		        $value->comments,
										'diagnose_comments' =>  	$value->diagnose_comments

									]);




									if($Search->transaction_type == 2 ){
										//$closed_personnel = $orderRow->verified_by;
										//$closed_date = 		$orderRow->level1_personnel_edited;



										DB::table('returns_header')->where('return_reference_no', $value->return_reference)
										->update([

											'level1_personnel' =>  		    $value->verified_by,
											'level1_personnel_edited' =>  		$value->verified_date,

											'level3_personnel' =>  		    $value->diagnose_by,
											'level3_personnel_edited' =>  		$value->diagnose_date,

											'level4_personnel' =>  		    $value->printed_by,
											'level4_personnel_edited' =>  		$value->printed_date,


											//'level4_personnel' =>  		    $value->sor_by,
											//'level4_personnel_edited' =>  		$value->sor_date,

											'level1_personnel' =>  		    $value->closed_by,
											'level1_personnel_edited' =>  		$value->closed_date

										]);

										
									}else{
			
											if($Search->diagnose == "REFUND"){
												//$transacted_personnel = $orderRow->transacted_by;
												//$transacted_date = 		$orderRow->level5_personnel_edited;
												//$closed_personnel = 	$orderRow->closed_by;
												//$closed_date = 			$orderRow->level7_personnel_edited;


												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level1_personnel' =>  		    $value->verified_by,
													'level1_personnel_edited' =>  		$value->verified_date,
		
													'level3_personnel' =>  		    $value->diagnose_by,
													'level3_personnel_edited' =>  		$value->diagnose_date,
		
													'level4_personnel' =>  		    $value->printed_by,
													'level4_personnel_edited' =>  		$value->printed_date,
		
		
													'level5_personnel' =>  		    $value->sor_by,
													'level5_personnel_edited' =>  		$value->sor_date,
		
													'level7_personnel' =>  		    $value->closed_by,
													'level7_personnel_edited' =>  		$value->closed_date
		
												]);

											}else{
												//$transacted_personnel = "";
												//$transacted_date = "";
												//$closed_personnel = $orderRow->transacted_by;
												//$closed_date = 		$orderRow->level5_personnel_edited;


												DB::table('returns_header')->where('return_reference_no', $value->return_reference)
												->update([
		
													'level1_personnel' =>  		    $value->verified_by,
													'level1_personnel_edited' =>  		$value->verified_date,
		
													'level3_personnel' =>  		    $value->diagnose_by,
													'level3_personnel_edited' =>  		$value->diagnose_date,
		
													'level4_personnel' =>  		    $value->printed_by,
													'level4_personnel_edited' =>  		$value->printed_date,
		
		
													//'level5_personnel' =>  		    $value->sor_by,
													//'level5_personnel_edited' =>  		$value->sor_date,
		
													'level5_personnel' =>  		    $value->closed_by,
													'level5_personnel_edited' =>  		$value->closed_date
		
												]);

											}
											
											if($Search->mode_of_return == "STORE DROP-OFF"){

													$scheduled_by = 	$orderRow->scheduled_logistics_by;
													$scheduled_date =	$orderRow->level8_personnel_edited;


													DB::table('returns_header')->where('return_reference_no', $value->return_reference)
													->update([
			
														'level8_personnel' =>  		    $value->scheduled_by,
														'level8_personnel_edited' =>  		$value->scheduled_date
	
													]);

											}else{
													//$scheduled_by = 	$orderRow->scheduled_by;
													//$scheduled_date =	$orderRow->level2_personnel_edited;

													DB::table('returns_header')->where('return_reference_no', $value->return_reference)
													->update([
			
														'level2_personnel' =>  		    $value->scheduled_by,
														'level2_personnel_edited' =>  		$value->scheduled_date
	
													]);
											}


									
									}


									if($Search->returns_status_1 == 1 ){

										DB::table('returns_header')->where('return_reference_no', $value->return_reference)
										->update([

											'returns_status' =>  		$Status->id,
											'returns_status_1' =>  		$Status->id

										]);


										DB::table('returns_body_item')->where('returns_header_id', $Search->id)
										->update([
			
											'digits_code' =>  		    $value->digits_code,
											'upc_code' =>  		$value->upc_code,
											'item_description' =>  		$value->item_description,
											'cost' =>  		$value->cost,
											'problem_details' =>  		$value->problem_details,
											'problem_details_other' =>  		$value->problem_details_others,
											'brand' =>  		$value->brand,
											'category' =>  		$Category->wh_category_description
										
										]);


										DB::table('returns_serial')->where('returns_header_id', $Search->id)
										->update([
			
											'serial_number' =>  		    $value->serial_number
										
										]);

									}


									$Search1 = DB::table('returns_body_item')->where('returns_header_id', $Search->id)->first();


									if (empty($Search1)) {

										DB::table('returns_body_item')
										->insert([

											'returns_header_id' =>  	$Search->id,
											'digits_code' =>  		    $value->digits_code,
											'upc_code' =>  		$value->upc_code,
											'item_description' =>  		$value->item_description,
											'cost' =>  		$value->cost,
											'problem_details' =>  		$value->problem_details,
											'problem_details_other' =>  		$value->problem_details_others,
											'brand' =>  		$value->brand,
											'category' =>  		$Category->wh_category_description
										
										]);



										$Search2 = DB::table('returns_body_item')->where('returns_header_id', $Search->id)->first();

										DB::table('returns_serial')
										->insert([
											
											'returns_body_item_id' =>  		    $Search2->id,

											'returns_header_id' =>  		    $Search->id,
											'serial_number' =>  		    $value->serial_number
										
										]);
										


									}
							}*/
						}

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Successfully Uploaded."), 'success');

				}else{
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Incorrect File, Check your File."), 'danger');
				}

			}
			
	        
	    }
	    
	    public function EditHistoryEcomm($id)
		{
		    
		  
		    
		}

		public function exportReturnHistoryEcomm()
		{
			$filename = 'Returns - ' . date("d M Y - h.i.sa");
			$orderData = self::getQueryData();
			$storeList = self::getStoreList();

			if(CRUDBooster::myPrivilegeName() == "Aftersales" || CRUDBooster::myPrivilegeName() == "Ecomm Ops"){

				$result = self::getAfterSalesOrEcommOpsResult($orderData);
				$headers = self::getAftersalesOrEcommOpsExportHeaders();

			} elseif(CRUDBooster::myPrivilegeName() == "Logistics") {

				$result = self::getLogisticsResult($orderData);
				$headers = self::getLogisticsExportHeaders();

			} elseif(CRUDBooster::myPrivilegeName() == "Retail Ops") {

				$result = self::getRetailOpsResult($orderData);
				$headers = self::getRetailOpsExportHeaders();

			} elseif(CRUDBooster::myPrivilegeName() == "Store Ops") {

				$result = self::getStoreOpsResult($orderData, $storeList);
				$headers = self::getStoreOpsExportHeaders();

			} else if(in_array(CRUDBooster::myPrivilegeName(), ['RMA Inbound', 'Tech Lead', 'RMA Technician', 'RMA Specialist'])) {

				$result = self::getRMAResult($orderData);
				$headers = self::getRMAExportHeaders();

			} else if (CRUDBooster::myPrivilegeName() == "Service Center") {
				
				$result = self::getServiceCenterResult($orderData, $storeList);
				$headers = self::getServiceCenterExportHeaders();

			} elseif(CRUDBooster::myPrivilegeName() == "Accounting") {

				$result = self::getAccountingResult($orderData);
				$headers = self::getAccountingExportHeaders();

			} elseif(CRUDBooster::myPrivilegeName() == "SDM") {

				$result = self::getSDMResult($orderData);
				$headers = self::getSDMExportHeaders();

			} else{

				$result = self::getOthersResult($orderData);
				$headers = self::getOthersExportHeaders();
			}

			$finalData = self::filterFinalData($result);

			$orderItems = self::processOrderData($finalData);

			self::exportToExcel($filename, $orderItems, $headers);
		}

		private function getQueryData(){
			$orderData = DB::table('returns_header')
			->leftjoin('via', 'returns_header.via_id','=', 'via.id')
			->leftjoin('warranty_statuses', 'returns_header.returns_status_1','=', 'warranty_statuses.id')
			->leftjoin('cms_users as verified', 'returns_header.level1_personnel','=', 'verified.id')
			->leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')		
			->leftjoin('cms_users as scheduled_logistics', 'returns_header.level8_personnel','=', 'scheduled_logistics.id')		
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')	
			->leftjoin('cms_users as received1', 'returns_header.received_by_rma_sc','=', 'received1.id')
			->leftjoin('cms_users as turnover', 'returns_header.rma_receiver_id','=', 'turnover.id')
			->leftjoin('cms_users as specialist', 'returns_header.rma_specialist_id','=', 'specialist.id')
			->leftJoin('returns_body_item', 'returns_header.id', '=', 'returns_body_item.returns_header_id')
			->select(   'returns_header.*', 
						'returns_header.created_at as datecreated',
						'returns_body_item.*', 
						'returns_body_item.id as body_id', 
						'verified.name as verified_by',	
						'scheduled.name as scheduled_by',
						'scheduled_logistics.name as scheduled_logistics_by',
						'diagnosed.name as diagnosed_by',
						'printed.name as printed_by',	
						'transacted.name as transacted_by',	
						'received.name as received_by',
						'received1.name as received_by1',
						'turnover.name as turnover_by',
						'specialist.name as specialist_by',
						'closed.name as closed_by',
						'via.*',
						'warranty_statuses.*'
		);

			return $orderData;
		}

		private function filterData($query){

			$filter_column = \Request::get('filter_column');

			$query->where(function($query) use ($filter_column) {

				foreach($filter_column as $key => $fc) {

					$value = @$fc['value'];
					$type  = @$fc['type'];

					if($value == '' && ($type == '' || $type == null)) continue;

					if($type == 'empty') {
						$query->whereNull($key)->orWhere($key,'');
						continue;
					}

					if($type == 'between') {
						$query->whereBetween($key, [$value[0], $value[1]]);
						continue;
					}

					switch($type) {
						case 'like':
						case 'not like':
							$value = '%'.$value.'%';
							if($key && $type && $value) $query->where($key,$type,$value);
						break;

						case 'in':
						case 'not in':
							if($value) {
								$value = explode(',',$value);
								if($key && $value) $query->whereIn($key,$value);
							}
						break;

						default:
							if($key && $type && $value) $query->where($key,$type,$value);
						break;
					}
				}
			});

			foreach($filter_column as $key=>$fc) {
				$sorting = @$fc['sorting'];

				if($sorting!='') {
					if($key) {
						$query->orderBy($key,$sorting);
					}
				}

			}

			return $query;
		
		}

		private function filterFinalData($result)
		{
			if (\Request::get('filter_column')) {
				return self::filterData($result)->get();
			} else {
				return $result->orderBy('returns_header.id', 'asc')->get();
			}
		}
		
		private function processOrderData($finalData)
		{
			$orderItems = [];

			$privilegeName = CRUDBooster::myPrivilegeName();

			foreach ($finalData as $orderLine) {

				switch($privilegeName){
					case 'Aftersales':
					case 'Ecomm Ops':
						$orderItems[] = self::getAfterSalesOrEcommOpsData($orderLine);
					break;

					case 'Logistics':
						$orderItems[] = self::getLogisticsData($orderLine);
					break;

					case 'Retail Ops':
						$orderItems[] = self::getRetailOpsData($orderLine);
					break;

					case 'RMA Inbound':
					case 'Tech Lead':
					case 'RRMA Technician':
					case 'RMA Specialist':
						$orderItems[] = self::getRMAData($orderLine);
					break;

					case 'Service Center':
						$orderItems[] = self::getServiceCenterData($orderLine);
					break;

					case 'Accounting':
						$orderItems[] = self::getAccountingData($orderLine);
					break;

					case 'SDM':
						$orderItems[] = self::getSDMData($orderLine);
					break;

					default:
						$orderItems[] = self::getOthersData($orderLine);
					break;
				}

				
			}

			return $orderItems;
		}

		private function exportToExcel($filename, $orderItems, $headers)
		{
			Excel::create($filename, function ($excel) use ($orderItems, $headers) {
				$excel->sheet('orders', function ($sheet) use ($orderItems, $headers) {
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat([
						'J' => '@', // for upc code
						'AI' => '0.00',
						'AJ' => '0.00',
						'AK' => '0.00',
					]);

					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headers);

				});
			})->export('xlsx');
		}

		private function getAfterSalesOrEcommOpsResult($orderData)
		{
			return $orderData->whereNotNull('returns_status_1')
			->where('transaction_type','!=', 2)
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
		private function getLogisticsResult($orderData)
		{
			return $orderData->whereNotIn('returns_status_1',  [
					ReturnsStatus::TO_SCHEDULE_LOGISTICS, 
					ReturnsStatus::RETURN_DELIVERY_DATE
			])
			->where('transaction_type','!=', 2)
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
		private function getRetailOpsResult($orderData)
		{
			return $orderData->whereNotNull('received_by')
			->where('transaction_type','!=', 2)
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
		private function getStoreOpsResult($orderData)
		{
			$storeList = self::getStoreList();

			return $orderData->whereNotNull('received_by')
			->where('returns_status_1','!=', ReturnsStatus::TO_PRINT_PF)
			->where('transaction_type','!=', 2)
			->whereIn('returns_header.stores_id', $storeList)
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}

		private function getRMAResult($orderData)
		{
			return $orderData->whereNotNull('returns_status_1')
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}

		private function getServiceCenterResult($orderData, $storeList)
		{

			return $orderData->where(function ($query) use ($storeList) {
				$query->whereNotIn('returns_status_1', [
					ReturnsStatus::TO_DIAGNOSE,
					ReturnsStatus::TO_SOR,
					ReturnsStatus::TO_PRINT_SSR
				])
				->where('transaction_type', 1)
				->whereNotNull('diagnose')
				->whereIn('returns_header.stores_id', $storeList);
			})
			->orWhere(function ($query) use ($storeList) {
				$query->whereNotIn('returns_status_1', [
					ReturnsStatus::TO_RECEIVE,
					ReturnsStatus::TO_PRINT_SSR,
					ReturnsStatus::TO_SOR
				])
				->where('transaction_type', 3)
				->whereNotNull('diagnose')
				->whereIn('returns_header.stores_id', $storeList);
			})
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
			
		}

		private function getAccountingResult($orderData)
		{
			return $orderData->whereNotIn('returns_status_1', [
				ReturnsStatus::TO_PRINT_CRF, 
				ReturnsStatus::REFUND_IN_PROCESS
			])
			->where('transaction_type','!=', 2)
			->where('diagnose', "REFUND")
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
	
		private function getSDMResult($orderData)
		{
			return $orderData->where('returns_status_1','!=',	ReturnsStatus::TO_SOR)
			->where('transaction_type','!=', 2)
			->where('diagnose', "REFUND")
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
		private function getOthersResult($orderData)
		{
			return $orderData->whereNotNull('returns_status_1')
			->groupBy('return_reference_no')
			->orderBy('datecreated', 'desc'); 
		}
		
		private function getRMAData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,				
				$orderLine->return_reference_no,
				$orderLine->inc_number,			
				$orderLine->rma_number,			
				$orderLine->via_name,			
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,								
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,      
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->warranty_status,
				$orderLine->ship_back_status,
				$orderLine->claimed_status,
				$orderLine->credit_memo_number,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->turnover_by,
				$orderLine->rma_receiver_date_received,
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->specialist_by,
				$orderLine->rma_specialist_date_received,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getServiceCenterData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,				
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,				
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,     
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->warranty_status,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getAfterSalesOrEcommOpsData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,
				$orderLine->return_reference_no,	
				$orderLine->via_name,			
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,				
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->mode_of_refund,	
				$orderLine->bank_name,                  
				$orderLine->bank_account_no,                   
				$orderLine->bank_account_name,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,    
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getLogisticsData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,			
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,								
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,      
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getRetailOpsData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,				
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,	
				$orderLine->branch_dropoff,			
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,    
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
    			$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getAccountingData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,		
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->mode_of_refund,	
				$orderLine->bank_name,                  
				$orderLine->bank_account_no,                   
				$orderLine->bank_account_name,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,      
				$orderLine->negative_positive_invoice,    
				$orderLine->pos_replacement_ref,
				$orderLine->pos_crf_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->turnover_by,
				$orderLine->rma_receiver_date_received,
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->specialist_by,
				$orderLine->rma_specialist_date_received,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getSDMData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->created_at,				
				$orderLine->return_reference_no,					
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,				
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,     
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getStoreOpsData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,				
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,				
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,    
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getOthersData($orderLine){
			$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->datecreated,				
				$orderLine->return_reference_no,	
				$orderLine->via_name,				
				$orderLine->purchase_location,
				$orderLine->branch_dropoff,
				$orderLine->customer_last_name,		
				$orderLine->customer_first_name,	
				$orderLine->address,		            
				$orderLine->email_address,      
				$orderLine->contact_no,    
				$orderLine->order_no,		
				$orderLine->purchase_date,			
				$orderLine->mode_of_payment,		
				$orderLine->mode_of_refund,	
				$orderLine->bank_name,                  
				$orderLine->bank_account_no,                   
				$orderLine->bank_account_name,		
				$orderLine->items_included,                      
				$orderLine->items_included_others, 
				$orderLine->verified_items_included,                      
				$orderLine->verified_items_included_others, 
				$orderLine->customer_location,  
				$orderLine->deliver_to,                 
				$orderLine->return_schedule,                     
				$orderLine->pickup_schedule,   
				$orderLine->refunded_date,  
				$orderLine->sor_number,      
				$orderLine->pos_crf_number,
				$orderLine->dr_number,
				$orderLine->digits_code,               
				$orderLine->upc_code,                 
				$orderLine->item_description,            
				$orderLine->cost,          
				$orderLine->brand,
				$serial_no->serial_number,
				$orderLine->problem_details,
				$orderLine->problem_details_other,                
				$orderLine->quantity,
				$orderLine->warranty_status,
				$orderLine->ship_back_status,
				$orderLine->claimed_status,
				$orderLine->credit_memo_number,
				$orderLine->verified_by,
				$orderLine->level1_personnel_edited,
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->turnover_by,
				$orderLine->rma_receiver_date_received,
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level3_personnel_edited,
				$orderLine->specialist_by,
				$orderLine->rma_specialist_date_received,
				$orderLine->printed_by,
				$orderLine->level4_personnel_edited,
				self::getTransactedPersonnel($orderLine),
				self::getTransactedDate($orderLine),
				self::getClosedPersonnel($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}
		private function getRMAExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'INC#',
				'RMA#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',        
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',        
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                
				'UPC CODE',      
				'ITEM DESCRIPTION',               
				'COST',                 
				'BRAND',                
				'SERIAL#',                  
				'PROBLEM DETAILS',       
				'PROBLEM DETAILS OTHERS',       
				'QUANTITY',             
				'WARRANTY STATUS',
				'SHIP BACK STATUS',             
				'CLAIMED STATUS',             
				'CREDIT MEMO#',             
				'VERIFIED BY',             
				'VERIFIED DATE',             
				'SCHEDULED BY',             
				'SCHEDULED DATE',             
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PROCESSED BY',           
				'PROCESSED DATE',  
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getAftersalesOrEcommOpsExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'MODE OF REFUND',
				'BANK NAME',   
				'BANK ACCOUNT#',      
				'BANK ACCOUNT NAME',        
				'ITEMS INCLUDED',       
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',        
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',             
				'DELIVER TO',            
				'RETURN SCHEDULE',              
				'PICKUP SCHEDULE',             
				'REFUNDED DATE',              
				'SOR#',             
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',      
				'ITEM DESCRIPTION',
				'COST',                 
				'BRAND',             
				'SERIAL#',             
				'PROBLEM DETAILS',      
				'PROBLEM DETAILS OTHERS',      
				'QUANTITY',          
				'VERIFIED BY',           
				'VERIFIED DATE',          
				'SCHEDULED BY',        
				'SCHEDULED DATE',       
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',          
				'PROCESSED BY',          
				'PROCESSED DATE',  
				'PRINTED BY',          
				'PRINTED DATE',         
				'SOR BY',        
				'SOR DATE',          
				'CLOSED BY',          
				'CLOSED DATE',          
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getLogisticsExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',        
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',        
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',      
				'ITEM DESCRIPTION',               
				'COST',               
				'BRAND',              
				'SERIAL#',                
				'PROBLEM DETAILS',       
				'PROBLEM DETAILS OTHERS',  
				'QUANTITY',             
				'VERIFIED BY',           
				'VERIFIED DATE',           
				'SCHEDULED BY',           
				'SCHEDULED DATE',           
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PROCESSED BY',           
				'PROCESSED DATE',  
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getRetailOpsExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',         
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',         
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',      
				'ITEM DESCRIPTION',              
				'COST',                
				'BRAND',             
				'SERIAL#',              
				'PROBLEM DETAILS',       
				'PROBLEM DETAILS OTHERS',       
				'QUANTITY',         
				'VERIFIED BY',           
				'VERIFIED DATE',           
				'SCHEDULED BY',           
				'SCHEDULED DATE',           
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PROCESSED BY',           
				'PROCESSED DATE',  
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getStoreOpsExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',         
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',         
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'Negative/Positive Invoice', 
				'POS Replacement Ref#', 
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',      
				'ITEM DESCRIPTION',               
				'COST',                 
				'BRAND',               
				'SERIAL#',               
				'PROBLEM DETAILS',      
				'PROBLEM DETAILS OTHERS',      
				'QUANTITY',             
				'VERIFIED BY',             
				'VERIFIED DATE',             
				'SCHEDULED BY',             
				'SCHEDULED DATE',             
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PROCESSED BY',           
				'PROCESSED DATE',  
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getServiceCenterExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',         
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',         
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',     
				'ITEM DESCRIPTION',              
				'COST',                
				'BRAND',              
				'SERIAL#',              
				'PROBLEM DETAILS',    
				'PROBLEM DETAILS OTHERS',    
				'QUANTITY',           
				'WARRANTY STATUS', 
				'VERIFIED BY',           
				'VERIFIED DATE',           
				'SCHEDULED BY',           
				'SCHEDULED DATE',           
				'RECEIVED BY',
				'RECEIVED DATE',    						
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getAccountingExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'MODE OF REFUND',
				'BANK NAME',    
				'BANK ACCOUNT#',      
				'BANK ACCOUNT NAME',       
				'ITEMS INCLUDED',        
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',        
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'Negative/Positive Invoice', 
				'POS Replacement Ref#', 
				'POS CRF#',
				'DIGITS CODE',                 
				'UPC CODE',      
				'ITEM DESCRIPTION',              
				'COST',               
				'BRAND',             
				'SERIAL#',              
				'PROBLEM DETAILS',     
				'PROBLEM DETAILS OTHERS',     
				'QUANTITY',          
				'VERIFIED BY',           
				'VERIFIED DATE',           
				'SCHEDULED BY',           
				'SCHEDULED DATE',           
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',           
				'DIAGNOSED DATE',           
				'PROCESSED BY',           
				'PROCESSED DATE',  
				'PRINTED BY',           
				'PRINTED DATE',           
				'SOR BY',           
				'SOR DATE',           
				'CLOSED BY',           
				'CLOSED DATE',           
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getSDMExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'ITEMS INCLUDED',        
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',         
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',     
				'ITEM DESCRIPTION',               
				'COST',                 
				'BRAND',             
				'SERIAL#',               
				'PROBLEM DETAILS',      
				'PROBLEM DETAILS OTHERS',      
				'QUANTITY',          
				'VERIFIED BY',          
				'VERIFIED DATE',          
				'SCHEDULED BY',          
				'SCHEDULED DATE',          
				'DIAGNOSED BY',          
				'DIAGNOSED DATE',          
				'PRINTED BY',          
				'PRINTED DATE',          
				'SOR BY',          
				'SOR DATE',          
				'CLOSED BY',          
				'CLOSED DATE',          
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}
		private function getOthersExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'VIA',
				'PURCHASE LOCATION',
				'BRANCH DROP-OFF',
				'CUSTOMER LAST NAME',
				'CUSTOMER FIRST NAME',
				'ADDRESS',
				'EMAIL ADDRESS',
				'CONTACT#',
				'ORDER#',
				'PURCHASE DATE',
				'ORIGINAL MODE OF PAYMENT',
				'MODE OF REFUND',
				'BANK NAME',    
				'BANK ACCOUNT#',      
				'BANK ACCOUNT NAME',         
				'ITEMS INCLUDED',         
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',         
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'RETURN SCHEDULE',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'SOR#',               
				'POS CRF#',
				'DR#',
				'DIGITS CODE',                 
				'UPC CODE',     
				'ITEM DESCRIPTION',              
				'COST',               
				'BRAND',              
				'SERIAL#',                
				'PROBLEM DETAILS',      
				'PROBLEM DETAILS OTHERS',      
				'QUANTITY',         
				'WARRANTY STATUS',
				'SHIP BACK STATUS',         
				'CLAIMED STATUS',          
				'CREDIT MEMO#',          
				'VERIFIED BY',          
				'VERIFIED DATE',          
				'SCHEDULED BY',          
				'SCHEDULED DATE',          
				'RECEIVED BY',
				'RECEIVED DATE',
				'TURNOVER BY',
				'TURNOVER DATE',
				'DIAGNOSED BY',          
				'DIAGNOSED DATE',          
				'PROCESSED BY',          
				'PROCESSED DATE',  
				'PRINTED BY',          
				'PRINTED DATE',          
				'SOR BY',          
				'SOR DATE',          
				'CLOSED BY',          
				'CLOSED DATE',          
				'COMMENTS',
				'DIAGNOSED COMMENTS'
			];		

		}

		private function getStoreList()
		{
			$userStores = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->pluck('stores_id')->toArray();
			return array_map('intval', explode(",", implode(",", $userStores)));
		}

		private function getScheduledBy($orderLine)
		{
			return $orderLine->mode_of_return == "STORE DROP-OFF" ? $orderLine->scheduled_logistics_by : $orderLine->scheduled_by;
		}

		private function getScheduledDate($orderLine)
		{
			return $orderLine->mode_of_return == "STORE DROP-OFF" ? $orderLine->level8_personnel_edited : $orderLine->level2_personnel_edited;
		}

		private function getTransactedPersonnel($orderLine)
		{
			return $orderLine->transaction_type == 2 ? $orderLine->verified_by : ($orderLine->diagnose == "REFUND" ? $orderLine->transacted_by : "");
		}

		private function getTransactedDate($orderLine)
		{
			return $orderLine->transaction_type == 2 ? $orderLine->level1_personnel_edited : ($orderLine->diagnose == "REFUND" ? $orderLine->level5_personnel_edited : "");
		}

		private function getClosedPersonnel($orderLine)
		{
			return $orderLine->transaction_type == 2 ? $orderLine->verified_by : ($orderLine->diagnose == "REFUND" ? $orderLine->closed_by : $orderLine->transacted_by);
		}

		private function getClosedDate($orderLine)
		{
			return $orderLine->transaction_type == 2 ? $orderLine->level1_personnel_edited : ($orderLine->diagnose == "REFUND" ? $orderLine->level7_personnel_edited : $orderLine->level5_personnel_edited);
		}

	}