<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_wis_budgetline extends Model
{
	protected $table = 'tbl_customer_wis_budgetline';
	protected $primaryKey = "wis_budgetline_id";
    public $timestamps = false;

    public function scopeItem($query)
    {
    	return $query->leftjoin("tbl_item","item_id","=","budgetline_item_id");
    }
}