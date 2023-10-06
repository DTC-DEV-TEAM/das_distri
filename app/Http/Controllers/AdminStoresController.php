<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Excel;
	use App\StoreType;
	use App\Store;

	class AdminStoresController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			// Register ENUM type
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "store_name";
			$this->limit = "20";
			$this->orderby = "store_name,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = true;
			$this->button_export = true;
			$this->table = "stores";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Purchase Location","name"=>"channels_id","join"=>"channels,channel_name"];
			$this->col[] = ["label"=>"Store Name","name"=>"stores_frontend_id","join"=>"stores_frontend,store_name"];
			$this->col[] = ["label"=>"Store Drop-Off","name"=>"store_dropoff_privilege"];
			$this->col[] = ["label"=>"Branch Name","name"=>"branch_id"];
			$this->col[] = ["label"=>"Customer Location","name"=>"store_name"];
			$this->col[] = ["label"=>"Customer Status","name"=>"store_status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Purchase Location','name'=>'channels_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'channels,channel_name','datatable_where'=>"channel_status='ACTIVE'"];
			
			$this->form[] = ['label'=>'Store Name','name'=>'stores_frontend_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'stores_frontend,store_name','parent_select'=>'channels_id'];
			
			$this->form[] = ['label'=>'Store Drop-Off','name'=>'store_dropoff_privilege','type'=>'select','width'=>'col-sm-5','dataenum'=>'YES;NO'];

			$this->form[] = ['label'=>'Branch Name','name'=>'branch_id','type'=>'text','validation'=>'min:1|max:150','width'=>'col-sm-5'];
			
			$this->form[] = ['label'=>'Customer Location','name'=>'store_name','type'=>'text','validation'=>'required|min:1|max:150','width'=>'col-sm-5'];
			
			$this->form[] = ['label'=>'State/Region','name'=>'region','type'=>'select','validation'=>'required','width'=>'col-sm-5','datatable'=>'states,state_name','datatable_where'=>"status='ACTIVE'"];
			if(CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave' || CRUDBooster::getCurrentMethod() == 'getDetail') {
				$this->form[] = ['label'=>'Customer Status','name'=>'store_status','type'=>'select','validation'=>'required','width'=>'col-sm-5','dataenum'=>'ACTIVE;INACTIVE'];
			}
			if(CRUDBooster::getCurrentMethod() == 'getDetail'){
				$this->form[] = ["label"=>"Created By","name"=>"created_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Created Date','name'=>'created_at', 'type'=>'date'];
				$this->form[] = ["label"=>"Updated By","name"=>"updated_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Updated Date','name'=>'updated_at', 'type'=>'date'];
			}
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
            if(CRUDBooster::isUpdate() && CRUDBooster::isSuperadmin())
	        {
	        	$this->button_selected[] = ['label'=>'Set Store Type GOOD','icon'=>'fa fa-check-circle','name'=>'set_store_type_GOOD'];
				$this->button_selected[] = ['label'=>'Set Store Type RMA','icon'=>'fa fa-check-circle','name'=>'set_store_type_RMA'];
				$this->button_selected[] = ['label'=>'Set Store Type MARKETING','icon'=>'fa fa-check-circle','name'=>'set_store_type_MARKETING'];
				
	        }
	                
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
			$this->script_js = "
				$('#alerts_msg').fadeTo(1500, 500).slideUp(500, function(){
					$('#alerts_msg').slideUp(500);
				});
			";

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
	        if($button_name == 'set_store_type_GOOD') {
				$store_type_id = StoreType::where('store_type','GOOD')->value('id');
				DB::table('stores')->whereIn('id',$id_selected)->update(['store_types_id'=>$store_type_id, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => CRUDBooster::myId()]);
				
			}
			else if($button_name == 'set_store_type_RMA') {
				$store_type_id = StoreType::where('store_type','RMA')->value('id');
				DB::table('stores')->whereIn('id',$id_selected)->update(['store_types_id'=>$store_type_id, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => CRUDBooster::myId()]);
				
			}
			else if($button_name == 'set_store_type_MARKETING') {
				$store_type_id = StoreType::where('store_type','RMA')->value('id');
				DB::table('stores')->whereIn('id',$id_selected)->update(['store_types_id'=>$store_type_id, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => CRUDBooster::myId()]);
				
			}    
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
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
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
			//$postdata['groups_id']=$postdata['channels_id'];
			$postdata['created_by']=CRUDBooster::myId();


			DB::connection('mysql_front_end')
			->statement('insert into stores_backend (channels_id, stores_frontend_id, store_dropoff_privilege, branch_id, store_name, region, created_by, created_at) values (?, ?, ?, ?, ?, ?, ?,?)', 
			[	$postdata['channels_id'], 
				$postdata['stores_frontend_id'],
				$postdata['store_dropoff_privilege'],
				$postdata['branch_id'],
				$postdata['store_name'],
				$postdata['region'],
			 	CRUDBooster::myId(),
				date('Y-m-d H:i:s')
			]);

			DB::disconnect('mysql_front_end');
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
			
			$postdata['updated_by']=CRUDBooster::myId();

			$a = $postdata['channels_id'];
			$b = $postdata['stores_frontend_id'];
			$c = $postdata['branch_id'];
			$d = $postdata['store_name'];
			$e = $postdata['store_status'];
			$f = CRUDBooster::myId();
			$g = date('Y-m-d H:i:s');
			$i = $postdata['region'];
		
			
			if($postdata['store_dropoff_privilege'] == "NO"){
			     $h = "";
			}else{
			    $h = $postdata['store_dropoff_privilege'];
			}

		

			DB::connection('mysql_front_end')
			->statement(" update stores_backend set channels_id = '$a', stores_frontend_id = '$b', store_dropoff_privilege = '$h', branch_id = '$c', store_name = '$d', store_status = '$e',updated_by = '$f', updated_at = '$g', region = '$i' WHERE  id = '$id' ");

			DB::disconnect('mysql_front_end');
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

		public function importTemplate() {
			Excel::create('stores-import-submaster-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
				$excel->sheet('stores', function ($sheet) {
					$sheet->row(1, array('Channel', 'Store Type','Store Name','POS Name','Bill To','Store Location','Status'));
				});
			})->download('csv');
		}

	}