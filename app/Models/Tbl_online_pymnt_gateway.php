<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_online_pymnt_gateway extends Model
{
	protected $table = 'tbl_online_pymnt_gateway';
	protected $primaryKey = "gateway_id";
    public $timestamps = false;

    // public function scopeGatewayInfo($query, $shop_id)
    // {
    // 	return $query->join(DB::raw("Select * from tbl_online_pymnt_apo"))
    // }

    public function scopeGatewayApi($query, $shop_id)
    {
    	return $query->leftJoin(DB::raw('(select * from tbl_online_pymnt_api where api_shop_id='.$shop_id.') as api'),"api_gateway_id","=","gateway_id");
    }

    public function scopeUnionGateway($query, $shop_id)
    {
        $first = DB::table("tbl_online_pymnt_gateway")->selectRaw("gateway_id, gateway_name, gateway_code_name, 'api' as reference_name, api_id as reference_id, gateway_name as display_name, api_client_id, api_secret_id, NULL as other_name, NULL as other_description")
                     ->Join(DB::raw('(select * from tbl_online_pymnt_api where api_shop_id='.$shop_id.') as api'),"api_gateway_id","=","gateway_id");
        return $query->selectRaw("gateway_id, gateway_name, gateway_code_name, 'other' as reference_name, other_id as reference_id, other_name as display_name, NULL as api_client_id, NULL as api_secet_id, other_name, other_description")
                     ->Join(DB::raw('(Select * from tbl_online_pymnt_other where other_shop_id='.$shop_id.') as other'),"other_gateway_id","=","gateway_id")->union($first);

    }

        // public function scopeJoinApi($query, $shop_id)
    // {
    //  return $query->selectRaw("gateway_id, gateway_name, gateway_code_name, 'api' as reference_name, api_id as reference_id, gateway_name as display_name")
    //               ->Join(DB::raw('(select * from tbl_online_pymnt_api where api_shop_id='.$shop_id.') as api'),"api_gateway_id","=","gateway_id"); 
    // }

    // public function scopeJoinother($query, $shop_id)
    // {
    //  return $query->selectRaw("gateway_id, gateway_name, gateway_code_name, 'other' as reference_name, other_id as reference_id, other_name as display_name")
    //               ->Join(DB::raw('(select * from tbl_online_pymnt_other where other_shop_id='.$shop_id.') as other'),"other_gateway_id","=","gateway_id");
    // }
}