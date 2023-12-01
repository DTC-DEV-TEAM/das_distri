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
use App\ItemsIncluded;
use App\DiagnoseWarranty;
use App\StoresFrontEnd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

	class AdminReturnsDiagnosingController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 
        			$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
        			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
        			$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
        			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
        			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
        			$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
        			$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
        			$this->col[] = ["label"=>"Store","name"=>"store"];
        		    $this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
        			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
        			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
        			$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
			}else{
				$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
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
			$to_diagnose = ReturnsStatus::where('id','5')->value('id');
			$to_receive_sor = ReturnsStatus::where('id','10')->value('id');
			$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
			$requested = ReturnsStatus::where('id','1')->value('id');


				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsDiagnosingEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_diagnose"];
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsSORReceivingEdit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $to_receive_sor"];
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrint/[id]'),'icon'=>'fa fa-print', "showIf"=>"[returns_status_1] == $to_print_return_form"];
		
			//$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsDiagnosingDetail/[id]'),'icon'=>'fa fa-eye'];

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

			if(CRUDBooster::getCurrentMethod() == 'getIndex' && (CRUDBooster::myPrivilegeName()=="Service Center")){

				$this->index_button[] = ["title"=>"Export Returns",
				"label"=>"Export Returns",
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsDiagnosingSC').'?'.urldecode(http_build_query(@$_GET))];
				//$this->index_button[] = ["label"=>"Export Returns","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturns'),"color"=>"success"];
			}else{
				if(CRUDBooster::getCurrentMethod() == 'getIndex'){
					$this->index_button[] = ["title"=>"Export Returns",
					"label"=>"Export Returns",
					"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsDiagnosing').'?'.urldecode(http_build_query(@$_GET))];		
				}		
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
		
			if(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				$query->where(function($sub_query){
	 
					//$to_indicate_store = ReturnsStatus::where('warranty_status','TO DIAGNOSE')->value('id');
					//$to_indicate_store = 	ReturnsStatus::where('id','3')->value('id');
					$to_diagnose = ReturnsStatus::where('id','5')->value('id');
					$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
					
					
					    $approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
        				$approval_array = array();
        				foreach($approvalMatrix as $matrix){
        				    array_push($approval_array, $matrix->stores_id);
        				}
        				$approval_string = implode(",",$approval_array);
        				$storeList = array_map('intval',explode(",",$approval_string));      
	 
					$sub_query->where('returns_status_1', $to_diagnose)->where('transaction_type', 1)->whereIn('returns_header.stores_id', $storeList)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $to_receive_sor)->where('transaction_type', 1)->whereIn('returns_header.stores_id', $storeList)->orderBy('id', 'asc');
					$sub_query->orWhere('returns_status_1', $to_print_return_form)->where('transaction_type', 1)->whereIn('returns_header.stores_id', $storeList)->orderBy('id', 'asc');
				});

			}
			else if(CRUDBooster::myPrivilegeName() == "RMA Technician"){
				$query->where(function($sub_query){
					$to_diagnose = ReturnsStatus::where('id','5')->value('id');
					$sub_query->where('returns_status_1', $to_diagnose)->where('transaction_type', 0)->orderBy('id', 'asc');  
				});
			}
			else{
			    

				$query->where(function($sub_query){
					//$to_indicate_store = ReturnsStatus::where('warranty_status','TO DIAGNOSE')->value('id');
					//$to_indicate_store = 	ReturnsStatus::where('id','3')->value('id');
					// $to_diagnose = ReturnsStatus::where('id','5')->value('id');
					$to_diagnose_action = ReturnsStatus::where('id','38')->value('id');
					$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
	
					$sub_query->where('returns_status_1', $to_diagnose_action)->where('transaction_type', 0)->orderBy('id', 'asc');  
					$sub_query->orWhere('returns_status_1', $to_receive_sor)->where('transaction_type', 0)->orderBy('id', 'asc');
					$sub_query->orWhere('returns_status_1', $to_print_return_form)->where('transaction_type', 0)->orderBy('id', 'asc');
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

			$to_receive_sor = ReturnsStatus::where('id','10')->value('id');

            
				if($ReturnRequest->returns_status_1 == $to_receive_sor){
					$refund_in_process = ReturnsStatus::where('id','8')->value('id');

					DB::beginTransaction();
		
					try {
		
						DB::connection('mysql_front_end')
						->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
						[$ReturnRequest->return_reference_no, 
						$refund_in_process,
						date('Y-m-d H:i:s')
						]);
			
						$postdata['level6_personnel'] = 					CRUDBooster::myId();
						$postdata['level6_personnel_edited']=				date('Y-m-d H:i:s');
						$postdata['returns_status'] = 					    $refund_in_process;
						$postdata['returns_status_1'] = 					$refund_in_process;
		
		
						DB::commit();
		
					}catch (\Exception $e) {
						DB::rollback();
						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
					}
		
					DB::disconnect('mysql_front_end');

				}else{

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

					if($field_1 == "Repair"){

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
				
								$postdata['level3_personnel'] = 					CRUDBooster::myId();
								$postdata['level3_personnel_edited']=				date('Y-m-d H:i:s');
								$postdata['returns_status'] = 						$repair_approved;
								$postdata['returns_status_1'] = 					$to_print_return_form;
								$postdata['diagnose_comments'] = 					$field_2;
								$postdata['diagnose'] = 							"REPAIR";
								$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
								$postdata['verified_items_included_others'] = 		$items_included_others;
								$postdata['warranty_status'] = 						$warranty_status;

								ReturnsBody::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
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
				
								$postdata['level3_personnel'] = 					CRUDBooster::myId();
								$postdata['level3_personnel_edited']=				date('Y-m-d H:i:s');
								$postdata['returns_status'] = 						$return_rejected;
								$postdata['returns_status_1'] = 					$to_print_return_form;
								$postdata['diagnose_comments'] = 					$field_2;
								$postdata['diagnose'] = 							"REJECT";
								$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
								$postdata['verified_items_included_others'] = 		$items_included_others;
								$postdata['warranty_status'] = 						$warranty_status;

								ReturnsBody::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
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

					}else if($field_1 == "Refund"){
					    
						$to_refund_approved = 	  ReturnsStatus::where('warranty_status','REFUND APPROVED')->value('id');
						//$to_print_crf = ReturnsStatus::where('warranty_status','TO PRINT CRF')->value('id');

						$to_create_crf = 		ReturnsStatus::where('id','25')->value('id');
			
						DB::beginTransaction();
		
						try {
			
							DB::connection('mysql_front_end')
							->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
							[$ReturnRequest->return_reference_no, 
							$to_refund_approved,
							date('Y-m-d H:i:s')
							]);
				
									$postdata['level3_personnel'] = 					CRUDBooster::myId();
									$postdata['level3_personnel_edited']=				date('Y-m-d H:i:s');
									$postdata['returns_status'] = 						$to_refund_approved;
									$postdata['returns_status_1'] = 					$to_create_crf;
									$postdata['diagnose_comments'] = 					$field_2;
									$postdata['diagnose'] = 							"REFUND";
									$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
									$postdata['verified_items_included_others'] = 		$items_included_others;
									$postdata['warranty_status'] = 						$warranty_status;

									ReturnsBody::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
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
					}else if($field_1 == "Replace"){

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
						
										$postdata['level3_personnel'] = 					CRUDBooster::myId();
										$postdata['level3_personnel_edited']=				date('Y-m-d H:i:s');
										$postdata['returns_status'] = 						$for_replacement_frontend;
										$postdata['returns_status_1'] = 					$to_sor;
										$postdata['diagnose_comments'] = 					$field_2;
										$postdata['diagnose'] = 							"REPLACE";
										$postdata['verified_items_included'] = 				implode(", ",$items_included_lines);
										$postdata['verified_items_included_others'] = 		$items_included_others;
										$postdata['warranty_status'] = 						$warranty_status;

										ReturnsBody::where('returns_header_id',$ReturnRequest->id)->whereNotNull('brand')
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

			$refund_in_process = ReturnsStatus::where('id','8')->value('id');

            
				if($ReturnRequest->returns_status_1 == $refund_in_process){

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been received successfully!"), 'success');

				}else{

					$returns_fields = Input::all();
					$field_1 		= $returns_fields['diagnose'];


					if($field_1 == "Repair"){

						$repair_approved = 	  ReturnsStatus::where('id','16')->value('warranty_status');

						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$repair_approved,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						////CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

						return redirect()->action('AdminReturnsDiagnosingController@ReturnsReturnFormPrint',['id'=>$ReturnRequest->id])->send();
						exit;
					}else if($field_1 == "Reject"){

						
						$return_rejected = 	  ReturnsStatus::where('id','12')->value('warranty_status');


						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$return_rejected,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

						return redirect()->action('AdminReturnsDiagnosingController@ReturnsReturnFormPrint',['id'=>$ReturnRequest->id])->send();
						exit;
					}else if($field_1 == "Refund"){

						$to_refund_approved = 	  ReturnsStatus::where('id','6')->value('warranty_status');


						$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$to_refund_approved,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
				
				
						////CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

						CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been diagnosed as refund successfully!"), 'success');
					}else if($field_1 == "Replace"){
					$for_replacement_frontend =	ReturnsStatus::where('id','27')->value('warranty_status');

					$for_replacement = 	  ReturnsStatus::where('id','20')->value('warranty_status');
							
					$fullname = $ReturnRequest->customer_first_name." ".$ReturnRequest->customer_last_name;
		
					$data = ['name'=>$fullname,'status_return'=>$for_replacement_frontend,'ref_no'=>$ReturnRequest->return_reference_no,'store_name'=>$ReturnRequest->store];
			
				//	//CRUDBooster::sendEmail(['to'=>$ReturnRequest->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);

					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been diagnosed as replace successfully!"), 'success');
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
		public function ReturnsDiagnosingEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();


			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')	
			->leftjoin('cms_users as received_item', 'returns_header.received_by_rma_sc','=', 'received_item.id')
			->leftjoin('cms_users as scheduled_logistics', 'returns_header.level8_personnel','=', 'scheduled_logistics.id')																	
			->select(
			'returns_header.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'received_item.name as received_item_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'scheduled_logistics.name as scheduled_by_logistics'						
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
			
		
			
	
			
			$this->cbView("returns.edit_diagnosing", $data);
		}


		public function ReturnsDiagnosingEditSC($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();


			$data['problem_details_list'] = ProblemDetails::all();

			$data['items_included_list'] = ItemsIncluded::orderBy('items_description_included','asc')->get();

			$data['warranty_status'] = DiagnoseWarranty::orderBy('warranty_name','asc')->get();
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level2_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level1_personnel','=', 'tagged.id')
			->leftjoin('cms_users as diagnosed', 'returns_header.level3_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header.level4_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header.level5_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header.level7_personnel','=', 'closed.id')	
			->leftjoin('cms_users as scheduled_logistics', 'returns_header.level8_personnel','=', 'scheduled_logistics.id')																	
			->select(
			'returns_header.*',
			'scheduled.name as scheduled_by',
			'tagged.name as tagged_by',	
			'tagged.name as diagnosed_by',
			'printed.name as printed_by',	
			'transacted.name as transacted_by',	
			'received.name as received_by',
			'closed.name as closed_by',
			'scheduled_logistics.name as scheduled_by_logistics'						
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

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.edit_diagnosingSC", $data);
		}

		public function ReturnsSORReceivingEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Receiving SOR';
		
			$data['row'] = ReturnsHeader::
			//->leftjoin('stores', 'pullout_headers.pull_out_from', '=', 'stores.id')	
			leftjoin('cms_users as scheduled', 'returns_header.level1_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as tagged', 'returns_header.level2_personnel','=', 'tagged.id')
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
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.edit_sor_receiving", $data);
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
		
			
			//$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store )->where('channels_id', 4 )->first();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )->first();
			

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_return_form", $data);
		}

		public function ReturnsReturnFormPrintSC($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Printing';
		
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
			->where('returns_body_item.returns_header_id',$data['row']->id)->whereNull('returns_body_item.category')->groupby('returns_body_item.digits_code')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.print_return_formSC", $data);
		}

		public function FormRejectUpdateStatus(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');
			

			$return_request =  ReturnsHeader::where('id',$request_id)->first();

            
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
    		
    						ReturnsHeader::where('id',$request_id)
    						->update([
    						//'status_level0'=> $status_all,
    						'level4_personnel'=> 		CRUDBooster::myId(),
    						'level4_personnel_edited'=> date('Y-m-d H:i:s'),
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
		
						ReturnsHeader::where('id',$request_id)
						->update([
						//'status_level0'=> $status_all,
						'level4_personnel'=> 		CRUDBooster::myId(),
						'level4_personnel_edited'=> date('Y-m-d H:i:s'),
						'returns_status'=> 			$return_delivery_date,
						'returns_status_1'=> 		$return_delivery_date
						]);	
	
					DB::commit();
					
					
					    /*
								    
			    		$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
				
						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]);	 */
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');

			}
			
            }
		}


		public function FormRejectUpdateStatusSC(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			//$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('id');

			$return_request =  ReturnsHeader::where('id',$request_id)->first();

			if($return_request->returns_status_1 != $return_invalid){

			
				DB::beginTransaction();
	
				try {
	
					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[$return_request->return_reference_no, 
					$return_invalid,
					date('Y-m-d H:i:s')
					]);
		
						ReturnsHeader::where('id',$request_id)
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


		public function FormRepairUpdateStatus(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			
			$return_delivery_date = ReturnsStatus::where('id','33')->value('id');
			

			$return_request =  ReturnsHeader::where('id',$request_id)->first();

            
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
        		
        							ReturnsHeader::where('id',$request_id)
        							->update([
        							//'status_level0'=> $status_all,
        							'level4_personnel'=> 		CRUDBooster::myId(),
        							'level4_personnel_edited'=> date('Y-m-d H:i:s'),
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
		
							ReturnsHeader::where('id',$request_id)
							->update([
							//'status_level0'=> $status_all,
							'level4_personnel'=> 		CRUDBooster::myId(),
							'level4_personnel_edited'=> date('Y-m-d H:i:s'),
							'returns_status'=> 			$return_delivery_date,
							'returns_status_1'=> 		$return_delivery_date
							]);
	
					DB::commit();
					
					
					    /*
						$to_ship_back = ReturnsStatus::where('id','14')->value('warranty_status');
				
						$fullname = $return_request->customer_first_name." ".$return_request->customer_last_name;
			
						$data = ['name'=>$fullname,'status_return'=>$to_ship_back,'ref_no'=>$return_request->return_reference_no,'store_name'=>$return_request->store];
				
				
						//CRUDBooster::sendEmail(['to'=>$return_request->email_address,'data'=>$data,'template'=>'details_return','attachments'=>[]]); */
	
				}catch (\Exception $e) {
					DB::rollback();
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_database_error",['database_error'=>$e]), 'danger');
				}
	
				DB::disconnect('mysql_front_end');
			}
			
            }
		}


		public function FormRepairUpdateStatusSC(){
			$data = Input::all();		
			$request_id = $data['return_id']; 
			//$comments_variable = $data['comments']; 			
			//$to_ship_back = ReturnsStatus::where('id','14')->value('id');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('id');

			$return_request =  ReturnsHeader::where('id',$request_id)->first();


			if($return_request->returns_status_1 != $repair_complete){
				
				DB::beginTransaction();
	
				try {
	
					DB::connection('mysql_front_end')
					->statement('insert into returns_tracking_status (return_reference_no, returns_status, 	created_at) values (?, ?, ?)', 
					[$return_request->return_reference_no, 
					$repair_complete,
					date('Y-m-d H:i:s')
					]);
		
							ReturnsHeader::where('id',$request_id)
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



		public function GetExtractReturnsDiagnosing() {

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

						$to_diagnose = ReturnsStatus::where('id','5')->value('id');
						$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
						$to_print_return_form = ReturnsStatus::where('id','13')->value('id');

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
						)->whereNotNull('returns_body_item.category')->where('transaction_type', 0)->where('returns_status_1', $to_diagnose)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('transaction_type', 0)->where('returns_status_1', $to_receive_sor)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('transaction_type', 0)->where('returns_status_1', $to_print_return_form)->groupby('returns_body_item.digits_code');
					

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


		public function GetExtractReturnsDiagnosingSC() {

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

						$to_diagnose = ReturnsStatus::where('id','5')->value('id');
						$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
						$to_print_return_form = ReturnsStatus::where('id','13')->value('id');
						
						
						$approvalMatrix = DB::table("cms_users")->where('cms_users.id', CRUDBooster::myId())->get();
        				$approval_array = array();
        				foreach($approvalMatrix as $matrix){
        				    array_push($approval_array, $matrix->stores_id);
        				}
        				$approval_string = implode(",",$approval_array);
        				$storeList = array_map('intval',explode(",",$approval_string));    

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
						)->whereNotNull('returns_body_item.category')->where('transaction_type', 1)->where('returns_status_1', $to_diagnose)->whereIn('returns_header.stores_id', $storeList)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('transaction_type', 1)->where('returns_status_1', $to_receive_sor)->whereIn('returns_header.stores_id', $storeList)->groupby('returns_body_item.digits_code')
						->orwhereNotNull('returns_body_item.category')->where('transaction_type', 1)->where('returns_status_1', $to_print_return_form)->whereIn('returns_header.stores_id', $storeList)->groupby('returns_body_item.digits_code');
					

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
	}