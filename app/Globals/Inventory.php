<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_settings;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * Inventory
 *
 * @author Arcylen Gutierrez
 */
class Inventory
{
	public static function allow_out_of_stock($shop_id)
	{
		return Tbl_settings::where("settings_key",'out_of_stock')->where('shop_id', $shop_id)->value("settings_value");
	}
}