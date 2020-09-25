<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_wis_budget extends Model
{
	protected $table = 'tbl_customer_wis_budget';
	protected $primaryKey = "wis_budget_id";
    public $timestamps = false;

    public function scopeWis($query)
    {
        $query->leftjoin("tbl_customer_wis","cust_wis_id", "=", "budget_wis_id");
        return $query;        
    }
}