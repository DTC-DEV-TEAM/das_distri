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
use App\ItemsIncluded;
use App\Stores;
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
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use App\Item;
use App\StoresFrontEnd;
use App\TransactionTypeList;

	class AdminReturnsHeaderController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			//$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			$this->col[] = ["label"=>"Mode Of Return","name"=>"mode_of_return"];
			//$this->col[] = ["label"=>"Store","name"=>"store"];
		
				$this->col[] = ["label"=>"Store Drop-Off","name"=>"store_dropoff"];
				$this->col[] = ["label"=>"Branch Drop-Off","name"=>"branch_dropoff"];
			
			//$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
			$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
			$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Return Reference No','name'=>'return_reference_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Location','name'=>'purchase_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Store','name'=>'store','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Last Name','name'=>'customer_last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer First Name','name'=>'customer_first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email Address','name'=>'email_address','type'=>'email','validation'=>'required|min:1|max:255|email|unique:returns_header','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Contact No','name'=>'contact_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Order No','name'=>'order_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Purchase Date','name'=>'purchase_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Mode Of Payment','name'=>'mode_of_payment','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Name','name'=>'bank_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account No','name'=>'bank_account_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bank Account Name','name'=>'bank_account_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included','name'=>'items_included','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Items Included Others','name'=>'items_included_others','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Comments','name'=>'comments','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer Location','name'=>'customer_location','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Sor No','name'=>'sor_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
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
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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

			//$requested = ReturnsStatus::where('warranty_status','REQUESTED')->value('id');
			//$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');
			$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
			$pending = ReturnsStatus::where('id','19')->value('id');
			$requested = ReturnsStatus::where('id','1')->value('id');
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

            
		    if(CRUDBooster::myPrivilegeName() == "Ecomm Ops"){
		        
		        $this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsDetail/[id]'),'icon'=>'fa fa-eye'];
			    
			}else{
			    $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsTaggingEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $requested"];
			    $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDeliveryEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $return_delivery_date"];
			    $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSchedulingEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_schedule_aftersales or [returns_status_1] == $to_schedule_logistics"];
			    $this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsCloseRejectEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_ship_back"];
			    $this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsPulloutPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $pending"];
			    
			}

			//$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsSchedulingDetail/[id]'),'icon'=>'fa fa-eye'];
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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsScheduling').'?'.urldecode(http_build_query(@$_GET))];
				//$this->index_button[] = ["label"=>"Export Returns","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturns'),"color"=>"success"];
			}
			/*
			if(CRUDBooster::myPrivilegeName() == "Aftersales" && CRUDBooster::getCurrentMethod() == 'getIndex'){ 
				$this->index_button[] = ['label' => 'Upload Warranty Request', "url" => CRUDBooster::mainpath("import-excel"), "icon" => "fa fa-upload", "color"=>"success"];
			}
			*/

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
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {

			if(CRUDBooster::myPrivilegeName() == "Logistics"){ 
					//Your code here
					$query->where(function($sub_query){
						//$requested = ReturnsStatus::where('warranty_status','REQUESTED')->value('id');
						//$to_schedule = 				ReturnsStatus::where('id','18')->value('id');
					
						//$to_ship_back = ReturnsStatus::where('id','14')->value('id');

						$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
						$pending = ReturnsStatus::where('id','19')->value('id');
						$return_delivery_date = ReturnsStatus::where('id','33')->value('id');

						$sub_query->where('returns_status_1', $to_schedule_logistics)->where('transaction_type','!=', 2)->orderBy('returns_status_1', 'asc');  
						$sub_query->orWhere('returns_status_1', $pending)->where('level8_personnel_edited','!=', null)->where('transaction_type','!=', 2)->orderBy('returns_status_1', 'asc');
						$sub_query->orWhere('returns_status_1', $return_delivery_date)->where('transaction_type','!=', 2)->orderBy('returns_status_1', 'asc');
					});
					
			}elseif(CRUDBooster::myPrivilegeName() == "Aftersales" || CRUDBooster::myPrivilegeName() == "Ecomm Ops"){ 
			
				//Your code here
				$query->where(function($sub_query){
					//$requested = ReturnsStatus::where('warranty_status','REQUESTED')->value('id');
					//$to_schedule = 				ReturnsStatus::where('id','18')->value('id');
					$requested = ReturnsStatus::where('id','1')->value('id');
					$to_ship_back = ReturnsStatus::where('id','14')->value('id');
					$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');
					
					$to_print_pf  =             ReturnsStatus::where('id','19')->value('id');

					$sub_query->where('returns_status_1', $to_schedule_aftersales)->where('transaction_type','!=', 2)->orderBy('returns_status_1', 'asc');  
					//$sub_query->orWhere('returns_status_1', $to_ship_back)->orderBy('returns_status_1', 'asc');
					$sub_query->orWhere('returns_status_1', $requested)->where('transaction_type','!=', 2)->orderBy('returns_status_1', 'asc');
					
					$sub_query->orWhere('returns_status_1', $to_print_pf)->where('mode_of_return', 'DOOR-TO-DOOR')->orderBy('returns_status_1', 'asc');
				});
				
			}else{
								//Your code here
								$query->where(function($sub_query){
									//$requested = ReturnsStatus::where('warranty_status','REQUESTED')->value('id');
									//$to_schedule = 				ReturnsStatus::where('id','18')->value('id');
									$pending = ReturnsStatus::where('id','19')->value('id');
									$requested = ReturnsStatus::where('id','1')->value('id');
									$to_ship_back = ReturnsStatus::where('id','14')->value('id');
									$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');		
									$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');

									$sub_query->where('returns_status_1', $to_schedule_aftersales)->orderBy('returns_status_1', 'asc');  
									$sub_query->orWhere('returns_status_1', $to_ship_back)->orderBy('returns_status_1', 'asc');
									$sub_query->orWhere('returns_status_1', $to_schedule_logistics)->orderBy('returns_status_1', 'asc');
									$sub_query->orWhere('returns_status_1', $pending)->orderBy('returns_status_1', 'asc');
									$sub_query->orWhere('returns_status_1', $requested)->orderBy('returns_status_1', 'asc');
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
			$to_ship_back = 			ReturnsStatus::where('id','14')->value('warranty_status');
			$to_schedule = 				ReturnsStatus::where('id','18')->value('warranty_status');
			$to_schedule_aftersales = 	ReturnsStatus::where('id','22')->value('warranty_status');
			$to_schedule_logistics = 	ReturnsStatus::where('id','23')->value('warranty_status');
			$pending =                  ReturnsStatus::where('id','19')->value('warranty_status');
			$return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
			if($column_index == 1){
				if($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $to_indicate_store){
					$column_value = '<span class="label label-warning">'.$to_indicate_store.'</span>';
			
				}elseif($column_value == $to_ship_back){
					$column_value = '<span class="label label-warning">'.$to_ship_back.'</span>';
			
				}elseif($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $to_schedule_aftersales){
					$column_value = '<span class="label label-warning">'.$to_schedule_aftersales.'</span>';
			
				}elseif($column_value == $to_schedule_logistics){
					$column_value = '<span class="label label-warning">'.$to_schedule_logistics.'</span>';
			
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
			
			$ReturnRequest = ReturnsHeader::where('id',$id)->first();
			$requested = ReturnsStatus::where('id','1')->value('id');
			
			if($ReturnRequest->returns_status_1 == $requested){
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

				$transaction_type_id 		= $returns_fields['transaction_type_id'];
				$negative_positive_invoice 		= $returns_fields['negative_positive_invoice'];
				$pos_replacement_ref 		= $returns_fields['pos_replacement_ref'];

				$via_id 		= $returns_fields['via_id'];
				$carried_by 		= $returns_fields['carried_by'];
		        
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
		
					$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');

					$to_diagnose = ReturnsStatus::where('id','5')->value('id');

					$to_sor = 	ReturnsStatus::where('id','9')->value('id');

					
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
        			
					$postdata['items_included'] = 						implode(", ",$items_included_lines);
					$postdata['items_included_others'] = 				$items_included_others;		
					$postdata['level1_personnel'] = 					CRUDBooster::myId();
					$postdata['level1_personnel_edited']=				date('Y-m-d H:i:s');
					$postdata['customer_location'] = 						$field_1;
					//$postdata['returns_status'] = 						$to_ship;
					//$postdata['returns_status_1'] = 					$to_schedule_aftersales;
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

					$postdata['via_id'] = 								$via_id;

					$postdata['carried_by'] = 							$carried_by;

					$postdata['transaction_type_id'] = 					$transaction_type_id;
					$postdata['negative_positive_invoice'] = 			$negative_positive_invoice;
					$postdata['pos_replacement_ref'] = 					$pos_replacement_ref;

					if($mode_of_return == "STORE DROP-OFF"){
						$postdata['returns_status_1'] = 					$to_schedule_aftersales;
					}else{
					    
					    if($transaction_type_id == "3"){
					        
					        	//$postdata['diagnose'] = "REPLACE";
						        //$postdata['returns_status_1'] = 					$to_sor;
						        
						        $postdata['returns_status_1'] = 			$to_schedule_aftersales;
					        
					    }else{
                                $postdata['returns_status_1'] = 			$to_schedule_aftersales;
					        
					    }

						
					}

					
					
					$ReturnRequest = ReturnsHeader::where('id',$id)->first();

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

					$deliver_to	 	= $ReturnItems['deliver_to'];
					
					$Itemproblem_details_text	 		= $ReturnItems['problem_details_text'];
					$Itemproblem_details_other_text	 	= $ReturnItems['problem_details_other_text'];
					// dd($deliver_to);
					for($x=0; $x < count($digitsCode); $x++){
					    
					    
					  if (str_contains($ReturnRequest->store_dropoff, 'SERVICE') && ($itemBrand[$x] == "APPLE" || $itemBrand[$x] == "BEATS")){
					                //$postdata['deliver_to'] = 		    "SERVICE CENTER.GREENHILLS.VMALL.RTL";

									$postdata['deliver_to'] = 		    $deliver_to;
                					//$postdata['transaction_type'] =     1;
					  }else{
                			    if($itemBrand[$x] == "APPLE" || $itemBrand[$x] == "BEATS"){

									$postdata['deliver_to'] = 		    $deliver_to;

									if($ReturnRequest->mode_of_return == "DOOR-TO-DOOR"){

										$postdata['stores_id'] =            DB::table("stores")->where('store_name', $deliver_to)->value('id');

										$postdata['sc_location_id'] = 			DB::table("stores")->where('store_name', $deliver_to)->value('id');
									}
									

                					$postdata['transaction_type'] =     1;

                					    //$postdata['stores_id'] = 100;
                					
                					
                					/*if(	$ReturnRequest->stores_id == 0 || $ReturnRequest->stores_id == "0"){
                					    $postdata['stores_id'] = 100;
                					}*/
                					
                					
                				}else{

                					$postdata['deliver_to'] = 		    "WAREHOUSE.RMA.DEP";
                					$postdata['transaction_type'] =     0;
									
                				}					      
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
						//$dataLines[$x]['problem_details'] =          $Itemproblem_details_text;
						$dataLines[$x]['problem_details'] = implode(", ",$problem_dataLines);
						//$dataLines[$x]['problem_details_other'] =    $Itemproblem_details_other_text;
						$dataLines[$x]['problem_details_other'] 	= $problem_description[$x];
						$dataLines[$x]['serialize'] 			= $itemSerialize[$x];
						$dataLines[$x]['line_id'] 			= $itemLineID[$x];
						$dataLines[$x]['created_at'] 			= date('Y-m-d H:i:s');
					}
		
		
					DB::beginTransaction();
			
					try {
		
						ReturnsBody::insert($dataLines);
		
						DB::commit();
		
						//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');	
		
					}catch (\Exception $e){
						DB::rollback();
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
					}
				}
			}else{
			    
				$to_ship_back = ReturnsStatus::where('id','14')->value('id');
				
				$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
				
				$to_pickup = 	  ReturnsStatus::where('warranty_status','TO PICKUP')->value('id');
				
				$indicate_store = ReturnsStatus::where('warranty_status','INDICATE STORE')->value('id');
				
				$return_delivery_date = ReturnsStatus::where('id','33')->value('id');
				
				$ReturnItems = Input::all();
				
				$remarks 			= $ReturnItems['remarks'];
				
				$delivery_date 		= $ReturnItems['return_delivery_date'];
				
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
				    
				    
				    
					if($ReturnRequest->returns_status_1 == $to_ship_back){

						if($ReturnRequest->diagnose == "REPAIR"){
							$repair_complete = ReturnsStatus::where('id','17')->value('id');

							DB::beginTransaction();
			
							try {
				
								DB::connection('mysql_front_end')
								->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
								[$ReturnRequest->return_reference_no, 
								$repair_complete,
								date('Y-m-d H:i:s')
								]);
					
								$postdata['level5_personnel'] = 					CRUDBooster::myId();
								$postdata['level5_personnel_edited']=				date('Y-m-d H:i:s');
								$postdata['returns_status'] = 					    $repair_complete;
								$postdata['returns_status_1'] = 					$repair_complete;
				
				
								DB::commit();
				
							}catch (\Exception $e) {
								DB::rollback();
								CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
							}
				
							DB::disconnect('mysql_front_end');

						}else{
							$return_invalid = ReturnsStatus::where('id','15')->value('id');

							DB::beginTransaction();
			
							try {
				
								DB::connection('mysql_front_end')
								->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
								[$ReturnRequest->return_reference_no, 
								$return_invalid,
								date('Y-m-d H:i:s')
								]);
					
								$postdata['level5_personnel'] = 					CRUDBooster::myId();
								$postdata['level5_personnel_edited']=				date('Y-m-d H:i:s');
								$postdata['returns_status'] = 					    $return_invalid;
								$postdata['returns_status_1'] = 					$return_invalid;
				
				
								DB::commit();
				
							}catch (\Exception $e) {
								DB::rollback();
								CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
							}
				
							DB::disconnect('mysql_front_end');
						}
					}elseif($ReturnRequest->returns_status_1 == $to_schedule_logistics){

						$returns_fields = Input::all();
						$field_1 		= $returns_fields['pickup_schedule'];

						//enhancement

						if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
							$pending = ReturnsStatus::where('id','19')->value('id');

							//$to_receive_rma = ReturnsStatus::where('id','34')->value('id');
							//$to_receive_sc = ReturnsStatus::where('id','35')->value('id');
							
							$postdata['level8_personnel'] = 					CRUDBooster::myId();
							$postdata['level8_personnel_edited']=				date('Y-m-d H:i:s');
							$postdata['pickup_schedule'] = 						$field_1;
							$postdata['returns_status_1'] = 					$pending;

						}else{

                            //dd('');
							$to_diagnose = ReturnsStatus::where('id','5')->value('id');

							$to_receive_rma = ReturnsStatus::where('id','34')->value('id');

							$to_receive_sc = ReturnsStatus::where('id','35')->value('id');

							$postdata['level8_personnel'] = 					CRUDBooster::myId();
							$postdata['level8_personnel_edited']=				date('Y-m-d H:i:s');
							$postdata['pickup_schedule'] = 						$field_1;

							//$postdata['returns_status_1'] = 					$to_diagnose;

							if($ReturnRequest->deliver_to == "WAREHOUSE.RMA.DEP"){
							    
								$postdata['returns_status_1'] = 					$to_receive_rma;

								
							}else{
							    
								$postdata['returns_status_1'] = 					$to_receive_sc;

							}
							
							
							if($ReturnRequest->via_id == 1){
							    
								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');

								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $ReturnRequest->return_reference_no, 
									    $to_receive_sc_frontend,
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




					}elseif($ReturnRequest->returns_status_1 == $return_delivery_date){
					    
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
    					$postdata['return_delivery_date']=			$delivery_date;
    					
    					
    					 $user_info =   DB::table("cms_users")->where('cms_users.id', $ReturnRequest->received_by)->first();
    					
    					$postdata['stores_id'] = 							$user_info->stores_id;
					    
					}else{

						$returns_fields = Input::all();
						$field_1 		= $returns_fields['return_schedule'];
						
						$dr_number 		= $returns_fields['dr_number'];
						

						$problem_details_lines = array();
						$items_included_lines = array();

						$problem_details 		= $returns_fields['problem_details'];
						$problem_details_other	= $returns_fields['problem_details_other'];
			
						for($xx=0; $xx < count((array)$problem_details); $xx++) {
							array_push($problem_details_lines,$problem_details[$xx]); 

						
						}
						$problem_details_lines = $problem_details_lines;


						$items_included 		= $returns_fields['items_included'];
						$items_included_others	= $returns_fields['items_included_others'];


						for($xxx=0; $xxx < count((array)$items_included); $xxx++) {
							array_push($items_included_lines,$items_included[$xxx]); 
						}

						$items_included_lines = $items_included_lines;


						//$field_2 		= $returns_fields['requestor_comments'];
						//$field_3 		= $returns_fields['total_quantity'];
						$to_pickup = 	  ReturnsStatus::where('id','2')->value('id');
						$to_drop_off = 	  ReturnsStatus::where('id','24')->value('id');
						$to_diagnose = ReturnsStatus::where('id','5')->value('id');

						//$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');
						$to_schedule_logistics = 	ReturnsStatus::where('id','23')->value('id');
						$to_receive = 				ReturnsStatus::where('id','29')->value('id');

                        $to_print_pf  =             ReturnsStatus::where('id','19')->value('id');

						$to_diagnose = ReturnsStatus::where('id','5')->value('id');

						//$indicate_store = ReturnsStatus::where('warranty_status','INDICATE STORE')->value('id');
						$dataLines = array();
			
						$postdata['level2_personnel'] = 					CRUDBooster::myId();
						$postdata['level2_personnel_edited']=				date('Y-m-d H:i:s');
						$postdata['return_schedule'] = 						$field_1;
						
						$postdata['dr_number'] = 						    $dr_number;
						
						//$postdata['items_included'] = 						implode(", ",$items_included_lines);
						//$postdata['items_included_others'] = 				$items_included_others;
						//$postdata['comments'] = 							$field_2;
						
						if (str_contains($ReturnRequest->store_dropoff, 'SERVICE') && $ReturnRequest->deliver_to == "SERVICE CENTER.GREENHILLS.VMALL.RTL"){
						    
						            $postdata['returns_status'] = 						$to_drop_off;
        							$postdata['returns_status_1'] = 					$to_receive;
        							$postdata['transaction_type'] =                     3;
						        
						}else{

						        if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
        							$postdata['returns_status'] = 						$to_drop_off;
        							$postdata['returns_status_1'] = 					$to_receive;
        						}else{
        							$postdata['returns_status'] = 						$to_pickup;


        							//$postdata['returns_status_1'] = 					$to_schedule_logistics;

									if($mode_of_return == "STORE DROP-OFF"){
										$postdata['returns_status_1'] = 					    $to_schedule_logistics;
									}else{
									    
									    
                					    if($transaction_type_id == "3"){
                					        
                					            
                					        
                					        	$postdata['returns_status_1'] = 			    $to_diagnose;
                					        
                					    }else{
                					   
                					   		   if($ReturnRequest->deliver_to !="WAREHOUSE.RMA.DEP"){
                					   		       
						                            $postdata['returns_status_1'] = 			$to_print_pf;
						                            
						                       }else{

													//dd('');

													if($ReturnRequest->via_id == 1){

														$postdata['returns_status_1'] = 			$to_schedule_logistics;

													}else{

														$to_receive_rma = ReturnsStatus::where('id','34')->value('id');

														$to_receive_sc = ReturnsStatus::where('id','35')->value('id');

														if($ReturnRequest->deliver_to == "WAREHOUSE.RMA.DEP"){

															$postdata['returns_status_1'] = 			$to_receive_rma;
															
                            					
														}else{

															$postdata['returns_status_1'] = 			$to_receive_sc;
															
														}

													}
						                           
						                            
						                       }
   
                					    }
										
										
										
									}
        						}
						}


						
						$postdata['total_quantity'] = 							1;
			
						$ReturnRequest = ReturnsHeader::where('id',$id)->first();
			
						DB::beginTransaction();
			
						try {

							if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
								DB::connection('mysql_front_end')
								->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
								[$ReturnRequest->return_reference_no, 
								$to_drop_off,
								date('Y-m-d H:i:s')
								]);
							}else{
							    
							  
							    
								DB::connection('mysql_front_end')
								->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
								[$ReturnRequest->return_reference_no, 
								$to_pickup,
								date('Y-m-d H:i:s')
								]);
								
								
								if($ReturnRequest->via_id == 2){
								    
								    if($ReturnRequest->deliver_to == "WAREHOUSE.RMA.DEP"){
								        
        								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');
        
        								DB::beginTransaction();
        				
        								try {
        					
        									DB::connection('mysql_front_end')
        									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
        									[   $ReturnRequest->return_reference_no, 
        									    $to_receive_sc_frontend,
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
								
							}

							/*
							ReturnsBody::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
							->update([		
								'problem_details'=> implode(", ",$problem_details_lines),
								'problem_details_other'=> $problem_details_other
							]);
							*/

							//ReturnsBody::insert($dataLines);
			
							DB::commit();
			
							//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');	
			
						} catch (\Exception $e) {
							DB::rollback();
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
						}

						DB::disconnect('mysql_front_end');
					}
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

			$ReturnRequest = ReturnsHeader::where('id',$id)->first();
			$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');

			$to_ship_back = ReturnsStatus::where('id','14')->value('id');

			$to_sor = 		ReturnsStatus::where('id','9')->value('id');

			if($ReturnRequest->returns_status_1 == $to_schedule_aftersales){
				$ReturnRequestBody = ReturnsBody::where('returns_header_id',$id)->orderBy('id','desc')->first();
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
					for($x=0; $x < count($digitsCode); $x++) {
						$dataLines[$x]['returns_header_id'] 			= $ReturnRequest->id;
						$dataLines[$x]['returns_body_item_id'] 			= $ReturnRequestBody->id;
						$dataLines[$x]['serial_number'] 			= $SerialNo[$x];
						$dataLines[$x]['created_at'] 			=  date('Y-m-d H:i:s');
					}
		
		
					DB::beginTransaction();
			
					try {
		
						ReturnsSerials::insert($dataLines);
		
						DB::commit();
					
						//CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been verified successfully!"), 'success');	

						return redirect()->action('AdminReturnsHeaderController@ReturnsSchedulingEdit',['id'=>$ReturnRequest->id])->send();
						exit;

					} catch (\Exception $e) {
						DB::rollback();
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
					}
		
				}
			}elseif($ReturnRequest->returns_status_1 == $to_ship_back){
			        
			   		    $to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
				
						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);  
						
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');
						
			}else{
				$return_invalid = ReturnsStatus::where('id','15')->value('id');
				$repair_complete = ReturnsStatus::where('id','17')->value('id');
				$to_diagnose = ReturnsStatus::where('id','5')->value('id');
				$pending = ReturnsStatus::where('id','19')->value('id');
				$ReturnItems = Input::all();
				$remarks 			= $ReturnItems['remarks'];
				
				if($remarks == "CANCEL"){
	
					$cancelled = 	ReturnsStatus::where('id','28')->value('warranty_status');
		
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
					$data = ['name'=>$fullname,'status_return'=>$cancelled,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
					////CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
		
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been cancelled successfully!"), 'success');
		
				}else{			
					if($ReturnRequest->returns_status_1 == $return_invalid){
	
						$return_invalid = ReturnsStatus::where('id','15')->value('warranty_status');
	
						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$return_invalid,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
	
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been closed successfully!"), 'success');	
					
					}else if ($ReturnRequest->returns_status_1 == $repair_complete){
	
						$repair_complete = ReturnsStatus::where('id','17')->value('warranty_status');
	
						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$repair_complete,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);
	
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been closed successfully!"), 'success');	
	
					}else if ($ReturnRequest->returns_status_1 == $pending){
						if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
	
	
							return redirect()->action('AdminReturnsHeaderController@ReturnsPulloutPrint',['id'=>$ReturnRequest->id])->send();
							exit;
	
						}else{
						    

	
							return redirect()->action('AdminReturnsHeaderController@ReturnsPulloutPrint',['id'=>$ReturnRequest->id])->send();
							exit;
													
							
						}					
					
					}else if ($ReturnRequest->returns_status_1 == $to_diagnose){
						if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
	
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');	
	
						}else{
							CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been proceed successfully!"), 'success');
						}					
					
					}else{
						
						$to_pickup = 	  ReturnsStatus::where('id','2')->value('warranty_status');
						$to_drop_off = 	  ReturnsStatus::where('id','24')->value('warranty_status');
	
						$ReturnRequest = ReturnsHeader::where('id',$id)->first();
						$ReturnRequestBody = ReturnsBody::where('returns_header_id',$id)->orderBy('id','desc')->first();
						$ReturnItems = Input::all();
						/*
						$dataLines = array();
			
						$digitsCode 		= $ReturnItems['digits_code'];
						$SerialNo 			= $ReturnItems['serial_no'];
			
						for($x=0; $x < count($digitsCode); $x++) {
							$dataLines[$x]['returns_header_id'] 			= $ReturnRequest->id;
							$dataLines[$x]['returns_body_item_id'] 			= $ReturnRequestBody->id;
							$dataLines[$x]['serial_number'] 			= $SerialNo[$x];
							$dataLines[$x]['created_at'] 			=  date('Y-m-d H:i:s');
						}
						*/
						if($ReturnRequest->returns_status_1 == $to_sor){

							$for_replacement = 	  		ReturnsStatus::where('id','20')->value('id');
							$for_replacement_frontend =	ReturnsStatus::where('id','27')->value('id');
							
							$to_sor = 				ReturnsStatus::where('id','9')->value('id');
			
								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[$ReturnRequest->return_reference_no, 
									$for_replacement_frontend,
									date('Y-m-d H:i:s')
									]);

									DB::commit();
					
								}catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
					
								DB::disconnect('mysql_front_end');


								CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been tag as replace successfully!"), 'success');


						}else{

							if($ReturnRequest->mode_of_return == "STORE DROP-OFF"){
							    
										$to_schedule_logistics = ReturnsStatus::where('id','23')->value('warranty_status');
											
										DB::beginTransaction();
						
										try {
							
											//ReturnsSerials::insert($dataLines);
							
						
											$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
								
											$data = ['name'=>$fullname,'status_return'=>$to_drop_off,'ref_no'=>$ReturnRequest->return_reference_no,'return_schedule'=>$ReturnRequest->return_schedule,'store_name'=>$ReturnRequest->store];
									
									
											//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return_drop_off','attachments'=>[]]);
						


											//logistics
											//$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
											//'name'=>$fullname,
											$data = ['status_return'=>$to_schedule_logistics,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
									
									
											//CRUDBooster::sendEmail(['to'=>'lewieadona@digits.ph','data'=>$data,'template'=>'details_return_update','attachments'=>[]]);

											DB::commit();
						
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');	
										} catch (\Exception $e) {
											DB::rollback();
											CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
										}
							}else{
							    
								$to_schedule_logistics = ReturnsStatus::where('id','23')->value('warranty_status');
								
		                      //dd('otid');
		
								DB::beginTransaction();
				
								try {
					
									//ReturnsSerials::insert($dataLines);
					
				
									$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
						
									$data = ['name'=>$fullname,'status_return'=>$to_pickup,'ref_no'=>$ReturnRequest->return_reference_no,'return_schedule'=>$ReturnRequest->return_schedule,'store_name'=>$ReturnRequest->store];
							
							
									//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return_pick_up','attachments'=>[]]);
				

									//logistics
									//$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
									//'name'=>$fullname,
									$data = ['status_return'=>$to_schedule_logistics,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
									
									//CRUDBooster::sendEmail(['to'=>'lewieadona@digits.ph','data'=>$data,'template'=>'details_return_update','attachments'=>[]]);

																
									DB::commit();
				
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been scheduled successfully!"), 'success');
								
								   
							
								} catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
		
							}

						}
			
	
					}
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



	    //By the way, you can still create your own method in here... :) 


		public function ReturnsSchedulingEdit($id){
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
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
			)
			->where('returns_header.id',$id)->first();

			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::all();
			
			$store_id = StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();

			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();

			if(CRUDBooster::myPrivilegeName() == "Logistics"){ 

				$this->cbView("returns.edit_scheduling_logistics", $data);

			}else{
				$this->cbView("returns.edit_scheduling", $data);
			}
		}
		
		
		
	    public function ReturnsDeliveryEdit($id){
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
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
			)
			->where('returns_header.id',$id)->first();

			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::all();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();

			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();


			$this->cbView("returns.edit_delivery_logistics", $data);

		}



		public function ReturnsSchedulingDetail($id)
		{
			$this->cbLoader();
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE) {    
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }   

			$data = array();
			$data['page_title'] = 'Pullout Request Details';

			$this->cbView("pullout.request_voucher", $data);
		}


		public function itemSearch(Request $request) {
			$data = array();
			$data['problem_details']  = array();
			
			$problem_details = ProblemDetails::all();
			$xx = 0;
			$prob = array();
			foreach ($problem_details as $key => $values) {
				array_push($prob, $values->problem_details);
				$xx++;
			}
			$data['sample'] = implode(",", $prob);
			$data['sample_count'] = $xx;
			$data['status_no'] = 0;
			$data['message']   ='No Item Found!';
			$data['items'] = array();
			$items = DB::table('digits_imfs')
				->where('digits_imfs.digits_code','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.upc_code','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.item_description','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.upc_code2','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.upc_code3','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.upc_code4','LIKE','%'.$request->search.'%')
				->orWhere('digits_imfs.upc_code5','LIKE','%'.$request->search.'%')
				->join('brand', 'digits_imfs.brand_id','=', 'brand.id')
				->join('warehouse_category', 'digits_imfs.warehouse_category_id','=', 'warehouse_category.id')
				->select('digits_imfs.*',
					'warehouse_category.wh_category_description as wh_category_description',
					'brand.brand_description as brand_description')->take(10)->get();
			
			if($items){
				$data['status'] = 1;
				$data['problem']  = 1;
				$data['status_no'] = 1;
				$data['message']   ='Item Found';
				$i = 0;
				foreach ($items as $key => $value) {
				$return_data[$i]['id'] = $value->id;
				$return_data[$i]['digits_code'] = $value->digits_code;
				$return_data[$i]['upc_code'] = $value->upc_code;
				$return_data[$i]['item_description'] = $value->item_description;
				$return_data[$i]['brand'] = $value->brand_description;
				$return_data[$i]['category'] = $value->wh_category_description;
				$return_data[$i]['current_srp'] = $value->current_srp;
				$i++;
				}
				$data['items'] = $return_data;
			}
			echo json_encode($data);
			exit;  
		}

		public function ReturnsCloseRejectEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level1_personnel','=', 'tagged.id')
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
			
			$this->cbView("returns.edit_closing_reject", $data);
		}


		public function GetExtractReturnsScheduling(){

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


			if(CRUDBooster::myPrivilegeName() == "Logistics"){ 

						$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');
						$pending = ReturnsStatus::where('id','19')->value('id');

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
						->select(   'returns_header.*', 
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
						)->whereNotNull('returns_body_item.category')->where('transaction_type','!=', 2)->where('returns_status_1', $to_schedule_logistics)->groupby('returns_body_item.digits_code')
						 ->orwhereNotNull('returns_body_item.category')->where('transaction_type','!=', 2)->where('returns_status_1', $pending)->groupby('returns_body_item.digits_code');
						 
						 
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
    
    					$ordeDataLines = $orderData->orderBy('returns_header.id','asc')->get();
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
    
    			
    						$serial_no = ReturnsSerials::where('returns_body_item_id', $orderRow->body_id)->first();
    						
    						if($orderRow->transaction_type == 2 ){
    						    $closed_personnel = $orderRow->verified_by;
    							$closed_date = 		$orderRow->level1_personnel_edited;
    							
    						}else{
    
            						if($orderRow->diagnose == "REFUND"){
            							$transacted_personnel = $orderRow->transacted_by;
            							$transacted_date = 		$orderRow->level5_personnel_edited;
            							$closed_personnel = 	$orderRow->closed_by;
            							$closed_date = 			$orderRow->level7_personnel_edited;
            						}else{
            							$transacted_personnel = "";
            							$transacted_date = "";
            							$closed_personnel = $orderRow->transacted_by;
            							$closed_date = 		$orderRow->level5_personnel_edited;
            						}
            						
            						if($orderRow->mode_of_return == "STORE DROP-OFF"){
            								$scheduled_by = 	$orderRow->scheduled_logistics_by;
            								$scheduled_date =	$orderRow->level8_personnel_edited;
            						}else{
            								$scheduled_by = 	$orderRow->scheduled_by;
            								$scheduled_date =	$orderRow->level2_personnel_edited;
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
    							//$orderRow->mode_of_refund,	
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
    							$orderRow->pickup_schedule,   
    							$orderRow->refunded_date,  
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
    							//$orderRow->ship_back_status,
    							//$orderRow->claimed_status,
    						//	$orderRow->credit_memo_number,
    							$orderRow->verified_by,
    							$orderRow->level1_personnel_edited,
    							$scheduled_by,
    							$scheduled_date,
    							$orderRow->diagnosed_by,
    							$orderRow->level3_personnel_edited,
    							$orderRow->printed_by,
    							$orderRow->level4_personnel_edited,
    							$transacted_personnel,							
    							$transacted_date,
    							$closed_personnel,
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
    						//'MODE OF REFUND',
    						//'BANK NAME',    //yellow
    						//'BANK ACCOUNT#',      //red
    						//'BANK ACCOUNT NAME',         //red
    						'ITEMS INCLUDED',         //red
    						'ITEMS INCLUDED OTHERS',//green
    						'VERIFIED ITEMS INCLUDED',         //red
    						'VERIFIED ITEMS INCLUDED OTHERS',//green
    						'CUSTOMER LOCATION',               //green
    						'DELIVER TO',               //green
    						'RETURN SCHEDULE',               //green
    						'PICKUP SCHEDULE',               //green
    						'REFUNDED DATE',               //green
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
			}elseif(CRUDBooster::myPrivilegeName() == "Aftersales" || CRUDBooster::myPrivilegeName() == "Ecomm Ops"){ 

						$requested = ReturnsStatus::where('id','1')->value('id');
						$to_ship_back = ReturnsStatus::where('id','14')->value('id');
						$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');

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
						->select(   'returns_header.*', 
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
						)->whereNull('returns_body_item.category')->where('transaction_type','!=', 2)->where('returns_status_1', $requested)->groupby('returns_body_item.digits_code')
						//->orwhereNotNull('returns_body_item.category')->where('returns_status_1', $to_ship_back)
						->orwhereNotNull('returns_body_item.category')->where('transaction_type','!=', 2)->where('returns_status_1', $to_schedule_aftersales)->groupby('returns_body_item.digits_code');			
						
						
						
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
    
    					$ordeDataLines = $orderData->orderBy('returns_header.id','asc')->get();
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
    
    			
    						$serial_no = ReturnsSerials::where('returns_body_item_id', $orderRow->body_id)->first();
    						
    						if($orderRow->transaction_type == 2 ){
    						    $closed_personnel = $orderRow->verified_by;
    							$closed_date = 		$orderRow->level1_personnel_edited;
    							
    						}else{
    
            						if($orderRow->diagnose == "REFUND"){
            							$transacted_personnel = $orderRow->transacted_by;
            							$transacted_date = 		$orderRow->level5_personnel_edited;
            							$closed_personnel = 	$orderRow->closed_by;
            							$closed_date = 			$orderRow->level7_personnel_edited;
            						}else{
            							$transacted_personnel = "";
            							$transacted_date = "";
            							$closed_personnel = $orderRow->transacted_by;
            							$closed_date = 		$orderRow->level5_personnel_edited;
            						}
            						
            						if($orderRow->mode_of_return == "STORE DROP-OFF"){
            								$scheduled_by = 	$orderRow->scheduled_logistics_by;
            								$scheduled_date =	$orderRow->level8_personnel_edited;
            						}else{
            								$scheduled_by = 	$orderRow->scheduled_by;
            								$scheduled_date =	$orderRow->level2_personnel_edited;
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
    							$orderRow->mode_of_refund,	
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
    							$orderRow->pickup_schedule,   
    							$orderRow->refunded_date,  
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
    							//$orderRow->ship_back_status,
    							//$orderRow->claimed_status,
    							//$orderRow->credit_memo_number,
    							$orderRow->verified_by,
    							$orderRow->level1_personnel_edited,
    							$scheduled_by,
    							$scheduled_date,
    							$orderRow->diagnosed_by,
    							$orderRow->level3_personnel_edited,
    							$orderRow->printed_by,
    							$orderRow->level4_personnel_edited,
    							$transacted_personnel,							
    							$transacted_date,
    							$closed_personnel,
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
    						'MODE OF REFUND',
    						'BANK NAME',    //yellow
    						'BANK ACCOUNT#',      //red
    						'BANK ACCOUNT NAME',         //red
    						'ITEMS INCLUDED',         //red
    						'ITEMS INCLUDED OTHERS',//green
    						'VERIFIED ITEMS INCLUDED',         //red
    						'VERIFIED ITEMS INCLUDED OTHERS',//green
    						'CUSTOMER LOCATION',               //green
    						'DELIVER TO',               //green
    						'RETURN SCHEDULE',               //green
    						'PICKUP SCHEDULE',               //green
    						'REFUNDED DATE',               //green
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
			}else{

						$pending = ReturnsStatus::where('id','19')->value('id');
						$requested = ReturnsStatus::where('id','1')->value('id');
						$to_ship_back = ReturnsStatus::where('id','14')->value('id');
						$to_schedule_aftersales = ReturnsStatus::where('id','22')->value('id');		
						$to_schedule_logistics = ReturnsStatus::where('id','23')->value('id');

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
						->select(   'returns_header.*', 
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
						)->whereNotNull('returns_body_item.category')->where('returns_status_1', $pending)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('returns_status_1', $requested)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('returns_status_1', $to_ship_back)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('returns_status_1', $to_schedule_aftersales)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('returns_status_1', $to_schedule_logistics)->groupby('returns_body_item.digits_code');		
						
						
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
    
    					$ordeDataLines = $orderData->orderBy('returns_header.id','asc')->get();
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
    
    			
    						$serial_no = ReturnsSerials::where('returns_body_item_id', $orderRow->body_id)->first();
    						
    						if($orderRow->transaction_type == 2 ){
    						    $closed_personnel = $orderRow->verified_by;
    							$closed_date = 		$orderRow->level1_personnel_edited;
    							
    						}else{
    
            						if($orderRow->diagnose == "REFUND"){
            							$transacted_personnel = $orderRow->transacted_by;
            							$transacted_date = 		$orderRow->level5_personnel_edited;
            							$closed_personnel = 	$orderRow->closed_by;
            							$closed_date = 			$orderRow->level7_personnel_edited;
            						}else{
            							$transacted_personnel = "";
            							$transacted_date = "";
            							$closed_personnel = $orderRow->transacted_by;
            							$closed_date = 		$orderRow->level5_personnel_edited;
            						}
            						
            						if($orderRow->mode_of_return == "STORE DROP-OFF"){
            								$scheduled_by = 	$orderRow->scheduled_logistics_by;
            								$scheduled_date =	$orderRow->level8_personnel_edited;
            						}else{
            								$scheduled_by = 	$orderRow->scheduled_by;
            								$scheduled_date =	$orderRow->level2_personnel_edited;
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
    							$orderRow->mode_of_refund,	
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
    							$orderRow->pickup_schedule,   
    							$orderRow->refunded_date,  
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
    							$orderRow->warranty_status,
    							$orderRow->ship_back_status,
    							$orderRow->claimed_status,
    							$orderRow->credit_memo_number,
    							$orderRow->verified_by,
    							$orderRow->level1_personnel_edited,
    							$scheduled_by,
    							$scheduled_date,
    							$orderRow->diagnosed_by,
    							$orderRow->level3_personnel_edited,
    							$orderRow->printed_by,
    							$orderRow->level4_personnel_edited,
    							$transacted_personnel,							
    							$transacted_date,
    							$closed_personnel,
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
    						'MODE OF REFUND',
    						'BANK NAME',    //yellow
    						'BANK ACCOUNT#',      //red
    						'BANK ACCOUNT NAME',         //red
    						'ITEMS INCLUDED',         //red
    						'ITEMS INCLUDED OTHERS',//green
    						'VERIFIED ITEMS INCLUDED',         //red
    						'VERIFIED ITEMS INCLUDED OTHERS',//green
    						'CUSTOMER LOCATION',               //green
    						'DELIVER TO',               //green
    						'RETURN SCHEDULE',               //green
    						'PICKUP SCHEDULE',               //green
    						'REFUNDED DATE',               //green
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
    						'WARRANTY STATUS',
    						'SHIP BACK STATUS',           //blue  //additional code 20200205
    						'CLAIMED STATUS',           //blue  //additional code 20200205
    						'CREDIT MEMO#',           //blue  //additional code 20200205
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
					}
					

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


			public function sendmail() {
				$data = [];
				//CRUDBooster::sendEmail(['to'=>'rkalninmusic@gmail.com','data'=>$data,'template'=>'details_return','attachments'=>[]]);
			}



			
		public function ReturnsPulloutPrint($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level8_personnel','=', 'scheduled.id')			
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
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			if($data['row']->mode_of_return == "DOOR-TO-DOOR"){

			    $this->cbView("returns.print_pullout_online1", $data);
			}else{
			    $this->cbView("returns.print_pullout_online", $data);
			}
			    
		}



		public function ReturnPulloutUpdateONL(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');

			$return_request =  ReturnsHeader::where('id',$request_id)->first();


			$to_receive_rma = ReturnsStatus::where('id','34')->value('id');

			$to_receive_sc = ReturnsStatus::where('id','35')->value('id');

			//enhancement
			
			if($return_request->returns_status_1 != $to_diagnose){

			
				DB::beginTransaction();
	
				try {

					if($return_request->deliver_to == "WAREHOUSE.RMA.DEP"){
					    
						ReturnsHeader::where('id',$request_id)
						->update([
							'returns_status_1'=> 		$to_receive_rma
						]);	
						
								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');

								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $return_request->return_reference_no, 
									    $to_receive_sc_frontend,
									    date('Y-m-d H:i:s')
									]);

									DB::commit();
					
								}catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
					
								DB::disconnect('mysql_front_end');						
						
					}else{
					    
						ReturnsHeader::where('id',$request_id)
						->update([
							'returns_status_1'=> 		$to_receive_sc
						]);	
						
								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');

								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $return_request->return_reference_no, 
									    $to_receive_sc_frontend,
									    date('Y-m-d H:i:s')
									]);

									DB::commit();
					
								}catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
					
								DB::disconnect('mysql_front_end');	
								
					}
	
						
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');

			}
		}


		public function ReturnPulloutUpdateONLDTD(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			$return_request =  ReturnsHeader::where('id',$request_id)->first();
			
			$to_schedule_logistics = 	ReturnsStatus::where('id','23')->value('id');

			
			if($return_request->returns_status_1 != $to_schedule_logistics){
			    
				DB::beginTransaction();
	
				try {


					if($return_request->via_id == 1){

						ReturnsHeader::where('id',$request_id)
						->update([
						'returns_status_1'=> 		$to_schedule_logistics
						]);


					}else{

						$to_receive_rma = ReturnsStatus::where('id','34')->value('id');

						$to_receive_sc = ReturnsStatus::where('id','35')->value('id');


						if($return_request->deliver_to == "WAREHOUSE.RMA.DEP"){

							ReturnsHeader::where('id',$request_id)
							->update([
							'returns_status_1'=> 		$to_receive_rma
							]);
							
							
								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');

								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $return_request->return_reference_no, 
									    $to_receive_sc_frontend,
									    date('Y-m-d H:i:s')
									]);

									DB::commit();
					
								}catch (\Exception $e) {
									DB::rollback();
									CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
								}
					
								DB::disconnect('mysql_front_end');	
								

						}else{

							ReturnsHeader::where('id',$request_id)
							->update([
							'returns_status_1'=> 		$to_receive_sc
							]);


								$to_receive_sc_frontend = ReturnsStatus::where('id','36')->value('id');

								DB::beginTransaction();
				
								try {
					
									DB::connection('mysql_front_end')
									->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
									[   $return_request->return_reference_no, 
									    $to_receive_sc_frontend,
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

					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
						
					$data = ['name'=>$fullname,'status_return'=>$to_pickup,'ref_no'=>$ReturnRequest->return_reference_no,'return_schedule'=>$ReturnRequest->return_schedule,'store_name'=>$ReturnRequest->store];
							
							
					//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return_pick_up','attachments'=>[]]);
				

					//logistics
					//$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
					//'name'=>$fullname,
					$data = ['status_return'=>$to_schedule_logistics,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
									
					//CRUDBooster::sendEmail(['to'=>'lewieadona@digits.ph','data'=>$data,'template'=>'details_return_update','attachments'=>[]]);
									
					DB::commit();
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');

			}
		}



		public function ReturnsTaggingEdit($id)
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
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by'						
			)
			->where('returns_header.id',$id)->first();


			$data['Location'] = 0;

			$data['resultlist'] = ReturnsBody::
			leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')					
			->select(
			'returns_body_item.*',
			'returns_serial.*'					
			)
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
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
                if($sku->brand == "APPLE" || $sku->brand == "BEATS"){
                    $data['Location'] = 1;
                }

			}	

			$data['SCLocation'] = DB::table('sc_location')->where('status', "ACTIVE")->orderBy('sc_location_name', 'ASC')->get();

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();
            /*
			$store_id = 		 StoresFrontEnd::where('store_name', $data['row']->store )->first();
	
			$data['store_list'] = Stores::where('stores_frontend_id', $store_id->id )->get();
			*/
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store )->where('channels_id', 4 )->first();
	
			$data['store_list'] = Stores::where('branch_id',  $data['row']->branch )->where('stores_frontend_id',  $store_id->id )->get();
			
			$data['payments'] = ModeOfPayment::orderBy('payment_name','asc')->get();

			$data['purchaselocation'] = Channel::orderBy('channel_name','asc')->where('channel_status', 'ACTIVE')->where('id', 4)->get();
			
			$data['store_front_end'] =  StoresFrontEnd::where('channels_id',  4)->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();


			$data['branch'] = Stores::where('stores_frontend_id',  $store_id->id )->where('store_status', 'ACTIVE')->get();

			$data['store_drop_off'] =  DB::table(env('DB_DATABASE').'.stores')
                                            ->leftjoin('stores_frontend', 'stores.stores_frontend_id','=', 'stores_frontend.id')
                                            ->select('stores_frontend.store_name as store_name')
                                            ->where('stores.store_status','=','ACTIVE')->where('stores.channels_id', 6)->where('store_dropoff_privilege','YES')->orderBy('stores_frontend.store_name', 'ASC')->groupby('stores_frontend.store_name')->get();


            
			$storefrontend = StoresFrontEnd::where('channels_id',  6)->where('store_name', $data['row']->store_dropoff )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->first();

			
			if($request->location == 6){
			    $data['branch_dropoff'] = Stores::where('stores_frontend_id',  $storefrontend->id )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();			    
			}else{
			    $data['branch_dropoff'] = Stores::where('stores_frontend_id',  $storefrontend->id )->where('store_dropoff_privilege', 'YES')->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();
			}
			
			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();

			if($data['row']->mode_of_return == "DOOR-TO-DOOR"){
				$data['transaction_type'] = TransactionTypeList::where('transaction_for', 1)->orderBy('transaction_type_name','desc')->get();
			}else{
				$data['transaction_type'] = TransactionTypeList::where('id', 5)->orderBy('transaction_type_name','desc')->get();
			}

			$data['via'] =  DB::table('via')->where('status', 'ACTIVE')->get();

			$this->cbView("returns.edit_tagging", $data);
		}
		
		
		public function ReturnsDetail($id)
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
			'tagged.name as diagnosed_by',
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
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
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
            /*
			$store_id = 		 StoresFrontEnd::where('store_name', $data['row']->store )->first();
	
			$data['store_list'] = Stores::where('stores_frontend_id', $store_id->id )->get();
			*/
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store )->where('channels_id', 4 )->first();
	
			$data['store_list'] = Stores::where('branch_id',  $data['row']->branch )->where('stores_frontend_id',  $store_id->id )->get();
			
			$data['payments'] = ModeOfPayment::orderBy('payment_name','asc')->get();

			$data['purchaselocation'] = Channel::orderBy('channel_name','asc')->where('channel_status', 'ACTIVE')->where('id', 4)->get();
			
			$data['store_front_end'] =  StoresFrontEnd::where('channels_id',  4)->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();


			$data['branch'] = Stores::where('stores_frontend_id',  $store_id->id )->where('store_status', 'ACTIVE')->get();

			$data['store_drop_off'] =  DB::table(env('DB_DATABASE').'.stores')
                                            ->leftjoin('stores_frontend', 'stores.stores_frontend_id','=', 'stores_frontend.id')
                                            ->select('stores_frontend.store_name as store_name')
                                            ->where('stores.store_status','=','ACTIVE')->where('stores.channels_id', 6)->where('store_dropoff_privilege','YES')->orderBy('stores_frontend.store_name', 'ASC')->groupby('stores_frontend.store_name')->get();


            
			$storefrontend = StoresFrontEnd::where('channels_id',  6)->where('store_name', $data['row']->store_dropoff )->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->first();

			$data['branch_dropoff'] = Stores::where('stores_frontend_id',  $storefrontend->id )->where('store_dropoff_privilege', 'YES')->where('store_status', 'ACTIVE')->orderBy('store_name','asc')->get();
			
			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
			
			$this->cbView("returns.returns_detail", $data);
		}


		public function importPage() {
	    	$data['page_title'] = 'Warranty Request Upload';
	    	return view('returns.item_create_upload',$data);
	    }



		public function importTemplate() {

	    	Excel::create('returns-import-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
			$excel->sheet('returns', function ($sheet) {
				$sheet->row(1, 
					array(
					    'Purchase Location',
						'Store',
						'Branch',
						'Mode of Return',
						'Store Drop-Off',
						'Branch Drop-Off',
						'First Name',
						'Last Name',
						'Address Line 1',
						'Address Line 2',
						'State/Province',
						'City/Municipality',
						'Barangay',
						'Country',
						'Email Address',
						'Contact Number',
						'Order Number',
						'Purchase Date',
						'Original Mode of Payment',
						'Purchase Amount',
						'Mode of Refund',
						'Bank Name',
						'Bank Account Number',
						'Bank Account Name',
						'Item Code',
						'Item Description',
						'Serial Number',
						'Problem Details',
						'Other Problem Details',
						'Items Included',
						'Other Items Included'
						)
					);
			});

			})->download('csv');	
	    }

		public function importExcel(Request $request) {
	    	$insert = array();
	    	$data_saved = false;

	    	if ($request->hasFile('import_file')) {

				$prev_count = DB::table('digits_imfs')->count();
				$item_counter = 0;
				$path = $request->file('import_file')->getRealPath();
				$up_option = $request->input('upload_option');

				$data = Excel::load($path, function ($reader) {

				})->get();
				
				//ini_set('memory_limit', '-1');

				Excel::filter('chunk')->load($path)->chunk(10, function ($reader) {
					foreach ($reader as $csv) {
						$up_batch = DB::table('tbl_counter')->where('id', 1)->value('upload_init');
						$brand_id_csv = DB::table('brand')->where('brand_description', $csv['brand_description'])->value('id');
						$category_id_csv = DB::table('category')->where('category_description', $csv['category_description'])->value('id');
						$class_id_csv = DB::table('class')->where('class_description', $csv['class_description'])->value('id');
						$subclass_id_csv = DB::table('subclass')->where('subclass_description', $csv['subclass'])->where('class_id', $class_id_csv)->value('id');
						//$store_category_id_csv = DB::table('store_category')->where('store_category_description', $csv['store_category'])->where('subclass_id',$subclass_id_csv)->value('id');
						$margincategory_id_csv = DB::table('margin_category')->where('margin_category_description', $csv['margin_category'])->where('subclass_id',$subclass_id_csv)->value('id');
						$warehouse_category_id_csv = DB::table('warehouse_category')->where('wh_category_description', $csv['warehouse_category'])->value('id');
						$modelspecific_id_csv = DB::table('modelspecific')->where('modelspecific_description', $csv['model_specific_description'])->value('id');
						
						$color_id_csv = DB::table('color')->where('color_description', $csv['main_color_description'])->value('id');
						$uom_id_csv = DB::table('uom')->where('uom_code', $csv['uom_code'])->value('id');
						$vendor_id_csv = DB::table('vendor')->where('vendor_name', $csv['vendor_name'])->where('brand_id', $brand_id_csv)->value('id');
						//$supplier_id_csv = DB::table('supplier')->where('supplier_name', $csv['supplier_name'])->value('id');
						$inventory_id_csv = DB::table('inventory')->where('inventory_description', $csv['inventory_type'])->value('id');
						$skustatus_id_csv = DB::table('skustatus')->where('sku_status_description', $csv['sku_status'])->value('id');
						$skuclass_id_csv = DB::table('skuclass')->where('sku_class_description', $csv['sku_class'])->value('id');
						$skulegend_id_csv = DB::table('skulegend')->where('sku_legend_description', $csv['sku_legend'])->value('id');
						
						$size_id_csv = DB::table('size')->where('size_code', $csv['size_code'])->value('id');

						$currency_id_csv = DB::table('currency')->where('currency_code', $csv['currency'])->value('id');
						$vendor_type_id_csv = DB::table('vendor_type')->where('vendor_type_code', $csv['vendor_type_code'])->value('id');
						
						//$incoterms_id_csv = DB::table('incoterms')->where('incoterms_code', $csv['incoterms'])->value('id');
						$incoterms_id_csv = DB::table('vendor')->where('id', $vendor_id_csv)->value('incoterms_id');

						$createdby_csv = DB::table('cms_users')->where('user_name',$csv['created_by'])->value('id');
						$updatedby_csv = DB::table('cms_users')->where('user_name',$csv['updated_by'])->value('id');

						$original_srp_csv = $csv['original_srp'];
						$current_srp_csv = $csv['current_srp'];

						if (is_null($brand_id_csv)) {
							$brand_id_csv = null;
						}
						if (is_null($margincategory_id_csv)) {
							$margincategory_id_csv = null;
						}
						if (is_null($category_id_csv)) {
							$category_id_csv = null;
						}
						if (is_null($class_id_csv)) {
							$class_id_csv = null;
						}
						if (is_null($subclass_id_csv)) {
							$subclass_id_csv = null;
						}
				// 		if (is_null($store_category_id_csv)) {
				// 			$store_category_id_csv = null;
				// 		}
						if (is_null($modelspecific_id_csv)) {
							$modelspecific_id_csv = null;
						}
						if (is_null($color_id_csv)) {
							$color_id_csv = null;
						}
						if (is_null($uom_id_csv)) {
							$uom_id_csv = null;
						}
						if (is_null($size_id_csv)) {
							$size_id_csv = null;
						}
						if (is_null($vendor_id_csv)) {
							$vendor_id_csv = null;
						}
						if (is_null($vendor_type_id_csv)) {
							$vendor_type_id_csv = null;
						}
						if (is_null($incoterms_id_csv)) {
							$incoterms_id_csv = null;
						}
				// 		if (is_null($supplier_id_csv)) {
				// 			$supplier_id_csv = null;
				// 		}
						if (is_null($inventory_id_csv)) {
							$inventory_id_csv = null;
						}
						if (is_null($skustatus_id_csv)) {
							$skustatus_id_csv = null;
						}
						if (is_null($skulegend_id_csv)) {
							$skulegend_id_csv = null;
						}
						if (is_null($currency_id_csv)) {
							$currency_id_csv = null;
						}
						if (is_null($skuclass_id_csv)) {
							$skuclass_id_csv = null;
						}
						if (is_null($csv['original_srp'])) {
							$original_srp_csv = '0.00';
						}
						if (is_null($csv['current_srp'])) {
							$current_srp_csv = '0.00';
						}

						$insert[] = [
							'upc_code' => $csv['upc_code'],
							'supplier_itemcode' => $csv['supplier_item_code'],
							'item_description' => $csv['item_description'],
							'brand_id' => $brand_id_csv,
							'margin_category_id' => $margincategory_id_csv,
							'category_id' => $category_id_csv,
							'class_id' => $class_id_csv,
							'subclass_id' => $subclass_id_csv,
							//'store_category_id' => $store_category_id_csv,
							'warehouse_category_id' => $warehouse_category_id_csv,
							'model' => $csv['model'],
							'compatibility' => $csv['compatibility'],
							'modelspecific_id' => $modelspecific_id_csv,
							'color_id' => $color_id_csv,
							'actual_color' => $csv['actual_color'],
							'size' => ($csv['size'] == 0) ? 'N/A' : $csv['size'].$csv['size_code'],
							'size_num' => $csv['size'],
							'size_id' => $size_id_csv,
							'uom_id' => $uom_id_csv,
							'vendor_id' => $vendor_id_csv,
							'vendor_type_id' => $vendor_type_id_csv, 
							'incoterms_id' => $incoterms_id_csv,
							'supplier_id' => $supplier_id_csv,
							'inventory_id' => $inventory_id_csv,
							'serialized' => null,
							'serial_code' => $csv['serial_code'],
							'imei_code1' => '0',
							'imei_code2' => '0',
							'skustatus_id' => $skustatus_id_csv,
							'skulegend_id' => $skulegend_id_csv,
							'skuclass_id' => $skuclass_id_csv,
							'core_sku' => null,
							'dtp_rf' => $csv['store_cost'],
							'dtp_rf_percentage' => $csv['store_margin_percentage'],
							'working_dtp_rf' => $csv['working_store_cost'],
							'working_dtp_rf_percentage' => $csv['working_store_margin_percentage'],
							//'dtp_dcon' => $csv['dtp_dcon'],
							'dtp_dcon_percentage' => $csv['max_consignment_rate_percentage'],
							'original_srp' => $original_srp_csv,
							'current_srp' => $current_srp_csv,
							'price_change' => null,
							'pricestatus_id' => null,
							'effective_date' => null,
							'moq' => $csv['moq'],
							'currency_id' => $currency_id_csv,
							'purchase_price' => $csv['supplier_cost'],
							'promo_srp' => $csv['dg_srp'],
							'landed_cost' => $csv['landed_cost'],
							'working_landed_cost' => $csv['working_landed_cost'],
							'btb_segmentation' => $csv['btb'],
							'segment_btb' => '0',
							'dw_segmentation' => $csv['dw'],
							'segment_dw' => '0',
							'omg_segmentation' => $csv['omg'],
							'segment_omg' => '0',
							'online_segmentation' => $csv['online'],
							'segment_online' => '0',
							'baseus_segmentation' => $csv['baseus'],
							'segment_baseus' => '0',
							'districon_segmentation' => $csv['distri_con'],
							'segment_districon' => '0',
							'distriout_segmentation' => $csv['distri_out'],
							'segment_districon' => '0',
							'guam_segmentation' => $csv['guam'],
							'segment_guam' => '0',
							'ecomm_segmentation' => null,
							'segment_ecomm' => '0',
							'warranty_duration' => '0',
							'warranty_id' => null,
							'is_approved' => null,
							'up_batch_add' => $up_batch,
							'uploaded_by' => CRUDBooster::myId(),
							'uploaded_at' => date("Y-m-d h:i:s"),
							'created_by' => CRUDBooster::myId(),
							'created_at' => date('Y-m-d H:i:s')
						];

						//$item_counter++;
					}

				// 	$encoded_data = json_encode($insert);
				// 	\Log::info('Insert Data: '.$encoded_data);
					
					if (!empty($insert)) {
						try {
							DB::beginTransaction();
							DB::table('digits_imfs')->insert($insert);
							DB::table('digits_preimfs')->insert($insert);
							DB::table('tbl_counter')->where('id', 1)->increment('upload_init');
							
							
							
							DB::commit();
						} catch (\Exception $e) {
							DB::rollback();
							return back()->with('error_import', $e->errorInfo[2]);
						}
					}
					/*//if (!empty($insert)) {

						switch ($up_option) {
							case '1': 
							case 1:
								try {
									DB::beginTransaction();
									DB::table('digits_imfs')->insert($insert);
									DB::table('tbl_counter')->where('id', 1)->increment('upload_init');
									DB::commit();
								} catch (\Exception $e) {
									DB::rollback();
									return back()->with('error_import', $e->errorInfo[2]);
								}
								break;
							case '2': 
							case 2:
								try {
									DB::beginTransaction();
									DB::table('digits_imfs')->update($insert)->where('digits_code',[]);
									DB::table('tbl_counter')->where('id', 1)->increment('upload_init');
									DB::commit();
								} catch (\Exception $e) {
									DB::rollback();
									return back()->with('error_import', $e->errorInfo[2]);
								}
								break;
							case '3':
							case 3:
								try {
									DB::beginTransaction();
									DB::table('digits_imfs')->insert($insert);
									DB::table('tbl_counter')->where('id', 1)->increment('upload_init');
									DB::commit();
								} catch (\Exception $e) {
									DB::rollback();
									return back()->with('error_import', $e->errorInfo[2]);
								}
								break;
							
							default:
								# code...
								break;
						}
						
					//}*/

					$pres_count = DB::table('digits_imfs')->count();
					DB::statement("UPDATE digits_imfs, digits_preimfs SET digits_preimfs.item_id = digits_imfs.id where digits_imfs.upc_code = digits_preimfs.upc_code and digits_preimfs.item_id is null");

					if ($pres_count > $prev_count) {
						$ans_count = $pres_count - $prev_count;
						return back()->with('success_import', 'Success ! ' . $ans_count . ' item(s) were uploaded successfully.');
					}
					elseif($prev_count != 0){
						if ($pres_count == $prev_count) {
							return back()->with('error_import', 'Duplicate ' . $data->count() . ' item(s) found !!!');
						}

					}
					elseif($prev_count == 0) {
						if ($pres_count == $prev_count) {
							return back()->with('error_import', $data->count() . ' item(s) not saved !!!');
						}
					}

				});

				$pres_count = DB::table('digits_imfs')->count();

				if ($pres_count > $prev_count) {
					$ans_count = $pres_count - $prev_count;
					return back()->with('success_import', 'Success ! ' . $ans_count . ' item(s) were uploaded successfully.');
				}
				elseif($prev_count != 0){
					if ($pres_count == $prev_count) {
						return back()->with('error_import', 'Duplicate ' . $data->count() . ' item(s) found !!!');
					}

				}
				elseif($prev_count == 0) {
					if ($pres_count == $prev_count) {
						return back()->with('error_import', $data->count() . ' item(s) not saved !!!');
					}
				}
				
			} 

			else {
				return back()->with('error_import', 'Oppss... Something went wrong. Please check your excel file.');
			}
	    }



		public function stores(Request $request)
		{
			if(!empty($request->stores))
			{

				$channels_id = Channel::where('channel_name',$request->stores)->first();

				$channels = StoresFrontEnd::where('channels_id',$channels_id->id)->where('store_status','=','ACTIVE')->orderBy('store_name', 'ASC')->get();
			}else{
				$channels = StoresFrontEnd::orderBy('store_name', 'ASC')->where('store_status','=','ACTIVE')->get();
			}
			
			return($channels);
		}

		public function backend_stores(Request $request)
		{   
			if(!empty($request->store_backend))
			{
				$store_id = StoresFrontEnd::where('store_name', $request->store_backend)
					->where('channels_id', 4)
					->where('store_status', 'ACTIVE')->first();
	
					$customer_location =    Stores::select(DB::raw('DISTINCT branch_id, COUNT(*) AS count_pid'))
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
			
			        $store_id = 	StoresFrontEnd::where('store_name', $request->store_front )->where('channels_id', 4 )->first();
	
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
				$store_id =          StoresFrontEnd::where('store_name', $request->drop_off_store)->where('channels_id', 6)->where('store_status', 'ACTIVE')->first();
	            
	            if($request->location == 6){
	                $customer_location = Stores::where('stores_frontend_id',$store_id->id)->where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
	            }else{
				    $customer_location = Stores::where('stores_frontend_id',$store_id->id)->where('store_status', 'ACTIVE')->where('store_dropoff_privilege', 'YES')->orderBy('branch_id', 'ASC')->get();
	            }
			}else{
	
				$customer_location = Stores::where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
	
				//$customer_location = DB::table('stores_backend')->where('store_status','=','ACTIVE')->orderBy('branch_name', 'ASC')->get();
				//$customer_location = DB::table('stores_backend')->orderBy('store_name', 'ASC')->where('store_status','=','ACTIVE')->get();
			}
			
			return($customer_location);
		}
	}