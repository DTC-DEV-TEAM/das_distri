<?php namespace App\Http\Controllers;

use Session;
//use Request;
use DB;
use CRUDBooster;
use App\ReturnsStatus;
use App\ReturnsHeaderRTL;
use App\ReturnsBodyRTL;
use App\ReturnsSerialsRTL;
use App\ProblemDetails;
use App\Stores;
use App\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use App\ShipBackStatus;
use App\ClaimedStatus;
use App\StoresFrontEnd;


	class AdminRetailReturnHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "returns_header_retail";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];


			if(CRUDBooster::myPrivilegeName() == "Logistics"){
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else if(CRUDBooster::myPrivilegeName() == "RMA"){
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
			}else if(CRUDBooster::myPrivilegeName() == "Accounting" || CRUDBooster::myPrivilegeName() == "Inventory Control" ){
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
			}else if(CRUDBooster::myPrivilegeName() == "Aftersales (Ops)"){
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
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
			}else if(CRUDBooster::myPrivilegeName() == "SDM"){
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
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
			}elseif(CRUDBooster::myPrivilegeName() == "Retail Ops"){ 
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Customer","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else{
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
			$this->form[] = ['label'=>'Online Store','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Reference No','name'=>'return_reference_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Location','name'=>'purchase_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store','name'=>'store','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Last Name','name'=>'customer_last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer First Name','name'=>'customer_first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header_retail','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
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
			$this->form[] = ['label'=>'Diagnose Comments','name'=>'diagnose_comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sor Number','name'=>'sor_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Total Quantity','name'=>'total_quantity','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Diagnose','name'=>'diagnose','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			//$this->form[] = ["label"=>"Online Store","name"=>"customer_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Return Reference No","name"=>"return_reference_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purchase Location","name"=>"purchase_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Store","name"=>"store","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer Last Name","name"=>"customer_last_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer First Name","name"=>"customer_first_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Address","name"=>"address","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Email Address","name"=>"email_address","type"=>"email","required"=>TRUE,"validation"=>"required|min:1|max:255|email|unique:returns_header_retail","placeholder"=>"Please enter a valid email address"];
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
			//$this->form[] = ["label"=>"Diagnose Comments","name"=>"diagnose_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Customer Location","name"=>"customer_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sor Number","name"=>"sor_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total Quantity","name"=>"total_quantity","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Diagnose","name"=>"diagnose","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			$cancelled = 	ReturnsStatus::where('id','28')->value('id');

			$refund_complete = 			ReturnsStatus::where('id','11')->value('id');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('id');
			$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');	
			if(CRUDBooster::myPrivilegeName() == "RMA"){
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsHistoryEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $refund_complete or [returns_status_1] == $return_invalid or [returns_status_1] == $repair_complete or [returns_status_1] == $replacement_complete"];
			}
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsHistoryDetailRTL/[id]'),'icon'=>'fa fa-eye'];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsPulloutPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] != $cancelled"];
			$this->addaction[] = ['title'=>'CRF','url'=>CRUDBooster::mainpath('ReturnsCRFPrintRTL/[id]'),'icon'=>'fa fa-file', "showIf"=>"[diagnose] == 'REFUND' and [level2_personnel] != null"];
			$this->addaction[] = ['title'=>'RF','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrintRTL/[id]'),'icon'=>'fa fa-file', "showIf"=>"[diagnose] == 'REJECT' and [level2_personnel] != null or [diagnose] == 'REPAIR' and [level2_personnel] != null"];
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
				if(CRUDBooster::myPrivilegeName() == "Admin Ops" ||  CRUDBooster::isSuperadmin()){ 

						$this->button_selected[] = ['label'=>'Void',
													'icon'=>'fa fa-times-circle',
													'name'=>'void'];
													
						$this->button_selected[] = ['label'=>'Change to Instant Replacement',
													'icon'=>'fa fa-times-circle',
													'name'=>'to_instant_replacement'];
						$this->button_selected[] = ['label'=>'Change to Normal Replacement',
													'icon'=>'fa fa-times-circle',
													'name'=>'to_normal_replacement'];							
		
				}else{
				    
			
				    
				    if(CRUDBooster::myName() == "Jomark Tamboong" || 
				       CRUDBooster::myName() == "Aldrin Gerasmio" ||
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
        				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsRTL').'?'.urldecode(http_build_query(@$_GET))];
        				//$this->index_button[] = ["label"=>"Export Returns","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturns'),"color"=>"success"];

						//$this->index_button[] = ['label' => 'Upload', "url" => CRUDBooster::mainpath("import-retail"), "icon" => "fa fa-upload", "color"=>"warning"];
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
				
				ReturnsHeaderRTL::whereIn('id',$id_selected)->update([
					'returns_status_1'=> 28,
					'returns_status'=> 28
				]);
					

			

				DB::connection('mysql_front_end')
				->statement("update returns_header_retail set returns_status_1 = 28,
								returns_status = 28
								where id  in  ('$id_selected')");
	
				DB::disconnect('mysql_front_end');

			
				
			}
			
			if($button_name == 'to_normal_replacement'){
			    ReturnsHeaderRTL::whereIn('id',$id_selected)->update([
					'transaction_type_id'=> 2
				]);
			}
			
			if($button_name == 'to_instant_replacement'){
			    ReturnsHeaderRTL::whereIn('id',$id_selected)->update([
					'transaction_type_id'=> 1
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
	   
	        if(CRUDBooster::myPrivilegeName() == "Retail Ops"){
				$requested = ReturnsStatus::where('id','1')->value('id');

				$query->where('returns_status_1','!=',$requested)->where('transaction_type','!=', 2)->orderBy('history_status', 'desc');  

			}elseif(CRUDBooster::myPrivilegeName() == "Store Ops"){
			    
				$requested = ReturnsStatus::where('id','1')->value('id');
				
				$print_pf = 	ReturnsStatus::where('id','19')->value('id');
				
    			$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
				    array_push($approval_array, $matrix->stores_id);
				}
				$approval_string = implode(",",$approval_array);
				$storeList = array_map('intval',explode(",",$approval_string));	
				
				//->whereIn('returns_header_retail.stores_id', $storeList)->where('returns_status_1','!=',$print_pf);

				$query->where('returns_status_1','!=',$requested)->where('transaction_type','!=', 2)->where('returns_header_retail.level7_personnel', CRUDBooster::myId())->orderBy('history_status', 'desc');  

			}else if(CRUDBooster::myPrivilegeName() == "Logistics"){
				$requested = ReturnsStatus::where('id','1')->value('id');
				$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
				$pending = ReturnsStatus::where('id','19')->value('id');
				
				$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

				$query->where('returns_status_1','!=',$requested)->where('returns_status_1','!=', $return_delivery_date)->where('transaction_type','!=', 2)->where('returns_status_1','!=',$to_schedule)->where('returns_status_1','!=',$pending)->orderBy('history_status', 'desc'); 

			}else if(CRUDBooster::myPrivilegeName() == "RMA"){
				$to_diagnose = ReturnsStatus::where('id','5')->value('id');
				$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
				$to_sor = 			ReturnsStatus::where('id','9')->value('id');

				//$query->where('returns_status_1','!=',$to_diagnose)->where('transaction_type', 0)->where('returns_status_1','!=',$to_print_return_form)->where('returns_status_1','!=',$to_sor)->whereNotNull('diagnose')->orderBy('history_status', 'desc'); 

                $query->whereNotNull('returns_status_1')->orderBy('history_status', 'desc'); 
                
			}elseif(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				$query->where(function($sub_query){
				                
				                $requested = 		ReturnsStatus::where('id','1')->value('id');
                				$to_diagnose = ReturnsStatus::where('id','5')->value('id');
                	
                				$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
                
                				$to_sor = ReturnsStatus::where('id','9')->value('id');	
                				
                				$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
                				$approval_array = array();
                				foreach($approvalMatrix as $matrix){
                				    array_push($approval_array, $matrix->stores_id);
                				}
                				$approval_string = implode(",",$approval_array);
                				$storeList = array_map('intval',explode(",",$approval_string));     
        				
                				
				        		$sub_query->where('returns_status_1','!=',	$to_diagnose)->where('transaction_type', 1)->where('returns_status_1','!=',	$to_print_return_form)->where('returns_status_1','!=',	$to_sor)->whereNotNull('diagnose')->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('history_status', 'desc'); 
				        		
				        		$sub_query->orwhere('returns_status_1','!=',$to_diagnose)->where('transaction_type', 3)->where('returns_status_1','!=',$to_print_return_form)->where('returns_status_1','!=',$to_sor)->whereNotNull('diagnose')->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('history_status', 'desc'); 
				});		
				
			}else if(CRUDBooster::myPrivilegeName() == "Accounting"){

				$query->where(function($sub_query){
					$to_print_crf = 	ReturnsStatus::where('id','7')->value('id');
					$refund_in_process = ReturnsStatus::where('id','8')->value('id');
					$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');

					$sub_query->where('returns_status_1','!=',$to_print_crf)->where('transaction_type','!=', 2)->where('returns_status_1','!=',$refund_in_process)->where('diagnose', "REFUND")->orderBy('history_status', 'desc'); 
					//$sub_query->orWhere('returns_status_1', $replacement_complete)->where('diagnose', "REPLACE")->orderBy('id', 'asc'); 
				});

			}else if(CRUDBooster::myPrivilegeName() == "Aftersales (Ops)"){
			    	/*
				$query->where(function($sub_query){
					$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
					$repair_complete = 			ReturnsStatus::where('id','17')->value('id');
					$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
					$to_close = ReturnsStatus::where('id','30')->value('id');
				
				$sub_query->where('returns_status_1', $return_invalid)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $repair_complete)->orderBy('id', 'asc'); 
					$sub_query->orWhere('returns_status_1', $replacement_complete)->orderBy('id', 'asc');
					$sub_query->orwherenotnull('level8_personnel')->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $to_close)->orderBy('id', 'asc'); 
				});
                    */ 
       			$requested = ReturnsStatus::where('id','1')->value('id');

				//$query->where('returns_status_1','!=',$requested)->orderBy('id', 'asc');  
				$query->whereNotNull('returns_status_1')->where('transaction_type','!=', 2)->orderBy('history_status', 'desc'); 
                    
			}else if(CRUDBooster::myPrivilegeName() == "SDM"){
				$query->where(function($sub_query){
					
					$sub_query->where('diagnose', "REPLACE")->wherenotnull('level6_personnel')->where('transaction_type','!=', 2)->orderBy('history_status', 'asc');  
 
				});
			}else{
				$query->whereNotNull('returns_status_1')->orderBy('history_status', 'desc'); 
			}
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
			$to_schedule = 				ReturnsStatus::where('id','18')->value('warranty_status');
			$pending = 					ReturnsStatus::where('id','19')->value('warranty_status');
			$to_diagnose = 				ReturnsStatus::where('id','5')->value('warranty_status');
			$to_print_crf = 			ReturnsStatus::where('id','7')->value('warranty_status');
			$to_sor = 					ReturnsStatus::where('id','9')->value('warranty_status');
			$refund_in_process = 		ReturnsStatus::where('id','8')->value('warranty_status');
			$refund_complete = 			ReturnsStatus::where('id','11')->value('warranty_status');
			$to_print_return_form = 	ReturnsStatus::where('id','13')->value('warranty_status');
			$to_ship_back = 			ReturnsStatus::where('id','14')->value('warranty_status');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('warranty_status');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('warranty_status');
			$for_replacement = 	  		ReturnsStatus::where('id','20')->value('warranty_status');
			$replacement_complete = 	ReturnsStatus::where('id','21')->value('warranty_status');
			$to_create_crf = 			ReturnsStatus::where('id','25')->value('warranty_status');
			$for_replacement_sdm = 	  	ReturnsStatus::where('id','26')->value('warranty_status');
			$cancelled = 	  			ReturnsStatus::where('id','28')->value('warranty_status');
			$to_close = 				ReturnsStatus::where('id','30')->value('warranty_status');
            $to_print_pf = 				ReturnsStatus::where('id','19')->value('warranty_status');
            $received = 	            ReturnsStatus::where('id','31')->value('warranty_status');
             
            $to_receive  = 	            ReturnsStatus::where('id','29')->value('warranty_status');    
            $return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
            
            $toscheduleLogisitics =     ReturnsStatus::where('id','23')->value('warranty_status');

			$to_receive_rma = 			ReturnsStatus::where('id','34')->value('warranty_status');
			$to_receive_sc = 			ReturnsStatus::where('id','35')->value('warranty_status');
			$to_turnover = 				ReturnsStatus::where('id','37')->value('warranty_status');


						if($column_index == 1){
				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $toscheduleLogisitics){
					$column_value = '<span class="label label-warning">'.$toscheduleLogisitics.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_print_crf){
					$column_value = '<span class="label label-warning">'.$to_print_crf.'</span>';
			
				}elseif($column_value == $to_print_pf){
					$column_value = '<span class="label label-warning">'.$to_print_pf.'</span>';
			
				}elseif($column_value == $to_sor){
					$column_value = '<span class="label label-warning">'.$to_sor.'</span>';
			
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
			
				}elseif($column_value == $for_replacement){
					$column_value = '<span class="label label-warning">'.$for_replacement.'</span>';
			
				}elseif($column_value == $replacement_complete){
					$column_value = '<span class="label label-success">'.$replacement_complete.'</span>';
			
				}elseif($column_value == $to_create_crf){
					$column_value = '<span class="label label-warning">'.$to_create_crf.'</span>';
			
				}elseif($column_value == $for_replacement_sdm){
					$column_value = '<span class="label label-warning">'.$for_replacement_sdm.'</span>';
			
				}elseif($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
			
				}elseif($column_value == $to_close){
					$column_value = '<span class="label label-warning">'.$to_close.'</span>';
			
				}elseif($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $received){
					$column_value = '<span class="label label-success">'.$received.'</span>';
			
				}elseif($column_value == $to_receive){
					$column_value = '<span class="label label-warning">'.$to_receive.'</span>';
			
				}elseif($column_value == $return_delivery_date){
					$column_value = '<span class="label label-warning">'.$return_delivery_date.'</span>';
			
				}elseif($column_value == $to_receive_rma){
					$column_value = '<span class="label label-warning">'.$to_receive_rma.'</span>';
			
				}elseif($column_value == $to_receive_sc){
					$column_value = '<span class="label label-warning">'.$to_receive_sc.'</span>';
			
				}elseif($column_value == $to_turnover){
					$column_value = '<span class="label label-warning">'.$to_turnover.'</span>';
			
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



	    //By the way, you can still create your own method in here... :) 

		public function ReturnsHistoryDetailRTL($id)
		{
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            } 

			$data = array();
			$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')																		
			
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'created.name as created_by'						
			)
			->where('returns_header_retail.id',$id)->first();

			

            if($data['row']->returns_status_1 == 1){
            			$data['resultlist'] = ReturnsBodyRTL::
            			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
            			->select(
            			'returns_body_item_retail.*',
            			'returns_serial_retail.*'					
            			)
            			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();                
            }else{
            			$data['resultlist'] = ReturnsBodyRTL::
            			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
            			->select(
            			'returns_body_item_retail.*',
            			'returns_serial_retail.*'					
            			)
            			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
            }
            
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.history_view_retail", $data);
		}


		public function ReturnsPulloutPrint($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'created.name as created_by'						
			)
			->where('returns_header_retail.id',$id)->first();

			


			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_pullout", $data);
		}
		


		public function ReturnsCRFPrintRTL($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'created.name as created_by'						
			)
			->where('returns_header_retail.id',$id)->first();

			


			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.print_crf_retail", $data);
		}

		public function ReturnsReturnFormPrintRTL($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'created.name as created_by'						
			)
			->where('returns_header_retail.id',$id)->first();

			


			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_return_form_retail", $data);
		}


		public function GetExtractReturnsRTL() {

            $filename = 'Returns Retail - ' . date("d M Y - h.i.sa");
			$sheetname = 'Returns Retail'.date("d-M-Y");
            ini_set('memory_limit', '512M');
			Excel::create($filename, function ($excel) {
				$excel->sheet('orders', function ($sheet) {	
					// Set auto size for sheet
					
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
					    'J' => '@',		//for upc code
					    'AI' => '0.00',
					    'AJ' => '0.00',
					    'AK' => '0.00',
					));


					if(CRUDBooster::myPrivilegeName() == "Retail Ops"){
									$requested = ReturnsStatus::where('id','1')->value('id');

									$orderData = DB::table('returns_header_retail')
									->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
									->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
									->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
									->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
									->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
									->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
									->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
									->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
									->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
									->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
									->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
									->select(   'returns_header_retail.*', 
									            'returns_header_retail.created_at as datecreated',
												'returns_body_item_retail.*', 
												'returns_body_item_retail.id as body_id', 
												'verified.name as verified_by',	
												'scheduled_logistics.name as scheduled_logistics_by',
												'diagnosed.name as diagnosed_by',
												'printed.name as printed_by',	
												'transacted.name as transacted_by',	
												'received.name as received_by',
												'received1.name as received_by1',
												'closed.name as closed_by',
												'via.*',
												'warranty_statuses.*'
									)->whereNotNull('returns_body_item_retail.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested)->groupby('returns_header_retail.return_reference_no');
									
									
															if(\Request::get('filter_column')) {

									$filter_column = \Request::get('filter_column');

									$orderData->where(function($w) use ($filter_column,$fc) {
										foreach($filter_column as $key=>$fc) {

											$value = @$fc['value'];
											$type  = @$fc['type'];

											if($type == 'empty') {
												$w->whereNull($key)->orWhere($key,'');
												continue;
											}

											if($value=='' || $type=='') continue;

											if($type == 'between') continue;

											switch($type) {
												default:
													if($key && $type && $value) $w->where($key,$type,$value);
												break;
												case 'like':
												case 'not like':
													$value = '%'.$value.'%';
													if($key && $type && $value) $w->where($key,$type,$value);
												break;
												case 'in':
												case 'not in':
													if($value) {
														$value = explode(',',$value);
														if($key && $value) $w->whereIn($key,$value);
													}
												break;
											}
										}
									});

									foreach($filter_column as $key=>$fc) {
										$value = @$fc['value'];
										$type  = @$fc['type'];
										$sorting = @$fc['sorting'];

										if($sorting!='') {
											if($key) {
												$orderData->orderby($key,$sorting);
												$filter_is_orderby = true;
											}
										}

										if ($type=='between') {
											if($key && $value) $orderData->whereBetween($key,$value);
										}

										else {
											continue;
										}
									}
								}

								$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
								$blank_field = '';
								$store_inv = '';
								$counter=0;
								$final_count = count((array)$ordeDataLines) + 1;
								foreach ($ordeDataLines as $orderRow) {
									$counter++;
									/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
									$itemBrand = Brand::where('id', $item->brand_id)->first();
									$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
									$itemCategory = Category::where('id', $item->category_id)->first();
									$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
									*/

						
									$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
									
									
									if($orderRow->transaction_type == 3 ){
										

											
											$scheduled_by =  "";
											$scheduled_date = "";
											$verified = $orderRow->verified_by;
											$verified_date = $orderRow->level7_personnel_edited;
											
											
										if($orderRow->diagnose == "REFUND"){
												$printed_by = $orderRow->printed_by;
												$printed_date = $orderRow->level3_personnel_edited;
												$transacted_by = $orderRow->transacted_by;
												$transacted_date = $orderRow->level4_personnel_edited;
												$closed_by = $orderRow->closed_by;
												$closed_date = $orderRow->level5_personnel_edited;
										}elseif($orderRow->diagnose == "REPLACE"){
											$printed_by = "";
											$printed_date = "";
											$transacted_by = $orderRow->printed_by;
											$transacted_date = $orderRow->level3_personnel_edited;
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;
										}else{
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = "";
											$transacted_date = "";
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;	
										}
											
											
									}else{
										
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										$scheduled_by = $orderRow->scheduled_logistics_by;
										$scheduled_date = $orderRow->level1_personnel_edited;
										if($orderRow->diagnose == "REFUND"){
												$printed_by = $orderRow->printed_by;
												$printed_date = $orderRow->level3_personnel_edited;
												$transacted_by = $orderRow->transacted_by;
												$transacted_date = $orderRow->level4_personnel_edited;
												$closed_by = $orderRow->closed_by;
												$closed_date = $orderRow->level5_personnel_edited;
										}elseif($orderRow->diagnose == "REPLACE"){
											$printed_by = "";
											$printed_date = "";
											$transacted_by = $orderRow->printed_by;
											$transacted_date = $orderRow->level3_personnel_edited;
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;
										}else{
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = "";
											$transacted_date = "";
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;	
										}
									}
									
						
									$orderItems[] = array(
										//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
										//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
										$orderRow->warranty_status, 		
										$orderRow->diagnose, 	
										$orderRow->datecreated,				
										$orderRow->return_reference_no,	
										$orderRow->via_name,				
										$orderRow->purchase_location,				
										$orderRow->customer_last_name,		
										$orderRow->customer_first_name,	
										$orderRow->address,		            
										$orderRow->email_address,      
										$orderRow->contact_no,    
										$orderRow->order_no,		
										$orderRow->purchase_date,			
										$orderRow->mode_of_payment,		
										$orderRow->bank_name,                  
										$orderRow->bank_account_no,                   
										$orderRow->bank_account_name,		
										$orderRow->items_included,                      
										$orderRow->items_included_others, 
										$orderRow->verified_items_included,                      
										$orderRow->verified_items_included_others, 
										$orderRow->customer_location,  
										$orderRow->deliver_to,                 
										$orderRow->return_schedule,                      
										$orderRow->refunded_date,  
										$orderRow->date_adjusted,
										$orderRow->stock_adj_ref_no,
										$orderRow->sor_number,      
										
										$orderRow->negative_positive_invoice,    
										$orderRow->pos_replacement_ref, 
										
										$orderRow->pos_crf_number,
										
										$orderRow->digits_code,               
										$orderRow->upc_code,                 
										$orderRow->item_description,            
										$orderRow->cost,          
										$orderRow->brand,
										$serial_no->serial_number,
										$orderRow->problem_details,
										$orderRow->problem_details_other,                
										$orderRow->quantity,
										//$orderRow->warranty_status,
										//$orderRow->ship_back_status,
										//$orderRow->claimed_status,
										//$orderRow->credit_memo_number,
										$verified,
										$verified_date,
										$scheduled_by,
										$scheduled_date,
										
										$orderRow->received_by1,
                                        $orderRow->received_at_rma_sc,


										$orderRow->diagnosed_by,
										$orderRow->level2_personnel_edited,
										$printed_by,
										$printed_date,
										$transacted_by,							
										$transacted_date,
										$closed_by,
										$closed_date,
										$orderRow->comments,
										$orderRow->diagnose_comments
									);
								}

								$headings = array(
									'RETURN STATUS',
									'DIAGNOSE',
									'CREATED DATE',
									'RETURN REFERENCE#',
									'VIA',
									'PURCHASE LOCATION',
									'CUSTOMER LAST NAME',
									'CUSTOMER FIRST NAME',
									'ADDRESS',
									'EMAIL ADDRESS',
									'CONTACT#',
									'ORDER#',
									'PURCHASE DATE',
									'ORIGINAL MODE OF PAYMENT',
									'BANK NAME',    //yellow
									'BANK ACCOUNT#',      //red
									'BANK ACCOUNT NAME',         //red
									'ITEMS INCLUDED',         //red
									'ITEMS INCLUDED OTHERS',//green
									'VERIFIED ITEMS INCLUDED',         //red
									'VERIFIED ITEMS INCLUDED OTHERS',//green
									'CUSTOMER LOCATION',               //green
									'DELIVER TO',               //green
									'PICKUP SCHEDULE',               //green
									'REFUNDED DATE',               //green
									'DATE ADJUSTED',               //green
									'STOCK ADJUSTED REF#',               //green
									'SOR#',               //green
									'Negative/Positive Invoice', 
									'POS Replacement Ref#', 
									
									'POS CRF#',
									
									'DIGITS CODE',                 //green
									'UPC CODE',      //blue
									'ITEM DESCRIPTION',               //blue
									'COST',                 //bue
									'BRAND',              //blue  //additional code 20200121
									'SERIAL#',                //bue   //additional code 20200121
									'PROBLEM DETAILS',       //additional code 20200207
									'PROBLEM DETAILS OTHERS',       //additional code 20200207
									'QUANTITY',           //blue  //additional code 20200205
									//'WARRANTY STATUS',
									//'SHIP BACK STATUS',           //blue  //additional code 20200205
									//'CLAIMED STATUS',           //blue  //additional code 20200205
									//'CREDIT MEMO#',           //blue  //additional code 20200205
									'VERIFIED BY',           //blue  //additional code 20200205
									'VERIFIED DATE',           //blue  //additional code 20200205
									'SCHEDULED BY',           //blue  //additional code 20200205
									'SCHEDULED DATE',           //blue  //additional code 20200205
									
									'RECEIVED BY',
                                    'RECEIVED DATE',

									'DIAGNOSED BY',           //blue  //additional code 20200205
									'DIAGNOSED DATE',           //blue  //additional code 20200205
									'PRINTED BY',           //blue  //additional code 20200205
									'PRINTED DATE',           //blue  //additional code 20200205
									'SOR BY',           //blue  //additional code 20200205
									'SOR DATE',           //blue  //additional code 20200205
									'CLOSED BY',           //blue  //additional code 20200205
									'CLOSED DATE',           //blue  //additional code 20200205
									'COMMENTS',
									'DIAGNOSED COMMENTS'
								);
					}elseif(CRUDBooster::myPrivilegeName() == "Store Ops"){
								$requested = ReturnsStatus::where('id','1')->value('id');
								
								$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
								$approval_array = array();
								foreach($approvalMatrix as $matrix){
									array_push($approval_array, $matrix->stores_id);
								}
								$approval_string = implode(",",$approval_array);
								$storeList = array_map('intval',explode(",",$approval_string));						

								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*',
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested)->whereIn('returns_header_retail.stores_id', $storeList)->groupby('returns_header_retail.return_reference_no');
								
								
														if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,	
									$orderRow->via_name,				
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									$orderRow->bank_name,                  
									$orderRow->bank_account_no,                   
									$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number,      
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref, 
									
									$orderRow->pos_crf_number,
									
									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									//$orderRow->warranty_status,
									//$orderRow->ship_back_status,
									//$orderRow->claimed_status,
									//$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								'BANK NAME',    //yellow
								'BANK ACCOUNT#',      //red
								'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 

								'POS CRF#',

								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								//'WARRANTY STATUS',
								//'SHIP BACK STATUS',           //blue  //additional code 20200205
								//'CLAIMED STATUS',           //blue  //additional code 20200205
								//'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
					}else if(CRUDBooster::myPrivilegeName() == "Logistics"){
								$requested = ReturnsStatus::where('id','1')->value('id');
								$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
							
								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*',
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested)->where('returns_status_1','!=', $to_schedule)->groupby('returns_header_retail.return_reference_no');				
							
								
														if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,		
									$orderRow->via_name,			
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									//$orderRow->bank_name,                  
									//$orderRow->bank_account_no,                   
									//$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number,      
									
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref,  
									
									$orderRow->pos_crf_number,

									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									//$orderRow->warranty_status,
									//$orderRow->ship_back_status,
									//$orderRow->claimed_status,
									//$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								//'BANK NAME',    //yellow
								//'BANK ACCOUNT#',      //red
								//'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 
								
								'POS CRF#',

								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								//'WARRANTY STATUS',
								//'SHIP BACK STATUS',           //blue  //additional code 20200205
								//'CLAIMED STATUS',           //blue  //additional code 20200205
								//'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
								
					}else if(CRUDBooster::myPrivilegeName() == "RMA"){
								$to_diagnose = ReturnsStatus::where('id','5')->value('id');
								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*', 
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->where('transaction_type', 0)->where('returns_status_1','!=', $to_diagnose)->whereNotNull('diagnose')->groupby('returns_header_retail.return_reference_no');					
						
												if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,	
									$orderRow->via_name,				
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									//$orderRow->bank_name,                  
									//$orderRow->bank_account_no,                   
									//$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number,      
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref, 
									
									$orderRow->pos_crf_number,

									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									$orderRow->warranty_status,
									$orderRow->ship_back_status,
									$orderRow->claimed_status,
									$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								//'BANK NAME',    //yellow
								//'BANK ACCOUNT#',      //red
								//'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 
								
								'POS CRF#',

								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								'WARRANTY STATUS',
								'SHIP BACK STATUS',           //blue  //additional code 20200205
								'CLAIMED STATUS',           //blue  //additional code 20200205
								'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
				
					}else if(CRUDBooster::myPrivilegeName() == "Service Center"){
									$to_diagnose = ReturnsStatus::where('id','5')->value('id');
									$requested = ReturnsStatus::where('id','1')->value('id');
									
									$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
									$approval_array = array();
									foreach($approvalMatrix as $matrix){
										array_push($approval_array, $matrix->stores_id);
									}
									$approval_string = implode(",",$approval_array);
									$storeList = array_map('intval',explode(",",$approval_string)); 
									
									$orderData = DB::table('returns_header_retail')
									->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
									->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
									->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
									->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
									->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
									->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
									->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
									->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
									->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
									->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
									->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
									->select(   'returns_header_retail.*',
									            'returns_header_retail.created_at as datecreated',
												'returns_body_item_retail.*', 
												'returns_body_item_retail.id as body_id', 
												'verified.name as verified_by',	
												'scheduled_logistics.name as scheduled_logistics_by',
												'diagnosed.name as diagnosed_by',
												'printed.name as printed_by',	
												'transacted.name as transacted_by',	
												'received.name as received_by',
												'received1.name as received_by1',
												'closed.name as closed_by',
												'via.*',
												'warranty_statuses.*'
									)->whereNotNull('returns_body_item_retail.category')->where('transaction_type', 1)->where('returns_status_1','!=', $to_diagnose)->whereNotNull('diagnose')->whereIn('returns_header_retail.stores_id', $storeList)->groupby('returns_header_retail.return_reference_no')
									->orwhereNotNull('returns_body_item_retail.category')->where('transaction_type', 3)->where('returns_status_1','!=', $to_diagnose)->whereNotNull('diagnose')->whereIn('returns_header_retail.stores_id', $storeList)->groupby('returns_header_retail.return_reference_no');	
														
									
									
															if(\Request::get('filter_column')) {

									$filter_column = \Request::get('filter_column');

									$orderData->where(function($w) use ($filter_column,$fc) {
										foreach($filter_column as $key=>$fc) {

											$value = @$fc['value'];
											$type  = @$fc['type'];

											if($type == 'empty') {
												$w->whereNull($key)->orWhere($key,'');
												continue;
											}

											if($value=='' || $type=='') continue;

											if($type == 'between') continue;

											switch($type) {
												default:
													if($key && $type && $value) $w->where($key,$type,$value);
												break;
												case 'like':
												case 'not like':
													$value = '%'.$value.'%';
													if($key && $type && $value) $w->where($key,$type,$value);
												break;
												case 'in':
												case 'not in':
													if($value) {
														$value = explode(',',$value);
														if($key && $value) $w->whereIn($key,$value);
													}
												break;
											}
										}
									});

									foreach($filter_column as $key=>$fc) {
										$value = @$fc['value'];
										$type  = @$fc['type'];
										$sorting = @$fc['sorting'];

										if($sorting!='') {
											if($key) {
												$orderData->orderby($key,$sorting);
												$filter_is_orderby = true;
											}
										}

										if ($type=='between') {
											if($key && $value) $orderData->whereBetween($key,$value);
										}

										else {
											continue;
										}
									}
								}

								$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
								$blank_field = '';
								$store_inv = '';
								$counter=0;
								$final_count = count((array)$ordeDataLines) + 1;
								foreach ($ordeDataLines as $orderRow) {
									$counter++;
									/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
									$itemBrand = Brand::where('id', $item->brand_id)->first();
									$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
									$itemCategory = Category::where('id', $item->category_id)->first();
									$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
									*/

						
									$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
									
									
									if($orderRow->transaction_type == 3 ){
										

											
											$scheduled_by =  "";
											$scheduled_date = "";
											$verified = $orderRow->verified_by;
											$verified_date = $orderRow->level7_personnel_edited;
											
											
										if($orderRow->diagnose == "REFUND"){
												$printed_by = $orderRow->printed_by;
												$printed_date = $orderRow->level3_personnel_edited;
												$transacted_by = $orderRow->transacted_by;
												$transacted_date = $orderRow->level4_personnel_edited;
												$closed_by = $orderRow->closed_by;
												$closed_date = $orderRow->level5_personnel_edited;
										}elseif($orderRow->diagnose == "REPLACE"){
											$printed_by = "";
											$printed_date = "";
											$transacted_by = $orderRow->printed_by;
											$transacted_date = $orderRow->level3_personnel_edited;
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;
										}else{
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = "";
											$transacted_date = "";
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;	
										}
											
											
									}else{
										
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										$scheduled_by = $orderRow->scheduled_logistics_by;
										$scheduled_date = $orderRow->level1_personnel_edited;
										if($orderRow->diagnose == "REFUND"){
												$printed_by = $orderRow->printed_by;
												$printed_date = $orderRow->level3_personnel_edited;
												$transacted_by = $orderRow->transacted_by;
												$transacted_date = $orderRow->level4_personnel_edited;
												$closed_by = $orderRow->closed_by;
												$closed_date = $orderRow->level5_personnel_edited;
										}elseif($orderRow->diagnose == "REPLACE"){
											$printed_by = "";
											$printed_date = "";
											$transacted_by = $orderRow->printed_by;
											$transacted_date = $orderRow->level3_personnel_edited;
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;
										}else{
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = "";
											$transacted_date = "";
											$closed_by = $orderRow->transacted_by;
											$closed_date = $orderRow->level4_personnel_edited;	
										}
									}
									
						
									$orderItems[] = array(
										//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
										//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
										$orderRow->warranty_status, 		
										$orderRow->diagnose, 	
										$orderRow->datecreated,				
										$orderRow->return_reference_no,	
										$orderRow->via_name,				
										$orderRow->purchase_location,				
										$orderRow->customer_last_name,		
										$orderRow->customer_first_name,	
										$orderRow->address,		            
										$orderRow->email_address,      
										$orderRow->contact_no,    
										$orderRow->order_no,		
										$orderRow->purchase_date,			
										$orderRow->mode_of_payment,		
										//$orderRow->bank_name,                  
										//$orderRow->bank_account_no,                   
										//$orderRow->bank_account_name,		
										$orderRow->items_included,                      
										$orderRow->items_included_others, 
										$orderRow->verified_items_included,                      
										$orderRow->verified_items_included_others, 
										$orderRow->customer_location,  
										$orderRow->deliver_to,                 
										$orderRow->return_schedule,                      
										$orderRow->refunded_date,  
										$orderRow->date_adjusted,
										$orderRow->stock_adj_ref_no,
										$orderRow->sor_number,   
										
										$orderRow->negative_positive_invoice,    
										$orderRow->pos_replacement_ref, 
										
										$orderRow->pos_crf_number,

										$orderRow->digits_code,               
										$orderRow->upc_code,                 
										$orderRow->item_description,            
										$orderRow->cost,          
										$orderRow->brand,
										$serial_no->serial_number,
										$orderRow->problem_details,
										$orderRow->problem_details_other,                
										$orderRow->quantity,
										$orderRow->warranty_status,
										//$orderRow->ship_back_status,
										//$orderRow->claimed_status,
										//$orderRow->credit_memo_number,
										$verified,
										$verified_date,
										$scheduled_by,
										$scheduled_date,
										
										$orderRow->received_by1,
                                        $orderRow->received_at_rma_sc,

										$orderRow->diagnosed_by,
										$orderRow->level2_personnel_edited,
										$printed_by,
										$printed_date,
										$transacted_by,							
										$transacted_date,
										$closed_by,
										$closed_date,
										$orderRow->comments,
										$orderRow->diagnose_comments
									);
								}

								$headings = array(
									'RETURN STATUS',
									'DIAGNOSE',
									'CREATED DATE',
									'RETURN REFERENCE#',
									'VIA',
									'PURCHASE LOCATION',
									'CUSTOMER LAST NAME',
									'CUSTOMER FIRST NAME',
									'ADDRESS',
									'EMAIL ADDRESS',
									'CONTACT#',
									'ORDER#',
									'PURCHASE DATE',
									'ORIGINAL MODE OF PAYMENT',
									//'BANK NAME',    //yellow
									//'BANK ACCOUNT#',      //red
									//'BANK ACCOUNT NAME',         //red
									'ITEMS INCLUDED',         //red
									'ITEMS INCLUDED OTHERS',//green
									'VERIFIED ITEMS INCLUDED',         //red
									'VERIFIED ITEMS INCLUDED OTHERS',//green
									'CUSTOMER LOCATION',               //green
									'DELIVER TO',               //green
									'PICKUP SCHEDULE',               //green
									'REFUNDED DATE',               //green
									'DATE ADJUSTED',               //green
									'STOCK ADJUSTED REF#',               //green
									'SOR#',               //green
									
									'Negative/Positive Invoice', 
									'POS Replacement Ref#', 
									
									'POS CRF#',

									'DIGITS CODE',                 //green
									'UPC CODE',      //blue
									'ITEM DESCRIPTION',               //blue
									'COST',                 //bue
									'BRAND',              //blue  //additional code 20200121
									'SERIAL#',                //bue   //additional code 20200121
									'PROBLEM DETAILS',       //additional code 20200207
									'PROBLEM DETAILS OTHERS',       //additional code 20200207
									'QUANTITY',           //blue  //additional code 20200205
									'WARRANTY STATUS',
									//'SHIP BACK STATUS',           //blue  //additional code 20200205
									//'CLAIMED STATUS',           //blue  //additional code 20200205
									//'CREDIT MEMO#',           //blue  //additional code 20200205
									'VERIFIED BY',           //blue  //additional code 20200205
									'VERIFIED DATE',           //blue  //additional code 20200205
									'SCHEDULED BY',           //blue  //additional code 20200205
									'SCHEDULED DATE',           //blue  //additional code 20200205
									
									'RECEIVED BY',
                                    'RECEIVED DATE',

									'DIAGNOSED BY',           //blue  //additional code 20200205
									'DIAGNOSED DATE',           //blue  //additional code 20200205
									'PRINTED BY',           //blue  //additional code 20200205
									'PRINTED DATE',           //blue  //additional code 20200205
									'SOR BY',           //blue  //additional code 20200205
									'SOR DATE',           //blue  //additional code 20200205
									'CLOSED BY',           //blue  //additional code 20200205
									'CLOSED DATE',           //blue  //additional code 20200205
									'COMMENTS',
									'DIAGNOSED COMMENTS'
								);
					}else if(CRUDBooster::myPrivilegeName() == "Accounting"){
								$to_print_crf = 	ReturnsStatus::where('id','7')->value('id');
								$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*', 
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $to_print_crf)->where('diagnose', "REFUND")->groupby('returns_header_retail.return_reference_no')
								;			
								
								
														if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,	
									$orderRow->via_name,				
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									$orderRow->bank_name,                  
									$orderRow->bank_account_no,                   
									$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number,      
									
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref,  

									$orderRow->pos_crf_number,

									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									//$orderRow->warranty_status,
									//$orderRow->ship_back_status,
									//$orderRow->claimed_status,
									//$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								'BANK NAME',    //yellow
								'BANK ACCOUNT#',      //red
								'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								
								
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 
								
								'POS CRF#',

								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								//'WARRANTY STATUS',
								//'SHIP BACK STATUS',           //blue  //additional code 20200205
								//'CLAIMED STATUS',           //blue  //additional code 20200205
								//'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
					}else if(CRUDBooster::myPrivilegeName() == "Aftersales (Ops)"){
								$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
								$repair_complete = 			ReturnsStatus::where('id','17')->value('id');
								$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
								$to_close = ReturnsStatus::where('id','30')->value('id');

								$requested = ReturnsStatus::where('id','1')->value('id');
								
								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*', 
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->where('transaction_type','!=', 2)->groupby('returns_header_retail.return_reference_no')//->where('returns_status_1','!=',$requested)
								/*->orwhereNotNull('returns_body_item_retail.category')->where('returns_status_1', $repair_complete)
								->orwhereNotNull('returns_body_item_retail.category')->where('returns_status_1', $replacement_complete)
								->orwhereNotNull('returns_body_item_retail.category')->where('returns_status_1', $to_close)*/;	
								
								
														if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){

											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;

									}elseif($orderRow->diagnose == "REPLACE"){

										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;

									}else{

										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;

									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,		
									$orderRow->via_name,			
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									$orderRow->bank_name,                  
									$orderRow->bank_account_no,                   
									$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number,      
									
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref,
									
									$orderRow->pos_crf_number,
									
									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									//$orderRow->warranty_status,
									//$orderRow->ship_back_status,
								//	$orderRow->claimed_status,
									//$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								'BANK NAME',    //yellow
								'BANK ACCOUNT#',      //red
								'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 
								
								'POS CRF#',
								
								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								//'WARRANTY STATUS',
								//'SHIP BACK STATUS',           //blue  //additional code 20200205
								//'CLAIMED STATUS',           //blue  //additional code 20200205
								//'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
					}else{
								$orderData = DB::table('returns_header_retail')
								->leftjoin('via', 'returns_header_retail.via_id','=', 'via.id')
								->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
								->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
								->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
								->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
								->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
								->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
								->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
								->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
								->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
								->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
								->select(   'returns_header_retail.*', 
								            'returns_header_retail.created_at as datecreated',
											'returns_body_item_retail.*', 
											'returns_body_item_retail.id as body_id', 
											'verified.name as verified_by',	
											'scheduled_logistics.name as scheduled_logistics_by',
											'diagnosed.name as diagnosed_by',
											'printed.name as printed_by',	
											'transacted.name as transacted_by',	
											'received.name as received_by',
											'received1.name as received_by1',
											'closed.name as closed_by',
											'via.*',
											'warranty_statuses.*'
								)->whereNotNull('returns_body_item_retail.category')->groupby('returns_header_retail.return_reference_no');
								
								
								
								
								if(\Request::get('filter_column')) {

								$filter_column = \Request::get('filter_column');

								$orderData->where(function($w) use ($filter_column,$fc) {
									foreach($filter_column as $key=>$fc) {

										$value = @$fc['value'];
										$type  = @$fc['type'];

										if($type == 'empty') {
											$w->whereNull($key)->orWhere($key,'');
											continue;
										}

										if($value=='' || $type=='') continue;

										if($type == 'between') continue;

										switch($type) {
											default:
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'like':
											case 'not like':
												$value = '%'.$value.'%';
												if($key && $type && $value) $w->where($key,$type,$value);
											break;
											case 'in':
											case 'not in':
												if($value) {
													$value = explode(',',$value);
													if($key && $value) $w->whereIn($key,$value);
												}
											break;
										}
									}
								});

								foreach($filter_column as $key=>$fc) {
									$value = @$fc['value'];
									$type  = @$fc['type'];
									$sorting = @$fc['sorting'];

									if($sorting!='') {
										if($key) {
											$orderData->orderby($key,$sorting);
											$filter_is_orderby = true;
										}
									}

									if ($type=='between') {
										if($key && $value) $orderData->whereBetween($key,$value);
									}

									else {
										continue;
									}
								}
							}

							$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
							$blank_field = '';
							$store_inv = '';
							$counter=0;
							$final_count = count((array)$ordeDataLines) + 1;
							foreach ($ordeDataLines as $orderRow) {
								$counter++;
								/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
								$itemBrand = Brand::where('id', $item->brand_id)->first();
								$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
								$itemCategory = Category::where('id', $item->category_id)->first();
								$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
								*/

					
								$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
								
								
								if($orderRow->transaction_type == 3 ){
									

										
										$scheduled_by =  "";
										$scheduled_date = "";
										$verified = $orderRow->verified_by;
										$verified_date = $orderRow->level7_personnel_edited;
										
										
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
										
										
								}else{
									
									$verified = $orderRow->verified_by;
									$verified_date = $orderRow->level7_personnel_edited;
									
									$scheduled_by = $orderRow->scheduled_logistics_by;
									$scheduled_date = $orderRow->level1_personnel_edited;
									if($orderRow->diagnose == "REFUND"){
											$printed_by = $orderRow->printed_by;
											$printed_date = $orderRow->level3_personnel_edited;
											$transacted_by = $orderRow->transacted_by;
											$transacted_date = $orderRow->level4_personnel_edited;
											$closed_by = $orderRow->closed_by;
											$closed_date = $orderRow->level5_personnel_edited;
									}elseif($orderRow->diagnose == "REPLACE"){
										$printed_by = "";
										$printed_date = "";
										$transacted_by = $orderRow->printed_by;
										$transacted_date = $orderRow->level3_personnel_edited;
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;
									}else{
										$printed_by = $orderRow->printed_by;
										$printed_date = $orderRow->level3_personnel_edited;
										$transacted_by = "";
										$transacted_date = "";
										$closed_by = $orderRow->transacted_by;
										$closed_date = $orderRow->level4_personnel_edited;	
									}
								}
								
					
								$orderItems[] = array(
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
									//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
									$orderRow->warranty_status, 		
									$orderRow->diagnose, 	
									$orderRow->datecreated,				
									$orderRow->return_reference_no,	
									$orderRow->via_name,				
									$orderRow->purchase_location,				
									$orderRow->customer_last_name,		
									$orderRow->customer_first_name,	
									$orderRow->address,		            
									$orderRow->email_address,      
									$orderRow->contact_no,    
									$orderRow->order_no,		
									$orderRow->purchase_date,			
									$orderRow->mode_of_payment,		
									$orderRow->bank_name,                  
									$orderRow->bank_account_no,                   
									$orderRow->bank_account_name,		
									$orderRow->items_included,                      
									$orderRow->items_included_others, 
									$orderRow->verified_items_included,                      
									$orderRow->verified_items_included_others, 
									$orderRow->customer_location,  
									$orderRow->deliver_to,                 
									$orderRow->return_schedule,                      
									$orderRow->refunded_date,  
									$orderRow->date_adjusted,
									$orderRow->stock_adj_ref_no,
									$orderRow->sor_number, 
									
									$orderRow->negative_positive_invoice,    
									$orderRow->pos_replacement_ref, 
									
									$orderRow->pos_crf_number,
									
									$orderRow->digits_code,               
									$orderRow->upc_code,                 
									$orderRow->item_description,            
									$orderRow->cost,          
									$orderRow->brand,
									$serial_no->serial_number,
									$orderRow->problem_details,
									$orderRow->problem_details_other,                
									$orderRow->quantity,
									$orderRow->warranty_status,
									$orderRow->ship_back_status,
									$orderRow->claimed_status,
									$orderRow->credit_memo_number,
									$verified,
									$verified_date,
									$scheduled_by,
									$scheduled_date,
									
									$orderRow->received_by1,
                                    $orderRow->received_at_rma_sc,

									$orderRow->diagnosed_by,
									$orderRow->level2_personnel_edited,
									$printed_by,
									$printed_date,
									$transacted_by,							
									$transacted_date,
									$closed_by,
									$closed_date,
									$orderRow->comments,
									$orderRow->diagnose_comments
								);
							}

							$headings = array(
								'RETURN STATUS',
								'DIAGNOSE',
								'CREATED DATE',
								'RETURN REFERENCE#',
								'VIA',
								'PURCHASE LOCATION',
								'CUSTOMER LAST NAME',
								'CUSTOMER FIRST NAME',
								'ADDRESS',
								'EMAIL ADDRESS',
								'CONTACT#',
								'ORDER#',
								'PURCHASE DATE',
								'ORIGINAL MODE OF PAYMENT',
								'BANK NAME',    //yellow
								'BANK ACCOUNT#',      //red
								'BANK ACCOUNT NAME',         //red
								'ITEMS INCLUDED',         //red
								'ITEMS INCLUDED OTHERS',//green
								'VERIFIED ITEMS INCLUDED',         //red
								'VERIFIED ITEMS INCLUDED OTHERS',//green
								'CUSTOMER LOCATION',               //green
								'DELIVER TO',               //green
								'PICKUP SCHEDULE',               //green
								'REFUNDED DATE',               //green
								'DATE ADJUSTED',               //green
								'STOCK ADJUSTED REF#',               //green
								'SOR#',               //green
								
								'Negative/Positive Invoice', 
								'POS Replacement Ref#', 
								
								'POS CRF#',

								'DIGITS CODE',                 //green
								'UPC CODE',      //blue
								'ITEM DESCRIPTION',               //blue
								'COST',                 //bue
								'BRAND',              //blue  //additional code 20200121
								'SERIAL#',                //bue   //additional code 20200121
								'PROBLEM DETAILS',       //additional code 20200207
								'PROBLEM DETAILS OTHERS',       //additional code 20200207
								'QUANTITY',           //blue  //additional code 20200205
								'WARRANTY STATUS',
								'SHIP BACK STATUS',           //blue  //additional code 20200205
								'CLAIMED STATUS',           //blue  //additional code 20200205
								'CREDIT MEMO#',           //blue  //additional code 20200205
								'VERIFIED BY',           //blue  //additional code 20200205
								'VERIFIED DATE',           //blue  //additional code 20200205
								'SCHEDULED BY',           //blue  //additional code 20200205
								'SCHEDULED DATE',           //blue  //additional code 20200205
								
								'RECEIVED BY',
                                'RECEIVED DATE',

								'DIAGNOSED BY',           //blue  //additional code 20200205
								'DIAGNOSED DATE',           //blue  //additional code 20200205
								'PRINTED BY',           //blue  //additional code 20200205
								'PRINTED DATE',           //blue  //additional code 20200205
								'SOR BY',           //blue  //additional code 20200205
								'SOR DATE',           //blue  //additional code 20200205
								'CLOSED BY',           //blue  //additional code 20200205
								'CLOSED DATE',           //blue  //additional code 20200205
								'COMMENTS',
								'DIAGNOSED COMMENTS'
							);
					}


					



					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);

                             
                    $sheet->getStyle('A1:BD1')->applyFromArray(array(
                        'fill' => array(
                            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '8DB4E2') //141,180,226->8DB4E2
                        )
                    ));
                    $sheet->cells('A1:BA1'.$final_count, function($cells) {
                    	$cells->setAlignment('left');
                    	
                    });
 
				});
			})->export('xlsx');

		}


		public function ReturnsHistoryEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')																		
			
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'created.name as created_by'						
			)
			->where('returns_header_retail.id',$id)->first();

			


			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			

			$data['ClaimedStatus'] = ClaimedStatus::all();


			$data['ShipBackStatus'] = ShipBackStatus::all();


			$this->cbView("returns.history_edit_retail", $data);
		}


		public function GetExtractReturnsRTLSC() {

            $filename = 'Returns - ' . date("d M Y - h.i.sa");
			$sheetname = 'Returns'.date("d-M-Y");
            ini_set('memory_limit', '512M');
			Excel::create($filename, function ($excel) {
				$excel->sheet('orders', function ($sheet) {	
					// Set auto size for sheet
					
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
					    'J' => '@',		//for upc code
					    'AI' => '0.00',
					    'AJ' => '0.00',
					    'AK' => '0.00',
					));

					$requested = ReturnsStatus::where('id','1')->value('id');
					//$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');

					$orderData = DB::table('returns_header_retail')
					->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
					->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
					->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
					->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
					->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
					->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
					->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
					->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
					->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
					->select( 	'returns_header_retail.*', 
								'returns_body_item_retail.*', 
								'returns_body_item_retail.id as body_id', 
								'verified.name as verified_by',	
								'scheduled_logistics.name as scheduled_logistics_by',
								'diagnosed.name as diagnosed_by',
								'printed.name as printed_by',	
								'transacted.name as transacted_by',	
								'received.name as received_by',
								'closed.name as closed_by',
								'warranty_statuses.*'
								)->whereNull('returns_body_item_retail.category')->where('transaction_type', 1)->where('returns_status_1','!=',$requested)->where('returns_status_1','!=',$to_print_return_form)->whereNotNull('diagnose')->groupby('returns_body_item_retail.digits_code');
					

						if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');

						$orderData->where(function($w) use ($filter_column,$fc) {
							foreach($filter_column as $key=>$fc) {

								$value = @$fc['value'];
								$type  = @$fc['type'];

								if($type == 'empty') {
									$w->whereNull($key)->orWhere($key,'');
									continue;
								}

								if($value=='' || $type=='') continue;

								if($type == 'between') continue;

								switch($type) {
									default:
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'like':
									case 'not like':
										$value = '%'.$value.'%';
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'in':
									case 'not in':
										if($value) {
											$value = explode(',',$value);
											if($key && $value) $w->whereIn($key,$value);
										}
									break;
								}
							}
						});

						foreach($filter_column as $key=>$fc) {
							$value = @$fc['value'];
							$type  = @$fc['type'];
							$sorting = @$fc['sorting'];

							if($sorting!='') {
								if($key) {
									$orderData->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}

							if ($type=='between') {
								if($key && $value) $orderData->whereBetween($key,$value);
							}

							else {
								continue;
							}
						}
					}

					$ordeDataLines = $orderData->orderBy('returns_header_retail.id','asc')->get();
					$blank_field = '';
					$store_inv = '';
					$counter=0;
					$final_count = count((array)$ordeDataLines) + 1;
					foreach ($ordeDataLines as $orderRow) {
					    $counter++;
						/*$item = Item::where('digits_code', $orderRow->digits_code)->first();
						$itemBrand = Brand::where('id', $item->brand_id)->first();
						$itemStoreCategory = StoreCategory::where('id', $item->store_category_id)->first();
					    $itemCategory = Category::where('id', $item->category_id)->first();
						$itemWHCategory = WarehouseCategory::where('id', $item->warehouse_category_id)->first();
						*/

			
						$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderRow->body_id)->first();
						
						if($orderRow->diagnose == "REFUND"){
							//$transacted_personnel = $orderRow->transacted_by;
							//$transacted_date = 		$orderRow->level5_personnel_edited;
							$closed_personnel = 	$orderRow->verified_by;
							$closed_date = 			$orderRow->level1_personnel_edited;
						}else{
							//$transacted_personnel = "";
							//$transacted_date = "";
							//$closed_personnel = $orderRow->scheduled_by;
							//$closed_date = 		$orderRow->level2_personnel_edited;
							$closed_personnel = 	$orderRow->verified_by;
							$closed_date = 			$orderRow->level1_personnel_edited;
						}
						
					
						
						$orderItems[] = array(
							//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
							//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
							$orderRow->warranty_status, 		
							$orderRow->diagnose, 	
							$orderRow->created_at,				
							$orderRow->return_reference_no,					
							$orderRow->purchase_location,				
							$orderRow->customer_last_name,		
							$orderRow->customer_first_name,	
							$orderRow->address,		            
							$orderRow->email_address,      
							$orderRow->contact_no,    
							$orderRow->order_no,		
							$orderRow->purchase_date,			
							$orderRow->mode_of_payment,		
							$orderRow->mode_of_refund,	
							$orderRow->bank_name,                  
							$orderRow->bank_account_no,                   
							$orderRow->bank_account_name,		
							$orderRow->items_included,                      
							$orderRow->items_included_others, 
							$orderRow->verified_items_included,                      
							$orderRow->verified_items_included_others, 
							$orderRow->customer_location,  
				 			$orderRow->digits_code,               
				 			$orderRow->upc_code,                 
				 			$orderRow->item_description,            
				 			$orderRow->cost,          
							$orderRow->brand,
							$serial_no->serial_number,
							$orderRow->problem_details,
				 			$orderRow->problem_details_other,                
							$orderRow->quantity,
							$closed_personnel,
							$closed_date,
							$orderRow->diagnose_comments
						);
					}

					$headings = array(
						'RETURN STATUS',
						'DIAGNOSE',
						'CREATED DATE',
						'RETURN REFERENCE#',
						'PURCHASE LOCATION',
						'CUSTOMER LAST NAME',
						'CUSTOMER FIRST NAME',
						'ADDRESS',
						'EMAIL ADDRESS',
						'CONTACT#',
						'ORDER#',
						'PURCHASE DATE',
						'ORIGINAL MODE OF PAYMENT',
						'MODE OF REFUND',
						'BANK NAME',    //yellow
						'BANK ACCOUNT#',      //red
						'BANK ACCOUNT NAME',         //red
						'ITEMS INCLUDED',         //red
						'ITEMS INCLUDED OTHERS',//green
						'VERIFIED ITEMS INCLUDED',         //red
						'VERIFIED ITEMS INCLUDED OTHERS',//green
						'CUSTOMER LOCATION',               //green
						'DIGITS CODE',                 //green
						'UPC CODE',      //blue
						'ITEM DESCRIPTION',               //blue
						'COST',                 //bue
						'BRAND',              //blue  //additional code 20200121
                        'SERIAL#',                //bue   //additional code 20200121
						'PROBLEM DETAILS',       //additional code 20200207
						'PROBLEM DETAILS OTHERS',       //additional code 20200207
						'QUANTITY',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'DIAGNOSED COMMENTS'
					);

					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);

                             
                    $sheet->getStyle('A1:AH1')->applyFromArray(array(
                        'fill' => array(
                            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '8DB4E2') //141,180,226->8DB4E2
                        )
                    ));
                    $sheet->cells('A1:AH1'.$final_count, function($cells) {
                    	$cells->setAlignment('left');
                    	
                    });
 
				});
			})->export('xlsx');
			
		}


		public function importPage() {
		    
	    	$data['page_title'] = 'Upload Retail';

	    	return view('retail_upload',$data);
	    	
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

							$Search = DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)->first();

							//$Status = DB::table('warranty_statuses')->where('warranty_status', $value->return_status)->first();

							//$Items = DB::table('digits_imfs')->where('digits_code', $value->digits_code)->first(); 

							//$Category = DB::table('warehouse_category')->where('id', $Items->warehouse_category_id)->first();  

							if($Search->transaction_type == 3 ){
                       
								if($Search->diagnose == "REFUND"){

									if($Search->level7_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level7_personnel' =>  DB::table('cms_users')->where('name', $value->verified_by)->value('id')
										]);

									}

									if($Search->level2_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level2_personnel' =>  		     DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
										]);

									}

									if($Search->level3_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level3_personnel' =>  		   DB::table('cms_users')->where('name', $value->printed_by)->value('id')
										]);

									}

									if($Search->level4_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level4_personnel' =>  		    DB::table('cms_users')->where('name', $value->sor_by)->value('id')
										]);

									}

									if($Search->level5_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level5_personnel' =>  		    DB::table('cms_users')->where('name', $value->closed_by)->value('id')
										]);

									}






								}elseif($Search->diagnose == "REPLACE"){


									if($Search->level7_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
	
											'level7_personnel' =>  		  DB::table('cms_users')->where('name', $value->verified_by)->value('id')
	
										]);

									}


									if($Search->level2_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level2_personnel' =>  		   DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
	
										]);

									}

									if($Search->level3_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level3_personnel' =>  		 DB::table('cms_users')->where('name', $value->sor_by)->value('id')
	
										]);

									}


									if($Search->level4_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level4_personnel' =>  		 DB::table('cms_users')->where('name', $value->sor_by)->value('id')
	
										]);

									}




								}else{


									if($Search->level7_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
	
											'level7_personnel' =>  		    DB::table('cms_users')->where('name', $value->verified_by)->value('id')
	
										]);

									}

									if($Search->level2_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level2_personnel' =>  		 DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
										]);

									}

									if($Search->level4_personnel == 0 ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level4_personnel' =>  		   DB::table('cms_users')->where('name', $value->closed_by)->value('id')
	
										]);

									}

								}
                                
                                
							}else{
						    
								if($Search->diagnose == "REFUND"){

									if($Search->level7_personnel == 0  ){
										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
	
											'level7_personnel' =>  		  DB::table('cms_users')->where('name', $value->verified_by)->value('id')
	
										]);
									}

									if($Search->level2_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level2_personnel' =>  		 DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
	
										]);

									}

									if($Search->level3_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level3_personnel' =>  	DB::table('cms_users')->where('name', $value->printed_by)->value('id')
	
										]);

									}

									if($Search->level4_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level4_personnel' =>  		  DB::table('cms_users')->where('name', $value->sor_by)->value('id')

										]);
									
									}

									if($Search->level5_personnel == 0  ){

											DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
											->update([

												'level5_personnel' =>  		  DB::table('cms_users')->where('name', $value->closed_by)->value('id')

											]);

									}


								}elseif($Search->diagnose == "REPLACE"){

									if($Search->level7_personnel == 0  ){
										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											'level7_personnel' =>  		DB::table('cms_users')->where('name', $value->verified_bys)->value('id')
										]);
									}


									if($Search->level2_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
											
											'level2_personnel' =>  		   DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
										
	
										]);

									}

									if($Search->level3_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level3_personnel' =>  		   DB::table('cms_users')->where('name', $value->sor_by)->value('id')

										]);

									}

									if($Search->level4_personnel == 0  ){

											DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
											->update([

												'level4_personnel' =>  		 DB::table('cms_users')->where('name', $value->closed_by)->value('id')

											]);

									}
	

								}else{

									if($Search->level7_personnel == 0  ){
										
										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
	
											'level7_personnel' =>  		 DB::table('cms_users')->where('name', $value->verified_by)->value('id')
										]);

									}

									if($Search->level2_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
 
											'level2_personnel' =>  		    DB::table('cms_users')->where('name', $value->diagnose_by)->value('id')
	
										]);

									}

									if($Search->level3_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([

											'level3_personnel' =>  		 DB::table('cms_users')->where('name', $value->sor_by)->value('id')
	
										]);

									}

									if($Search->level4_personnel == 0  ){

										DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
										->update([
	
											'level4_personnel' =>  		 DB::table('cms_users')->where('name', $value->closed_by)->value('id')
	
										]);

									}

									
								}


							}
							

							//dd($Category->wh_category_description);

							//dd($Status->id);
							
							/*DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
							->update([

								'diagnose' =>  		    $value->diagnose,
								//'created_at' =>  		$value->created_date,

								'returns_status' =>  		$Status->id,
								'returns_status_1' =>  		$Status->id,
								
								//'return_reference_no' =>  		$value->return_reference_no,
								'purchase_location' =>  		$value->purchase_location,
								//'customer_last_name' =>  		$value->customer_last_name,
								//'customer_first_name' =>  		$value->customer_first_name,
								//'address' =>  		        $value->address,
								//'email_address' =>  		$value->email_address,
								//'contact_no' =>  		$value->contact,
								'order_no' =>  		$value->order,
								'purchase_date' =>  		$value->purchase_date,
								'bank_name' =>  		$value->bank_name,
								'bank_account_no' =>  		$value->bank_account,
								'bank_account_name' =>  		$value->bank_account_name,
								'items_included' =>  		$value->items_included,
								'items_included_others' =>  		$value->items_included_others,
								'verified_items_included' =>  		$value->verified_items_included,
								'verified_items_included_others' =>  		$value->verified_items_included_others,
								'customer_location' =>  		$value->customer_location,
								'deliver_to' =>  		$value->deliver_to,
								'return_schedule' =>  		$value->pickup_schedule,
								'refunded_date' =>  		$value->refunded_date,
								'date_adjusted' =>  		$value->date_adjusted,
								'stock_adj_ref_no' =>  		$value->stock_adjusted_ref,
								'sor_number' =>  		$value->sor_number,
								'negative_positive_invoice' =>  		$value->negative_positive_invoice,
								'pos_replacement_ref' =>  		$value->pos_replacement_ref,
								'warranty_status' =>  		$value->warranty_status,
								'ship_back_status' =>  		$value->ship_back_status,
								'claimed_status' =>  		$value->claimed_status,
								'credit_memo_number' =>  	$value->credit_memo,
								'comments' =>  		        $value->comments,
								'diagnose_comments' =>  	$value->diagnose_comments
							]);


							if($Search->transaction_type == 3 ){
                       
								if($Search->diagnose == "REFUND"){

									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,

										'level3_personnel' =>  		    $value->printed_by,
										'level3_personnel_edited' =>  		$value->printed_date,


										'level4_personnel' =>  		    $value->sor_by,
										'level4_personnel_edited' =>  		$value->sor_date,

										'level5_personnel' =>  		    $value->closed_by,
										'level5_personnel_edited' =>  		$value->closed_date,

									]);

								}elseif($Search->diagnose == "REPLACE"){
									

									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,



										'level3_personnel' =>  		    $value->sor_by,
										'level3_personnel_edited' =>  		$value->sor_date,

										'level4_personnel' =>  		    $value->closed_by,
										'level4_personnel_edited' =>  		$value->closed_date,

									]);

								}else{

									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,


										'level4_personnel' =>  		    $value->closed_by,
										'level4_personnel_edited' =>  		$value->closed_date,

									]);
								}
                                
                                
							}else{
						    
								

								if($Search->diagnose == "REFUND"){


									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,

										'level3_personnel' =>  		    $value->printed_by,
										'level3_personnel_edited' =>  		$value->printed_date,


										'level4_personnel' =>  		    $value->sor_by,
										'level4_personnel_edited' =>  		$value->sor_date,

										'level5_personnel' =>  		    $value->closed_by,
										'level5_personnel_edited' =>  		$value->closed_date,

									]);


								}elseif($Search->diagnose == "REPLACE"){
	

									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,



										'level3_personnel' =>  		    $value->sor_by,
										'level3_personnel_edited' =>  		$value->sor_date,

										'level4_personnel' =>  		    $value->closed_by,
										'level4_personnel_edited' =>  		$value->closed_date,

									]);

								}else{

									DB::table('returns_header_retail')->where('return_reference_no', $value->return_reference)
									->update([

										'level7_personnel' =>  		    $value->verified_by,
										'level7_personnel_edited' =>  		$value->verified_date,

										'level2_personnel' =>  		    $value->diagnose_by,
										'level2_personnel_edited' =>  		$value->diagnose_date,

										'level3_personnel' =>  		    $value->sor_by,
										'level3_personnel_edited' =>  		$value->sor_date,


										'level4_personnel' =>  		    $value->closed_by,
										'level4_personnel_edited' =>  		$value->closed_date,

									]);


								}
							}

							if($Search->returns_status_1 == 1 ){

								DB::table('returns_body_item_retail')->where('returns_header_id', $Search->id)
								->update([
	
									'digits_code' =>  		    $value->digits_code,
									'upc_code' =>  		$value->upc_code,
									'item_description' =>  		$value->item_description,
									'cost' =>  		$value->cost,
									'problem_details' =>  		$value->problem_details,
									'problem_details_other' =>  		$value->problem_details_others,
									'category' =>  		$Category->wh_category_description
								
								]);


								DB::table('returns_serial_retail')->where('returns_header_id', $Search->id)
								->update([
	
									'serial_number' =>  		    $value->serial_number
								
								]);

							}


							$Search1 = DB::table('returns_body_item_retail')->where('returns_header_id', $Search->id)->first();


							if (empty($Search1)) {

								DB::table('returns_body_item_retail')
								->insert([

									'returns_header_id' =>  		    $Search->id,
									'digits_code' =>  		    $value->digits_code,
									'upc_code' =>  		$value->upc_code,
									'item_description' =>  		$value->item_description,
									'cost' =>  		$value->cost,
									'problem_details' =>  		$value->problem_details,
									'problem_details_other' =>  		$value->problem_details_others,
									'category' =>  		$Category->wh_category_description
								
								]);



								$Search2 = DB::table('returns_body_item_retail')->where('returns_header_id', $Search->id)->first();

								DB::table('returns_serial_retail')
								->insert([
									
									'returns_body_item_id' =>  		    $Search2->id,

									'returns_header_id' =>  		    $Search->id,
									'serial_number' =>  		    $value->serial_number
								
								]);
								


							}*/

						}

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Successfully Uploaded."), 'success');

				}else{
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Incorrect File, Check your File."), 'danger');
				}

			}
			
	        
	    }
		
	}