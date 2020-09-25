<?php
namespace App\Globals;

use DB;
use App\Models\Tbl_category;
use App\Models\Tbl_user;
use Session;
class Tablet_global
{
	public static function getShopId()
	{
		$id = 0;
		$shop_id = collect(Session::get("sales_agent"));
		if(isset($shop_id['shop_id']))
		{
			$id = $shop_id['shop_id'];
		}
        return $id;
	}
}