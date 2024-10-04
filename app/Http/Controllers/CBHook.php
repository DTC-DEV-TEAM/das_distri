<?php 
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class CBHook extends Controller {

	/*
	| --------------------------------------
	| Please note that you should re-login to see the session work
	| --------------------------------------
	|
	*/
	public function afterLogin() {
		$usersData = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();

        if (Hash::check(request('password'), $usersData->password)) {
            if($usersData->status == 'INACTIVE'){
                Session::flush();
                return redirect()->route('getLogin')->with('message', 'The user does not exist!');
            }
        }

        $today = Carbon::now()->format('Y-m-d H:i:s');
        $lastChangePass = Carbon::parse($usersData->last_password_updated);
        $needsPasswordChange = Hash::check('qwerty', $usersData->password) || $lastChangePass->diffInMonths($today) >= 3;

		if($needsPasswordChange){
			Session::put('check-user',true);
			return redirect()->route('show-change-password')->send();
		}
	}
}