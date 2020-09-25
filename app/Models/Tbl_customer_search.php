<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer_search extends Model
{
	protected $table = 'tbl_customer_search';
	protected $primaryKey = "id";
    public $timestamps = false;


    public function scopesearch($query, $str = '', $shop_id = 0, $archived = 0)
    {
    	$query->leftjoin('tbl_customer','tbl_customer.customer_id','=','tbl_customer_search.customer_id')
				->leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')
				->whereRaw("MATCH(tbl_customer_search.body) AGAINST('*".$str."*' IN BOOLEAN MODE)")
				->where('tbl_customer.archived',$archived)
				->where('tbl_customer.IsWalkin',0)
				->where('tbl_customer.shop_id',$shop_id)
				->select('tbl_customer.customer_id as customer_id1', 'tbl_customer.*', 'tbl_customer_other_info.*', 'tbl_customer_other_info.customer_id as cus_id')
				->orderBy('tbl_customer.first_name','asc');

		return $query;
    }
       public function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=", "tbl_customer_search.customer_id");
    }
}