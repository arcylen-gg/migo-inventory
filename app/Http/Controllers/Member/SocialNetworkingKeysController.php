<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Globals\SocialNetwork;
class SocialNetworkingKeysController extends Member
{	

	public function getIndex()
    {
    	$data['_app_list'] = SocialNetwork::get_all_app($this->user_info->shop_id);
    	return view('member.maintenance.social_network_keys.key_list',$data);
    }
    public function getAddAppkey(Request $request)
    {
    	$data['action'] = '/member/maintenance/app_keys/submit-add';
    	$data['process'] = 'CREATE';

    	if($request->id)
    	{
	    	$data['action'] = '/member/maintenance/app_keys/submit-update';
	    	$data['process'] = 'EDIT';
	    	$data['app'] = SocialNetwork::get_data($request->id);
    	}

    	return view('member.maintenance.social_network_keys.add_key',$data);
    }
    public function postSubmitAdd(Request $request)
    {
    	$ins['social_network_name'] = strtolower(trim($request->social_network_name));
    	$ins['app_id'] = $request->app_id;
    	$ins['app_secret'] = $request->app_secret;
    	$ins['app_created'] = Carbon::now();

    	$validate = SocialNetwork::validate($this->user_info->shop_id, $ins);
    	if(!$validate)
    	{
	    	$id = SocialNetwork::create($this->user_info->shop_id, $ins);
	    	if($id > 0)
	    	{
	    		$return['status'] = 'success';
	    		$return['call_function'] = 'success_key';
	    		$return['message'] = 'App Key Successfully Created';
	    	}
	    	else
	    	{
	    		$return['status'] = 'error';
	    		$return['message'] = "Something  wen't wrong.";
	    	}
    	}
    	else
    	{
    		$return['status'] = 'error';
    		$return['message'] = $validate;
    	}

    	return $return;
    }
    public function postSubmitUpdate(Request $request)
    {
    	// $ins['social_network_name'] = strtolower(trim($request->social_network_name));
    	$ins['app_id'] = $request->app_id;
    	$ins['app_secret'] = $request->app_secret;
    	// $ins['app_created'] = Carbon::now();

    	$validate = SocialNetwork::validate($this->user_info->shop_id, $ins, $request->keys_id);

    	if(!$validate)
    	{
	    	$id = SocialNetwork::update($request->keys_id, $this->user_info->shop_id, $ins);
	    	if($id > 0)
	    	{
	    		$return['status'] = 'success';
	    		$return['call_function'] = 'success_key';
	    		$return['message'] = 'App Key Successfully Updated';
	    	}
	    	else
	    	{
	    		$return['status'] = 'error';
	    		$return['message'] = "Something  wen't wrong.";
	    	}
    	}
    	else
    	{
    		$return['status'] = 'error';
    		$return['message'] = $validate;
    	}

    	return $return;
    }
}
