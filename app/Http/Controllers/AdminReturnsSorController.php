<?php namespace App\Http\Controllers;

use PHPExcel_Style_Alignment;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
	class AdminReturnsSorController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
			$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
			$this->col[] = ["label"=>"Store","name"=>"store"];
			$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
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
			$this->form[] = ['label'=>'Diagnose Comments','name'=>'diagnose_comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Sor No','name'=>'sor_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			//$this->form[] = ["label"=>"Diagnose Comments","name"=>"diagnose_comments","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Customer Location","name"=>"customer_location","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sor No","name"=>"sor_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			$to_sor = ReturnsStatus::where('id','9')->value('id');

			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSOREdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_sor"];
			$this->addaction[] = ['title'=>'View','url'=>CRUDBooster::mainpath('ViewSOR/[id]'),'color'=>'none','icon'=>'fa fa-eye'];

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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export_return_sor_ecomm').'?'.urldecode(http_build_query(@$_GET))];
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

			// Chatbox
			$query->leftJoin('ecomm_last_comments', 'ecomm_last_comments.returns_header_id', 'returns_header.id')
			->leftJoin('chat_ecomms', 'chat_ecomms.id', 'ecomm_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chat_ecomms.created_by')
			->addSelect('chat_ecomms.message as last_message',
				'chat_ecomms.file_name as last_image',
				'sender.name as sender_name',
				'chat_ecomms.created_at as date_send'
			);


	        if(CRUDBooster::myPrivilegeName() == "Service Center"){ 
	            
				$storeList = self::getStoreList();
        
	            $query->where('returns_status_1', ReturnsStatus::TO_SOR)
				->whereIn('transaction_type', [1,3])
				->where(function($query) use ($storeList){
					$query->whereIn('returns_header.sc_location_id', $storeList)
					->orWhereIn('returns_header.stores_id', $storeList);
				})
				->orderBy('created_at', 'desc');
        			
	        } else{
       		
				$query->where('returns_status_1', ReturnsStatus::TO_SOR)
				->where('transaction_type', 0)
				->orderBy('created_at', 'desc'); 	 
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
			$to_print_crf = 			ReturnsStatus::where('id','7')->value('warranty_status');
			$to_sor = 					ReturnsStatus::where('id','9')->value('warranty_status');

			if($column_index == 3){
				if($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_indicate_store){
					$column_value = '<span class="label label-warning">'.$to_indicate_store.'</span>';
			
				}elseif($column_value == $to_print_crf){
					$column_value = '<span class="label label-warning">'.$to_print_crf.'</span>';
			
				}elseif($column_value == $to_sor){
					$column_value = '<span class="label label-warning">'.$to_sor.'</span>';
			
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
			$field_1 		= $returns_fields['sor_number'];
			
			$field_2 		= $returns_fields['pos_crf_number'];

			$refund_in_process = ReturnsStatus::where('id','8')->value('id');
			$to_receive_sor = ReturnsStatus::where('id','10')->value('id');
			$ReturnRequest = ReturnsHeader::where('id',$id)->first();

			if($ReturnRequest->diagnose == "REPLACE"){
			    
			    $postdata['pos_crf_number'] = 			$field_2;
				
				//$for_replacement = 	  ReturnsStatus::where('id','20')->value('id');

				$for_replacement = 	  ReturnsStatus::where('id','20')->value('id');

				$replacement_complete = ReturnsStatus::where('id','21')->value('id');

				$postdata['level4_personnel'] = 					CRUDBooster::myId();
				$postdata['level4_personnel_edited']=				date('Y-m-d H:i:s');

				$postdata['sor_number'] = 							$field_1;
				$postdata['history_status'] = 					    1;

				if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){

					

					if($ReturnRequest->diagnose == "REPLACE"){

						$postdata['returns_status'] = 					    $for_replacement;
						$postdata['returns_status_1'] = 					$for_replacement;

					}else{

						$postdata['returns_status'] = 					    $replacement_complete;
						$postdata['returns_status_1'] = 					$replacement_complete;

					}

				}else{

					if($ReturnRequest->transaction_type_id == 3){
						
						$postdata['returns_status'] = 					    $replacement_complete;
						$postdata['returns_status_1'] = 					$replacement_complete;			
						
						DB::beginTransaction();

						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$replacement_complete,
							date('Y-m-d H:i:s')
							]);

							DB::commit();
			
						}catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}
			
						DB::disconnect('mysql_front_end');

					}else{

						$postdata['returns_status'] = 					    $for_replacement;
						$postdata['returns_status_1'] = 					$for_replacement;			
					}

				}



			}else{
			    
    			DB::beginTransaction();
    	
    			try {
    				/*
    				DB::connection('mysql_front_end')
    				->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
    				[$ReturnRequest->return_reference_no, 
    				$refund_in_process,
    				date('Y-m-d H:i:s')
    				]);*/
    	
    				$postdata['level5_personnel'] = 					CRUDBooster::myId();
    				$postdata['level5_personnel_edited']=				date('Y-m-d H:i:s');
    				$postdata['returns_status'] = 					    $refund_in_process;
    				$postdata['returns_status_1'] = 					$refund_in_process;
    				$postdata['sor_number'] = 							$field_1;
                    $postdata['pos_crf_number'] = 			            $field_2;
    
    				DB::commit();
    
    			}catch (\Exception $e) {
    				DB::rollback();
    				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
    			}
    
    			DB::disconnect('mysql_front_end');
    			
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
	        
	        $ReturnRequest = ReturnsHeader::where('id',$id)->first();
	        	
			if($ReturnRequest->diagnose == "REPLACE"){


				if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){


					if($ReturnRequest->diagnose == "REPLACE"){

						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been transacted successfully!"), 'success');

					}else{
    					$replacement_complete = ReturnsStatus::where('id','21')->value('warranty_status');
    
    					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
    		
    					$data = ['name'=>$fullname,'status_return'=>$replacement_complete,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
    
    					////CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
    					
    					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been closed successfully!"), 'success');
					}


				}else{

					if($ReturnRequest->transaction_type_id == 3){

						$replacement_complete = ReturnsStatus::where('id','21')->value('warranty_status');

						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$replacement_complete,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
						
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been closed successfully!"), 'success');

					}else{
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been transacted successfully!"), 'success');
					}

				}
			    

							
			    
			}else{
			    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been transacted successfully!"), 'success');
			}
			//Your code here 
			/*
			$ReturnRequest = ReturnsHeader::where('id',$id)->first();

		
			$refund_in_process = ReturnsStatus::where('id','8')->value('warranty_status');

			$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;

			$data = ['name'=>$fullname,'status_return'=>$refund_in_process,'ref_no'=>$ReturnRequest->return_reference_no];
	
	
			//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been transacted successfully!"), 'success');

			*/
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
		public function ReturnsSOREdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
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

			$data['comments_data'] = (new ChatController)->getCommentsEcomm($id);
			
			$this->cbView("returns.edit_sor", $data);
		}

		public function ViewSOR($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
		
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
			
			$this->cbView("returns.view_rma", $data);
		}


		public function exportReturnSOREcomm()
		{
			$filename = 'Returns for SOR ECOMM - ' . date("d M Y - h.i.sa");
			$orderData = self::getQueryData();

			if (CRUDBooster::myPrivilegeName() == "Service Center") {

				$result = self::getServiceCenterResult($orderData);
			} else {
				//RMA export result
				$result = self::getOtherResult($orderData);
			}

			$finalData = self::filterFinalData($result);

			$orderItems = self::processOrderData($finalData);

			self::exportToExcel($filename, $orderItems);
		}


		private function getQueryData(){
			$orderData = DB::table('returns_header')
			->leftjoin('warranty_statuses', 'returns_header.returns_status_1','=', 'warranty_statuses.id')
			->leftjoin('cms_users as verified', 'returns_header.level1_personnel','=', 'verified.id')
			->leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')		
			->leftjoin('cms_users as scheduled_logistics', 'returns_header.level8_personnel','=', 'scheduled_logistics.id')		
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')	
			->leftJoin('returns_body_item', 'returns_header.id', '=', 'returns_body_item.returns_header_id')
			->select(   'returns_header.created_at as rh_created_at',
						'returns_header.*', 
						'returns_body_item.*', 
						'returns_body_item.id as body_id', 
						'verified.name as verified_by',	
						'scheduled.name as scheduled_by',
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
				return self::filterData($result);
			} else {
				return $result->orderBy('returns_header.id', 'asc')->get();
			}
		}

		private function processOrderData($finalData)
		{
			$orderItems = [];
			$isRMA = CRUDBooster::myPrivilegeName() == 'RMA Specialist'; 

			foreach ($finalData as $orderLine) {
				$serial_no = ReturnsSerials::where('returns_body_item_id', $orderLine->body_id)->first();


				$data = [
					$orderLine->warranty_status,
					$orderLine->diagnose,
					$orderLine->rh_created_at,
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
					$orderLine->pickup_schedule,
					$orderLine->refunded_date,
					$orderLine->sor_number,
					$orderLine->digits_code,
					$orderLine->upc_code,
					$orderLine->item_description,
					$orderLine->cost,
					$orderLine->brand,
					$serial_no->serial_number,
					$orderLine->problem_details,
					$orderLine->problem_details_other,
					$orderLine->warranty_status,
					$orderLine->quantity,
					$orderLine->ship_back_status,
					$orderLine->claimed_status,
					$orderLine->credit_memo_number,
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


				if ($isRMA) {
					$headersBefore = array_slice($data, 0, 4);
					$headersAfter = array_slice($data, 4);

					// Include RMA-specific fields
					$rmaAdditionalData = [
						$orderLine->inc_number,
						$orderLine->rma_number,
					];

					$orderItems[] = array_merge($headersBefore, $rmaAdditionalData, $headersAfter);
				}else {
					//SERVICE CENTER DATA
					$orderItems[] = $data;
				}
				
			}

			return $orderItems;
		}

		private function exportToExcel($filename, $orderItems)
		{
			Excel::create($filename, function ($excel) use ($orderItems) {
				$excel->sheet('orders', function ($sheet) use ($orderItems) {
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat([
						'J' => '@', // for upc code
						'AI' => '0.00',
						'AJ' => '0.00',
						'AK' => '0.00',
					]);

					$headers = self::getExportHeaders();
					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headers);

				});
			})->export('xlsx');
		}

		private function getServiceCenterResult($orderData)
		{
			$storeList = self::getStoreList();

			return $orderData->where('returns_status_1', ReturnsStatus::TO_SOR)
			->whereIn('transaction_type', [1,3])
			->where(function($query) use ($storeList){
				$query->whereIn('returns_header.sc_location_id', $storeList)
				->orWhereIn('returns_header.stores_id', $storeList);
			})
			->groupBy('return_reference_no')
			->orderBy('rh_created_at', 'desc');
		}

		private function getOtherResult($orderData)
		{
			return $orderData
				->where('transaction_type', 0)
				->where('returns_status_1', ReturnsStatus::TO_SOR)
				->groupBy('return_reference_no')
				->orderBy('rh_created_at', 'desc');
		}

		private function getExportHeaders(){	
			
			$baseHeadings = [
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
				'RETURN SCHEDULE',            
				'PICKUP SCHEDULE',            
				'REFUNDED DATE',            
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
				'DIAGNOSED BY',          
				'DIAGNOSED DATE',          
				'PRINTED BY',          
				'PRINTED DATE',          
				'SOR BY',          
				'SOR DATE',          
				'CLOSED BY',          
				'CLOSED DATE',          
				'COMMENTS',
				'DIAGNOSED COMMENTS',
			];		

			$rmaAdditionalHeaders = [
				'INC#',
				'RMA#',
			];

			if (CRUDBooster::myPrivilegeName() == 'RMA Specialist') {

				$headersBefore = array_slice($baseHeadings, 0, 4);
				$headersAfter = array_slice($baseHeadings, 4);
				
				// Merge new headers with the existing ones, Put INC and RMA after return ref no
				$resultHeadings = array_merge($headersBefore, $rmaAdditionalHeaders, $headersAfter);
			} else {
				$resultHeadings = $baseHeadings;
			}

			return $resultHeadings;
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


