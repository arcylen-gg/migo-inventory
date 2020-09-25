<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;

use App\Models\Tbl_social_network_keys;
use Carbon\carbon;
use DB;

/**
 * 
 *
 * @author ARCY
 */

class SocialNetwork
{
	public static function get_keys($shop_id, $app_name)
	{
		$return = null; 
		$data = Tbl_social_network_keys::where('shop_id',$shop_id)->where('social_network_name',$app_name)->first();
		if($data)
		{
			if($data->app_id && $data->app_secret)
			{
				$return['app_id'] = $data->app_id;
				$return['app_secret'] = $data->app_secret;				
			}
		}

		return $return;
	}

	public static function get_all_app($shop_id)
	{
		return Tbl_social_network_keys::where('shop_id',$shop_id)->get();
	}
	public static function get_data($id)
	{
		return Tbl_social_network_keys::where('keys_id',$id)->first();
	}
	public static function validate($shop_id, $info, $keys_id = 0)
	{
		$data = Tbl_social_network_keys::where('shop_id',$shop_id)->where('keys_id','!=',$keys_id)->first();
		if($keys_id == 0)
		{
			$data = Tbl_social_network_keys::where('shop_id',$shop_id)->first();
		}
		$return = null;
		if($data)
		{
			if(isset($info['social_network_name']))
			{
				if($data->social_network_name == $info['social_network_name'])
				{
					$return .= "App name already exist";
				}
			}
		}

		return $return;
	}
	public static function create($shop_id, $info)
	{
		$info['shop_id'] = $shop_id;
		return Tbl_social_network_keys::insertGetId($info);
	}
	public static function update($keys_id, $shop_id, $info)
	{
		Tbl_social_network_keys::where('keys_id',$keys_id)->update($info);
		return $keys_id;
	}
}