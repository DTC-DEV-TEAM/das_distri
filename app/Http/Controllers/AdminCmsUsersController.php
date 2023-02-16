<?php namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Excel;
use CRUDbooster;
use App\Store;
use App\Channel;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function __construct() {
		// Register ENUM type
		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
	}

	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->limit				= "20";
		$this->orderby				= "id_cms_privileges,asc";
		$this->table				= 'cms_users';
		$this->primary_key			= 'id';
		$this->title_field			= "name";
		$this->button_action_style	= 'button_icon';	
		$this->button_import		= FALSE;	
		$this->button_export		= true;
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		if(!CRUDBooster::isSuperadmin()) {
		    $this->col[] = array("label"=>"Name","name"=>"name");
    		$this->col[] = array("label"=>"Email","name"=>"email");
    		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
    		$this->col[] = array("label"=>"Channel","name"=>"channels_id", "join"=>"channels,channel_name");
    		$this->col[] = array("label"=>"Store Name","name"=>"stores_id","join"=>"stores,store_name");
    // 		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
    		$this->col[] = array("label"=>"Status","name"=>"status");
		} else {
    		$this->col[] = array("label"=>"First Name","name"=>"first_name");
    		$this->col[] = array("label"=>"Last Name","name"=>"last_name");
    		$this->col[] = array("label"=>"Email","name"=>"email");
    		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
    		$this->col[] = array("label"=>"Channel","name"=>"channels_id", "join"=>"channels,channel_name");
    		$this->col[] = array("label"=>"Store Name","name"=>"stores_id", "join"=>"stores,store_name");
    		$this->col[] = array("label"=>"Status","name"=>"status");
    // 		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
    		
		}
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"First Name","name"=>"first_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-5');
		$this->form[] = array("label"=>"Last Name","name"=>"last_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-5');
		$this->form[] = array("label"=>"Full Name","name"=>"name", "type"=>"hidden",'required'=>true,'validation'=>'required|min:3','width'=>'col-sm-5','readonly'=>true);
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(), 'width'=>'col-sm-5');		
	    $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed", 'width'=>'col-sm-5');
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90, 'width'=>'col-sm-5');
		
		if(!CRUDBooster::isSuperadmin() && CRUDBooster::myPrivilegeName() == "Admin"){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name","datatable_where"=>"id!=1",'required'=>true, 'width'=>'col-sm-5');				
			//$this->form[] = array("label"=>"Channel","name"=>"channels_id","type"=>"select","datatable"=>"channels,channel_name",'required'=>true, 'width'=>'col-sm-5');
			//$this->form[] = array("label"=>"Store Name","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,store_name",'required'=>true, 'width'=>'col-sm-5', 'parent_select'=>'channels_id');
		}
		else {
		    if(CRUDBooster::isSuperadmin()) {
    			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true, 'width'=>'col-sm-5','help'=>'*Please select a store if Privilege is Requestor.');				
    			$this->form[] = array("label"=>"Channel","name"=>"channels_id","type"=>"select","datatable"=>"channels,channel_name", 'width'=>'col-sm-5','datatable_where'=>"id!='4'");
    			$this->form[] = array("label"=>"Store Name","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,store_name", 'width'=>'col-sm-5','parent_select'=>'channels_id');
		    }
		}


		# END FORM DO NOT REMOVE THIS LINE

		$this->button_selected = array();
        if(CRUDBooster::isUpdate() && CRUDBooster::isSuperadmin())
        {
        	$this->button_selected[] = ['label'=>'Set Login Status OFFLINE ','icon'=>'fa fa-times-circle','name'=>'set_login_status_OFFLINE'];
        	$this->button_selected[] = ['label'=>'Set Status ACTIVE ','icon'=>'fa fa-check-circle','name'=>'set_status_ACTIVE'];
        	$this->button_selected[] = ['label'=>'Set Status INACTIVE ','icon'=>'fa fa-times-circle','name'=>'set_status_INACTIVE'];
        	$this->button_selected[] = ['label'=>'Reset Password ','icon'=>'fa fa-refresh','name'=>'reset_password'];
		}

		$this->table_row_color = array();     	          
	    $this->table_row_color[] = ["condition"=>"[login_status_id] == 1","color"=>"success"];
	    
	    $this->index_button = array();
        if(CRUDBooster::getCurrentMethod() == 'getIndex') {
			if(CRUDBooster::isSuperadmin()){
				$this->index_button[] = [
					"title"=>"Upload User Accounts",
					"label"=>"Upload User Accounts",
					"icon"=>"fa fa-download",
					"url"=>CRUDBooster::mainpath('useraccount-upload')];
			}
		}

		$this->script_js = NULL;
		$this->script_js = "
		
		if($('#id_cms_privileges').val() == 13 || $('#id_cms_privileges').val() == 12  || $('#id_cms_privileges').val() == 10 || $('#id_cms_privileges').val() == 15  ){
		    $('#form-group-stores_id').show();
		    $('#form-group-channels_id').show();
		    
		}else{

		    $('#form-group-stores_id').hide();
		    $('#form-group-channels_id').hide();
		}
		        
		$('#id_cms_privileges').change(function(){
            if($('#id_cms_privileges').val() != null){
                var items = $(this).val();
            }else{
                var items = '';
             }
            if(items.includes('13') || items.includes('12')  || items.includes('15')) {
     
              $('#form-group-stores_id').show();
              $('#form-group-channels_id').show();
              
            }else{

               $('#form-group-stores_id').hide();
               $('#form-group-channels_id').hide();
            }
        });
        

        
		$(document).ready(function() {
			var offset = 250;
			var duration = 300;
			 
			jQuery(window).scroll(function() {
				if (jQuery(this).scrollTop() > offset) {
					jQuery('.back-to-top').fadeIn(duration);
				} 
				else {
					jQuery('.back-to-top').fadeOut(duration);
				}
			});
			 
			jQuery('.back-to-top').click(function(event) {
			 
				event.preventDefault();
				jQuery('html, body').animate({scrollTop: 0}, duration);
				return false;
			 
			});

			$('#alerts_msg').fadeTo(1500, 500).slideUp(500, function(){
			    $('#alerts_msg').slideUp(1500);
			});
		});
		";		
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());		
		$this->cbView('crudbooster::default.form',$data);				
	}

	public function hook_row_index($column_index,&$column_value) {	        
		//Your code here
		
		if(!CRUDBooster::isSuperadmin() && $column_index == 5){
			$storeLists = self::storeListing($column_value);
			
			foreach ($storeLists as $value) {
				$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
			}
			$column_value = $col_values;
		}
		elseif(CRUDBooster::isSuperadmin() && $column_index == 6){
			$storeLists = self::storeListing($column_value);
			
			foreach ($storeLists as $value) {
				$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
			}
			$column_value = $col_values;
		}
	}

	public function hook_before_add(&$postdata) {        
	    //Your code here
		$postdata['created_by']=CRUDBooster::myId();

	    if($postdata['photo'] == '' || $postdata['photo'] == NULL) {
	    	$postdata['photo'] = 'uploads/mrs-avatar.png';
	    }
		$postdata['status'] = 'ACTIVE';
		$postdata['name'] = $postdata['first_name'].' '.$postdata['last_name'];
		$postdata['user_name'] = $postdata['last_name'].''.substr($postdata['first_name'], 0, 1);

		$storeData = array();
		$storeList = json_encode($postdata['stores_id'], true);
		$storeArray = explode(",", $storeList);

		foreach ($storeArray as $key => $value) {
			$storeData[$key] = preg_replace("/[^0-9]/","",$value);
		}

		$postdata['stores_id'] = implode(",", $storeData);
	}

	public function hook_before_edit(&$postdata,$id) {        
        //Your code here
        if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Admin") {
            $postdata['name'] = $postdata['first_name'].' '.$postdata['last_name'];
    		$postdata['user_name'] = $postdata['last_name'].''.substr($postdata['first_name'], 0, 1);
        
    
        	/*if(!is_null($postdata['password'])){
    
        		DB::connection('mysql_timfs')->table('cms_users')
        		->where('email',$postdata['email'])
        		->update(
        			[
        				'password' => $postdata['password']
        			]);
    		}*/
    		
    		$storeData = array();
    		$storeList = json_encode($postdata['stores_id'], true);
    		$storeArray = explode(",", $storeList);
    
    		foreach ($storeArray as $key => $value) {
    			$storeData[$key] = preg_replace("/[^0-9]/","",$value);
    		}
    
    		$postdata['stores_id'] = implode(",", $storeData);
        }
    	$postdata['updated_by']=CRUDBooster::myId();
    	$postdata['id']=$id;
    }

    public function hook_after_delete($id) {
		//Your code here
		DB::table('cms_users')->where('id', $id)->update(['status' => 'INACTIVE']);
	}

    public function hook_query_index(&$query) {
        //Your code here
        if(!CRUDBooster::isSuperadmin()) {
        	if(CRUDBooster::myPrivilegeName() == 'Admin'){
        		$query->where('cms_users.id_cms_privileges','!=','1');
        	}
        	else{
        		$query->where('cms_users.id',"'".CRUDBooster::myId()."'");
        	}
        }    
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
        if($button_name == 'set_login_status_OFFLINE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['login_status_id'=>'2']);	
		}
		if($button_name == 'set_status_INACTIVE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['status'=>'INACTIVE']);	
		}
		if($button_name == 'set_status_ACTIVE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['status'=>'ACTIVE']);	
		}
		if($button_name == 'reset_password') {
			DB::beginTransaction();
		    DB::table('cms_users')->whereIn('id',$id_selected)->update([
		    	'password'			=> bcrypt('qwerty'),
		    	'reset_password'	=> 1	
		    ]);
		    DB::commit();	
		}  
    }

    public function showChangePasswordForm(){
    	if(CRUDBooster::myId()){
    		$array_data['data'] = "Reset Password";
    		return view('changepassword',$array_data);
    	}
        else{
        	return view('crudbooster::login');
        }
    }

    public function changePassword(Request $request){

 		$users = DB::table('cms_users')->where('id',CRUDBooster::myId())->first();

        if (!(\Hash::check($request->input('current-password'), $users->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
 
        if(strcmp($request->input('current-password'), $request->input('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Your new password cannot be same as your current password. Please choose a different password.");
        }

        /*
        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
		*/
        
        \Validator::make($request->all(), [
		    'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
		])->validate();
 		
        //Change Password
        try{
	        DB::beginTransaction();
		    DB::table('cms_users')->where('id',CRUDBooster::myId())->update([
		    	'password'			=> bcrypt($request->input('new-password')),
		    	'reset_password'	=> 0	
		    ]);
		    DB::commit();
		   /* Transaction successful. */
	    }
	    catch (\Exception $error_msg){
	        $error_code = $error_msg->errorInfo[1];
	        DB::rollback();
	    }
 
        return redirect()->back()->with("success","Your password has been changed successfully !");
 
	}

	public function storeListing($ids) {
		$stores = explode(",", $ids);
		return Store::whereIn('id', $stores)->pluck('store_name');
	}
	
	public function uploadUserAccountTemplate() {
		Excel::create('user-account-upload-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
			$excel->sheet('useraccount', function ($sheet) {
				$sheet->row(1, array('FIRST NAME', 'LAST NAME', 'EMAIL', 'PRIVILEGE', 'CHANNEL', 'STORES ID'));
				$sheet->row(2, array('John', 'Doe', 'johndoe@digits.ph','Requestor','Retail','1'));
			});
		})->download('csv');
	}

	public function uploadUserAccount() {
	    if(!CRUDBooster::isSuperadmin()) {    
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		$data['page_title']= 'User Account Upload';
		return view('user-account.user_account_upload', $data)->render();
	}

	public function userAccountUpload(Request $request) {
			$file = $request->file('import_file');
			
			$validator = \Validator::make(
				[
					'file' => $file,
					'extension' => strtolower($file->getClientOriginalExtension()),
				],
				[
					'file' => 'required',
					'extension' => 'required|in:csv',
				]
			);

			if ($validator->fails()) {
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_user_data_failed"), 'danger');
			}

			if (Input::hasFile('import_file')) {
				$path = Input::file('import_file')->getRealPath();
				
				$csv = array_map('str_getcsv', file($path));
				$dataExcel = Excel::load($path, function($reader) {
                })->get();
				
				$unMatch = [];
				$header = array('FIRST NAME', 'LAST NAME', 'EMAIL', 'PRIVILEGE', 'CHANNEL', 'STORES ID');

				for ($i=0; $i < sizeof($csv[0]); $i++) {
					if (! in_array($csv[0][$i], $header)) {
						$unMatch[] = $csv[0][$i];
					}
				}

				if(!empty($unMatch)) {
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_user_data_failed"), 'danger');
				}
				
				$data = array();
				// DB::beginTransaction();

				// try {
				// 	//OrderLogicMatrix::destroy();
				// 	DB::commit();
				// } catch (\Exception $e) {
				// 	DB::rollback();
				// }
				
				if(!empty($dataExcel) && $dataExcel->count()) {
					$cnt_success = 0;
					$cnt_fail = 0;
					
					foreach ($dataExcel as $key => $value) {
						$check_upload = false;
						$privilegeId = DB::table('cms_privileges')->where('name', $value->privilege)->value('id');
						$channelId = Channel::where('channel_name', $value->channel)->value('id');
						if($privilegeId != 1){
    						$data = [
    						    'first_name'    =>  $value->first_name,
    						    'last_name' =>  $value->last_name,
    						    'name'  =>  $value->first_name. ' '.$value->last_name,
    						    'user_name' =>  $value->last_name.''.substr($value->first_name, 0, 1),
    						    'channels_id'   => $channelId,
    						    'stores_id' =>  $value->stores_id,
    						    'photo' => 'uploads/1/2019-05/businessman.png',
    						    'email' => $value->email,
    						    'password' => bcrypt('qwerty'),
    						    'id_cms_privileges' => $privilegeId,
    							'status'    => 'ACTIVE',
    							'created_by'    => CRUDBooster::myId(),
    							'created_at'    => date('Y-m-d H:i:s'),
    						];
						}

						DB::beginTransaction();

						try {
							$isItemUpload = DB::table('cms_users')->insert($data);
							DB::commit();
						} catch (\Exception $e) {
							DB::rollback();
						}

						if ($isItemUpload) {
							$check_upload = true;
							$cnt_success++;
                        }
                        else{
							$check_upload = false;
							$cnt_fail++;
                        }
					}

					if($check_upload){
                        CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_user_success", ['total_row'=>count($dataExcel),'success'=>$cnt_success,'fail'=>$cnt_fail]), 'success');
                    }
                    else{
                        CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_user_success", ['total_row'=>count($dataExcel),'success'=>$cnt_success,'fail'=>$cnt_fail]), 'success');
                    }
				}
			}
		}
	
}
