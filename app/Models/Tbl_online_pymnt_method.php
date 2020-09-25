<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_online_pymnt_method extends Model
{
	protected $table = 'tbl_online_pymnt_method';
	protected $primaryKey = "method_id";
    public $timestamps = false;

    public function scopeLink($query, $shop_id)
    {
    	return $query->leftjoin(DB::raw("(select * from tbl_online_pymnt_link where link_shop_id = ".$shop_id.") as link"),"link_method_id","=","method_id")
    				 ->leftJoin("tbl_image","image_id","=","link_img_id")
    				 ->orderBy("method_id");
    }
}