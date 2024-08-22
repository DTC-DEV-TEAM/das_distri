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


	class AdminToSorDistriController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Brand","name"=>"id", 'callback'=>function($row){
				
				$id = $row->id;

				$brand = DB::table('returns_body_item_distribution')->where('returns_header_id', $id)->orderBy('id', 'desc')->first()->brand;
				
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
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
			$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
			$this->col[] = ["label"=>"Store","name"=>"store"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			// $this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
			$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
			$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
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
			$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header_distribution','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Contact No','name'=>'contact_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Order No','name'=>'order_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Date','name'=>'purchase_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Payment','name'=>'mode_of_payment','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Mode Of Refund','name'=>'mode_of_refund','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Name','name'=>'bank_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account No','name'=>'bank_account_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account Name','name'=>'bank_account_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included','name'=>'items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included Others','name'=>'items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Verified Items Included','name'=>'verified_items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			$to_sor = 			ReturnsStatus::where('id','9')->value('id');

			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSOREditDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_sor"];
            $this->addaction[] = ['title'=>'View','url'=>CRUDBooster::mainpath('ViewSORDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-eye'];			


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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export_return_sor_distri').'?'.urldecode(http_build_query(@$_GET))];
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

			// $query->whereNotNull('returns_body_item_distribution.category');

			if(CRUDBooster::myPrivilegeName() == "Service Center" ){ 
				
				$storeList = self::getStoreList();

				$query->where('transaction_type','!=', 2)
				->where('returns_status_1', ReturnsStatus::TO_SOR)
				->whereIn('returns_header_distribution.stores_id', $storeList)
				->orderBy('created_at', 'desc');  
					
	        }else{
				$query->where('transaction_type', 0)
				->where('returns_status_1', ReturnsStatus::TO_SOR)
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
			$to_sor = ReturnsStatus::where('id','9')->value('warranty_status');

			if($column_index == 3){
				if($column_value == $to_sor){
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
			$field_2 		= $returns_fields['crf_number'];
			$field_3 		= $returns_fields['dr_number'];

			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();

			if($ReturnRequest->diagnose == "REPLACE"){
				
				$for_replacement = 	  ReturnsStatus::where('id','20')->value('id');
				$schedule_item_replacement = ReturnsStatus::where('id','33')->value('id');

				$postdata['level3_personnel'] = 					CRUDBooster::myId();
				$postdata['level3_personnel_edited']=				date('Y-m-d H:i:s');
				$postdata['returns_status_1'] = 					$schedule_item_replacement;
				$postdata['sor_number'] = 							$field_1;
				$postdata['pos_crf_number'] = 						$field_2;
				$postdata['dr_number'] = 							$field_3;

			}else{

				$refund_in_process = ReturnsStatus::where('id','8')->value('id');
				$to_receive_sor = ReturnsStatus::where('id','10')->value('id');

						DB::beginTransaction();
				
						try {

							$postdata['level4_personnel'] = 					CRUDBooster::myId();
							$postdata['level4_personnel_edited']=				date('Y-m-d H:i:s');
							$postdata['returns_status'] = 					    $refund_in_process;
							$postdata['returns_status_1'] = 					$refund_in_process;
							$postdata['sor_number'] = 							$field_1;


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
			//Your code here 
			$ReturnRequest = ReturnsHeaderDISTRI::where('id',$id)->first();

			
			if($ReturnRequest->diagnose == "REFUND"){

			}
					
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been transacted successfully!. Next step is to schedule return items (Logistic Privilege)"), 'success');
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
		public function ReturnsSOREditDISTRI($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For SOR';
		
			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
				->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level5_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')
				->leftjoin('cms_users as crf', 'returns_header_distribution.level8_personnel','=', 'crf.id')	
				->select('returns_header_distribution.*','scheduled.name as scheduled_by',
						'diagnosed.name as diagnosed_by','printed.name as printed_by',	
						'transacted.name as transacted_by',	'received.name as received_by',
						'closed.name as closed_by','created.name as created_by',	
						'crf.name as created_crf_by')
				->where('returns_header_distribution.id',$id)
				->first();
			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*','returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->groupby('returns_body_item_distribution.digits_code')
				->get();

			$data['comments_data'] = (new ChatController)->getCommentsDistri($id);
			
			$this->cbView("returns.edit_sor_distri", $data);

		}

		public function ViewSORDISTRI($id)
		{

			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For SOR';
		
			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
				->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level5_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')
				->leftjoin('cms_users as crf', 'returns_header_distribution.level8_personnel','=', 'crf.id')																		
				->select('returns_header_distribution.*','scheduled.name as scheduled_by',
						'diagnosed.name as diagnosed_by','printed.name as printed_by',	
						'transacted.name as transacted_by',	'received.name as received_by',
						'closed.name as closed_by','created.name as created_by',	
						'crf.name as created_crf_by')
				->where('returns_header_distribution.id',$id)->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*','returns_serial_distribution.*'					)
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.view_rma_retail", $data);

		}

		public function exportReturnSORDISTRI()
		{
			$filename = 'Returns - ' . date("d M Y - h.i.sa");
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
			->select(   'returns_header_distribution.created_at as rhd_created_at',
						'returns_header_distribution.*', 
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
				return $result->orderBy('returns_header_distribution.id', 'asc')->get();
			}
		}

		private function processOrderData($finalData)
		{
			$orderItems = [];
			$isRMA = CRUDBooster::myPrivilegeName() == 'RMA Specialist'; // Determine if privilege is RMA

			foreach ($finalData as $orderLine) {
				$serial_no = ReturnsSerialsDISTRI::where('returns_body_item_id', $orderLine->body_id)->first();


				$data = [
					$orderLine->warranty_status, 		
					$orderLine->diagnose, 	
					$orderLine->rhd_created_at,				
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
					$orderLine->ship_back_status,
					$orderLine->claimed_status,
					$orderLine->credit_memo_number,
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

			return $orderData->where('transaction_type','!=', 2)
			->where('returns_status_1', ReturnsStatus::TO_SOR)
			->whereIn('returns_header_distribution.stores_id', $storeList)
			->groupBy('return_reference_no')
			->orderBy('rhd_created_at', 'desc');  

		}

		private function getOtherResult($orderData)
		{
			return $orderData
				->where('transaction_type', 0)
				->where('returns_status_1', ReturnsStatus::TO_SOR)
				->groupBy('return_reference_no')
				->orderBy('rhd_created_at', 'desc');  
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