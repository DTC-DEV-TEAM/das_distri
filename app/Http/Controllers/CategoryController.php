<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct() {
        // Register ENUM type
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
    
    
        public function getCategoryUpdatedAPI() {

            $secretKey = "4fea7e3c217fa58b26d57fc186e906b4"; 
            $uniqueString = time(); 
            $userAgent = $_SERVER['HTTP_USER_AGENT']; 
            $userAgent = $_SERVER['HTTP_USER_AGENT']; 
            if($userAgent == '' || is_null($userAgent)){
                $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';    
            }
            $xAuthorizationToken = md5( $secretKey . $uniqueString . $userAgent);
            $xAuthorizationTime = $uniqueString;
            $vars = [
                "your_param"=>1
            ];
    
            //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"http://dimfs.digitstrading.ph/api/imfs_category_updated");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS,null);
            curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);
    
            $headers = [
            'X-Authorization-Token: ' . $xAuthorizationToken,
            'X-Authorization-Time: ' . $xAuthorizationTime,
            'User-Agent: '.$userAgent
            ];
    
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $server_output = curl_exec ($ch);
            curl_close ($ch);
    
            $response = json_decode($server_output, true);
            
            $cnt_success = 0;
            $cnt_fail = 0;
            $check_sync = false;
            if(!empty($response["data"])) {
                foreach ($response["data"] as $key => $value) {
                    
                    DB::beginTransaction();
    
    				try {
    				    $isBrandUpdated = DB::table('category')->where('category_code', $value['category_code'])->update($value);
    					DB::commit();
    				} catch (\Exception $e) {
    					DB::rollback();
    				}
    				
    				if ($isBrandUpdated) {
                        $check_sync = true;
                        $cnt_success++;
                    }
                    else{
                        $check_sync = false;
                        $cnt_fail++;
                    }
                    
                }
            }
            \Log::info('categoryupdate: executed! '.$cnt_success.' items');
            
        }
        
    public function getCategoryCreatedAPI() {

        $secretKey = "4fea7e3c217fa58b26d57fc186e906b4"; 
        $uniqueString = time(); 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        if($userAgent == '' || is_null($userAgent)){
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';    
        }
        $xAuthorizationToken = md5( $secretKey . $uniqueString . $userAgent);
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://dimfs.digitstrading.ph/api/imfs_category_created");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.$userAgent
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        
        $data = array();
        $count = 0;
        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                $count++;
                DB::beginTransaction();
				try {
				    DB::table('category')->insert($value);
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
				}
                
            }
        }
        \Log::info('categorycreate: executed! '.$count.' items');
        
    }
}
