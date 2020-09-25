<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_position;
use Log;
use Request;
use Session;
use Validator;
use Redirect;

class Employee
{

	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	public static function getPosition()
	{
		$shop 	= Employee::getShopId();
		$result = Tbl_position::where("archived",0)->where("position_shop_id",$shop)->get();
		
		return $result;
	}

}