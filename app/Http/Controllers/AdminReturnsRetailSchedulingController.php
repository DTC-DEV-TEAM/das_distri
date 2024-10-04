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
use App\StoresFrontEnd;

	class AdminReturnsRetailSchedulingController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"PO Date","name"=>"po_store_date"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			//$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
			//$this->col[] = ["label"=>"Store","name"=>"store"];
			$this->col[] = ["label"=>"Store Drop-Off","name"=>"store_dropoff"];
			$this->col[] = ["label"=>"Branch Drop-Off","name"=>"branch_dropoff"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			// $this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
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
			$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
			$pending = ReturnsStatus::where('id','19')->value('id');
            $return_delivery_date = ReturnsStatus::where('id','33')->value('id');
            
            $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDeliveryEditRTL/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $return_delivery_date"];
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSchedulingRetailEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_schedule"];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsPulloutPrint/[id]'),'color'=>'none','icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $pending"];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' ) {
				$this->index_button[] = ["title"=>"Export Returns",
				"label"=>"Export Returns",
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractSchedulingReturnsRTL').'?'.urldecode(http_build_query(@$_GET))];
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
				
			$query->where('transaction_type','!=', 2)
				->where(function($sub_query){
	
				$sub_query->whereIn('returns_status_1', [ReturnsStatus::TO_SCHEDULE, ReturnsStatus::RETURN_DELIVERY_DATE]);
				
				$sub_query->orWhere(function($q) {
					$q->where('returns_status_1', ReturnsStatus::TO_PRINT_PF)
					->whereNotNull('return_schedule');
				});
				
			})->orderBy('returns_status_1', 'asc');

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
            $return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
            
			if($column_index == 2){
				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $return_delivery_date){
					$column_value = '<span class="label label-warning">'.$return_delivery_date.'</span>';
			
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


			//$to_pickup   = ReturnsStatus::where('id','2')->value('id');
			//$to_diagnose = ReturnsStatus::where('id','5')->value('id');

			$pending = ReturnsStatus::where('id','19')->value('id');
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

			$returns_fields     =   Input::all();
			$field_1 		    =   date_create($returns_fields['return_schedule']);
			$delivery_date 		=   date_create($returns_fields['return_delivery_date']);
			// dd(date_format($field_1, 'Y-m-d'));
		
		    if($ReturnRequest->returns_status_1 == $return_delivery_date){
		        
		        	    $to_ship_back = ReturnsStatus::where('id','14')->value('id');

    					DB::connection('mysql_front_end')
    					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    					[$ReturnRequest->return_reference_no, 
    					$to_ship_back,
    					date('Y-m-d H:i:s')
    					]);
    					
    					$postdata['level6_personnel'] = 			CRUDBooster::myId();
						$postdata['level6_personnel_edited']=		date('Y-m-d H:i:s');
						$postdata['returns_status']=				$to_ship_back;
						$postdata['returns_status_1']=				$to_ship_back;
    					$postdata['return_delivery_date']=			date_format($delivery_date, 'Y-m-d');
    					
    					$user_info =   DB::table("cms_users")->where('cms_users.id', $ReturnRequest->level7_personnel)->first();
    					
    					$postdata['stores_id'] = 							$user_info->stores_id;
		        
		    }else{
    			$postdata['level1_personnel'] = 					CRUDBooster::myId();
    			$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');
    			$postdata['return_schedule'] = 						date_format($field_1, 'Y-m-d');
    			//$postdata['returns_status'] = 						$to_pickup;
    			$postdata['returns_status_1'] = 					$pending;
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
			
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			if($ReturnRequest->returns_status_1 == $to_ship_back){
			    
			   		    $to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
				
						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);  
						
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');			    
			}else{

            			$to_pickup   = ReturnsStatus::where('id','2')->value('warranty_status');
            						
            			$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
            
            			$data = ['name'=>$fullname,'status_return'=>$to_pickup,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
            	
            	
            			//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
            
            			return redirect()->action('AdminReturnsRetailSchedulingController@ReturnsPulloutPrint',['id'=>$ReturnRequest->id])->send();
            			exit;
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

		public function ReturnsSchedulingRetailEdit($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')					
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')	
			->select(
			'returns_header_retail.*',
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

            $data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->first();
            
			// if (CrudBooster::myPrivilegeName() == 'Super Administrator'){
			// 	$this->cbView("components.to_schedule", $data);
			// }else{
			// 	$this->cbView("returns.edit_scheduling_retail", $data);
			// }
			$data['comments_data'] = (new ChatController)->getComments($id);

			$this->cbView("components.to_schedule", $data);
		}



		public function ReturnsDeliveryEditRTL($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
			$data['row'] = ReturnsHeaderRTL::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')					
			leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')	
			->select(
			'returns_header_retail.*',
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

            $data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->first();
            
			// if(CrudBooster::myPrivilegeName() == 'Super Administrator'){
			// 	$this->cbView("components.return_delivery", $data);
			// }else{
			// 	$this->cbView("returns.edit_delivery_retail", $data);
			// }

			$data['comments_data'] = (new ChatController)->getComments($id);

			$this->cbView("components.return_delivery", $data);
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
			
		//edit	$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->first();
			
			$this->cbView("returns.print_pullout", $data);
		}


		public function ReturnPulloutUpdate(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			$to_pickup   = ReturnsStatus::where('id','2')->value('id');
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			$return_request =  ReturnsHeaderRTL::where('id',$request_id)->first();

			$to_receive_rma = ReturnsStatus::where('id','34')->value('id');

			$to_receive_sc = ReturnsStatus::where('id','35')->value('id');


			if($return_request->returns_status_1 != $to_diagnose){
			    
		    
			    
				DB::beginTransaction();
	
				try {


					/*DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[$return_request->return_reference_no, 
					$to_pickup,
					date('Y-m-d H:i:s')
					]);


					*/

					if($return_request->deliver_to == "WAREHOUSE.RMA.DEP"){

						ReturnsHeaderRTL::where('id',$request_id)
						->update([
						//'status_level0'=> $status_all,
						//'level4_personnel'=> 		CRUDBooster::myId(),
						//'level4_personnel_edited'=> date('Y-m-d H:i:s'),
						'returns_status'=> 			$to_pickup,
						'returns_status_1'=> 		$to_receive_rma
						]);	


					}else{

						ReturnsHeaderRTL::where('id',$request_id)
						->update([
						//'status_level0'=> $status_all,
						//'level4_personnel'=> 		CRUDBooster::myId(),
						//'level4_personnel_edited'=> date('Y-m-d H:i:s'),
						'returns_status'=> 			$to_pickup,
						'returns_status_1'=> 		$to_receive_sc
						]);	

					}

	
					DB::commit();

					CRUDBooster::redirect(CRUDBooster::mainpath(), 'Success' , 'success');
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');
			}
		}


		public function getQueryData(){

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
						->select(   'returns_header_retail.*', 
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
								);

			return $orderData;
		}

		// EXPORT HEADER
		private function ExportHeader(){

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


		// EXPORT ROW
		private function ExportRow($orderLine){

			
			$serial_no = ReturnsSerialsRTL::where('returns_body_item_id', $orderLine->body_id)->first();
						
						
			if($orderLine->transaction_type == 3 ){
				
					$scheduled_by =  "";
					$scheduled_date = "";
					$verified = $orderLine->verified_by;
					$verified_date = $orderLine->level7_personnel_edited;
					
					
				if($orderLine->diagnose == "REFUND"){
						$printed_by = $orderLine->printed_by;
						$printed_date = $orderLine->level3_personnel_edited;
						$transacted_by = $orderLine->transacted_by;
						$transacted_date = $orderLine->level4_personnel_edited;
						$closed_by = $orderLine->closed_by;
						$closed_date = $orderLine->level5_personnel_edited;
				}elseif($orderLine->diagnose == "REPLACE"){
					$printed_by = "";
					$printed_date = "";
					$transacted_by = $orderLine->printed_by;
					$transacted_date = $orderLine->level3_personnel_edited;
					$closed_by = $orderLine->transacted_by;
					$closed_date = $orderLine->level4_personnel_edited;
				}else{
					$printed_by = $orderLine->printed_by;
					$printed_date = $orderLine->level3_personnel_edited;
					$transacted_by = "";
					$transacted_date = "";
					$closed_by = $orderLine->transacted_by;
					$closed_date = $orderLine->level4_personnel_edited;	
				}
					
					
			}else{
				
				$verified = $orderLine->verified_by;
				$verified_date = $orderLine->level7_personnel_edited;
				
				$scheduled_by = $orderLine->scheduled_logistics_by;
				$scheduled_date = $orderLine->level1_personnel_edited;
				if($orderLine->diagnose == "REFUND"){
						$printed_by = $orderLine->printed_by;
						$printed_date = $orderLine->level3_personnel_edited;
						$transacted_by = $orderLine->transacted_by;
						$transacted_date = $orderLine->level4_personnel_edited;
						$closed_by = $orderLine->closed_by;
						$closed_date = $orderLine->level5_personnel_edited;
				}elseif($orderLine->diagnose == "REPLACE"){
					$printed_by = "";
					$printed_date = "";
					$transacted_by = $orderLine->printed_by;
					$transacted_date = $orderLine->level3_personnel_edited;
					$closed_by = $orderLine->transacted_by;
					$closed_date = $orderLine->level4_personnel_edited;
				}else{
					$printed_by = $orderLine->printed_by;
					$printed_date = $orderLine->level3_personnel_edited;
					$transacted_by = "";
					$transacted_date = "";
					$closed_by = $orderLine->transacted_by;
					$closed_date = $orderLine->level4_personnel_edited;	
				}
			}
			

			return [
				$orderLine->warranty_status, 		
				$orderLine->diagnose, 	
				$orderLine->created_at,				
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
				$verified,
				$verified_date,
				$scheduled_by,
				$scheduled_date,
				$orderLine->diagnosed_by,
				$orderLine->level2_personnel_edited,
				$printed_by,
				$printed_date,
				$transacted_by,							
				$transacted_date,
				$closed_by,
				$closed_date,
				$orderLine->comments,
				$orderLine->diagnose_comments
			];
							
		}

		private function getExportResult($orderData){

			return $orderData->where('transaction_type','!=', 2)
				->where(function($sub_query){

				$sub_query->whereIn('returns_status_1', [ReturnsStatus::TO_SCHEDULE, ReturnsStatus::RETURN_DELIVERY_DATE]);
				
				$sub_query->orWhere(function($q) {
					$q->where('returns_status_1', ReturnsStatus::TO_PRINT_PF)
					->whereNotNull('return_schedule');
				});
				
			})->orderBy('returns_status_1', 'asc')
			  ->groupBy('return_reference_no');
			
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
				return $result->orderBy('returns_header_retail.id','asc')->get();
			}
		}

		private function processOrderData($finalData)
		{
			$orderItems = [];

			foreach ($finalData as $orderLine) {

				$orderItems[] = self::ExportRow($orderLine);

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


		public function GetExtractSchedulingReturnsRTL() {

			$filename = 'Returns -  ' . date("d M Y - h.i.sa");
			$orderData = self::getQueryData();

			$result = self::getExportResult($orderData);
				$headers = self::ExportHeader();

			$finalData = self::filterFinalData($result);

			$orderItems = self::processOrderData($finalData);

			self::exportToExcel($filename, $orderItems, $headers);
		}
	}