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

	class AdminReturnsDistriSchedulingController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Drop-Off Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
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
			$this->form[] = ['label'=>'Return Schedule','name'=>'return_schedule','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Refunded Date','name'=>'refunded_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Return Reference No','name'=>'return_reference_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Location','name'=>'purchase_location','type'=>'tfext','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store','name'=>'store','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Return','name'=>'mode_of_return','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Branch','name'=>'branch','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store Dropoff','name'=>'store_dropoff','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Branch Dropoff','name'=>'branch_dropoff','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Last Name','name'=>'customer_last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer First Name','name'=>'customer_first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header_distribution','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Contact No','name'=>'contact_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Order No','name'=>'order_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Date','name'=>'purchase_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Payment','name'=>'mode_of_payment','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Refund','name'=>'mode_of_refund','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Name','name'=>'bank_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account No','name'=>'bank_account_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account Name','name'=>'bank_account_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
		///	$this->form[] = ['label'=>'Items Included','name'=>'items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included Others','name'=>'items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
		///	$this->form[] = ['label'=>'Verified Items Included','name'=>'verified_items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
			$pending = ReturnsStatus::where('id','19')->value('id');
            $return_delivery_date = ReturnsStatus::where('id','33')->value('id');
            $to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');

            $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDeliveryEditDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $return_delivery_date"];
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSchedulingDISTRIEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_schedule"];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsPulloutPrint/[id]'),'color'=>'none','icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $pending"];
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSchedulingDISTRIEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_schedule_logistics"];
			

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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractSchedulingReturnsDISTRI').'?'.urldecode(http_build_query(@$_GET))];
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

			$query->leftJoin('distri_last_comments', 'distri_last_comments.returns_header_distri_id', 'returns_header_distribution.id')
			->leftJoin('chat_distri', 'chat_distri.id', 'distri_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chat_distri.created_by')
			->addSelect('chat_distri.message as last_message',
				'chat_distri.file_name as last_image',
				'sender.name as sender_name',
				'chat_distri.created_at as date_send'
			);

			if(CRUDBooster::myPrivilegeName() == "Distri Logistics" || CRUDBooster::myPrivilegeName() == "Logistics"){
				$query->where(function($sub_query){

					$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
					$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					$return_deliver_date = ReturnsStatus::where('id','33')->value('id');
					$pending = ReturnsStatus::where('id','19')->value('id');

					$sub_query->where('returns_status_1', $to_schedule_logistics)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					// $sub_query->orWhere('returns_status_1', $to_schedule)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $return_deliver_date)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $pending)->where('pickup_schedule','!=', null)->orderBy('returns_status_1', 'asc');

				});

				
			}
			if(CRUDBooster::myPrivilegeName() == "Distri Store Ops" || CRUDBooster::myPrivilegeName() == "Distri Ops"){
				$query->where(function($sub_query){

					$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
					$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					$return_deliver_date = ReturnsStatus::where('id','33')->value('id');
					$pending = ReturnsStatus::where('id','19')->value('id');
					
					$sub_query->orWhere('returns_status_1', $to_schedule)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  

				});

				
			}
			else{
		
				$query->where(function($sub_query){

					$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
					$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					$return_deliver_date = ReturnsStatus::where('id','33')->value('id');
					$pending = ReturnsStatus::where('id','19')->value('id');

					$sub_query->where('returns_status_1', $to_schedule_logistics)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $to_schedule)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $return_deliver_date)->where('transaction_type','!=', 2)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $pending)->where('pickup_schedule','!=', null)->orderBy('returns_status_1', 'asc');

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
			
			$to_schedule = 	ReturnsStatus::where('id','18')->value('warranty_status');
			$pending = ReturnsStatus::where('id','19')->value('warranty_status');
			$return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
			$to_schedule_logistics = ReturnsStatus::where('id','23')->value('warranty_status');
			if($column_index == 2){
				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $return_delivery_date){
					$column_value = '<span class="label label-warning">'.$return_delivery_date.'</span>';
			
				}elseif($column_value == $to_schedule_logistics){
					$column_value = '<span class="label label-warning">'.$to_schedule_logistics.'</span>';
			
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
			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();
			$pending = ReturnsStatus::where('id','29')->value('id');
			$pf = ReturnsStatus::where('id','19')->value('id');
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');
			$for_replacement = 	  ReturnsStatus::where('id','20')->value('id');

			$returns_fields     =   Input::all();
			$field_1 		    =   date_create($returns_fields['return_schedule']);
			$delivery_date 		=   date_create($returns_fields['return_delivery_date']);
			$remarks 			= 	$returns_fields['remarks'];
			$pick_up 			= 	(new \DateTime($returns_fields['pickup_schedule']))->format('Y-m-d');
			
			if($ReturnRequest->returns_status_1 == $return_delivery_date && $ReturnRequest->diagnose != 'REPLACE'){

				$to_ship_back = ReturnsStatus::where('id','14')->value('id');

				DB::connection('mysql_distri')
				->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
				[$ReturnRequest->return_reference_no, 
				$to_ship_back,
				date('Y-m-d H:i:s')
				]);
				
				$postdata['level6_personnel'] = 			CRUDBooster::myId();
				$postdata['level6_personnel_edited']=		date('Y-m-d H:i:s');
				$postdata['returns_status']=				$to_ship_back;
				$postdata['returns_status_1']=				$to_ship_back;
				$postdata['return_delivery_date']=			$delivery_date;
				
			}
			elseif($ReturnRequest->returns_status_1 == 33 && $ReturnRequest->diagnose == 'REPLACE'){
				$postdata['level8_personnel'] = 					CRUDBooster::myId();
				$postdata['level8_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['pickup_schedule'] = 						$pick_up;
				$postdata['returns_status_1'] = 					$for_replacement;				
			
			}
			// SC Location ID
			elseif($ReturnRequest->returns_status_1 == 23){
				$postdata['level8_personnel'] = 					CRUDBooster::myId();
				$postdata['level8_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['pickup_schedule'] = 						$pick_up;
				$postdata['returns_status_1'] = 					$pf;		
				$postdata['sc_location_id'] = 						$ReturnRequest->deliver_to == 'WAREHOUSE.RMA.DEP' ? null : DB::table('stores')->where('store_name',$ReturnRequest->deliver_to)->first()->id;		
			}
			else{
				$postdata['level1_personnel'] = 					CRUDBooster::myId();
				$postdata['return_schedule'] = 						date_format($field_1,'Y-m-d');
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
			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');

			if($ReturnRequest->returns_status_1 == $to_ship_back){

				$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
				$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
				$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');			    
			}elseif($ReturnRequest->returns_status_1 == 29 || $ReturnRequest->returns_status_1 == 20){
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');
			}
			else{

				$to_pickup   = ReturnsStatus::where('id','2')->value('warranty_status');	
				$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
				$data = ['name'=>$fullname,'status_return'=>$to_pickup,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];

				return redirect()->action('AdminReturnsDistriSchedulingController@ReturnsPulloutPrint',['id'=>$ReturnRequest->id])->send();
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
		public function ReturnsSchedulingDISTRIEdit($id){
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();

			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.level1_personnel','=', 'created.id')	
				->select('returns_header_distribution.*','created.name as created_by')
				->where('returns_header_distribution.id',$id)
				->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*', 'returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->groupby('returns_body_item_distribution.digits_code')->get();

			$store_id = StoresFrontEnd::
				where('store_name', $data['row']->store_dropoff )
				->where('channels_id', 6 )->first();

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )
				->where('stores_frontend_id',  $store_id->id )
				->first();
				
			$data['current_status'] = ReturnsHeaderDISTRI::select('returns_status_1')->where('id', $id)->value('returns_status_1');

			$data['comments_data'] = (new ChatController)->getCommentsDistri($id);
			
			$this->cbView("returns.edit_scheduling_distri", $data);
		}

		public function ReturnsDeliveryEditDISTRI($id){

			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')	
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')
				->select('returns_header_distribution.*',
						'created.name as created_by',
						'diagnosed.name as diagnosed_by',
						'closed.name as verified_by')
				->where('returns_header_distribution.id',$id)->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*', 'returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->groupby('returns_body_item_distribution.digits_code')->get();
		
			$store_id = StoresFrontEnd::
				where('store_name', $data['row']->store_dropoff )
				->where('channels_id', 6 )->first();

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )
				->where('stores_frontend_id',  $store_id->id )
				->first();
            
			$data['comments_data'] = (new ChatController)->getCommentsDistri($id);
			
			$this->cbView("returns.edit_delivery_distri", $data);
		}

		public function ReturnsPulloutPrint($id){

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
				->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level8_personnel','=', 'scheduled_logistics.id')																
				->select('returns_header_distribution.*','scheduled.name as scheduled_by','diagnosed.name as diagnosed_by',
				'printed.name as printed_by','transacted.name as transacted_by','received.name as received_by','closed.name as closed_by',
				'created.name as created_by','scheduled_logistics.name as scheduled_by_logistics')
				->where('returns_header_distribution.id',$id)
				->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*','returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->groupby('returns_body_item_distribution.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )
				->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )
				->where('stores_frontend_id',  $store_id->id )->first();
			
			// dd('hello world');
			$this->cbView("returns.print_pullout_distri", $data);

		}

		public function ReturnPulloutUpdate(){

			$data = Input::all();		
			$request_id = $data['return_id']; 
			$to_pickup   = ReturnsStatus::where('id','2')->value('id');
			$to_rma = ReturnsStatus::where('id','34')->value('id');
			$to_sc = ReturnsStatus::where('id','35')->value('id');
			$return_request =  ReturnsHeaderDISTRI::where('id',$request_id)->first();

			if($return_request->returns_status_1 != $to_rma){
				
				DB::beginTransaction();

				try {

					DB::connection('mysql_distri')
						->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
						[
							$return_request->return_reference_no, 
							$to_pickup,
							date('Y-m-d H:i:s')
						]);

					if($return_request->deliver_to == 'WAREHOUSE.RMA.DEP'){

						ReturnsHeaderDISTRI::where('id',(int)$request_id)
							->update([
								'returns_status'=> 			$to_pickup,
								'returns_status_1'=> 		$to_rma
							]);	
					}else{
						// SERVICE CENTER.AYALA.BONIFACIO HIGH STREET.RTL
						ReturnsHeaderDISTRI::where('id',(int)$request_id)
						->update([
							'returns_status'=> 			$to_pickup,
							'returns_status_1'=> 		$to_sc
						]);	
					}
		
						
					DB::commit();

					CRUDBooster::redirect(CRUDBooster::mainpath(), 'Success', 'success');
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_distri');
			}

		}

		public function GetExtractSchedulingReturnsDISTRI() {

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



					$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					$pending = ReturnsStatus::where('id','19')->value('id');

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
									)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $to_schedule)
									->orwhereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $pending);

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
							}
							elseif($orderRow->diagnose == "REPLACE"){
								$printed_by = "";
								$printed_date = "";
								$transacted_by = $orderRow->printed_by;
								$transacted_date = $orderRow->level3_personnel_edited;
								$closed_by = $orderRow->transacted_by;
								$closed_date = $orderRow->level4_personnel_edited;
							}
							else{
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
							$scheduled_date = $orderRow->level7_personnel_edited;

							if($orderRow->diagnose == "REFUND"){
								$printed_by = $orderRow->printed_by;
								$printed_date = $orderRow->level3_personnel_edited;
								$transacted_by = $orderRow->transacted_by;
								$transacted_date = $orderRow->level4_personnel_edited;
								$closed_by = $orderRow->closed_by;
								$closed_date = $orderRow->level5_personnel_edited;
							}
							elseif($orderRow->diagnose == "REPLACE"){
								$printed_by = "";
								$printed_date = "";
								$transacted_by = $orderRow->printed_by;
								$transacted_date = $orderRow->level3_personnel_edited;
								$closed_by = $orderRow->transacted_by;
								$closed_date = $orderRow->level4_personnel_edited;
							}
							else{
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


	}