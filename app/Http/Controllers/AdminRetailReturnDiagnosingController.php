<?php namespace App\Http\Controllers;

use Session;
//use Request;
use DB;
use CRUDBooster;
use App\ReturnsStatus;
use App\CaseStatus;
use App\ReturnsHeaderRTL;
use App\ReturnsBodyRTL;
use App\ReturnsSerialsRTL;
use App\ProblemDetails;
use App\Stores;
use App\Channel;
use App\ItemsIncluded;
use App\DiagnoseWarranty;
use App\ReferenceCounter;
use App\StoresFrontEnd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

	class AdminRetailReturnDiagnosingController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			//$this->request = $request;
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "customer_last_name";
			$this->limit = "20";
			$this->orderby = "returns_status_1,desc";
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
			$this->col[] = ["label"=>"Brand","name"=>"id", 'callback'=>function($row){
				
				$id = $row->id;

				$brand = DB::table('returns_body_item_retail')->where('returns_header_id', $id)->orderBy('id', 'desc')->first()->brand;
				
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
			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else{
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
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
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			$to_for_action = ReturnsStatus::where('id','38')->value('id');
			$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
			$requested = ReturnsStatus::where('id','1')->value('id');
			$to_assign_inc = ReturnsStatus::where('id','39')->value('id');
			$to_ongoing_testing = ReturnsStatus::where('id','40')->value('id');

				if(CRUDBooster::myPrivilegeName() == "Tech Lead") {
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('TechLeadRTL/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_assign_inc"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('TechLeadRTL/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_ongoing_testing"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDiagnosingRTLEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_diagnose "];
				}
				else {
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('TechLeadRTL/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_ongoing_testing"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDiagnosingRTLEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_diagnose"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDiagnosingRTLEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_for_action"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('TechLeadRTL/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_assign_inc"];
					$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrintRTL/[id]'),'color'=>'none','icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $to_print_return_form"];
				}
	        
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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export_return_diagnosing_rtl').'?'.urldecode(http_build_query(@$_GET))];
				//$this->index_button[] = ["label"=>"Export Returns","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturns'),"color"=>"success"];
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

			$query->leftJoin('retail_last_comments', 'retail_last_comments.returns_header_retail_id', 'returns_header_retail.id')
			->leftJoin('chats', 'chats.id', 'retail_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chats.created_by')
			->addSelect('chats.message as last_message',
				'chats.file_name as last_image',
				'sender.name as sender_name',
				'chats.created_at as date_send'
			);


			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 

				$storeList = self::getStoreList();

				$query->whereIn('returns_status_1', [
					ReturnsStatus::TO_DIAGNOSE,
					ReturnsStatus::TO_PRINT_SSR
				])
				->whereIn('transaction_type',[1,3])
				->where(function($query) use($storeList) {
					$query->whereIn('returns_header_retail.stores_id', $storeList)
					->orWhereIn('returns_header_retail.sc_location_id', $storeList);
				});

			}else if (CRUDBooster::myPrivilegeName() == "Tech Lead") {

				$query->whereIn('returns_status_1', [
					ReturnsStatus::TO_ASSIGN_INC, 
					ReturnsStatus::TO_DIAGNOSE, 
					ReturnsStatus::TO_TEST
				])
				->where('transaction_type', 0);

			}else if (CRUDBooster::myPrivilegeName() == "RMA Technician") {

				$query->whereIn('returns_status_1', [
					ReturnsStatus::TO_DIAGNOSE, 
					ReturnsStatus::TO_TEST
				])
				->where('level2_personnel', CRUDBooster::myId())
				->where('transaction_type', 0);

			}else if (CRUDBooster::myPrivilegeName() == "RMA Specialist") {

				$query->whereIn('returns_status_1', [
					ReturnsStatus::FOR_WARRANTY_CLAIM, 
					ReturnsStatus::TO_PRINT_SSR
				])
				->where('transaction_type', 0);

			} else{
				$query->whereIn('returns_status_1', [
					ReturnsStatus::TO_DIAGNOSE,
					ReturnsStatus::FOR_WARRANTY_CLAIM,
					ReturnsStatus::TO_PRINT_SSR,
					ReturnsStatus::TO_ASSIGN_INC,
					ReturnsStatus::TO_TEST
				])->where('transaction_type', 0);
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
			$to_schedule = 	ReturnsStatus::where('id','18')->value('warranty_status');
			$pending = ReturnsStatus::where('id','19')->value('warranty_status');
			$to_diagnose = ReturnsStatus::where('id','5')->value('warranty_status');
			$to_for_action = ReturnsStatus::where('id','38')->value('warranty_status');
			$to_assign_inc = ReturnsStatus::where('id','39')->value('warranty_status');
			$to_ongoing_testing = ReturnsStatus::where('id','40')->value('warranty_status');
			$to_print_return_form = ReturnsStatus::where('id','13')->value('warranty_status');
			$requested = 				ReturnsStatus::where('id','1')->value('warranty_status');

			if($column_index == 3){

				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_for_action){
					$column_value = '<span class="label label-warning">'.$to_for_action.'</span>';
			
				}elseif($column_value == $to_assign_inc){
					$column_value = '<span class="label label-warning">'.$to_assign_inc.'</span>';
				}
				elseif($column_value == $to_print_return_form){
					$column_value = '<span class="label label-warning">'.$to_print_return_form.'</span>';
				}
				elseif($column_value == $to_ongoing_testing){
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
			$ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();
			$to_assign_inc = ReturnsStatus::where('id','39')->value('id');
			$to_ongoing_testing = ReturnsStatus::where('id','40')->value('id');
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			
			if($ReturnRequest->returns_status_1 == $to_assign_inc || CRUDBooster::myPrivilegeName() == 'Super Administrator') {

				$returns_fields = Input::all();
				$postdata['level2_personnel'] = 					$returns_fields['technician'];
				$postdata['returns_status_1'] = 					$to_ongoing_testing;
				$postdata['assigned_by_tech_lead_id'] = 			CRUDBooster::myId();
				$postdata['assigned_date_by_tech_lead']=			date('Y-m-d H:i:s');

			}else if ($ReturnRequest->returns_status_1 == $to_ongoing_testing) {
				
				$postdata['returns_status_1'] = 					$to_diagnose;
				$postdata['ongoing_testing_date']=					date('Y-m-d H:i:s');
				
			}
			else {
				$returns_fields = Input::all();
				$field_1 		= $returns_fields['diagnose'];
				$field_2 		= $returns_fields['diagnose_comments'];
				$case_status 	= $returns_fields['case_status'];
				$store_id =     StoresFrontEnd::where('store_name', $ReturnRequest->store_dropoff )->where('channels_id', 6 )->first();
				$customer_location = Stores::where('stores_frontend_id',  $store_id->id )->where('branch_id',$ReturnRequest->branch_dropoff)->where('store_dropoff_privilege', 'YES')->first();
	
				$problem_details_lines = array();
				$items_included_lines = array();
	
				$problem_details 		= $returns_fields['problem_details'];
				$problem_details_other	= $returns_fields['problem_details_other'];
	
				for($xx=0; $xx < count((array)$problem_details); $xx++) {
					array_push($problem_details_lines,$problem_details[$xx]); 
	
				
				}
				$problem_details_lines = $problem_details_lines;
	
	
				$items_included 		= $returns_fields['verified_items_included'];
				$items_included_others	= $returns_fields['verified_items_included_others'];
				$warranty_status 		= $returns_fields['warranty_status_val'];
	
				for($xxx=0; $xxx < count((array)$items_included); $xxx++) {
					array_push($items_included_lines,$items_included[$xxx]); 
				}
	
				$items_included_lines = $items_included_lines;
				
					if ($field_1 == 'Save' && CRUDBooster::myPrivilegeName() == "RMA Specialist") {
	
						$postdata['case_status'] =  $case_status;
						
					}else if($field_1 == 'Save'){
						try {
							
								$postdata['case_status'] =  $case_status;
								$postdata['diagnose_comments'] = $field_2;
								$postdata['warranty_status'] = $warranty_status;
	
								$postdata['verified_items_included'] = implode(", ",$items_included_lines);
								$postdata['verified_items_included_others'] = $items_included_others;
		
								ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
								->update([		
									'problem_details'=> implode(", ",$problem_details_lines),
									'problem_details_other'=> $problem_details_other
								]);
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
					}
					else if($field_1 == 'PrintSSR'){
	
						$postdata['case_status'] =  						$case_status;
						$postdata['diagnose'] = 							"Service Center Repair";
						$postdata['rma_specialist_id'] = 					CRUDBooster::myId();
						$postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
						// $postdata['level2_personnel'] = 					CRUDBooster::myId();
						// $postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
						return redirect()->action('AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTL',['id'=>$ReturnRequest->id])->send();
					}
					else if ($field_1 == 'Test Done') {
						if(in_array(CRUDBooster::myPrivilegeName(), ['Tech Lead', 'RMA Technician', 'SuperAdministrator'])){

							$to_for_action = ReturnsStatus::where('id','38')->value('id');
			
							$postdata['case_status'] =  						$case_status;
							$postdata['returns_status_1'] = 					$to_for_action;
							$postdata['diagnose_comments'] = 					$field_2;
							$postdata['warranty_status'] = 						$warranty_status;
							$postdata['diagnose'] = 							"Test Done";
	
							// $postdata['rma_specialist_id'] = 					CRUDBooster::myId();
							// $postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
							$postdata['level2_personnel'] = 					CRUDBooster::myId();
							$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
		
							$postdata['verified_items_included'] = implode(", ",$items_included_lines);
							$postdata['verified_items_included_others'] = $items_included_others;
		
							ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
							->update([		
								'problem_details'=> implode(", ",$problem_details_lines),
								'problem_details_other'=> $problem_details_other
							]);
		
						}
					}
					
					else if($field_1 == "Replace"){
	
						$for_replacement = 	  		ReturnsStatus::where('id','20')->value('id');
						$for_replacement_frontend =	ReturnsStatus::where('id','27')->value('id');
						
						$to_sor = 				ReturnsStatus::where('id','9')->value('id');
		
						$diagnose_value = "REPLACE";
		
						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$for_replacement_frontend,
							date('Y-m-d H:i:s')
							]);
							
								if(CRUDBooster::myPrivilegeName() == "Service Center") {
									$postdata['level2_personnel'] = 					CRUDBooster::myId();
									$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
									$postdata['diagnose_comments'] = 					$field_2;
									$postdata['warranty_status'] = 						$warranty_status;
									$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
									$postdata['verified_items_included_others'] = 		$items_included_others;
									
									ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
									->update([		
										'problem_details'=> implode(", ",$problem_details_lines),
										'problem_details_other'=> $problem_details_other
									]);
		
								} else {
									$postdata['rma_specialist_id'] = 					CRUDBooster::myId();
									$postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
								}
								$postdata['returns_status'] = 						$for_replacement_frontend;
								$postdata['returns_status_1'] = 					$to_sor;
								$postdata['diagnose'] = 							$diagnose_value;
								$postdata['case_status'] =  						$case_status;
		
			
							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');
						
					}else if($field_1 == "Repair"){
						
						$repair_approved = 	  ReturnsStatus::where('id','16')->value('id');
						$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
		
						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$repair_approved,
							date('Y-m-d H:i:s')
							]);
				
									
								if(CRUDBooster::myPrivilegeName() == "Service Center") {
									$postdata['level2_personnel'] = 					CRUDBooster::myId();
									$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
									$postdata['diagnose_comments'] = 					$field_2;
									$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
									$postdata['verified_items_included_others'] = 		$items_included_others;
									$postdata['warranty_status'] = 						$warranty_status;
												
								ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
								->update([		
									'problem_details'=> implode(", ",$problem_details_lines),
									'problem_details_other'=> $problem_details_other
								]);
								
								} else {
									$postdata['rma_specialist_id'] = 					CRUDBooster::myId();
									$postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
								}
								$postdata['returns_status'] = 						$repair_approved;
								$postdata['returns_status_1'] = 					$to_print_return_form;
								$postdata['diagnose'] = 							"REPAIR";
								$postdata['case_status'] =  						$case_status;
					
							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');
		
					}else if($field_1 == "Reject"){
		
						$return_rejected = 	  ReturnsStatus::where('id','12')->value('id');
						$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
			
						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$return_rejected,
							date('Y-m-d H:i:s')
							]);
				
									
								if(CRUDBooster::myPrivilegeName() == "Service Center") {
									$postdata['level2_personnel'] = 					CRUDBooster::myId();
									$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
									$postdata['diagnose_comments'] = 					$field_2;
									$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
									$postdata['verified_items_included_others'] = 		$items_included_others;
									$postdata['warranty_status'] = 						$warranty_status;
									
								ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
								->update([		
									'problem_details'=> implode(", ",$problem_details_lines),
									'problem_details_other'=> $problem_details_other
								]);
								} else {
									$postdata['rma_specialist_id'] = 					CRUDBooster::myId();
									$postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
								}
								$postdata['returns_status'] = 						$return_rejected;
								$postdata['returns_status_1'] = 					$to_print_return_form;
								$postdata['diagnose'] = 							"REJECT";
								$postdata['case_status'] =  						$case_status;
		
		
			
							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');
						
					}else if($field_1 == "Refund"){
		
						$to_refund_approved = 	ReturnsStatus::where('id','6')->value('id');
						$to_print_crf = 		ReturnsStatus::where('id','7')->value('id');
						$to_create_crf = 		ReturnsStatus::where('id','25')->value('id');
		
						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$to_refund_approved,
							date('Y-m-d H:i:s')
							]);
				
									
								if(CRUDBooster::myPrivilegeName() == "Service Center") {
									$postdata['level2_personnel'] = 					CRUDBooster::myId();
									$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
									$postdata['diagnose_comments'] = 					$field_2;
									$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
									$postdata['verified_items_included_others'] = 		$items_included_others;
									$postdata['warranty_status'] = 						$warranty_status;
									
									ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
									->update([		
										'problem_details'=> implode(", ",$problem_details_lines),
										'problem_details_other'=> $problem_details_other
									]);
			
								} else {
									$postdata['rma_specialist_id'] = 					CRUDBooster::myId();
									$postdata['rma_specialist_date_received']=			date('Y-m-d H:i:s');
								}
									$postdata['returns_status'] = 						$to_refund_approved;
									$postdata['returns_status_1'] = 					$to_create_crf;
									$postdata['diagnose'] = 							"REFUND";
									$postdata['case_status'] =  						$case_status;
		
					
			
							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');
		
					}
			}

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
			$ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();
			$returns_fields = Input::all();
			$field_1 		= $returns_fields['diagnose'];
			
            
				if($field_1 == "Replace"){
					$for_replacement_frontend =	ReturnsStatus::where('id','27')->value('warranty_status');

					$for_replacement = 	  ReturnsStatus::where('id','20')->value('warranty_status');
							
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
					$data = ['name'=>$fullname,'status_return'=>$for_replacement_frontend,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
					//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been diagnosed as replace successfully!"), 'success');
				}else if($field_1 == "Repair"){

					$repair_approved = 	  ReturnsStatus::where('id','16')->value('warranty_status');
							
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
					$data = ['name'=>$fullname,'status_return'=>$repair_approved,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
					//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

					return redirect()->action('AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTL',['id'=>$ReturnRequest->id])->send();
					exit;
				}else if($field_1 == "Reject"){

					$return_rejected = 	  ReturnsStatus::where('id','12')->value('warranty_status');
							
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
					$data = ['name'=>$fullname,'status_return'=>$return_rejected,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
					//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

					return redirect()->action('AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTL',['id'=>$ReturnRequest->id])->send();
					exit;
				}else if($field_1 == "Refund"){

					$to_refund_approved = 	ReturnsStatus::where('id','6')->value('warranty_status');
							
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
					$data = ['name'=>$fullname,'status_return'=>$to_refund_approved,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
					//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been diagnosed as refund successfully!"), 'success');
				}
			

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

		public function forWarrantyClaim(Request $request){

			$return_input = $request->all();
			$transaction_information = DB::table($return_input['table_name'])->where('id', $return_input['id'])->first();

			$to_print_return_form = 13;
			// Replacement
			$for_replacement = 20;
			$for_replacement_frontend =	27;
			$to_sor = 9;
			// Repair
			$repair_approved = 16;
			// Reject
			$return_rejected = 12;
			// Refund
			$to_refund_approved = 6;
			$to_print_crf = 7;
			$to_create_crf = 25;

			$date = date('Y-m-d H:i:s');

			$frontend_to_insert = [$date];

			$return_status = 0;
			$return_status_1 = 0;

			if($return_input['diagnose'] == 'Replace'){
				$arr = [$transaction_information->return_reference_no, $for_replacement_frontend];
				$frontend_to_insert = array_merge($arr, $frontend_to_insert);
				$return_status = $for_replacement_frontend;
				$return_status_1 = $to_sor;
			}elseif($return_input['diagnose'] == 'Repair'){
				$arr = [$transaction_information->return_reference_no, $repair_approved];
				$frontend_to_insert = array_merge($arr, $frontend_to_insert);
				$return_status = $repair_approved;
				$return_status_1 = $to_print_return_form;
			}elseif($return_input['diagnose'] == 'Reject'){
				$arr = [$transaction_information->return_reference_no, $return_rejected];
				$frontend_to_insert = array_merge($arr, $frontend_to_insert);
				$return_status = $return_rejected;
				$return_status_1 = $to_print_return_form;
			}elseif($return_input['diagnose'] == 'Refund'){
				$arr = [$transaction_information->return_reference_no, $to_refund_approved];
				$frontend_to_insert = array_merge($arr, $frontend_to_insert);
				$return_status = $to_refund_approved;
				$return_status_1 = $to_create_crf;
			}
			if(CRUDBooster::myPrivilegeName() == "RMA Specialist" || CRUDBooster::myPrivilegeName() == "Super Administrator"){

				DB::beginTransaction();
			
				try {
					
					$counter = new ReferenceCounter();
					$rma_count_number = $counter->incrementCounter('RMA');
					$formatted_counter = 'RMA-'.str_pad($rma_count_number, 6, '0', STR_PAD_LEFT);
					
					DB::table($return_input['table_name'])->where('id', $return_input['id'])
						->update([
							'rma_specialist_id' => CRUDBooster::myId(),
							'rma_specialist_date_received' => $date,
							'returns_status' => $return_status,
							'returns_status_1' => $return_status_1,
							'diagnose' => strtoupper($return_input['diagnose']),
							'case_status' => $return_input['case_status'],
							'rma_number' => $formatted_counter,
						]);

					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					$frontend_to_insert);

					DB::commit();

				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}

				DB::disconnect('mysql_front_end');
			}

			return response()->json(['success' => $formatted_counter]);
		}

		public function returnReferenceNumber($id, $diagnose, $ref_number, $module_mainpath){
			
			if(($diagnose == 'Repair' || $diagnose == 'Reject') && ($module_mainpath == 'retail_return_diagnosing')){
				return redirect()->action('AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTL',['id'=>$id])->send();
			}else if(($diagnose == 'Repair' || $diagnose == 'Reject') && ($module_mainpath == 'returns_diagnosing')){
				return redirect()->action('AdminReturnsDiagnosingController@ReturnsReturnFormPrint',['id'=>$id])->send();
			}else if(($diagnose == 'Repair' || $diagnose == 'Reject') && ($module_mainpath == 'distri_return_diagnosing')){
				return redirect()->action('AdminDistriReturnDiagnosingController@ReturnsReturnFormPrintDISTRI',['id'=>$id])->send();
			}else {
				CRUDBooster::redirect(CRUDBooster::adminPath()."/{$module_mainpath}", "The return request has been diagnosed as $diagnose successfully! RMA #: $ref_number", 'success');
			}

			// CRUDBooster::redirect(CRUDBooster::adminPath()."/{$module_mainpath}", "Request submitted successfully RMA #: $ref_number", 'success');
		}

		public function ReturnsDiagnosingRTLEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
			//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
			->leftjoin('cms_users as technician_assigned', 'returns_header_retail.level2_personnel','=', 'technician_assigned.id')				
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')	
			->leftjoin('cms_users as received_item', 'returns_header_retail.received_by_rma_sc','=', 'received_item.id')																	
			->leftjoin('cms_users as turnover_by', 'returns_header_retail.rma_receiver_id','=', 'turnover_by.id')																	
			->leftjoin('transaction_type', 'returns_header_retail.transaction_type_id', '=', 'transaction_type.id')
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			//'tagged.name as tagged_by',	
			'diagnosed.name as diagnosed_by',
			'technician_assigned.name as technician_assigned',
			'received_item.name as received_item_by',
			'turnover_by.name as turnover_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'transaction_type.transaction_type_name',
			'created.name as created_by'			
			)
			->where('returns_header_retail.id',$id)->first();
			$data['problem_details_list'] = ProblemDetails::all();
			$data['page_title'] = $data['row']->returns_status_1 == 5 ? 'Returns For Diagnosing' : 'Returns For Specialist';
			$data['case_status'] = CaseStatus::where('status','=','ACTIVE')->pluck('case_status_name');
			
			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();
			// dd($data['items_included_list'], $data['problem_details_list']);

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
			
			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			// if (CrudBooster::myPrivilegeName() == 'Super Administrator'){
			// 	$this->cbView("components.edit_diagnosing", $data);
			// }else{
			// 	$this->cbView("returns.edit_diagnosing_retail", $data);
			// }
			$data['comments_data'] = (new ChatController)->getComments($id);

			$this->cbView("components.edit_diagnosing", $data);
		}


		public function ReturnsDiagnosingRTLEditSC($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Diagnosing';
		
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

			
			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
			
			$data['resultlist'] = ReturnsBodyRTL::
			leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')					
			->select(
			'returns_body_item_retail.*',
			'returns_serial_retail.*'					
			)
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.edit_diagnosing_retailSC", $data);
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


		public function ReturnsReturnFormPrintRTLSC($id)
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
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.print_return_form_retailSC", $data);
		}

		public function FormRejectUpdateStatusRTL(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();
			
			if($return_request->transaction_type == 3){
			    
    			if($return_request->returns_status_1 != $to_ship_back){
    				DB::beginTransaction();
    				
    			
    				try {
    	                
    					DB::connection('mysql_front_end')
    					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    					[$return_request->return_reference_no, 
    					$to_ship_back,
    					date('Y-m-d H:i:s')
    					]); 
    		
    						ReturnsHeaderRTL::where('id',$request_id)
    						->update([
    						//'status_level0'=> $status_all,
    						'level3_personnel'=> 		CRUDBooster::myId(),
    						'level3_personnel_edited'=> date('Y-m-d H:i:s'),
    						'returns_status'=> 			$to_ship_back,
    						'returns_status_1'=> 		$to_ship_back
    						]);	
    	
    					DB::commit();
    					
    					
    					    
    						$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
    				
    						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
    			
    						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
    				
    				
    					//	CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);	 
    	
    				}catch (\Exception $e) {
    					DB::rollback();
    					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
    				}
    	
    				DB::disconnect('mysql_front_end');
    			}			    
    			
			}else{

    			if($return_request->returns_status_1 != $to_ship_back){
    				DB::beginTransaction();
    				
    			
    				try {
    	                /*
    					DB::connection('mysql_front_end')
    					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    					[$return_request->return_reference_no, 
    					$to_ship_back,
    					date('Y-m-d H:i:s')
    					]); */
    		
    						ReturnsHeaderRTL::where('id',$request_id)
    						->update([
    						//'status_level0'=> $status_all,
    						'level3_personnel'=> 		CRUDBooster::myId(),
    						'level3_personnel_edited'=> date('Y-m-d H:i:s'),
    						'returns_status'=> 			$return_delivery_date,
    						'returns_status_1'=> 		$return_delivery_date
    						]);	
    	
    					DB::commit();
    					
    					
    					    /*
    						$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
    				
    						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
    			
    						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
    				
    				
    						CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);	 */
    	
    				}catch (\Exception $e) {
    					DB::rollback();
    					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
    				}
    	
    				DB::disconnect('mysql_front_end');
    			}
    			
			}
		}


		public function FormRejectUpdateStatusRTLSC(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			//$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			$return_invalid = 			ReturnsStatus::where('id','15')->value('id');

			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();

			if($return_request->returns_status_1 != $return_invalid){
				DB::beginTransaction();
	
				try {
	
					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[$return_request->return_reference_no, 
					$return_invalid,
					date('Y-m-d H:i:s')
					]);
		
						ReturnsHeaderRTL::where('id',$request_id)
						->update([
						//'status_level0'=> $status_all,
						'level2_personnel'=> 		CRUDBooster::myId(),
						'level2_personnel_edited'=> date('Y-m-d H:i:s'),
						'returns_status'=> 			$return_invalid,
						'returns_status_1'=> 		$return_invalid
						]);	
	
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');
			}
		}

		public function FormRepairUpdateStatusRTL(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();
			
			
            if($return_request->transaction_type == 3){
                
                if($return_request->returns_status_1 != $to_ship_back){
                    
    				DB::beginTransaction();
    	
    	
    
    				try {
    	                
    					DB::connection('mysql_front_end')
    					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    					[$return_request->return_reference_no, 
    					$to_ship_back,
    					date('Y-m-d H:i:s')
    					]);
    		
    						ReturnsHeaderRTL::where('id',$request_id)
    						->update([
    						//'status_level0'=> $status_all,
    						'level3_personnel'=> 		CRUDBooster::myId(),
    						'level3_personnel_edited'=> date('Y-m-d H:i:s'),
    						'returns_status'=> 			$to_ship_back,
    						'returns_status_1'=> 		$to_ship_back
    						]);	
    	
    					DB::commit();
    					
    					
    					
    					    
    						$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
    				
    						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
    			
    						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
    				
    				
    						//CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);	 
    	
    				}catch (\Exception $e) {
    					DB::rollback();
    					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
    				}
    	
    				DB::disconnect('mysql_front_end');
    			}
                
            }else{

    			if($return_request->returns_status_1 != $to_ship_back){
    				DB::beginTransaction();
    	
    	
    
    				try {
    	                /*
    					DB::connection('mysql_front_end')
    					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    					[$return_request->return_reference_no, 
    					$to_ship_back,
    					date('Y-m-d H:i:s')
    					]);*/
    		
    						ReturnsHeaderRTL::where('id',$request_id)
    						->update([
    						//'status_level0'=> $status_all,
    						'level3_personnel'=> 		CRUDBooster::myId(),
    						'level3_personnel_edited'=> date('Y-m-d H:i:s'),
    						'returns_status'=> 			$return_delivery_date,
    						'returns_status_1'=> 		$return_delivery_date
    						]);	
    	
    					DB::commit();
    					
    					
    					
    					    /*
    						$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
    				
    						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
    			
    						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
    				
    				
    						CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);	 */
    	
    				}catch (\Exception $e) {
    					DB::rollback();
    					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
    				}
    	
    				DB::disconnect('mysql_front_end');
    			}
    			
            }
		}


		public function FormRepairUpdateStatusRTLSC(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			//$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('id');

			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();


			if($return_request->returns_status_1 != $repair_complete){
				DB::beginTransaction();
	
				try {
	
					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[$return_request->return_reference_no, 
					$repair_complete,
					date('Y-m-d H:i:s')
					]);
		
						ReturnsHeaderRTL::where('id',$request_id)
						->update([
						//'status_level0'=> $status_all,
						'level2_personnel'=> 		CRUDBooster::myId(),
						'level2_personnel_edited'=> date('Y-m-d H:i:s'),
						'returns_status'=> 			$repair_complete,
						'returns_status_1'=> 		$repair_complete
						]);	
	
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');
			}
		}


		public function TechLeadRTL($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();

			$data['technicians'] = DB::table('cms_users')->whereIn('id_cms_privileges', ['20', '22'])->where('status', "ACTIVE")->orderBy('id_cms_privileges','desc')->get();
			
			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header_retail.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header_retail.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')	
			->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level8_personnel','=', 'scheduled_logistics.id')																	
			->leftjoin('transaction_type', 'returns_header_retail.transaction_type_id', '=', 'transaction_type.id')
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'scheduled_logistics.name as scheduled_by_logistics',					
			'transaction_type.transaction_type_name'
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
						
			// $this->cbView("returns.to_receive_retail_rma", $data);
			$data['comments_data'] = (new ChatController)->getComments($id);

			$this->cbView("components.to_receive_rma", $data);

		}

		public function exportReturnDiagnosingRTL()
		{
			$filename = 'Returns - ' . date("d M Y - h.i.sa");
			$orderData = self::getQueryData();

			if (CRUDBooster::myPrivilegeName() == "Service Center") {

				$result = self::getServiceCenterResult($orderData);
				$headers = self::getServiceCenterExportHeaders();

			} elseif (CRUDBooster::myPrivilegeName() == "Tech Lead") {

				$result = self::getTechLeadResult($orderData);
				$headers = self::getRMAExportHeaders();

			} elseif (CRUDBooster::myPrivilegeName() == "RMA Technician") {

				$result = self::getRMATechnicianResult($orderData);
				$headers = self::getRMAExportHeaders();

			}  elseif (CRUDBooster::myPrivilegeName() == "RMA Specialist") {

				$result = self::getRMASpecialistResult($orderData);
				$headers = self::getRMAExportHeaders();

			} else {
				//other RMA
				$result = self::getOtherResult($orderData);
				$headers = self::getRMAExportHeaders();
			}

			
			$finalData = self::filterFinalData($result);

			$orderItems = self::processOrderData($finalData);

			self::exportToExcel($filename, $orderItems, $headers);
		}

		private function getQueryData(){

			$orderData = DB::table('returns_header_retail')
				->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
				->leftjoin('cms_users as verified', 'returns_header_retail.level7_personnel','=', 'verified.id')
				->leftjoin('cms_users as scheduled_logistics', 'returns_header_retail.level1_personnel','=', 'scheduled_logistics.id')		
				->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as received1', 'returns_header_retail.received_by_rma_sc','=', 'received1.id')
				->leftjoin('cms_users as turnover', 'returns_header_retail.rma_receiver_id','=', 'turnover.id')
				->leftjoin('cms_users as specialist', 'returns_header_retail.rma_specialist_id','=', 'specialist.id')
				->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')	
				->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
				->select( 	'returns_header_retail.created_at as rhr_created_at',
							'returns_header_retail.*', 
							'returns_body_item_retail.*', 
							'returns_body_item_retail.id as body_id', 
							'verified.name as verified_by',	
							'scheduled_logistics.name as scheduled_logistics_by',
							'diagnosed.name as diagnosed_by',
							'printed.name as printed_by',	
							'transacted.name as transacted_by',	
							'received.name as received_by',
							'received1.name as received_by1',
							'turnover.name as turnover_by',
							'specialist.name as specialist_by',
							'closed.name as closed_by',
							'warranty_statuses.*');
				

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
				return $result->orderBy('returns_header_retail.id', 'asc')->get();
			}
		}

		private function processOrderData($finalData)
		{
			$orderItems = [];

			$privilegeName = CRUDBooster::myPrivilegeName();

			foreach ($finalData as $orderLine) {


				switch($privilegeName){
				
					case 'RMA Inbound':
					case 'Tech Lead':
					case 'RMA Technician':
					case 'RMA Specialist':
						$orderItems[] = self::getRMAData($orderLine);
					break;

					case 'Service Center':
						$orderItems[] = self::getServiceCenterData($orderLine);
					break;

					default:
						$orderItems[] = [];
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

		private function getTechLeadResult($orderData) {
			return $orderData->whereIn('returns_status_1', [
				ReturnsStatus::TO_ASSIGN_INC, 
				ReturnsStatus::TO_DIAGNOSE, 
				ReturnsStatus::TO_TEST
			])
			->where('transaction_type', 0)
			->groupby('return_reference_no')
			->orderBy('rhr_created_at', 'desc');
		}

		private function getRMATechnicianResult($orderData) {
			return $orderData->whereIn('returns_status_1', [
				ReturnsStatus::TO_DIAGNOSE, 
				ReturnsStatus::TO_TEST
			])
			->where('level2_personnel', CRUDBooster::myId())
			->where('transaction_type', 0)
			->groupby('return_reference_no')
			->orderBy('rhr_created_at', 'desc');
		}

		private function getRMASpecialistResult($orderData)
		{
			return $orderData->whereIn('returns_status_1', [
				ReturnsStatus::FOR_WARRANTY_CLAIM, 
				ReturnsStatus::TO_PRINT_SSR
			])
			->where('transaction_type', 0)
			->groupby('return_reference_no')
			->orderBy('rhr_created_at', 'desc');

		}

		private function getOtherResult($orderData)
		{
			return $orderData->whereIn('returns_status_1', [
				ReturnsStatus::TO_DIAGNOSE,
				ReturnsStatus::FOR_WARRANTY_CLAIM,
				ReturnsStatus::TO_PRINT_SSR,
				ReturnsStatus::TO_ASSIGN_INC,
				ReturnsStatus::TO_TEST
			])->where('transaction_type', 0)
			->groupby('return_reference_no')
			->orderBy('rhr_created_at', 'desc');

		}

		private function getServiceCenterResult($orderData)
		{
			$storeList = self::getStoreList();

			return $orderData->whereIn('returns_status_1', [
				ReturnsStatus::TO_DIAGNOSE,
				ReturnsStatus::TO_PRINT_SSR
			])
			->whereIn('transaction_type',[1,3])
			->where(function($query) use($storeList) {
				$query->whereIn('returns_header_retail.stores_id', $storeList)
				->orWhereIn('returns_header_retail.sc_location_id', $storeList);
			})
			->groupby('return_reference_no')
			->orderBy('rhr_created_at', 'desc');

		}


		private function getServiceCenterData($orderLine){
			$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->rhr_created_at,				
				$orderLine->return_reference_no,					
				$orderLine->purchase_location,				
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
				$orderLine->refunded_date,  
				$orderLine->date_adjusted,
				$orderLine->stock_adj_ref_no,
				$orderLine->sor_number,      
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
				self::getVerifiedBy($orderLine),
				self::getVerifiedDate($orderLine),
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->diagnosed_by,
				$orderLine->level2_personnel_edited,
				self::getPrintedBy($orderLine),
				self::getPrintedDate($orderLine),
				self::getTransactedBy($orderLine),			
				self::getTransactedDate($orderLine),
				self::getClosedBy($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}

		private function getRMAData($orderLine){

			$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderLine->body_id)->first();

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->rhr_created_at,				
				$orderLine->return_reference_no,					
				$orderLine->inc_number,					
				$orderLine->purchase_location,				
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
				$orderLine->refunded_date,  
				$orderLine->date_adjusted,
				$orderLine->stock_adj_ref_no,
				$orderLine->sor_number,      
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
				self::getVerifiedBy($orderLine),
				self::getVerifiedDate($orderLine),
				self::getScheduledBy($orderLine),
				self::getScheduledDate($orderLine),
				$orderLine->turnover_by,
				$orderLine->rma_receiver_date_received,
				$orderLine->received_by1,
				$orderLine->received_at_rma_sc,
				$orderLine->diagnosed_by,
				$orderLine->level2_personnel_edited,
				$orderLine->specialist_by,
				$orderLine->rma_specialist_date_received,
				self::getPrintedBy($orderLine),
				self::getPrintedDate($orderLine),
				self::getTransactedBy($orderLine),			
				self::getTransactedDate($orderLine),
				self::getClosedBy($orderLine),
				self::getClosedDate($orderLine),
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
		}

		private function getServiceCenterExportHeaders(){	
			
			return [
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
				'ITEMS INCLUDED',        
				'ITEMS INCLUDED OTHERS',
				'VERIFIED ITEMS INCLUDED',        
				'VERIFIED ITEMS INCLUDED OTHERS',
				'CUSTOMER LOCATION',               
				'DELIVER TO',               
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'DATE ADJUSTED',               
				'STOCK ADJUSTED REF#',               
				'SOR#',               
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
		private function getRMAExportHeaders(){	
			
			return [
				'RETURN STATUS',
				'DIAGNOSE',
				'CREATED DATE',
				'RETURN REFERENCE#',
				'INC#',
				'PURCHASE LOCATION',
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
				'PICKUP SCHEDULE',               
				'REFUNDED DATE',               
				'DATE ADJUSTED',               
				'STOCK ADJUSTED REF#',               
				'SOR#',               
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

		private function getScheduledBy($orderRow)
		{
			if ($orderRow->transaction_type == 3) {
				return "";
			} else {
				return $orderRow->scheduled_logistics_by;
			}
		}

		private function getScheduledDate($orderRow)
		{
			if ($orderRow->transaction_type == 3) {
				return "";
			} else {
				return $orderRow->level1_personnel_edited;
			}
		}

		private function getVerifiedBy($orderRow)
		{
			return $orderRow->verified_by;
		}

		private function getVerifiedDate($orderRow)
		{
			return $orderRow->level7_personnel_edited;
		}

		private function getPrintedBy($orderRow)
		{
			if ($orderRow->diagnose == "REFUND") {
				return $orderRow->printed_by;
			} elseif($orderRow->diagnose == "REPLACE") {
				return "";
			} else {
				return $orderRow->printed_by;
			}
		}

		private function getPrintedDate($orderRow)
		{
			if ($orderRow->diagnose == "REFUND") {
				return $orderRow->level3_personnel_edited;
			} elseif($orderRow->diagnose == "REPLACE") {
				return "";
			} else {
				return $orderRow->level3_personnel_edited;
			}
		}

		private function getTransactedBy($orderRow)
		{
			if ($orderRow->diagnose == "REPLACE") {
				return $orderRow->printed_by;
			} elseif ($orderRow->diagnose == "REFUND") {
				return $orderRow->transacted_by;
			} else {
				return "";
			}
		}

		private function getTransactedDate($orderRow)
		{
			if ($orderRow->diagnose == "REPLACE") {
				return $orderRow->level3_personnel_edited;
			} elseif ($orderRow->diagnose == "REFUND") {
				return $orderRow->level4_personnel_edited;
			} else {
				return "";
			}
		}

		private function getClosedBy($orderRow)
		{
			if ($orderRow->diagnose == "REPLACE") {
				return $orderRow->transacted_by;
			} elseif ($orderRow->diagnose == "REFUND") {
				return $orderRow->closed_by;
			} else {
				return $orderRow->transacted_by;
			}
		}

		private function getClosedDate($orderRow)
		{
			if ($orderRow->diagnose == "REPLACE") {
				return $orderRow->level4_personnel_edited;
			} elseif ($orderRow->diagnose == "REFUND") {
				return $orderRow->level5_personnel_edited;
			} else {
				return $orderRow->level4_personnel_edited;
			}
		}


	}