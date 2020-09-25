<?php
namespace App\Globals;
use DB;
use Carbon\Carbon;
use App\Models\Tbl_truck;
class Truck
{
    public static function get($shop_id)
    {
    	return Tbl_truck::where("truck_shop_id", $shop_id)->get();
    }
}