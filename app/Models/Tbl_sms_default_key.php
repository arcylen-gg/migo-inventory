<?php
namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Tbl_sms_default_key extends Model
{
	protected $table = 'tbl_sms_default_key';
	protected $primaryKey = "sms_default_id";
    public $timestamps = false;

    public function scopeTemplate($query, $shop_id)
    {
    	return $query->leftJoin("tbl_sms_template", function($on) use($shop_id)
			    	{
			    		$on->on("sms_default_key","=","sms_temp_key");
			    		$on->on("sms_temp_shop_id","=", DB::raw($shop_id));
			    	});	

    		
    }
}