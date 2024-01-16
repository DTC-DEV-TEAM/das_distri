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
use App\ShipBackStatus;
use App\ClaimedStatus;
use App\StoresFrontEnd;

	class AdminDistriReturnHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct() {
			// Register ENUM type
			//$this->request = $request;
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "customer_last_name";
			$this->limit = "20";
			$this->orderby = "history_status,desc";
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
			$this->col[] = ["label"=>"Brand","name"=>"id","join"=>"returns_body_item_distribution,brand","join_id"=>"returns_header_id"];
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
			if(CRUDBooster::myPrivilegeName() == "Distri Logistics" || CRUDBooster::myPrivilegeName() == "Logistics"){
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
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
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else if(CRUDBooster::myPrivilegeName() == "Distri RMA"){
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
				//$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				//$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				//$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				//$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				//$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Ship Back Status","name"=>"ship_back_status" ,"join"=>"status_ship_back,ship_back_status_name"];
				$this->col[] = ["label"=>"Claimed Status","name"=>"claimed_status" ,"join"=>"status_claimed,claimed_status_name"];
				$this->col[] = ["label"=>"Credit Memo#","name"=>"credit_memo_number"];

				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Service Center"){ 
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Return Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
				//$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				//$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				//$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				//$this->col[] = ["label"=>"Store","name"=>"store"];
				//$this->col[] = ["label"=>"Branch","name"=>"branch"];
				$this->col[] = ["label"=>"Store Drop-Off","name"=>"store_dropoff"];
				$this->col[] = ["label"=>"Branch Drop-Off","name"=>"branch_dropoff"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Warranty Status","name"=>"warranty_status"];
				//$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				//$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				//$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				//$this->col[] = ["label"=>"Ship Back Status","name"=>"ship_back_status" ,"join"=>"status_ship_back,ship_back_status_name"];
				//$this->col[] = ["label"=>"Claimed Status","name"=>"claimed_status" ,"join"=>"status_claimed,claimed_status_name"];
				//$this->col[] = ["label"=>"Credit Memo#","name"=>"credit_memo_number"];

				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else if(CRUDBooster::myPrivilegeName() == "Accounting" || CRUDBooster::myPrivilegeName() == "Inventory Control" ){
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else if(CRUDBooster::myPrivilegeName() == "Aftersales (Ops)"){
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else if(CRUDBooster::myPrivilegeName() == "SDM"){
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				//$this->col[] = ["label"=>"SOR#","name"=>"sor_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}elseif(CRUDBooster::myPrivilegeName() == "Distri Ops"  || CRUDBooster::myPrivilegeName() == "Distri Store Ops" ){ 
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				//$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"Customer Customer","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}else{
				$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
				$this->col[] = ["label"=>"Pickup Schedule","name"=>"return_schedule"];
				$this->col[] = ["label"=>"INC#","name"=>"inc_number"];
				$this->col[] = ["label"=>"RMA#","name"=>"rma_number"];
				$this->col[] = ["label"=>"Order#","name"=>"order_no"];
				$this->col[] = ["label"=>"DR#","name"=>"dr_number"];
				$this->col[] = ["label"=>"Customer Location","name"=>"customer_location"];
				$this->col[] = ["label"=>"Purchase Location","name"=>"purchase_location"];
				$this->col[] = ["label"=>"Store","name"=>"store"];
				$this->col[] = ["label"=>"Mode of Return","name"=>"mode_of_return"];
				$this->col[] = ["label"=>"Action Plan","name"=>"diagnose"];
				$this->col[] = ["label"=>"Customer Last Name","name"=>"customer_last_name"];
				$this->col[] = ["label"=>"Customer First Name","name"=>"customer_first_name"];
				$this->col[] = ["label"=>"Mode Of Payment","name"=>"mode_of_payment"];
				$this->col[] = ["label"=>"Diagnose","name"=>"diagnose","visible"=>false];
				$this->col[] = ["label"=>"Level3 Personnel","name"=>"level3_personnel","visible"=>false];
			}
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
			$this->form[] = ['label'=>'Received By Sc','name'=>'received_by_rma_sc','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Received At Sc','name'=>'received_at_rma_sc','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
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
			$cancelled = 	ReturnsStatus::where('id','28')->value('id');

			$refund_complete = 			ReturnsStatus::where('id','11')->value('id');
			$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
			$repair_complete = 			ReturnsStatus::where('id','17')->value('id');
			$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');	
			if(CRUDBooster::myPrivilegeName() == "RMA"){
			$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('ReturnsHistoryEdit/[id]'),'color'=>'none','icon'=>'fa fa-pencil', "showIf"=>"[returns_status_1] == $refund_complete or [returns_status_1] == $return_invalid or [returns_status_1] == $repair_complete or [returns_status_1] == $replacement_complete"];
			}
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('ReturnsHistoryDetailDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-eye'];
			$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('ReturnsPulloutPrint/[id]'),'color'=>'none','icon'=>'fa fa-print', "showIf"=>"[returns_status_1] != $cancelled"];
			$this->addaction[] = ['title'=>'CRF','url'=>CRUDBooster::mainpath('ReturnsCRFPrintDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-file', "showIf"=>"[diagnose] == 'REFUND' and [level2_personnel] != null"];
			$this->addaction[] = ['title'=>'RF','url'=>CRUDBooster::mainpath('ReturnsReturnFormPrintDISTRI/[id]'),'color'=>'none','icon'=>'fa fa-file', "showIf"=>"[diagnose] == 'REJECT' and [level2_personnel] != null or [diagnose] == 'REPAIR' and [level2_personnel] != null"];


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
			if(CRUDBooster::isUpdate())
	        {
				if(CRUDBooster::myPrivilegeName() == "Admin Ops" ||  CRUDBooster::isSuperadmin()){ 
					$this->button_selected[] = ['label'=>'Void',
												'icon'=>'fa fa-times-circle',
												'name'=>'void'];
				}else{
				    if(CRUDBooster::myName() == "Joelan Delota" ||
				       CRUDBooster::myName() == "Jan Franz Josef Sevilla"){
				        						$this->button_selected[] = ['label'=>'Void',
													'icon'=>'fa fa-times-circle',
													'name'=>'void'];
				    }
				}

	        }
	                
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["title"=>"Export Returns",
				"label"=>"Export Returns",
				"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturnsDISTRI').'?'.urldecode(http_build_query(@$_GET))];
				//$this->index_button[] = ["label"=>"Export Returns","icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('GetExtractReturns'),"color"=>"success"];

				//$this->index_button[] = ['label' => 'Upload', "url" => CRUDBooster::mainpath("import-retail"), "icon" => "fa fa-upload", "color"=>"warning"];
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
			if($button_name == 'void') {
				
				DB::table('returns_header_distribution')->whereIn('id',$id_selected)->update([
					'returns_status_1'=> 28,
					'returns_status'=> 28
				]);
					

			

				DB::connection('mysql_front_end')
				->statement("update returns_header_retail set returns_status_1 = 28,
								returns_status = 28
								where id  in  ('$id_selected')");
	
				DB::disconnect('mysql_front_end');

			
				
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
			$query->leftJoin('distri_last_comments', 'distri_last_comments.returns_header_distri_id', 'returns_header_distribution.id')
			->leftJoin('chat_distri', 'chat_distri.id', 'distri_last_comments.chats_id')
			->leftJoin('cms_users as sender', 'sender.id', 'chat_distri.created_by')
			->addSelect('chat_distri.message as last_message',
				'chat_distri.file_name as last_image',
				'sender.name as sender_name',
				'chat_distri.created_at as date_send'
			);

			$query->whereNotNull('returns_body_item_distribution.category');

			$query->whereNotNull('returns_status_1')->orderBy('history_status', 'desc'); 
			
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
			$to_create_crf = 			ReturnsStatus::where('id','25')->value('warranty_status');
			$for_replacement_sdm = 	  	ReturnsStatus::where('id','26')->value('warranty_status');
			$cancelled = 	  			ReturnsStatus::where('id','28')->value('warranty_status');
			$to_close = 				ReturnsStatus::where('id','30')->value('warranty_status');
            $to_print_pf = 				ReturnsStatus::where('id','19')->value('warranty_status');
            $received = 	            ReturnsStatus::where('id','31')->value('warranty_status');
            $to_receive_rma = 			ReturnsStatus::where('id','34')->value('warranty_status');
			$to_receive_sc = 			ReturnsStatus::where('id','35')->value('warranty_status');
            $to_receive  = 	            ReturnsStatus::where('id','29')->value('warranty_status');    
            $return_delivery_date =     ReturnsStatus::where('id','33')->value('warranty_status');
			$to_schedule_logistics = 	ReturnsStatus::where('id','23')->value('warranty_status');
			$to_turnover = 				ReturnsStatus::where('id','37')->value('warranty_status');
			$to_for_action = 			ReturnsStatus::where('id','38')->value('warranty_status');
			$to_assign_inc = 			ReturnsStatus::where('id','39')->value('warranty_status');
			$to_ongoing_testing = 		ReturnsStatus::where('id','40')->value('warranty_status');

			if($column_index == 3){
				if($column_value == $to_schedule){
					$column_value = '<span class="label label-warning">'.$to_schedule.'</span>';
			
				}elseif($column_value == $pending){
					$column_value = '<span class="label label-warning">'.$pending.'</span>';
			
				}elseif($column_value == $to_diagnose){
					$column_value = '<span class="label label-warning">'.$to_diagnose.'</span>';
			
				}elseif($column_value == $to_print_crf){
					$column_value = '<span class="label label-warning">'.$to_print_crf.'</span>';
			
				}elseif($column_value == $to_print_pf){
					$column_value = '<span class="label label-warning">'.$to_print_pf.'</span>';
			
				}elseif($column_value == $to_sor){
					$column_value = '<span class="label label-warning">'.$to_sor.'</span>';
			
				}elseif($column_value == $refund_in_process){
					$column_value = '<span class="label label-warning">'.$refund_in_process.'</span>';
			
				}elseif($column_value == $refund_complete){
					$column_value = '<span class="label label-success">'.$refund_complete.'</span>';
			
				}elseif($column_value == $to_print_return_form){
					$column_value = '<span class="label label-warning">'.$to_print_return_form.'</span>';
			
				}elseif($column_value == $to_ship_back){
					$column_value = '<span class="label label-warning">'.$to_ship_back.'</span>';
			
				}elseif($column_value == $return_invalid){
					$column_value = '<span class="label label-success">'.$return_invalid.'</span>';
			
				}elseif($column_value == $repair_complete){
					$column_value = '<span class="label label-success">'.$repair_complete.'</span>';
			
				}elseif($column_value == $for_replacement){
					$column_value = '<span class="label label-warning">'.$for_replacement.'</span>';
			
				}elseif($column_value == $replacement_complete){
					$column_value = '<span class="label label-success">'.$replacement_complete.'</span>';
			
				}elseif($column_value == $to_create_crf){
					$column_value = '<span class="label label-warning">'.$to_create_crf.'</span>';
			
				}elseif($column_value == $for_replacement_sdm){
					$column_value = '<span class="label label-warning">'.$for_replacement_sdm.'</span>';
			
				}elseif($column_value == $cancelled){
					$column_value = '<span class="label label-danger">'.$cancelled.'</span>';
			
				}elseif($column_value == $to_close){
					$column_value = '<span class="label label-warning">'.$to_close.'</span>';
			
				}elseif($column_value == $requested){
					$column_value = '<span class="label label-warning">'.$requested.'</span>';
			
				}elseif($column_value == $received){
					$column_value = '<span class="label label-success">'.$received.'</span>';
			
				}elseif($column_value == $to_receive){
					$column_value = '<span class="label label-warning">'.$to_receive.'</span>';
			
				}elseif($column_value == $return_delivery_date){
					$column_value = '<span class="label label-warning">'.$return_delivery_date.'</span>';
					
				}elseif($column_value == $to_receive_rma){
					$column_value = '<span class="label label-warning">'.$to_receive_rma.'</span>';
					
				}elseif($column_value == $to_receive_scq){
					$column_value = '<span class="label label-warning">'.$to_receive_sc .'</span>';
					
				}elseif($column_value == $to_schedule_logistics){
					$column_value = '<span class="label label-warning">'.$to_schedule_logistics.'</span>';
			
				}elseif($column_value == $to_turnover){
					$column_value = '<span class="label label-warning">'.$to_turnover.'</span>';
			
				}elseif($column_value == $to_for_action){
					$column_value = '<span class="label label-warning">'.$to_for_action.'</span>';
			
				}elseif($column_value == $to_assign_inc){
					$column_value = '<span class="label label-warning">'.$to_assign_inc.'</span>';

				}elseif($column_value == $to_ongoing_testing){
					$column_value = '<span class="label label-warning">'.$to_ongoing_testing.'</span>';
					
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
			$field_1 		= $returns_fields['ship_back_status'];
			$field_2 		= $returns_fields['claimed_status'];
			$field_3 		= $returns_fields['credit_memo_number'];

			$postdata['ship_back_status'] = 	$field_1;
			$postdata['claimed_status'] = 		$field_2;
			$postdata['credit_memo_number'] = 	$field_3;
			$postdata['rma_edited_by'] = 		CRUDBooster::myId();
			$postdata['rma_edited_at']=			date('Y-m-d H:i:s');

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
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("The return request has been edited successfully!"), 'success');
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
		public function ReturnsHistoryDetailDISTRI($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
				->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level5_personnel','=', 'closed.id')																		
				->select('returns_header_distribution.*','scheduled.name as scheduled_by',
						'diagnosed.name as diagnosed_by','printed.name as printed_by',	
						'transacted.name as transacted_by',	'received.name as received_by',
						'closed.name as closed_by','created.name as created_by')
				->where('returns_header_distribution.id',$id)
				->first();

            if($data['row']->returns_status_1 == 1){
				$data['resultlist'] = ReturnsBodyDISTRI::
					leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
					->select('returns_body_item_distribution.*','returns_serial_distribution.*')
					->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
					->whereNull('returns_body_item_distribution.category')
					->get();                
            }else{
				$data['resultlist'] = ReturnsBodyDISTRI::
					leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
					->select('returns_body_item_distribution.*','returns_serial_distribution.*')
					->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
					->whereNotNull('returns_body_item_distribution.category')
					->get();
            }
            
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			$data['comments_data'] = (new ChatController)->getCommentsDistri($id);
			$this->cbView("returns.history_view_distri", $data);

		}


		public function ReturnsPulloutPrint($id)
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
			->select('returns_body_item_distribution.*','returns_serial_distribution.*')
			->where('returns_body_item_distribution.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_distribution.category')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
						
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )
			->orWhere('channels_id', 7 )->first();
			
			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();
			
			$this->cbView("returns.print_pullout", $data);
		}
		


		public function ReturnsCRFPrintDISTRI($id)
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
			->select('returns_body_item_distribution.*','returns_serial_distribution.*'					)
			->where('returns_body_item_distribution.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_distribution.category')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$this->cbView("returns.print_crf_retail", $data);
		}

		public function ReturnsReturnFormPrintDISTRI($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data = array();
			$data['page_title'] = 'Returns For Closing';
		
			$data['row'] = ReturnsHeaderDISTRI::
			leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
			->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
			->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
			->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
			->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
			->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
			->leftjoin('cms_users as closed', 'returns_header_distribution.level7_personnel','=', 'closed.id')																		
			->select('returns_header_distribution.*','scheduled.name as scheduled_by',
			'diagnosed.name as diagnosed_by','printed.name as printed_by',	
			'transacted.name as transacted_by',	'received.name as received_by',
			'closed.name as closed_by','created.name as created_by')
			->where('returns_header_distribution.id',$id)->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
			leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
			->select('returns_body_item_distribution.*','returns_serial_distribution.*')
			->where('returns_body_item_distribution.returns_header_id',$data['row']->id)->whereNotNull('returns_body_item_distribution.category')->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			
			$store_id = 	StoresFrontEnd::where('store_name', $data['row']->store_dropoff )->where('channels_id', 6 )
			->orWhere('channels_id', 7 )->first();

			$data['store_deliver_to'] = Stores::where('branch_id',  $data['row']->branch_dropoff )->where('stores_frontend_id',  $store_id->id )->first();

			$this->cbView("returns.print_return_form_retail", $data);
		}


		public function GetExtractReturnsDISTRI() {

            $filename = 'Returns Distribution - ' . date("d M Y - h.i.sa");
			$sheetname = 'Returns Distribution'.date("d-M-Y");
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


					if(CRUDBooster::myPrivilegeName() == "Retail Ops"){
						$requested = ReturnsStatus::where('id','1')->value('id');

						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*')
						->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested);
						
						
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

							// $orderRow->diagnosed_by,
							// $orderRow->level2_personnel_edited,
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					}elseif(CRUDBooster::myPrivilegeName() == "Distri Store Ops"){
						$requested = ReturnsStatus::where('id','1')->value('id');
						
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
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)
						->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested)->whereIn('returns_header_distribution.stores_id', $storeList);
						
						
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
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					}else if(CRUDBooster::myPrivilegeName() == "Logistics"){
						$requested = ReturnsStatus::where('id','1')->value('id');
						$to_schedule = 	ReturnsStatus::where('id','18')->value('id');
					
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $requested)->where('returns_status_1','!=', $to_schedule);				
					
					    
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
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					    
					}else if(in_array(CRUDBooster::myPrivilegeName(), ['RMA Inbound', 'Tech Lead', 'RMA Technician', 'RMA Specialist'])){
						$to_diagnose = ReturnsStatus::where('id','5')->value('id');
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type', 0)->whereNotNull('diagnose');					
				
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
							$orderRow->inc_number,			
							$orderRow->rma_number,	
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
							$orderRow->warranty_status,
							$orderRow->ship_back_status,
							$orderRow->claimed_status,
							$orderRow->credit_memo_number,
							$verified,
							$verified_date,
							$scheduled_by,
							$scheduled_date,
							
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'INC#',
						'RMA#',
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
						'WARRANTY STATUS',
						'SHIP BACK STATUS',           //blue  //additional code 20200205
						'CLAIMED STATUS',           //blue  //additional code 20200205
						'CREDIT MEMO#',           //blue  //additional code 20200205
						'VERIFIED BY',           //blue  //additional code 20200205
						'VERIFIED DATE',           //blue  //additional code 20200205
						'SCHEDULED BY',           //blue  //additional code 20200205
						'SCHEDULED DATE',           //blue  //additional code 20200205
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
				
					}else if(CRUDBooster::myPrivilegeName() == "Service Center"){
						$to_diagnose = ReturnsStatus::where('id','5')->value('id');
						$requested = ReturnsStatus::where('id','1')->value('id');
						
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
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type', 1)->where('returns_status_1','!=', $to_diagnose)->whereNotNull('diagnose')->whereIn('returns_header_distribution.stores_id', $storeList)
						->orwhereNotNull('returns_body_item_distribution.category')->where('transaction_type', 3)->where('returns_status_1','!=', $to_diagnose)->whereNotNull('diagnose')->whereIn('returns_header_distribution.stores_id', $storeList);	
						;					
						
						
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
							$orderRow->warranty_status,
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
						'WARRANTY STATUS',
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
					}else if(CRUDBooster::myPrivilegeName() == "Accounting"){
						$to_print_crf = 	ReturnsStatus::where('id','7')->value('id');
						$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1','!=', $to_print_crf)->where('diagnose', "REFUND")
						;			
						
						
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
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					}else if(CRUDBooster::myPrivilegeName() == "Inventory Control"){
		                $replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('returns_status_1', $replacement_complete)->where('diagnose', "REPLACE")
						;	
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
							$orderRow->warranty_status,
							$orderRow->ship_back_status,
							$orderRow->claimed_status,
							$orderRow->credit_memo_number,
							$verified,
							$verified_date,
							$scheduled_by,
							$scheduled_date,
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'WARRANTY STATUS',
						'SHIP BACK STATUS',           //blue  //additional code 20200205
						'CLAIMED STATUS',           //blue  //additional code 20200205
						'CREDIT MEMO#',           //blue  //additional code 20200205
						'VERIFIED BY',           //blue  //additional code 20200205
						'VERIFIED DATE',           //blue  //additional code 20200205
						'SCHEDULED BY',           //blue  //additional code 20200205
						'SCHEDULED DATE',           //blue  //additional code 20200205
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					}else if(CRUDBooster::myPrivilegeName() == "Aftersales (Ops)"){
						$return_invalid = 			ReturnsStatus::where('id','15')->value('id');
						$repair_complete = 			ReturnsStatus::where('id','17')->value('id');
						$replacement_complete = 	ReturnsStatus::where('id','21')->value('id');
						$to_close = ReturnsStatus::where('id','30')->value('id');

                        $requested = ReturnsStatus::where('id','1')->value('id');
                        
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)//->where('returns_status_1','!=',$requested)
						/*->orwhereNotNull('returns_body_item_distribution.category')->where('returns_status_1', $repair_complete)
						->orwhereNotNull('returns_body_item_distribution.category')->where('returns_status_1', $replacement_complete)
						->orwhereNotNull('returns_body_item_distribution.category')->where('returns_status_1', $to_close)*/;	
						
						
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
						//	$orderRow->claimed_status,
							//$orderRow->credit_memo_number,
							$verified,
							$verified_date,
							$scheduled_by,
							$scheduled_date,
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
						'PRINTED BY',           //blue  //additional code 20200205
						'PRINTED DATE',           //blue  //additional code 20200205
						'SOR BY',           //blue  //additional code 20200205
						'SOR DATE',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
						'COMMENTS',
						'DIAGNOSED COMMENTS'
					);
					}else if(CRUDBooster::myPrivilegeName() == "SDM"){
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category')->where('transaction_type','!=', 2)->where('diagnose', "REPLACE")->wherenotnull('level6_personnel');	
						
						
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
							$orderRow->warranty_status,
							$orderRow->ship_back_status,
							$orderRow->claimed_status,
							$orderRow->credit_memo_number,
							$verified,
							$verified_date,
							$scheduled_by,
							$scheduled_date,
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'WARRANTY STATUS',
						'SHIP BACK STATUS',           //blue  //additional code 20200205
						'CLAIMED STATUS',           //blue  //additional code 20200205
						'CREDIT MEMO#',           //blue  //additional code 20200205
						'VERIFIED BY',           //blue  //additional code 20200205
						'VERIFIED DATE',           //blue  //additional code 20200205
						'SCHEDULED BY',           //blue  //additional code 20200205
						'SCHEDULED DATE',           //blue  //additional code 20200205
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
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
						$orderData = DB::table('returns_header_distribution')
						->leftjoin('warranty_statuses', 'returns_header_distribution.returns_status_1','=', 'warranty_statuses.id')
						->leftjoin('cms_users as verified', 'returns_header_distribution.level7_personnel','=', 'verified.id')
						->leftjoin('cms_users as scheduled_logistics', 'returns_header_distribution.level1_personnel','=', 'scheduled_logistics.id')		
						->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
						->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
						->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
						->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
						->leftjoin('cms_users as received1', 'returns_header_distribution.received_by_rma_sc','=', 'received1.id')
						->leftjoin('cms_users as turnover', 'returns_header_distribution.rma_receiver_id','=', 'turnover.id')
						->leftjoin('cms_users as specialist', 'returns_header_distribution.rma_specialist_id','=', 'specialist.id')
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
									'received1.name as received_by1',
									'turnover.name as turnover_by',
									'specialist.name as specialist_by',
									'closed.name as closed_by',
									'warranty_statuses.*'
						)->whereNotNull('returns_body_item_distribution.category');
						
						
						
						
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
							$orderRow->warranty_status,
							$orderRow->ship_back_status,
							$orderRow->claimed_status,
							$orderRow->credit_memo_number,
							$verified,
							$verified_date,
							$scheduled_by,
							$scheduled_date,
							$orderRow->turnover_by,
							$orderRow->rma_receiver_date_received,
							$orderRow->received_by1,
							$orderRow->received_at_rma_sc,

							$orderRow->diagnosed_by,
							$orderRow->level2_personnel_edited,
							$orderRow->specialist_by,
							$orderRow->rma_specialist_date_received,

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
						'WARRANTY STATUS',
						'SHIP BACK STATUS',           //blue  //additional code 20200205
						'CLAIMED STATUS',           //blue  //additional code 20200205
						'CREDIT MEMO#',           //blue  //additional code 20200205
						'VERIFIED BY',           //blue  //additional code 20200205
						'VERIFIED DATE',           //blue  //additional code 20200205
						'SCHEDULED BY',           //blue  //additional code 20200205
						'SCHEDULED DATE',           //blue  //additional code 20200205
						'RECEIVED BY',
						'RECEIVED DATE',
						'TURNOVER BY',
						'TURNOVER DATE',
						'DIAGNOSED BY',           //blue  //additional code 20200205
						'DIAGNOSED DATE',           //blue  //additional code 20200205
						'PROCESSED BY',           //blue  //additional code 20200205
						'PROCESSED DATE',  
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


		public function ReturnsHistoryEdit($id)
		{
			$this->cbLoader();
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data['row'] = ReturnsHeaderDISTRI::
				leftjoin('cms_users as created', 'returns_header_distribution.created_by','=', 'created.id')
				->leftjoin('cms_users as scheduled', 'returns_header_distribution.level1_personnel','=', 'scheduled.id')			
				->leftjoin('cms_users as diagnosed', 'returns_header_distribution.level2_personnel','=', 'diagnosed.id')				
				->leftjoin('cms_users as printed', 'returns_header_distribution.level3_personnel','=', 'printed.id')																	
				->leftjoin('cms_users as transacted', 'returns_header_distribution.level4_personnel','=', 'transacted.id')
				->leftjoin('cms_users as received', 'returns_header_distribution.level6_personnel','=', 'received.id')
				->leftjoin('cms_users as closed', 'returns_header_distribution.level5_personnel','=', 'closed.id')																		
				->select('returns_header_distribution.*','scheduled.name as scheduled_by',
						'diagnosed.name as diagnosed_by','printed.name as printed_by',	
						'transacted.name as transacted_by',	'received.name as received_by',
						'closed.name as closed_by','created.name as created_by')
				->where('returns_header_distribution.id',$id)
				->first();

			$data['resultlist'] = ReturnsBodyDISTRI::
				leftjoin('returns_serial_distribution', 'returns_body_item_distribution.id', '=', 'returns_serial_distribution.returns_body_item_id')					
				->select('returns_body_item_distribution.*','returns_serial_distribution.*')
				->where('returns_body_item_distribution.returns_header_id',$data['row']->id)
				->whereNotNull('returns_body_item_distribution.category')
				->get();
			
			$channels = Channel::where('channel_name', 'ONLINE')->first();

			$data['store_list'] = Stores::where('channels_id',$channels->id)->get();
			

			$data['ClaimedStatus'] = ClaimedStatus::all();


			$data['ShipBackStatus'] = ShipBackStatus::all();


			$this->cbView("returns.history_edit_distri", $data);
			
		}


		public function GetExtractReturnsDISTRISC() {

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

					$requested = ReturnsStatus::where('id','1')->value('id');
					//$to_receive_sor = 		ReturnsStatus::where('id','10')->value('id');
					$to_print_return_form = ReturnsStatus::where('id','13')->value('id');

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
					->select( 	'returns_header_distribution.*', 
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
								)->whereNull('returns_body_item_distribution.category')->where('transaction_type', 1)->where('returns_status_1','!=',$requested)->where('returns_status_1','!=',$to_print_return_form)->whereNotNull('diagnose');
					

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
						
						if($orderRow->diagnose == "REFUND"){
							//$transacted_personnel = $orderRow->transacted_by;
							//$transacted_date = 		$orderRow->level5_personnel_edited;
							$closed_personnel = 	$orderRow->verified_by;
							$closed_date = 			$orderRow->level1_personnel_edited;
						}else{
							//$transacted_personnel = "";
							//$transacted_date = "";
							//$closed_personnel = $orderRow->scheduled_by;
							//$closed_date = 		$orderRow->level2_personnel_edited;
							$closed_personnel = 	$orderRow->verified_by;
							$closed_date = 			$orderRow->level1_personnel_edited;
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
				 			$orderRow->digits_code,               
				 			$orderRow->upc_code,                 
				 			$orderRow->item_description,            
				 			$orderRow->cost,          
							$orderRow->brand,
							$serial_no->serial_number,
							$orderRow->problem_details,
				 			$orderRow->problem_details_other,                
							$orderRow->quantity,
							$closed_personnel,
							$closed_date,
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
						'DIGITS CODE',                 //green
						'UPC CODE',      //blue
						'ITEM DESCRIPTION',               //blue
						'COST',                 //bue
						'BRAND',              //blue  //additional code 20200121
                        'SERIAL#',                //bue   //additional code 20200121
						'PROBLEM DETAILS',       //additional code 20200207
						'PROBLEM DETAILS OTHERS',       //additional code 20200207
						'QUANTITY',           //blue  //additional code 20200205
						'CLOSED BY',           //blue  //additional code 20200205
						'CLOSED DATE',           //blue  //additional code 20200205
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