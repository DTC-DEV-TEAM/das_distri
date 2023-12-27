<?php namespace App\Http\Controllers;

use Session;
//use Request;
use DB;
use CRUDBooster;
use App\ReturnsStatus;
use App\ReturnsHeaderDISTRI;
use App\ReturnsBodyDISTRI;
use App\ReturnsSerialsDISTRI;
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
use App\TransactionTypeList;

	class AdminDistriToVerifyController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "returns_header_distribution";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
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
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			//$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
			$this->col[] = ["label"=>"Store","name"=>"store"];

			$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			// $this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Returns Status','name'=>'returns_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Returns Status 1','name'=>'returns_status_1','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Schedule','name'=>'return_schedule','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Refunded Date','name'=>'refunded_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Reference No','name'=>'return_reference_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Location','name'=>'purchase_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store','name'=>'store','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Return','name'=>'mode_of_return','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Branch','name'=>'branch','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store Dropoff','name'=>'store_dropoff','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Branch Dropoff','name'=>'branch_dropoff','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Last Name','name'=>'customer_last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer First Name','name'=>'customer_first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header_distribution','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Contact No','name'=>'contact_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Order No','name'=>'order_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Date','name'=>'purchase_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Payment','name'=>'mode_of_payment','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Refund','name'=>'mode_of_refund','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Name','name'=>'bank_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account No','name'=>'bank_account_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account Name','name'=>'bank_account_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Items Included','name'=>'items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included Others','name'=>'items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Verified Items Included','name'=>'verified_items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Verified Items Included Others','name'=>'verified_items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Comments','name'=>'comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Diagnose Comments','name'=>'diagnose_comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sor Number','name'=>'sor_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Total Quantity','name'=>'total_quantity','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Diagnose','name'=>'diagnose','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Warranty Status','name'=>'warranty_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			$this->form[] = ['label'=>'Level8 Personnel','name'=>'level8_personnel','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Level8 Personnel Edited','name'=>'level8_personnel_edited','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Notes','name'=>'notes','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ship Back Status','name'=>'ship_back_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Claimed Status','name'=>'claimed_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Credit Memo Number','name'=>'credit_memo_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rma Edited By','name'=>'rma_edited_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rma Edited At','name'=>'rma_edited_at','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Date Adjusted','name'=>'date_adjusted','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Stock Adj Ref No','name'=>'stock_adj_ref_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Deliver To','name'=>'deliver_to','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Transaction Type','name'=>'transaction_type','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'History Status','name'=>'history_status','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Stores Id','name'=>'stores_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'stores,store_name'];
			$this->form[] = ['label'=>'Return Delivery Date','name'=>'return_delivery_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received By Sc','name'=>'received_by_sc','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received At Sc','name'=>'received_at_sc','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

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
			$requested = ReturnsStatus::where('id','1')->value('id');
			$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
			$to_schedule = 	ReturnsStatus::where('id','18')->value('id');

			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsTaggingDISTRIEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $requested"];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsSRRPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $to_print_srr"];
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSchedulingDISTRIEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_schedule"];


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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsTaggingRTL').'?'.urldecode(http_build_query(@$_GET))];
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
			$query->leftJoin('distri_last_comments', 'distri_last_comments.returns_header_distri_id', 'returns_header_distribution.id')
			->leftJoin('chat_distri', 'chat_distri.id', 'distri_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chat_distri.created_by')
			->addSelect('chat_distri.message as last_message',
				'chat_distri.file_name as last_image',
				'sender.name as sender_name',
				'chat_distri.created_at as date_send'
			);
			
		    if(CRUDBooster::myPrivilegeName() == "Distri Store Ops" || CRUDBooster::myPrivilegeName() == "Distri Ops"){ 

				$query->where(function($sub_query){

					$requested = ReturnsStatus::where('id','1')->value('id');
					$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');

					$sub_query->where('returns_status_1', $requested)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					
				});

			}
			else{
    	
				$query->where(function($sub_query){
    			    
					$requested = ReturnsStatus::where('id','1')->value('id');
					$to_print_srr  = ReturnsStatus::where('id','19')->value('id');

					$sub_query->where('returns_status_1', $requested)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  

				});

			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value){	        
			//Your code here
			$requested = 				ReturnsStatus::where('id','1')->value('warranty_status');
			$to_indicate_store = 		ReturnsStatus::where('id','3')->value('warranty_status');
			$to_print_srr  =            ReturnsStatus::where('id','19')->value('warranty_status');
			$to_schedule = 				ReturnsStatus::where('id','18')->value('warranty_status');

			if($column_index == 1){
				if($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_indicate_store){
					$column_value = '<span class="label label-warning">'.$to_indicate_store.'</span>';
			
				}elseif($column_value == $to_print_srr){
					$column_value = '<span class="label label-warning">'.$to_print_srr.'</span>';
			
				}elseif($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';

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

			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();
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
			$deliver_to 			= $returns_fields['deliver_to'];
			$via_id 				= $returns_fields['via_id'];
			$carried_by 			= $returns_fields['carried_by'];

			if($remarks == "CANCEL"){

				$cancelled = 	ReturnsStatus::where('id','28')->value('id');
				$postdata['level1_personnel'] = 					CRUDBooster::myId();
				$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['returns_status'] = 						$cancelled;
				$postdata['returns_status_1'] = 					$cancelled;
				$postdata['mode_of_payment'] = 						implode(", ",$mode_of_payment);
				
				DB::beginTransaction();

				try {

				DB::connection('mysql_distri')
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

				DB::disconnect('mysql_distri');

			}
			
			// TO VERIFY TRANSASACTIONS TO DATABASE
			else{

				$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
				$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
				$dataLines = array();
				$items_included_lines = array();
				$items_included 		= $returns_fields['items_included'];
				$items_included_others	= $returns_fields['items_included_others'];

				for($xxx=0; $xxx < count((array)$items_included); $xxx++) {

					array_push($items_included_lines,$items_included[$xxx]); 

				}

				$items_included_lines = $items_included_lines;
				$original_payments = array();	
				$mode_of_payments = $returns_fields['mode_of_payment'];

				for($xxx=0; $xxx < count((array)$mode_of_payments); $xxx++) {
					array_push($original_payments,$mode_of_payments[$xxx]); 
				}
				$original_payments = $original_payments;			
				$postdata['items_included'] = 						implode(", ",$items_included_lines);
				$postdata['items_included_others'] = 				$items_included_others;	
				$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
				$postdata['verified_items_included_others'] = 		$items_included_others;	
				$postdata['level1_personnel'] = 					CRUDBooster::myId();
				$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['level7_personnel'] = 					CRUDBooster::myId();
				$postdata['level7_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['customer_location'] = 					$field_1;
				$postdata['returns_status_1'] = 					$to_schedule;
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
				$postdata['deliver_to'] =							$deliver_to;
				$postdata['via_id'] =								$via_id;
				$postdata['carried_by'] =							$carried_by;

				$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();
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
				$items_included = $ReturnItems['items_included'];
				$items_included_others = $ReturnItems['items_included_others'];
				
				for($x=0; $x < count($digitsCode); $x++) {

					if($itemBrand[$x] == "APPLE" || $itemBrand[$x] == "BEATS"){
						$postdata['transaction_type'] =     1;
						
					}else{
						$postdata['transaction_type'] =     0;
					}
					
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
				DB::beginTransaction();
				try {
					
					foreach($dataLines as $value){

						DB::table('returns_body_item_distribution')->where('returns_header_id',$value['returns_header_id'])->update($value);
						DB::commit();

					}

				}catch (\Exception $e){

					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
					
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

			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();
			$ReturnRequestBody = ReturnsBodyDISTRI::where('returns_header_id',$id)->orderBy('id','desc')->first();
			$ReturnItems = Input::all();
			$dataLines = array();

			$digitsCode 		= $ReturnItems['digits_code'];
			$SerialNo 			= $ReturnItems['serial_no'];
			$remarks 			= $ReturnItems['remarks'];

			if($remarks == "CANCEL"){

				$cancelled = 	ReturnsStatus::where('id','28')->value('warranty_status');

				$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
				$data = ['name'=>$fullname,'status_return'=>$cancelled,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
		
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been cancelled successfully!"), 'success');

			}else{

				for($x=0; $x < count($digitsCode); $x++) {
					$dataLines[$x]['returns_header_id'] 			= $ReturnRequest->id;
					$dataLines[$x]['returns_body_item_id'] 			= $ReturnRequestBody->id;
					$dataLines[$x]['serial_number'] 			= $SerialNo[$x];
					$dataLines[$x]['created_at'] 			=  date('Y-m-d H:i:s');
				}
				DB::beginTransaction();
				try {
					foreach($dataLines as $value){
						
						DB::table('returns_serial_distribution')->where('returns_header_id',$value['returns_header_id'])->update($value);
						DB::commit();
						
						// CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The requested form updated successfully!. The next step will be scheduling of Logistic Personnel."), 'success');
						return redirect()->action('AdminReturnsDistriSchedulingController@ReturnsSchedulingDISTRIEdit',['id'=>$ReturnRequest->id])->send();
						
					}
					
				} catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
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

	    // By the way, you can still create your own method in here... :) 

		// TO VERIFY DISTRI INDEX PAGE
		public function ReturnsTaggingDISTRIEdit($id, Request $request)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = array();

			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as scheduled', 'returns_header_distribution.level2_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as tagged', 'returns_header_distribution.level1_personnel','=', 'tagged.id')
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level3_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level4_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level5_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')																		
				->select(
				'returns_header_distribution.*',
				'scheduled.name as scheduled_by',
				'tagged.name as tagged_by',	
				'tagged.name as diagnosed_by',
				'printed.name as printed_by',	
				'transacted.name as transacted_by',	
				'received.name as received_by',
				'closed.name as closed_by'						
				)
				->where('returns_header_distribution.id',$id)->first();
				$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*', 'returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)->whereNull('returns_body_item_distribution.category')->get();
		
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			foreach ($data['resultlist'] as $sku){ 

				if($sku->digits_code == null || $sku->digits_code == "" ){
					$data['ItemCount'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')->count();
					$data['ItemResult'] = Item::where('item_description', 'like', '%' . $sku->item_description . '%')->get();

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
	
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store)->first();

			$data['store_list'] = Stores::where('branch_id',  $data['row']->branch )->where('stores_frontend_id',  $store_id->id )->get();
			
			$data['payments'] = ModeOfPayment::orderBy('payment_name','asc')->get();

			$data['purchaselocation'] = Channel::orderBy('channel_name','asc')->where('channel_status', 'ACTIVE')->where('id', 7)->get();
			
			$data['store_front_end'] =  StoresFrontEnd::where('channels_id',  7)
				->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();


			$data['branch'] = Stores::select('branch_id')->where('stores_frontend_id',  $store_id->id )->where('store_status', 'ACTIVE')
			->distinct()->orderBy('branch_id', 'asc')->get();
			
			$data['store_drop_off'] =  DB::table(env('DB_DATABASE').'.stores')
				->leftjoin('stores_frontend', 'stores.stores_frontend_id','=', 'stores_frontend.id')
				->select('stores_frontend.store_name as store_name')
				->where('stores.store_status','=','ACTIVE')
				->where('stores_frontend.store_name','!=','BASEUS')
				->where('stores_frontend.store_name','!=','OMG')
				->where('stores.channels_id', 6)
				->orderBy('stores_frontend.store_name', 'ASC')
				->groupby('stores_frontend.store_name')->get();
                                            
			$storefrontend = StoresFrontEnd::where('channels_id',  4)->where('store_name', $data['row']->store_dropoff )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->first();
			
			$data['branch_dropoff'] = Stores::select('branch_id')->where('store_status','ACTIVE')
				// ->where('branch_id', $data['row']->branch_dropoff)
				->distinct()->orderBy('branch_id', 'asc')->get();
				
			$data['transaction_type'] = TransactionTypeList::where('id', 5)->orderBy('transaction_type_name','desc')->get();
			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();

			$data['via'] =  DB::table('via')->where('status', 'ACTIVE')->get();

			$data['SCLocation'] = DB::table('sc_location')->where('status', "ACTIVE")->orderBy('sc_location_name', 'ASC')->get();
			
			$data['comments_data'] = (new ChatController)->getCommentsDistri($id);

			$this->cbView("returns.edit_tagging_distri", $data);
			
		}

		public function GetExtractReturnsTaggingRTL(){

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
                
                if(CRUDBooster::myPrivilegeName() == "Distri Store Ops"){

						$requested = ReturnsStatus::where('id','1')->value('id');
						$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
						
						
						$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
						$approval_array = array();
						foreach($approvalMatrix as $matrix){
							array_push($approval_array, $matrix->stores_id);
						}
						$approval_string = implode(",",$approval_array);
						$storeList = array_map('intval',explode(",",$approval_string));

						$orderData = DB::table('returns_header_distribution')
							->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
							->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
							->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
							->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
							->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
							->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
							->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
							->leftjoin('cms_users as closed', 'returns_header_distribution.level5_personnel','=', 'closed.id')	
							->leftJoin('returns_body_item_distribution', 'returns_header_distribution.id', '=', 'returns_body_item_distribution.returns_header_id')
							->select(   'returns_header_distribution.*', 
										'returns_body_item_distribution.*', 
										'returns_body_item_distribution.id as body_id', 
										'verified.name as verified_by',	
										'scheduled_logistics.name as scheduled_logistics_by',
										'diagnosed.name as diagnosed_by',
										'printed.name as printed_by',	
										'transacted.name as transacted_by',	
										'received.name as received_by',
										'closed.name as closed_by',
										'warranty_statuses.*'
							)->whereNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $requested)->whereIn('returns_header_distribution.stores_id', $storeList)
							->orwhereNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $to_print_srr)->whereIn('returns_header_distribution.stores_id', $storeList);
						
                }else{

					$requested = ReturnsStatus::where('id','1')->value('id');
					$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');

					$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as closed', 'returns_header_distribution.level5_personnel','=', 'closed.id')	
						->leftJoin('returns_body_item_distribution', 'returns_header_distribution.id', '=', 'returns_body_item_distribution.returns_header_id')
						->select(   'returns_header_distribution.*', 
									'returns_body_item_distribution.*', 
									'returns_body_item_distribution.id as body_id', 
									'verified.name as verified_by',	
									'scheduled_logistics.name as scheduled_logistics_by',
									'diagnosed.name as diagnosed_by',
									'printed.name as printed_by',	
									'transacted.name as transacted_by',	
									'received.name as received_by',
									'closed.name as closed_by',
									'warranty_statuses.*')
						->whereNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $requested)
						->orwhereNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $to_print_srr);
						
                }

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

					$ordeDataLines = $orderData->orderBy('returns_header_distribution.id','asc')->get();
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

			
						$serial_no = ReturnsSerialsDISTRI::where('returns_body_item_id', $orderRow->body_id)->first();
						
						
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

					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);

                    $sheet->getStyle('A1:BA1')->applyFromArray(array(
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

		// Store ReturnsTaggingDISTRIEdit

		public function backend_stores(Request $request)
		{   
			if(!empty($request->store_backend))
			{
				$store_id = StoresFrontEnd::where('store_name', $request->store_backend)
					// ->where('channels_id', 4)
					->where('store_status', 'ACTIVE')->first();
	
				$customer_location = Stores::select(DB::raw('DISTINCT branch_id, COUNT(*) AS count_pid'))
					->where('stores_frontend_id', $store_id->id)
					->where('store_status', 'ACTIVE')->distinct('stores_frontend_id')
					->groupBy('branch_id')->orderBy('branch_id', 'ASC')->get();
	
			}else{
	
				$customer_location = Stores::where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
			}
			
			return($customer_location);
			
		}
		
		public function branch_change(Request $request)
		{   
			if(!empty($request->brand_change))
			{
		
				// $store_id = 	StoresFrontEnd::where('store_name', $request->store_front )->where('channels_id', 4 )->first();
				$store_id = 	StoresFrontEnd::where('store_name', $request->store_front )->first();

				$change_branch =    Stores::where('branch_id',  $request->brand_change )->where('stores_frontend_id',  $store_id->id )->where('store_status', 'ACTIVE')->get();
			}else{
	
					$change_branch =    Stores::where('stores_frontend_id',  $store_id->id )->where('store_status', 'ACTIVE')->get();
			}
			
			return($change_branch);
			
		}

		public function branch_drop_off(Request $request)
		{
			if(!empty($request->drop_off_store))
			{
				$store_id =	StoresFrontEnd::where('store_name', $request->drop_off_store)->where('channels_id', 6)->where('store_status', 'ACTIVE')->first();

				if($request->location == 6){
					$customer_location = Stores::where('stores_frontend_id',$store_id->id)->where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
				}else{
					$customer_location = Stores::where('stores_frontend_id',$store_id->id)->where('store_status', 'ACTIVE')->where('store_dropoff_privilege', 'YES')->orderBy('branch_id', 'ASC')->get();
				}
			}else{
	
				$customer_location = Stores::where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
			}
			
			return($customer_location);
		}

		// End ReturnsTaggingDISTRIEdit

		public function ReturnsSRRPrint($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
				->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level4_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level5_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')																		
				->select(
				'returns_header_distribution.*','scheduled.name as scheduled_by',
				'diagnosed.name as diagnosed_by','printed.name as printed_by',	
				'transacted.name as transacted_by',	'received.name as received_by',
				'closed.name as closed_by','created.name as created_by')
				->where('returns_header_distribution.id',$id)->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select(
				'returns_body_item_distribution.*','returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = StoresFrontEnd::where('store_name', $data['row']->store_dropoff )
				->where('channels_id', 6 )
				->orWhere('channels_id', 7 )->first();

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_srr_distri", $data);
		}
		
		public function ReturnsSRRPrintForDISTRI($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeaderDISTRI::
			leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_distribution.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_distribution.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')																		
			->select(
			'returns_header_distribution.*','scheduled.name as scheduled_by',
			'diagnosed.name as diagnosed_by','printed.name as printed_by',	
			'transacted.name as transacted_by',	'received.name as received_by',
			'closed.name as closed_by','created.name as created_by')
			->where('returns_header_distribution.id',$id)->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
			leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
			->select(
			'returns_body_item_distribution.*', 'returns_serial_distribution.*')
			->where('returns_body_item_distribution.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_distribution.category')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 7 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_srr_distri_new", $data);
		}
		
		public function ReturnsSRRUpdateDISTRI(){

			$data = Input::all();		
			$request_id = $data['return_id']; 
			
			$to_print_srr  =     ReturnsStatus::where('id','19')->value('id');
			
			$to_pickup = 	  ReturnsStatus::where('id','2')->value('id');
			
			$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
			
			$return_request =  ReturnsHeaderDISTRI::where('id',$request_id)->first();
			
			$to_schedule = 	ReturnsStatus::where('id','23')->value('id');

			if($return_request->returns_status_1 != $to_schedule){

				DB::beginTransaction();
	
				try {
			
					ReturnsHeaderDISTRI::where('id',$request_id)
						->update([
						'returns_status_1'=> 		$to_schedule
					]);	
						
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_distri');

			}
		}

	}