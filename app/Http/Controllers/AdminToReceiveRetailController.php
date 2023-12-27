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
use App\StoresFrontEnd;
use App\Channel;
use App\ModeOfPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use App\DiagnoseWarranty;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use App\Item;
use App\ItemsIncluded;
use App\ReferenceCounter;
use App\TransactionTypeList;


	class AdminToReceiveRetailController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
			$this->col[] = ["label"=>"Last Chat", "name"=>"id", 'callback'=>function($row){

				$img_url = asset("chat_img/$row->last_image");

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

			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Returns Status","name"=>"returns_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Returns Status 1","name"=>"returns_status_1","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Return Schedule","name"=>"return_schedule","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Refunded Date","name"=>"refunded_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Customer Location","name"=>"customer_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Return Reference No","name"=>"return_reference_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purchase Location","name"=>"purchase_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Store","name"=>"store","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Mode Of Return","name"=>"mode_of_return","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Branch","name"=>"branch","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Store Dropoff","name"=>"store_dropoff","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Branch Dropoff","name"=>"branch_dropoff","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer Last Name","name"=>"customer_last_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer First Name","name"=>"customer_first_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Address","name"=>"address","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Email Address","name"=>"email_address","type"=>"email","required"=>TRUE,"validation"=>"required|min:1|max:255|email|unique:returns_header_retail","placeholder"=>"Please enter a valid email address"];
			//$this->form[] = ["label"=>"Contact No","name"=>"contact_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Order No","name"=>"order_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Purchase Date","name"=>"purchase_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Mode Of Refund","name"=>"mode_of_refund","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Name","name"=>"bank_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Account No","name"=>"bank_account_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Bank Account Name","name"=>"bank_account_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Items Included","name"=>"items_included","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Items Included Others","name"=>"items_included_others","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Verified Items Included","name"=>"verified_items_included","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Verified Items Included Others","name"=>"verified_items_included_others","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Comments","name"=>"comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Diagnose Comments","name"=>"diagnose_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Sor Number","name"=>"sor_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total Quantity","name"=>"total_quantity","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Diagnose","name"=>"diagnose","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Warranty Status","name"=>"warranty_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			//$this->form[] = ["label"=>"Level8 Personnel","name"=>"level8_personnel","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Level8 Personnel Edited","name"=>"level8_personnel_edited","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Notes","name"=>"notes","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Ship Back Status","name"=>"ship_back_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Claimed Status","name"=>"claimed_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Credit Memo Number","name"=>"credit_memo_number","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rma Edited By","name"=>"rma_edited_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Rma Edited At","name"=>"rma_edited_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Date Adjusted","name"=>"date_adjusted","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Stock Adj Ref No","name"=>"stock_adj_ref_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Deliver To","name"=>"deliver_to","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Transaction Type","name"=>"transaction_type","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 

				$to_receive = 				ReturnsStatus::where('id','29')->value('id');				
				$to_print_return_form = 	ReturnsStatus::where('id','13')->value('id');
				$to_print_srr  =     		ReturnsStatus::where('id','19')->value('id');
				$to_diagnose = 				ReturnsStatus::where('id','5')->value('id');
				$to_receive_sc = 			ReturnsStatus::where('id','35')->value('id');
				

				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ToReceiveSCRTL/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_receive_sc"];

				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsToReceiveEditRTL/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_diagnose"];
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDiagnosingRTLEditSC/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_receive"];
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrintRTLSC/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $to_print_return_form"];
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsSRRPrintSC/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $to_print_srr"];

			}else{

				$to_receive_rma = ReturnsStatus::where('id','34')->value('id');
				$to_rma_received = ReturnsStatus::where('id','37')->value('id');

				if(CRUDBooster::myPrivilegeId() == 4){
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ToReceiveRTL/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_receive_rma"];
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ToReceiveRTL/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_rma_received"];
				}
				else{
					$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ToReceiveRTL/[id]'),'icon'=>'fa fa-pencil'];
				}
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' ){

				$this->index_button[] = ["title"=>"Export Returns",
				"label"=>"Export Returns",
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsToReceiveSCRTL').'?'.urldecode(http_build_query(@$_GET))];
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

				$query->where(function($sub_query){
					$to_receive = ReturnsStatus::where('id','29')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
					$to_diagnose = ReturnsStatus::where('id','5')->value('id');
					$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
					$to_receive_sc = 			ReturnsStatus::where('id','35')->value('id');
					
					
        				$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
        				$approval_array = array();
        				foreach($approvalMatrix as $matrix){
        				    array_push($approval_array, $matrix->stores_id);
        				}
        				$approval_string = implode(",",$approval_array);
        				$storeList = array_map('intval',explode(",",$approval_string));      
					
					
					//$sub_query->where('returns_status_1', $to_receive)->where('transaction_type', 3)->where('stores_id', CRUDBooster::myStoreId())->orderBy('id', 'asc');  
					//$sub_query->orWhere('returns_status_1', $to_print_return_form)->where('transaction_type', 3)->where('stores_id', CRUDBooster::myStoreId())->orderBy('id', 'asc');
					
					$sub_query->where('returns_status_1', $to_receive)->where('transaction_type', 3)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');  

					$sub_query->orWhere('returns_status_1', $to_print_return_form)->where('transaction_type', 3)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');

					$sub_query->orWhere('returns_status_1', $to_print_srr)->where('transaction_type', 3)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');

					$sub_query->orWhere('returns_status_1', $to_diagnose)->where('transaction_type', 3)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');

					$sub_query->where('returns_status_1', $to_receive)->where('transaction_type', 1)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');  

					$sub_query->orWhere('returns_status_1', $to_receive_sc)->where('transaction_type', 1)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');
					$sub_query->orWhere('returns_status_1', $to_receive_sc)->where('transaction_type', 0)->whereIn('returns_header_retail.stores_id', $storeList)->orderBy('id', 'asc');

					$sub_query->orWhere('returns_status_1', $to_receive_sc)->whereIn('transaction_type', [0,1])->whereIn('returns_header_retail.sc_location_id', $storeList)->orderBy('id', 'asc');
					
				});   


			}
			else{

				$query->where(function($sub_query){
			
					$to_receive_rma = ReturnsStatus::where('id','34')->value('id');
					$to_rma_received = ReturnsStatus::where('id','37')->value('id');
					
					$sub_query->whereIn('returns_status_1', [$to_receive_rma, $to_rma_received])->where('transaction_type', 0)->orderBy('id', 'asc');  

				});
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
			$to_indicate_store = 		ReturnsStatus::where('id','3')->value('warranty_status');
			$to_diagnose = 				ReturnsStatus::where('id','5')->value('warranty_status');
			$to_receive_sor = ReturnsStatus::where('id','10')->value('warranty_status');
			$to_print_return_form = ReturnsStatus::where('id','13')->value('warranty_status');
			$to_receive = ReturnsStatus::where('id','29')->value('warranty_status');
            
            $to_print_srr  =     ReturnsStatus::where('id','19')->value('warranty_status');

			$to_pickup_by_log = 			ReturnsStatus::where('id','34')->value('warranty_status');
			$to_rma_received = 			ReturnsStatus::where('id','37')->value('warranty_status');
			$to_receive_sc = 			ReturnsStatus::where('id','35')->value('warranty_status');

			if($column_index == 1){
				if($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_indicate_store){
					$column_value = '<span class="label label-warning">'.$to_indicate_store.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_receive_sor){
					$column_value = '<span class="label label-warning">'.$to_receive_sor.'</span>';
			
				}elseif($column_value == $to_print_return_form){
					$column_value = '<span class="label label-warning">'.$to_print_return_form.'</span>';
			
				}elseif($column_value == $to_receive){
					$column_value = '<span class="label label-warning">'.$to_receive.'</span>';
			
				}elseif($column_value == $to_print_srr){
					$column_value = '<span class="label label-warning">'.$to_print_srr.'</span>';
			
				}elseif($column_value == $to_pickup_by_log){
					$column_value = '<span class="label label-warning">'.$to_pickup_by_log.'</span>';
			
				}elseif($column_value == $to_receive_sc){
					$column_value = '<span class="label label-warning">'.$to_receive_sc.'</span>';
			
				}elseif($column_value == $to_rma_received){
					$column_value = '<span class="label label-warning">'.$to_rma_received.'</span>';
			
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
	    /*
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	        
                    $ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();
                    
					$returns_fields = Input::all();
					$field_1 		= $returns_fields['diagnose'];
					$field_2 		= $returns_fields['diagnose_comments'];

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
					
					
					
						$received = 	  ReturnsStatus::where('id','31')->value('id');
			

						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$received,
							date('Y-m-d H:i:s')
							]);
				
								$postdata['level1_personnel'] = 					CRUDBooster::myId();
								$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');
								$postdata['returns_status'] = 						$received;
								$postdata['returns_status_1'] = 					$received;
								$postdata['diagnose_comments'] = 					$field_2;
								//$postdata['diagnose'] = 							"REPAIR";
								$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
								$postdata['verified_items_included_others'] = 		$items_included_others;
								$postdata['warranty_status'] = 						$warranty_status;

								ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
								->update([		
									'problem_details'=> implode(", ",$problem_details_lines),
									'problem_details_other'=> $problem_details_other
								]);

							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');

	    } */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	        
	        $ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();
			
			if(CRUDBooster::myPrivilegeName() == "Service Center"){

				$ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();


				$to_receive_sc = 			ReturnsStatus::where('id','35')->value('id');

				if($ReturnRequest->returns_status_1 == $to_receive_sc){

					$returns_fields = Input::all();

					$to_diagnose = ReturnsStatus::where('id','5')->value('id');

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


					$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
					$postdata['verified_items_included_others'] = 		$items_included_others;

					$postdata['returns_status_1'] = 					$to_diagnose;
					$postdata['received_by_rma_sc'] = 					CRUDBooster::myId();
					$postdata['received_at_rma_sc']=					date('Y-m-d H:i:s');

					ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
					->update([		
						'problem_details'=> implode(", ",$problem_details_lines),
						'problem_details_other'=> $problem_details_other
					]);  
					
					
								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $ReturnRequest->return_reference_no, 
									    $to_diagnose,
									    date('Y-m-d H:i:s')
									]);

									DB::commit();
					
								}catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
					
								DB::disconnect('mysql_front_end');	
								

				}else{
				
					$returns_fields = Input::all();
					$field_1 		= $returns_fields['customer_location'];
					$field_2 		= $returns_fields['requestor_comments'];
					$remarks 		= $returns_fields['remarks'];

					$purchase_location 		= $returns_fields['purchase_location'];
					$store 			= $returns_fields['store'];
					$branch 		= $returns_fields['branch'];
					$mode_of_return 		= $returns_fields['mode_of_return'];
					$store_dropoff 		= $returns_fields['store_dropoff'];
					$branch_dropoff 		= $returns_fields['branch_dropoff'];
					$customer_last_name 		= $returns_fields['customer_last_name'];
					$customer_first_name 		= $returns_fields['customer_first_name'];
					$address 		= $returns_fields['address'];
					$email_address 		= $returns_fields['email_address'];
					$contact_no 		= $returns_fields['contact_no'];
					$order_no 		= $returns_fields['order_no'];
					$purchase_date 		= $returns_fields['purchase_date'];
					$mode_of_payment 		= $returns_fields['mode_of_payment'];
					$mode_of_refund 		= $returns_fields['mode_of_refund'];
					$bank_name 		= $returns_fields['bank_name'];
					$bank_account_no 		= $returns_fields['bank_account_no'];
					$bank_account_name 		= $returns_fields['bank_account_name'];
					$warranty_status 		= $returns_fields['warranty_status_val'];
					
					
					$field_diagnose 		        = $returns_fields['diagnose'];
					$field_diagnose_comments 		= $returns_fields['diagnose_comments'];
					
					$to_diagnose = ReturnsStatus::where('id','5')->value('id');
					
					if($remarks == "CANCEL"){
						$cancelled = 	ReturnsStatus::where('id','28')->value('id');

						$postdata['level1_personnel'] = 					CRUDBooster::myId();
						$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');

						$postdata['returns_status'] = 						$cancelled;
						$postdata['returns_status_1'] = 					$cancelled;

						DB::beginTransaction();

						try {

						DB::connection('mysql_front_end')
						->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
						[$ReturnRequest->return_reference_no, 
						$cancelled,
						date('Y-m-d H:i:s')
						]);

						DB::commit();
				
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}

						DB::disconnect('mysql_front_end');

					}else{
						
						if($ReturnRequest->returns_status_1 == $to_diagnose){
							
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
							

									if($field_diagnose == "Replace"){
					
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
								
												$postdata['level2_personnel'] = 					CRUDBooster::myId();
												$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
												$postdata['returns_status'] = 						$for_replacement_frontend;
												$postdata['returns_status_1'] = 					$to_sor;
												$postdata['diagnose_comments'] = 					$field_diagnose_comments;
												$postdata['diagnose'] = 							$diagnose_value;
												$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
												$postdata['verified_items_included_others'] = 		$items_included_others;
												$postdata['warranty_status'] = 						$warranty_status;
												
												
												ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
												->update([		
													'problem_details'=> implode(", ",$problem_details_lines),
													'problem_details_other'=> $problem_details_other
												]);  
						
							
											DB::commit();
							
										}catch (\Exception $e) {
											DB::rollback();
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
										}
							
										DB::disconnect('mysql_front_end');
										
									}else if($field_diagnose == "Repair"){
										
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
								
												$postdata['level2_personnel'] = 					CRUDBooster::myId();
												$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
												$postdata['returns_status'] = 						$repair_approved;
												$postdata['returns_status_1'] = 					$to_print_return_form;
												$postdata['diagnose_comments'] = 					$field_diagnose_comments;
												$postdata['diagnose'] = 							"REPAIR";
												$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
												$postdata['verified_items_included_others'] = 		$items_included_others;
												$postdata['warranty_status'] = 						$warranty_status;
						
												
												ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
												->update([		
													'problem_details'=> implode(", ",$problem_details_lines),
													'problem_details_other'=> $problem_details_other
												]);
						
							
											DB::commit();
							
										}catch (\Exception $e) {
											DB::rollback();
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
										}
							
										DB::disconnect('mysql_front_end');
						
									}else if($field_diagnose == "Reject"){
						
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
								
												$postdata['level2_personnel'] = 					CRUDBooster::myId();
												$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
												$postdata['returns_status'] = 						$return_rejected;
												$postdata['returns_status_1'] = 					$to_print_return_form;
												$postdata['diagnose_comments'] = 					$field_diagnose_comments;
												$postdata['diagnose'] = 							"REJECT";
												$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
												$postdata['verified_items_included_others'] = 		$items_included_others;
												$postdata['warranty_status'] = 						$warranty_status;
												
												
												ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
												->update([		
													'problem_details'=> implode(", ",$problem_details_lines),
													'problem_details_other'=> $problem_details_other
												]); 
						
							
											DB::commit();
							
										}catch (\Exception $e) {
											DB::rollback();
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
										}
							
										DB::disconnect('mysql_front_end');
										
									}else if($field_diagnose == "Refund"){
						
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
								
													$postdata['level2_personnel'] = 					CRUDBooster::myId();
													$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
													$postdata['returns_status'] = 						$to_refund_approved;
													$postdata['returns_status_1'] = 					$to_create_crf;
													$postdata['diagnose_comments'] = 					$field_diagnose_comments;
													$postdata['diagnose'] = 							"REFUND";
													$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
													$postdata['verified_items_included_others'] = 		$items_included_others;
													$postdata['warranty_status'] = 						$warranty_status;
						
													
													ReturnsBodyRTL::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
													->update([		
														'problem_details'=> implode(", ",$problem_details_lines),
														'problem_details_other'=> $problem_details_other
													]); 
							
							
											DB::commit();
							
										}catch (\Exception $e) {
											DB::rollback();
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
										}
							
										DB::disconnect('mysql_front_end');
						
									}                
							
							
						}else{
						
						
						
						
						
						
						$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
						$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
						//$to_ship = 	  ReturnsStatus::where('warranty_status','TO SHIP')->value('id');
						//$to_diagnose = ReturnsStatus::where('warranty_status','TO DIAGNOSE')->value('id');
						$dataLines = array();
						$items_included_lines = array();


						$items_included 		= $returns_fields['items_included'];
						$items_included_others	= $returns_fields['items_included_others'];


						for($xxx=0; $xxx < count((array)$items_included); $xxx++) {
							array_push($items_included_lines,$items_included[$xxx]); 
						}

						$items_included_lines = $items_included_lines;
						
						$original_payments = array();	
						
						$mode_of_payments 		= $returns_fields['mode_of_payment'];
						
						
						for($xxx=0; $xxx < count((array)$mode_of_payments); $xxx++) {
							array_push($original_payments,$mode_of_payments[$xxx]); 
						}

						$original_payments = $original_payments;			

						//verified_by
						$postdata['items_included'] = 						implode(", ",$items_included_lines);
						$postdata['items_included_others'] = 				$items_included_others;	
						$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
						$postdata['verified_items_included_others'] = 		$items_included_others;	
						
						$postdata['level7_personnel'] = 					CRUDBooster::myId();
						$postdata['level7_personnel_edited']=				date('Y-m-d H:i:s');

						$postdata['customer_location'] = 						$field_1;
						//$postdata['returns_status'] = 						$to_ship;
						//$postdata['returns_status_1'] = 					$to_schedule;
						$postdata['comments'] = 							$field_2;
						$postdata['total_quantity'] = 						1;

						$postdata['purchase_location'] = 					$purchase_location;
						$postdata['store'] = 					    		$store;
						$postdata['branch'] = 					    		$branch;
						$postdata['mode_of_return'] = 					    $mode_of_return;
						$postdata['store_dropoff'] = 					    $store_dropoff;
						$postdata['branch_dropoff'] = 					    $branch_dropoff;
						$postdata['customer_last_name'] = 					$customer_last_name;
						$postdata['customer_first_name'] = 					$customer_first_name;
						$postdata['address'] = 					    		$address;
						$postdata['email_address'] = 					    $email_address;
						$postdata['contact_no'] = 					    	$contact_no;
						$postdata['order_no'] = 					    	$order_no;
						$postdata['purchase_date'] = 					    $purchase_date;
						$postdata['mode_of_payment'] = 					    implode(", ",$original_payments);
						$postdata['mode_of_refund'] = 					    $mode_of_refund;
						$postdata['bank_name'] = 					    	$bank_name;
						$postdata['bank_account_no'] = 					    $bank_account_no;
						$postdata['bank_account_name'] = 					$bank_account_name;
						$postdata['warranty_status'] = 						$warranty_status;

						$ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();

						$ReturnItems = Input::all();

						$digitsCode 		= $ReturnItems['digits_code'];
						$upcCode 			= $ReturnItems['upc_code'];
						$itemDescription 	= $ReturnItems['item_description'];
						$itemCategory		 = $ReturnItems['category'];
						$itemCost		 	= $ReturnItems['cost'];
						$itemBrand		 	= $ReturnItems['brand'];
						$itemSerial		 	= $ReturnItems['serial_no'];
						$problems 				= $ReturnItems['problem_details'];
						$problem_description	= $ReturnItems['problem_details_other'];
						$quantity 			= $ReturnItems['quantity'];
						$itemSerialize	 	= $ReturnItems['serialize'];
						$itemLineID	 	= $ReturnItems['line_id'];
						

						for($x=0; $x < count($digitsCode); $x++) {


							$postdata['deliver_to'] = 		    "SERVICE CENTER.GREENHILLS.VMALL.RTL";

							$problem_dataLines = array();
				
							$problem_lines = $digitsCode[$x];

							$problems_data	= $ReturnItems[$problem_lines];

							$dataLines[$x]['returns_header_id'] 	= $ReturnRequest->id;
							$dataLines[$x]['digits_code'] 			= $digitsCode[$x];
							$dataLines[$x]['upc_code'] 			= $upcCode[$x];
							$dataLines[$x]['item_description'] 			= $itemDescription[$x];
							$dataLines[$x]['brand'] 			= $itemBrand[$x];
							$dataLines[$x]['category'] 			= $itemCategory[$x];
							$dataLines[$x]['cost'] 			= $itemCost[$x];
							$dataLines[$x]['quantity'] 			= $quantity[$x];

							for($xxx=0; $xxx < count($problems_data); $xxx++) {		
								array_push($problem_dataLines,$problems_data[$xxx]); 
								
						}

							$dataLines[$x]['problem_details'] = implode(", ",$problem_dataLines);

							$dataLines[$x]['problem_details_other'] 	= $problem_description[$x];
							$dataLines[$x]['serialize'] 			= $itemSerialize[$x];
							$dataLines[$x]['line_id'] 			= $itemLineID[$x];
							$dataLines[$x]['created_at'] 			= date('Y-m-d H:i:s');
						}
						
						
						
						
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


						$warranty_status 		= $returns_fields['warranty_status_val'];
						
						
							$postdata['returns_status'] = 					    $to_print_srr;
							$postdata['returns_status_1'] = 					$to_print_srr;
							//$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
							//$postdata['verified_items_included_others'] = 		$items_included_others;
							$postdata['received_by_sc'] = 					    CRUDBooster::myId();
							$postdata['received_at_sc']=				        date('Y-m-d H:i:s');
									

						DB::beginTransaction();
				
						try {

							ReturnsBodyRTL::insert($dataLines);

							DB::commit();

							//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');	

						}catch (\Exception $e){
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
						
						}
					}
				}

			}else if($ReturnRequest->returns_status_1 == 37){
				$to_diagnose = ReturnsStatus::where('id','5')->value('id');
				$to_tech_lead = ReturnsStatus::where('id','39')->value('id');

				if(CRUDBooster::myPrivilegeName() == "RMA Inbound" || CRUDBooster::myPrivilegeName() == "Super Administrator"){

					$postdata['returns_status_1'] = 					$to_tech_lead;
					$postdata['received_by_rma_sc'] = 					CRUDBooster::myId();
					$postdata['received_at_rma_sc']=					date('Y-m-d H:i:s');
	
					DB::beginTransaction();
					
					try {
		
						DB::connection('mysql_front_end')
						->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
						[   $ReturnRequest->return_reference_no, 
							$to_diagnose,
							date('Y-m-d H:i:s')
						]);
	
						DB::commit();
		
					}catch (\Exception $e) {
						DB::rollback();
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
					}
		
					DB::disconnect('mysql_front_end');	
				}
			}
			// To pickup by log
			else if($ReturnRequest->returns_status_1 == 34){

				if(CRUDBooster::myPrivilegeName() == "RMA Inbound" || CRUDBooster::myPrivilegeName() == "Super Administrator"){

					$to_diagnose = ReturnsStatus::where('id','5')->value('id');
					// TO RMA RECEIVED STATUS
					$to_rma_received = ReturnsStatus::where('id','37')->value('id');
	
					$postdata['returns_status_1'] = 					$to_rma_received;
					$postdata['rma_receiver_id'] = 					CRUDBooster::myId();
					$postdata['rma_receiver_date_received']=					date('Y-m-d H:i:s');
					
					
					
									// DB::beginTransaction();
					
									// try {
						
									// 	DB::connection('mysql_front_end')
									// 	->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									// 	[   $ReturnRequest->return_reference_no, 
									// 	    $to_diagnose,
									// 	    date('Y-m-d H:i:s')
									// 	]);
	
									// 	DB::commit();
						
									// }catch (\Exception $e) {
									// 	DB::rollback();
									// 	CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
									// }
						
									// DB::disconnect('mysql_front_end');	
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
	    /*
	    public function hook_after_edit($id) {
	        //Your code here 
                        $ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();
						//$to_refund_approved = 	  ReturnsStatus::where('id','6')->value('warranty_status');

                        $received = 	  ReturnsStatus::where('id','31')->value('id');

						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$received,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been received successfully!"), 'success');

	    } */
	    public function hook_after_edit($id) {
	        //Your code here 

			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 

				$ReturnRequest = ReturnsHeaderRTL::where('id',$id)->first();

				if($ReturnRequest->received_by_rma_sc != null){

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been received successfully!"), 'success');

				}else{
					
					$ReturnRequestBody = ReturnsBodyRTL::where('returns_header_id',$id)->orderBy('id','desc')->first();
					$ReturnItems = Input::all();
					$dataLines = array();

					$digitsCode 		= $ReturnItems['digits_code'];
					$SerialNo 			= $ReturnItems['serial_no'];
					$remarks 			= $ReturnItems['remarks'];

					if($remarks == "CANCEL"){

						$cancelled = 	ReturnsStatus::where('id','28')->value('warranty_status');

						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
				
						$data = ['name'=>$fullname,'status_return'=>$cancelled,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been cancelled successfully!"), 'success');

					}else{
						$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
						
						if($ReturnRequest->returns_status_1 == $to_print_srr){
							
							
						
						

						for($x=0; $x < count($digitsCode); $x++) {
							$dataLines[$x]['returns_header_id'] 			= $ReturnRequest->id;
							$dataLines[$x]['returns_body_item_id'] 			= $ReturnRequestBody->id;
							$dataLines[$x]['serial_number'] 			= $SerialNo[$x];
							$dataLines[$x]['created_at'] 			=  date('Y-m-d H:i:s');
						}


						DB::beginTransaction();
				
						try {

							ReturnsSerialsRTL::insert($dataLines);

											//logistics
											//$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
											//'name'=>$fullname,
							DB::commit();
							
									return redirect()->action('AdminToReceiveRetailController@ReturnsSRRPrintSC',['id'=>$ReturnRequest->id])->send();
									exit;
						
							//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been verified successfully!"), 'success');	
						} catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
						
						}else{
							
						
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

								return redirect()->action('AdminToReceiveRetailController@ReturnsReturnFormPrintRTLSC',['id'=>$ReturnRequest->id])->send();
								exit;
							}else if($field_1 == "Reject"){

								$return_rejected = 	  ReturnsStatus::where('id','12')->value('warranty_status');
										
								$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
					
								$data = ['name'=>$fullname,'status_return'=>$return_rejected,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
						
								//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

								return redirect()->action('AdminToReceiveRetailController@ReturnsReturnFormPrintRTLSC',['id'=>$ReturnRequest->id])->send();
								exit;
							}else if($field_1 == "Refund"){

								$to_refund_approved = 	ReturnsStatus::where('id','6')->value('warranty_status');
										
								$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
					
								$data = ['name'=>$fullname,'status_return'=>$to_refund_approved,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
						
								//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

								CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been diagnosed as refund successfully!"), 'success');
							}
							
						}
						

					}

				}

			}else{

				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been received successfully!"), 'success');

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

		public function toTurnOverProcess(Request $request){
			
			$return_input = $request->all();

			$transaction_information = DB::table($return_input['table_name'])->where('id', $return_input['id'])->first();

			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			$to_tech_lead = ReturnsStatus::where('id','39')->value('id');

			if(CRUDBooster::myPrivilegeName() == "RMA Inbound" || CRUDBooster::myPrivilegeName() == "Super Administrator"){

				$counter = new ReferenceCounter();
				$inc_count_number = $counter->incrementCounter('INC');
				$formatted_counter = 'INC-'.str_pad($inc_count_number, 6, '0', STR_PAD_LEFT);
				
				DB::table($return_input['table_name'])->where('id', $return_input['id'])
				->update([
					'returns_status_1' => $to_tech_lead,
					'received_by_rma_sc' => CRUDBooster::myId(),
					'received_at_rma_sc' => date('Y-m-d H:i:s'),
					'inc_number' => $formatted_counter,
				]);

				DB::beginTransaction();
				
				try {
	
					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[   $transaction_information->return_reference_no, 
						$to_diagnose,
						date('Y-m-d H:i:s')
					]);

					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');	
			}

			return response()->json(['success' => $formatted_counter]);
		}

		public function returnReferenceNumber($ref_number, $module_mainpath){
			
			CRUDBooster::redirect(CRUDBooster::adminPath()."/{$module_mainpath}", "Request successfully turned over to Tech Lead with INC #: $ref_number", 'success');
		}



	    //By the way, you can still create your own method in here... :) 

		public function ReturnsToReceiveEditRTL($id)
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
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.edit_diagnosing_sc_retail", $data);
		}


		public function GetExtractReturnsToReceiveSCRTL() {

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
					$to_receive = ReturnsStatus::where('id','29')->value('id');
					$to_receive_rma = ReturnsStatus::where('id','34')->value('id');
					$to_receive_sc = ReturnsStatus::where('id','35')->value('id');
					//$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
					
        				$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
        				$approval_array = array();
        				foreach($approvalMatrix as $matrix){
        				    array_push($approval_array, $matrix->stores_id);
        				}
        				$approval_string = implode(",",$approval_array);
        				$storeList = array_map('intval',explode(",",$approval_string)); 		

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
								)
					->whereIn('returns_header_retail.stores_id', $storeList)
					->whereNull('returns_body_item_retail.category')
					->where(function ($query) {
						$query->where('returns_header_retail.returns_status_1', 35)
							->whereIn('transaction_type', [1,0]);
						$query->orWhere('returns_header_retail.returns_status_1', 29)
							->whereIn('transaction_type', [1,3]);
					});
					// ->whereIn('returns_header_retail.returns_status_1', [$to_receive_sc, $to_receive_rma])
					// ->whereIn('transaction_type', [0,1])
					// ->groupby('returns_body_item_retail.digits_code');
					// ->orWhereIn('returns_header_retail.returns_status_1'
					// ->get();
								// ->whereNull('returns_body_item_retail.category')->where('transaction_type', 2)->where('returns_status_1', $to_receive_sc)->whereIn('returns_header_retail.stores_id', $storeList)->groupby('returns_body_item_retail.digits_code');
						//->orwhereNotNull('returns_body_item.category')->where('transaction_type', 0)->where('returns_status_1', $to_receive_sor)
						// ->orwhereNull('returns_body_item_retail.category')->where('transaction_type', 2)->where('returns_status_1', $to_print_return_form)->whereIn('returns_header_retail.stores_id', $storeList)->groupby('returns_body_item_retail.digits_code');
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
							//$closed_personnel,
							//$closed_date,
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
						//'CLOSED BY',           //blue  //additional code 20200205
						//'CLOSED DATE',           //blue  //additional code 20200205
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
		
		
		public function ReturnsDiagnosingRTLEditSC($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header_retail.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header_retail.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
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
	
			foreach ($data['resultlist'] as $sku){ 

				if($sku->digits_code == null || $sku->digits_code == "" ){
				   $data['ItemCount'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
				   ->count();
   
				   $data['ItemResult'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
											   ->get();
   
   
				}else{
   
				   $data['ItemCount'] =Item::where('item_description', 'like', '%' . $sku->item_description . '%')
				   ->orwhere('digits_code', 'like', '%' . $sku->digits_code . '%')
				   ->count();
   
				   $data['ItemResult'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
											   ->orwhere('digits_code', 'like', '%' . $sku->digits_code . '%')
											   ->get();
				}
   
   
			   }

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
	
			$data['store_list'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->get();
			

			$data['payments'] = ModeOfPayment::orderBy('payment_name','asc')->get();

			$data['purchaselocation'] = Channel::orderBy('channel_name','asc')->where('channel_status', 'ACTIVE')->where('id', 6)->get();
			
			$data['store_front_end'] =  StoresFrontEnd::where('channels_id',  6)->where('store_status', 'ACTIVE')->where('store_name','!=','APPLE SERVICE CENTER')->orderBy('store_name','asc')->get();

			$store_id1 = 	StoresFrontEnd::where('store_name', $data['row']->store )->where('channels_id', 6 )->first();

			$data['branch'] = Stores::where('stores_frontend_id',  $store_id1->id )->where('store_status', 'ACTIVE')->get();

            			$data['store_drop_off'] =  DB::table(env('DB_DATABASE').'.stores')
                                            ->leftjoin('stores_frontend', 'stores.stores_frontend_id','=', 'stores_frontend.id')
                                            ->select('stores_frontend.store_name as store_name')
                                            ->where('stores.store_status','=','ACTIVE')->where('stores.channels_id', 6)->where('store_dropoff_privilege','YES')->orderBy('stores_frontend.store_name', 'ASC')->groupby('stores_frontend.store_name')->get();

			$storefrontend = StoresFrontEnd::where('channels_id',  6)->where('store_name', $data['row']->store_dropoff )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->first();

			$data['branch_dropoff'] = Stores::where('stores_frontend_id',  $storefrontend->id )->where('store_dropoff_privilege', 'YES')->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
			
			$data['SCLocation'] = DB::table('sc_location')->where('status', "ACTIVE")->orderBy('sc_location_name', 'ASC')->get();
			
			$data['transaction_type'] = TransactionTypeList::orderBy('transaction_type_name','desc')->get();
			
			$this->cbView("returns.edit_receiving_retail_sc", $data);
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
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_return_form_retail", $data);
		}
		
		
		
		
		public function ReturnsSRRPrintSC($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header_retail.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header_retail.received_by_sc','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
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
	
			foreach ($data['resultlist'] as $sku){ 

				if($sku->digits_code == null || $sku->digits_code == "" ){
				   $data['ItemCount'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
				   ->count();
   
				   $data['ItemResult'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
											   ->get();
   
   
				}else{
   
				   $data['ItemCount'] =Item::where('item_description', 'like', '%' . $sku->item_description . '%')
				   ->orwhere('digits_code', 'like', '%' . $sku->digits_code . '%')
				   ->count();
   
				   $data['ItemResult'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')
											   ->orwhere('digits_code', 'like', '%' . $sku->digits_code . '%')
											   ->get();
				}
   
   
			   }

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
	
			$data['store_list'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->get();
			

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			

			$data['payments'] = ModeOfPayment::orderBy('payment_name','asc')->get();

			$data['purchaselocation'] = Channel::orderBy('channel_name','asc')->where('channel_status', 'ACTIVE')->where('id', 6)->get();
			
			$data['store_front_end'] =  StoresFrontEnd::where('channels_id',  6)->where('store_status', 'ACTIVE')->where('store_name','!=','APPLE SERVICE CENTER')->orderBy('store_name','asc')->get();

			$store_id1 = 	StoresFrontEnd::where('store_name', $data['row']->store )->where('channels_id', 6 )->first();

			$data['branch'] = Stores::where('stores_frontend_id',  $store_id1->id )->where('store_status', 'ACTIVE')->get();

            			$data['store_drop_off'] =  DB::table(env('DB_DATABASE').'.stores')
                                            ->leftjoin('stores_frontend', 'stores.stores_frontend_id','=', 'stores_frontend.id')
                                            ->select('stores_frontend.store_name as store_name')
                                            ->where('stores.store_status','=','ACTIVE')->where('stores.channels_id', 6)->where('store_dropoff_privilege','YES')->orderBy('stores_frontend.store_name', 'ASC')->groupby('stores_frontend.store_name')->get();

			$storefrontend = StoresFrontEnd::where('channels_id',  6)->where('store_name', $data['row']->store_dropoff )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->first();

			$data['branch_dropoff'] = Stores::where('stores_frontend_id',  $storefrontend->id )->where('store_dropoff_privilege', 'YES')->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
			
			
			$this->cbView("returns.print_srr_sc_retail", $data);
		}
		
		
		
		public function ReturnsSRRUpdateSCRTL(){
		    
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			

			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();
			
            $to_diagnose = ReturnsStatus::where('id','5')->value('id');
			
			if($return_request->returns_status_1 != $to_diagnose){

			
				DB::beginTransaction();
	
				try {
				    
					ReturnsHeaderRTL::where('id',$request_id)
						->update([
						'returns_status_1'=> 		$to_diagnose
					]);	
	
	                

						
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');

			}
		}


		public function ToReceiveSCRTL($id)
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
			->leftjoin('cms_users as tagged', 'returns_header_retail.level7_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_retail.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_retail.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_retail.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
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
			->where('returns_body_item_retail.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_retail.category')->groupby('returns_body_item_retail.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();

			$data['comments_data'] = (new ChatController)->getComments($id);
			
			$this->cbView("components.receiving_sc", $data);
			// $this->cbView("returns.edit_receiving_sc_retail", $data);
		}

		public function ToReceiveRTL($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();


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
			->select(
			'returns_header_retail.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'scheduled_logistics.name as scheduled_by_logistics'						
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
		
		
	}