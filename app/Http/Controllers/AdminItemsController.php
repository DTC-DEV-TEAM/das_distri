<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Excel;
use CRUDBooster;
use App\Brand;
use App\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminItemsController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function __construct()
	{
		// Register ENUM type
		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
	}

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "digits_code";
		$this->limit = "20";
		$this->orderby = "digits_code,asc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = false;
		$this->button_edit = false;
		$this->button_delete = true;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "digits_imfs";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Digits Code", "name" => "digits_code"];
		$this->col[] = ["label" => "UPC Code", "name" => "upc_code"];
		$this->col[] = ["label" => "Supplier Item Code", "name" => "supplier_itemcode"];
		$this->col[] = ["label" => "Item Description", "name" => "item_description"];
		$this->col[] = ["label" => "Brand", "name" => "brand_id", "join" => "brand,brand_description"];
		$this->col[] = ["label" => "WH Category", "name" => "warehouse_category_id", "join" => "warehouse_category,wh_category_description"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		if (CRUDBooster::getCurrentMethod() == 'getDetail') {
			$this->form[] = ["label" => "Digits Code", "name" => "digits_code", "type" => "text"];
			$this->form[] = ["label" => "UPC Code", "name" => "upc_code", "type" => "text"];
			//$this->form[] = ["label"=>"UPC Code-2","name"=>"upc_code2", "type"=>"text"];
			//$this->form[] = ["label"=>"UPC Code-3","name"=>"upc_code3", "type"=>"text"];
			//$this->form[] = ["label"=>"UPC Code-4","name"=>"upc_code4", "type"=>"text"];
			//$this->form[] = ["label"=>"UPC Code-5","name"=>"upc_code5", "type"=>"text"];
			$this->form[] = ["label" => "Supplier Item Code", "name" => "supplier_itemcode", "type" => "text"];
			$this->form[] = ["label" => "Item Description", "name" => "item_description", "type" => "text"];
			$this->form[] = [
				"label" => "Brand",
				"name" => "brand_id",
				"type" => "select",
				"datatable" => "brand,brand_description"
			];

			$this->form[] = [
				"label" => "Warehouse Category",
				"name" => "warehouse_category_id",
				"type" => "select",
				"datatable" => "warehouse_category,wh_category_description"
			];
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
		if (CRUDBooster::isUpdate()) {
			$this->button_selected[] = [
				'label' => 'Set SKU Legend CORE',
				'icon' => 'fa fa-check-circle',
				'name' => 'set_SKU_legend_CORE'
			];

			$this->button_selected[] = [
				'label' => 'Set SKU Legend NON-CORE',
				'icon' => 'fa fa-check-circle-o',
				'name' => 'set_SKU_legend_NON_CORE'
			];
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
		if (CRUDBooster::getCurrentMethod() == 'getIndex') {
			$this->index_button[] = ['label' => 'Export All', "url" => CRUDBooster::mainpath("export-excel"), "icon" => "fa fa-download"];
			//$this->index_button[] = ['label' => 'Update Items', "url" => CRUDBooster::mainpath("item-updated"), "icon" => "fa fa-download" ,"color" => "success"];
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
		$this->index_statistic[] = ['label' => 'Total SKUs', 'count' => DB::table($this->table)->count('digits_code'), 'icon' => 'fa fa-pie-chart', 'color' => 'blue', 'width' => 'col-sm-6'];


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
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here
		if ($button_name == 'set_SKU_legend_CORE') {
			Item::whereIn('id', $id_selected)->update([
				'skulegend_id' => 1,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => CRUDBooster::myId()
			]);
		} else if ($button_name == 'set_SKU_legend_NON_CORE') {
			Item::whereIn('id', $id_selected)->update([
				'skulegend_id' => 2,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => CRUDBooster::myId()
			]);
		}
	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	public function hook_query_index(&$query)
	{
		//Your code here

	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		//Your code here
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{
		$postdata['created_by'] = CRUDBooster::myId();
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id)
	{
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
	public function hook_before_edit(&$postdata, $id)
	{
		$postdata['updated_by'] = CRUDBooster::myId();
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id)
	{
		//Your code here 

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{
		//Your code here

	}

	public function getItemsUpdatedAPI()
	{
		$secretKey = config('api.API_Secret_key');
		$uniqueString = time();
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?: config('api.user_agent');
		$xAuthorizationToken = md5($secretKey . $uniqueString . $userAgent);
		$xAuthorizationTime = $uniqueString;
		$vars = [
			"your_param" => 1
		];

		// set date range and pagination parameters
		$dateFrom = Carbon::now()->subDay(50)->format('Y-m-d H:i:s');
		$dateTo = Carbon::now()->format('Y-m-d H:i:s');

		$queryString = http_build_query([
			'datefrom' => $dateFrom,
			'dateto' => $dateTo,
			'page' => 1,
			'limit' => 1000,
		]);

		// include the query parameters to the API url
		$itemUpdateUrl = "http://dimfs.digitstrading.ph/api/das_items_updated?" . $queryString;

		//https://stackoverflow.com/questions/8115683/php-curl-custom-headers
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $itemUpdateUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, null);
		curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

		$headers = [
			'X-Authorization-Token: ' . $xAuthorizationToken,
			'X-Authorization-Time: ' . $xAuthorizationTime,
			'User-Agent: ' . $userAgent
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$server_output = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($server_output, true);
		$cnt_success = 0;
		$cnt_fail = 0;
		$check_sync = false;

		if (!empty($response["data"])) {
			foreach ($response["data"] as $value) {
				DB::beginTransaction();
				try {
					$isItemUpdated = DB::table('digits_imfs')
						->where(
							'digits_code', $value['digits_code'])
						->update([	
							'digits_code' => $value['digits_code'],
							'upc_code' => $value['upc_code'],
							'supplier_itemcode' => $value['supplier_item_code'],
							'item_description' => $value['item_description'],
							'brand_id' => $value['brands_id'],
							'warehouse_category_id' => $value['warehouse_categories_id'], 
						]);
					DB::commit();
					if ($isItemUpdated || DB::table('digits_imfs')->where('digits_code', $value['digits_code'])->exists()) {
						$cnt_success++;
					} else {
						$cnt_fail++;
					}
				} catch (\Exception $e) {
					DB::rollback();
					$cnt_fail++;
				}
			}
		}
		Log::info('itemupdate: executed! ' . $cnt_success . ' items');
	}

	public function getItemsCreatedAPI()
	{
		$secretKey = config('api.API_Secret_key');
		$uniqueString = time();
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?: config('api.user_agent');
		$xAuthorizationToken = md5($secretKey . $uniqueString . $userAgent);
		$xAuthorizationTime = $uniqueString;
		$vars = [
			"your_param" => 1
		];

		// set date range and pagination parameters
		$dateFrom = Carbon::now()->subDay(50)->format('Y-m-d H:i:s');
		$dateTo = Carbon::now()->format('Y-m-d H:i:s');

		$queryString = http_build_query([
			'datefrom' => $dateFrom,
			'dateto' => $dateTo,
			'page' => 1,
			'limit' => 1000,
		]);

		// include the query parameters to the API url
		$ItemCreatedUrl = "http://dimfs.digitstrading.ph/api/das_items_created?" . $queryString;

		//https://stackoverflow.com/questions/8115683/php-curl-custom-headers
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ItemCreatedUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, null);
		curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

		$headers = [
			'X-Authorization-Token: ' . $xAuthorizationToken,
			'X-Authorization-Time: ' . $xAuthorizationTime,
			'User-Agent: ' . $userAgent
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$server_output = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($server_output, true);
		$count = 0;

		if (!empty($response["data"])) {
			foreach ($response["data"] as $value) {
				$count++;
				DB::beginTransaction();
				try {
					DB::table('digits_imfs')->insert([
						'digits_code' => $value['digits_code'],
						'upc_code' => $value['upc_code'],
						'supplier_itemcode' => $value['supplier_item_code'],
						'item_description' => $value['item_description'],
						'brand_id' => $value['brands_id'],
						'warehouse_category_id' => $value['warehouse_categories_id'], 
					]);
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
				}
			}
		}
		Log::info('itemcreate: executed! ' . $count . ' items');
	}

	public function customExport()
	{

		$dbhost = env('DB_HOST');
		$dbport = env('DB_PORT');
		$dbname = env('DB_DATABASE');
		$dbuser = env('DB_USERNAME');
		$dbpass = env('DB_PASSWORD');
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);

		if (! $conn) {
			die('Could not connect: ' . mysqli_error());
		}

		$sql_query = "SELECT 
		        `digits_imfs`.digits_code AS 'DIGITS CODE',
		        `digits_imfs`.upc_code AS 'UPC CODE-1',
		        `digits_imfs`.upc_code2 AS 'UPC CODE-2',
		        `digits_imfs`.upc_code3 AS 'UPC CODE-3',
		        `digits_imfs`.upc_code4 AS 'UPC CODE-4',
		        `digits_imfs`.upc_code5 AS 'UPC CODE-5',
			`digits_imfs`.supplier_itemcode AS 'SUPPLIER ITEM CODE',
		    `digits_imfs`.item_description AS 'ITEM DESCRIPTION',
			`brand`.brand_description AS 'BRAND DESCRIPTION',
			`warehouse_category`.wh_category_description AS 'WH CATEGORY DESCRIPTION'";

		$sql_query .= " FROM `digits_imfs` 
		        LEFT JOIN `brand` ON `digits_imfs`.brand_id = `brand`.id 
		        LEFT JOIN `warehouse_category` ON `digits_imfs`.warehouse_category_id = `warehouse_category`.id 
		        LEFT JOIN `cms_users` as user1 ON `digits_imfs`.created_by = `user1`.id 
		        LEFT JOIN `cms_users` as user2 ON `digits_imfs`.updated_by = `user2`.id ";
		$sql_query .= " ORDER BY `digits_imfs`.digits_code ASC";

		$resultset = mysqli_query($conn, $sql_query) or die("Database Error:" . mysqli_error($conn));

		$filename = "Export IMFS - " . date('Ymd H:i:s') . ".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		$delimiter = "\t";
		while ($header = mysqli_fetch_field($resultset)) {
			echo $header->name . "\t";
		}
		print "\n";
		while ($row = mysqli_fetch_row($resultset)) {
			$schema_insert = "";
			for ($j = 0; $j < mysqli_num_fields($resultset); $j++) {
				if (!isset($row[$j]))
					$schema_insert .= "" . $delimiter;
				elseif ($row[$j] != "") {
					if ($j == 0 && $row[0] != "") {
						$schema_insert .= '="' . "$row[0]" . '"' . $delimiter;
					} elseif ($j == 1 && $row[1] != "") {
						$schema_insert .= '="' . "$row[1]" . '"' . $delimiter;
					} elseif ($j == 2 && $row[2] != "") {
						$schema_insert .= '="' . "$row[2]" . '"' . $delimiter;
					} elseif ($j == 3 && $row[3] != "") {
						$schema_insert .= '="' . "$row[3]" . '"' . $delimiter;
					} elseif ($j == 4 && $row[4] != "") {
						$schema_insert .= '="' . "$row[4]" . '"' . $delimiter;
					} elseif ($j == 5 && $row[5] != "") {
						$schema_insert .= '="' . "$row[5]" . '"' . $delimiter;
					} elseif ($j == 6 && $row[6] != "") {
						$schema_insert .= '="' . "$row[6]" . '"' . $delimiter;
					} else {
						$schema_insert .= "$row[$j]" . $delimiter;
					}
				} else
					$schema_insert .= "" . $delimiter;
			}
			$schema_insert = str_replace($sep . "$", "", $schema_insert);
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
		}

		mysqli_close($conn);
		exit;
	}
}
