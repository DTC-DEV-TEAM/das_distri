<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use CRUDBooster;
use Route;
use App\ReturnsStatus;
use App\ReturnsHeaderRTL;
use App\ReturnsBodyRTL;
use App\ReturnsSerialsRTL;
use App\ProblemDetails;
use App\Stores;
use App\Channel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Carbon\Carbon;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

	class AdminRetailReturnRequestController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			//$this->request = $request;
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "customer_last_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "returns_header_retail";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];

			$this->col[] = ["label"=>"Status","name"=>"returns_status_1","join"=>"warranty_statuses,warranty_status"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Return Reference#","name"=>"return_reference_no"];
			$this->col[] = ["label"=>"Order#","name"=>"order_no"];
			//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
			$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
			$this->col[] = ["label"=>"Store","name"=>"store"];
			$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
			$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
			$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
			$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
			$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
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
	        $this->alert = array();
	        
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
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsCreated').'?'.urldecode(http_build_query(@$_GET))];
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
		
		public function getAdd() 
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['resultlist'] = DB::table('digits_imfs')->get();
			$data['problem_details'] = DB::table('srof_problem_details')->first();
			$data['items_included']  = DB::table('items_included')->where('status', '=', 'ACTIVE')->orderBy('items_description_included', 'ASC')->get();
			$data['stores'] = DB::table('stores')->where('store_status', '=', 'ACTIVE')->orderBy('store_name', 'ASC')->get();
			$data['stores_frontend'] = DB::table('stores_frontend')->where('store_status', '=', 'ACTIVE')->orderBy('store_name', 'ASC')->get();
			$data['mode_of_payment'] = DB::table('mode_of_payment')->where('status','=','ACTIVE')->orderBy('payment_name', 'ASC')->get();
			$data['channels'] = DB::table('channels')->where('channel_status','=','ACTIVE')->where('id',1)->orwhere('id',2)->orderBy('channel_name', 'ASC')->get();

			$this->cbView("returns.request", $data);
		}

		public function cbView($template, $data)
		{
			$this->cbLoader();
			if (! CRUDBooster::isCreate() && $this->global_privilege == false || $this->button_add == false) {
				CRUDBooster::insertLog(trans('crudbooster.log_try_add', ['module' => CRUDBooster::getCurrentModule()->name]));
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			echo view($template, $data)->with('data', $data);
		}

		public function getDetail($id)
		{
			$this->cbLoader();
			
			$data['resultlist'] = DB::table('digits_imfs')->get();

			$data['return_request'] = DB::table('returns_header_retail')
				->leftjoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
				->leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')	
				->select('return_reference_no',
					'purchase_location',
					'store',
					'customer_last_name',
					'customer_first_name',
					'address',
					'email_address',
					'contact_no',
					'order_no',
					'purchase_date',
					'mode_of_payment',
					'serial_number',
					'cost',
					'returns_header_retail.items_included AS ItemsIncluded',
					'returns_header_retail.items_included_others AS ItemsIncludedOthers',
					'problem_details',
					'problem_details_other',
					'digits_code',
					'upc_code',
					'item_description',
					'brand',
					'quantity',
					'comments',
					'customer_location','returns_header_retail.created_at AS CreatedDate')
				->where('returns_header_retail.id',$id)
				->first();
			$this->cbView("returns.request_details", $data);

			// return view("returns.request_details")->with('data', $data);
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
	
			
			if(CRUDBooster::myPrivilegeName() == "Store Personnel"){
				$query->WhereNotNull('returns_status_1')->where('created_by', CRUDBooster::myId())->orderBy('id', 'asc'); 
			}else{
				$query->WhereNotNull('returns_status_1')->orderBy('id', 'asc'); 
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

			if($column_index == 1){
				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_print_crf){
					$column_value = '<span class="label label-warning">'.$to_print_crf.'</span>';
			
				}elseif($column_value == $to_sor){
					$column_value = '<span class="label label-warning">'.$to_sor.'</span>';
			
				}elseif($column_value == $refund_in_process){
					$column_value = '<span class="label label-warning">'.$refund_in_process.'</span>';
			
				}elseif($column_value == $refund_complete){
					$column_value = '<span class="label label-warning">'.$refund_complete.'</span>';
			
				}elseif($column_value == $to_print_return_form){
					$column_value = '<span class="label label-warning">'.$to_print_return_form.'</span>';
			
				}elseif($column_value == $to_ship_back){
					$column_value = '<span class="label label-warning">'.$to_ship_back.'</span>';
			
				}elseif($column_value == $return_invalid){
					$column_value = '<span class="label label-warning">'.$return_invalid.'</span>';
			
				}elseif($column_value == $repair_complete){
					$column_value = '<span class="label label-warning">'.$repair_complete.'</span>';
			
				}elseif($column_value == $for_replacement){
					$column_value = '<span class="label label-warning">'.$for_replacement.'</span>';
			
				}elseif($column_value == $replacement_complete){
					$column_value = '<span class="label label-warning">'.$replacement_complete.'</span>';
			
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
			$postdata = NULL;
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
		public function ReturnRequestProcess(Request $request)
		{
			//**START OF REFERENCE NO**
			$checkExistingRef = DB::table('mps_local.returns_header')->selectRaw('SUBSTR(return_reference_no, 1, 8) AS refno')->orderBy('refno', 'DESC')->first();

			if(!empty($checkExistingRef)){
				$numeric = $checkExistingRef->refno + 1;
			}else{
				$numeric = 0 + 1;
			}

			$i = 0;
			do{
				$numberCode = str_pad($numeric + $i, 8, "0", STR_PAD_LEFT);
				$tracking_number = $numberCode.'R';
				$i++;

			}while( DB::table('mps_local.returns_header_retail')->whereRaw('SUBSTR(return_reference_no, 1, 8) >= '.$numberCode)->exists() );
			//**END OF REFERENCE NO**
			
			if(!empty($request->purchase_location))
			{
				$channel_name = DB::table('channels')->where('id',$request->purchase_location)->first();
			
				$purchase_location = $channel_name->channel_name;

			}else{
				$purchase_location = '';
			}

			$items_included_id = $request->items_included;
			$ItemsIncluded = '';
			foreach ($items_included_id as $keys=>$item_included_id) 
			{
				$Items = DB::table('items_included')->where('id',$item_included_id)->where('status','=','ACTIVE')->first();
				$ItemsIncluded .= $Items->items_description_included .', ';
			}
	
			if(!empty($request->items_included_others)){
			  
				$ItemsIncluded_Others = $request->items_included_others;
			}else{
				$ItemsIncluded_Others = '';
			}

			$header_id = DB::table('returns_header_retail')->insertGetId([
				'returns_status'   		=> '1',
				'returns_status_1'   	=> '18',
				'customer_location'			=> $request->customer_location,
				'return_reference_no'   => $tracking_number,
				'purchase_location'     => $purchase_location,
				'store' 			    => $request->store,
				'customer_last_name'    => $request->customer_last_name,
				'customer_first_name'   => $request->customer_first_name,
				'address'               => $request->address,
				'email_address'         => $request->email_address,
				'contact_no'            => $request->contact_no,
				'order_no'              => $request->order_no,
				'purchase_date'         => $request->purchase_date,
				'mode_of_payment'       => $request->mode_of_payment,
				'items_included'        => substr($ItemsIncluded, 0, -2),
				'items_included_others' => $ItemsIncluded_Others,
				'comments' 				=> $request->comments,
				// 'customer_location' 	=> $request->customer_location,
				'created_by' 			=> CRUDBooster::myId(),
				'created_at'            => date("Y-m-d H:i:s")
			]);

			if(!empty($request->problem_details))
			{
				$problem_details_name = $request->problem_details;
				$problemDetails = '';
				foreach ($problem_details_name as $keys=>$problem_detail_name) 
				{
					$problemDetails .= $problem_detail_name .', ';
				}
			}else{
				$problemDetails = '';
			}

			if(!empty($request->problem_details_other))
			{
				$problemDetailsOther = $request->problem_details_other;
			}else{
				$problemDetailsOther = '';
			}
			
			$body_id = DB::table('returns_body_item_retail')->insertGetId([
				'returns_header_id'   	=> $header_id,
				'digits_code'     		=> $request->digits_code,
				'upc_code' 			    => $request->upc_code,
				'item_description'    	=> $request->item_description,
				'brand'   				=> $request->brand,
				'cost'               	=> $request->cost,
				'quantity'         		=> $request->quantity,
				'problem_details'       => substr($problemDetails, 0, -2),
				'problem_details_other'	=> $problemDetailsOther,
				'created_at'            => date("Y-m-d H:i:s")
			]);

			DB::table('returns_serial_retail')->insert([
				'returns_header_id'   	=> $header_id,
				'returns_body_item_id'  => $body_id,
				'serial_number' 	    => $request->serial_no,
				'created_at'            => date("Y-m-d H:i:s")
			]);

			DB::connection('mysql_front_end')->table('returns_tracking_status')->insert([
				'return_reference_no' => $tracking_number,
				'returns_status'      => 1,
				'created_at'          => date("Y-m-d H:i:s")
			]);

			$details = DB::table('returns_header_retail')
				->leftjoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
				->leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')	
				->select('return_reference_no',
					'purchase_location',
					'store',
					'customer_last_name',
					'customer_first_name',
					'address',
					'email_address',
					'contact_no',
					'order_no',
					'purchase_date',
					'mode_of_payment',
					'serial_number',
					'cost',
					'returns_header_retail.items_included AS ItemsIncluded',
					'returns_header_retail.items_included_others AS ItemsIncludedOthers',
					'problem_details',
					'problem_details_other',
					'digits_code',
					'upc_code',
					'item_description',
					'brand',
					'quantity',
					'comments',
					'customer_location','returns_header_retail.created_at AS CreatedDate')
				->where('returns_header_retail.id',$header_id)
				->first();


				$data = ['reference_number'=>$details->return_reference_no,
						'purchase_location'=>$details->purchase_location,
						'store'=>$details->store,
						'first_name'=>$details->customer_first_name,
						'last_name'=>$details->customer_last_name,
						'address'=>$details->address,
						'email_address'=>$details->email_address,
						'contact_no'=>$details->contact_no,
						'order'=>$details->order_no,
						'purchase_date'=>$details->purchase_date,
						'mode_of_payment'=>$details->mode_of_payment,
						'items_inclued'=>$details->ItemsIncluded,
						'items_included_others'=>$details->ItemsIncludedOthers,
						'customer_location'=>$details->customer_location,
						'digits_code'=>$details->digits_code,
						'upc_code'=>$details->upc_code,
						'item_description'=>$details->item_description,
						'cost'=>$details->cost,
						'brand'=>$details->brand,
						'serial_number'=>$details->serial_number,
						'problem_details'=>$details->problem_details,
						'problem_details_other'=>$details->problem_details_other,
						'quantity'=>$details->quantity];


	   			CRUDBooster::sendEmail(['to'=>$details->email_address,'data'=>$data,'template'=>'request_return','attachments'=>[]]);

			return redirect('admin/retail_return_request');
		}

		public function stores(Request $request)
		{
			if(!empty($request->stores))
			{
				
				$channels = DB::table('stores_frontend')->where('channels_id',	$request->stores)->where('store_status','ACTIVE')->orderBy('store_name', 'ASC')->get();
			}else{
				$channels = DB::table('stores_frontend')->where('store_status','ACTIVE')->orderBy('store_name', 'ASC')->get();
			}
			
			return($channels);
		}

		public function backend_stores(Request $request)
		{
			if(!empty($request->store_backend))
			{
				$customer_location = DB::table('stores')->where('store_name', 'LIKE', '%'.$request->store_backend.'%')->where('store_status','=','ACTIVE')->orderBy('store_name', 'ASC')->get();
			}else{
				$customer_location = DB::table('stores')->orderBy('store_name', 'ASC')->where('store_status','=','ACTIVE')->get();
			}
			
			return($customer_location);
		}



		public function GetExtractReturnsCreated() {

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
				
					if(CRUDBooster::myPrivilegeName() == "Store Personnel"){

						$orderData = DB::table('returns_header_retail')
						->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
						->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
						//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
						->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
						->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
						->select('returns_header_retail.items_included as ItemsIncluded', 
								'returns_header_retail.items_included_others as ItemsIncludedOthers', 
								'returns_header_retail.*', 
								'returns_body_item_retail.*', 
								'returns_body_item_retail.id as body_id', 
								'scheduled.name as scheduled_by',
								//'tagged.name as tagged_by',	
								'diagnosed.name as diagnosed_by',
								'printed.name as printed_by',	
								'transacted.name as transacted_by',	
								'received.name as received_by',
								'closed.name as closed_by',
								'created.name as created_by_personnel',	
								'warranty_statuses.*'
						)->WhereNotNull('returns_body_item_retail.category')->where('created_by', CRUDBooster::myId())->groupby('returns_body_item_retail.digits_code');
					
					}else{

						$orderData = DB::table('returns_header_retail')
						->leftjoin('warranty_statuses', 'returns_header_retail.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as created', 'returns_header_retail.created_by','=', 'created.id')
						->leftjoin('cms_users as scheduled', 'returns_header_retail.level1_personnel','=', 'scheduled.id')			
						//->leftjoin('cms_users as tagged', 'returns_header_retail.level2_personnel','=', 'tagged.id')
						->leftjoin('cms_users as diagnosed', 'returns_header_retail.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_retail.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_retail.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_retail.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as closed', 'returns_header_retail.level5_personnel','=', 'closed.id')
						->leftJoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
						->select('returns_header_retail.items_included as ItemsIncluded', 
								'returns_header_retail.items_included_others as ItemsIncludedOthers', 
								'returns_header_retail.*', 
								'returns_body_item_retail.*', 
								'returns_body_item_retail.id as body_id', 
								'scheduled.name as scheduled_by',
								//'tagged.name as tagged_by',	
								'diagnosed.name as diagnosed_by',
								'printed.name as printed_by',	
								'transacted.name as transacted_by',	
								'received.name as received_by',
								'closed.name as closed_by',
								'created.name as created_by_personnel',	
								'warranty_statuses.*'
						)->WhereNotNull('returns_body_item_retail.category')->WhereNotNull('returns_status_1')->groupby('returns_body_item_retail.digits_code');

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
							$transacted_personnel = $orderRow->transacted_by;
							$transacted_date = 		$orderRow->level4_personnel_edited;
							$closed_personnel = 	$orderRow->closed_by;
							$closed_date = 			$orderRow->level5_personnel_edited;

							$printed_personnel = $orderRow->printed_by;
							$printed_date = $orderRow->level3_personnel_edited;
						}elseif($orderRow->diagnose == "REPLACE"){
							$transacted_personnel = "";
							$transacted_date = 		"";
							$closed_personnel = 	$orderRow->printed_by;
							$closed_date = 			$orderRow->level3_personnel_edited;

							$printed_personnel = "";
							$printed_date = "";
						}else{
							$transacted_personnel = "";
							$transacted_date = "";
							$closed_personnel = $orderRow->transacted_by;
							$closed_date = 		$orderRow->level4_personnel_edited;

							$printed_personnel = $orderRow->printed_by;
							$printed_date = $orderRow->level3_personnel_edited;
						}
						
						
						$orderItems[] = array(
							//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toDateString(),	//'APPROVED DATE',
							//is_null($orderRow->approved_at) ? "" : Carbon::parse($orderRow->approved_at)->toTimeString(), //'APPROVED TIME',
							$orderRow->warranty_status, 				//'REPLENISHMENT REF#',
							$orderRow->created_at,				//'CHANNEL',
							$orderRow->created_by_personnel,				//'CHANNEL',
							$orderRow->return_reference_no,					//'STORE',
							$orderRow->purchase_location,					//'DIGITS CODE',
							$orderRow->store,					//'UPC CODE',
							$orderRow->customer_last_name,			//'ITEM DESCRIPTION',
							$orderRow->customer_first_name,		//'SKU STATUS',
							$orderRow->address,		            //'ORDER LOGIC',
							$orderRow->email_address,         //'WH RESERVABLE QTY'
							$orderRow->contact_no,    //'STORE INVENTORY'
							$orderRow->order_no,		//'ORDERED QTY',
							$orderRow->purchase_date,			//'APPROVED QTY',
							$orderRow->mode_of_payment,			//'REPLENISHMENT QTY',
							$orderRow->bank_name,                      //'DR QTY',
							$orderRow->bank_account_no,                   //'DR #',
							$orderRow->bank_account_name,			//'REORDER QTY',
							$orderRow->ItemsIncluded,                      //'PO QTY',
							$orderRow->ItemsIncludedOthers,                   //'PO #',
				 			$orderRow->return_schedule,                     //MPS QTY       //additional code 20200121
				 			$orderRow->customer_location,                  //MPS #         //additional code 20200121
				 			$orderRow->sor_number,      //INVENTORY COVERAGE QTY        //additional code 20200207
				 			$orderRow->digits_code,                  //FOR ST QTY    //additional code 20200205
				 			$orderRow->upc_code,                 //ACTION PLAN   //additional code 20200205
				 			$orderRow->item_description,            //UNSERVED QTY  //additional code 20200205
				 			$orderRow->cost,           //UNPICKED QTY  //additional code 20200205
							$orderRow->brand,
							$serial_no->serial_number,
							$orderRow->problem_details,
				 			$orderRow->problem_details_other,                 //LINE STATUS   //additional code 20200205
							$orderRow->quantity,
							$orderRow->scheduled_by,
							$orderRow->level1_personnel_edited,
			
							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$printed_personnel,
							$printed_date,
							$transacted_personnel,							
							$transacted_date,
							//$orderRow->received_by,
							//$orderRow->level6_personnel_edited,
							$closed_personnel,
							$closed_date,
							$orderRow->comments,
							$orderRow->diagnose_comments
						);
					}

					$headings = array(
						'RETURN STATUS',
						'CREATED DATE',
						'CREATED BY',
						'RETURN REFERENCE#',
						'PURCHASE LOCATION',
						'STORE',
						'CUSTOMER LAST NAME',
						'CUSTOMER FIRST NAME',
						'ADDRESS',
						'EMAIL ADDRESS',
						'CONTACT#',
						'ORDER#',
						'PURCHASE DATE',
						'MODE OF PAYMENT',
						'BANK NAME',    //yellow
						'BANK ACCOUNT#',      //red
						'BANK ACCOUNT NAME',         //red
						'ITEMS INCLUDED',         //red
						'ITEMS INCLUDED OTHERS',//green
						'PICKUP SCHEDULE',               //green
						'ONLINE CUSTOMER',               //green
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
						'SCHEDULED BY',           //blue  //additional code 20200205
						'SCHEDULED DATE',           //blue  //additional code 20200205
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'TRANSACTED BY',           //blue  //additional code 20200205
						'TRANSACTED DATE',           //blue  //additional code 20200205
						//'RECEIVED BY',           //blue  //additional code 20200205
						//'RECEIVED DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);

					$sheet->fromArray($orderItems, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);

                             
                    $sheet->getStyle('A1:AQ1')->applyFromArray(array(
                        'fill' => array(
                            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '8DB4E2') //141,180,226->8DB4E2
                        )
                    ));
                    $sheet->cells('A1:AQ1'.$final_count, function($cells) {
                    	$cells->setAlignment('left');
                    	
                    });
 
				});
			})->export('xlsx');
		}
	}