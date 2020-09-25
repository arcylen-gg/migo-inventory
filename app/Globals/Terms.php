<?php
namespace App\Globals;

use App\Models\Tbl_terms;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */

class Terms
{
 	public static function terms($shop_id, $terms_id)
	{
		$return = '-';
		if($terms_id != 0)
		{
			$data =  Tbl_terms::where("archived", 0)->where('terms_shop_id', $shop_id)->where('terms_id', $terms_id)->first();
			if($data)
			{
				$return = $data->terms_name;
			}
		}
		else
		{
			$return = '-';
		}
		return $return;
	}
	public static function active_terms($shop_id)
	{
		return Tbl_terms::where("archived", 0)->where("terms_shop_id", $shop_id)->get();
	}
}