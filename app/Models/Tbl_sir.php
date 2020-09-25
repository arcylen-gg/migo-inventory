<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sir extends Model
{
	protected $table = 'tbl_sir';
	protected $primaryKey = "sir_id";
    public $timestamps = true;

    public function scopeTruck($query)
    {
    	return $query->leftjoin("tbl_truck","tbl_truck.truck_id","=","tbl_sir.truck_id");
    }
    public function scopeSaleagent($query)
    {
    	return $query->leftjoin("tbl_employee","tbl_employee.employee_id","=","tbl_sir.sales_agent_id");
    }
    public function scopeSir_item($query)
    {
    	return $query->leftjoin("tbl_sir_item","tbl_sir_item.sir_id","=","tbl_sir.sir_id")
    				 ->selectRaw("*, count(tbl_sir_item.sir_id) as total_item, tbl_sir.created_at as sir_created,tbl_sir.sir_id as sir_id, tbl_sir.archived as sir_archived")
    				 ->groupBy("tbl_sir.sir_id");
    }
    public function scopeSelect_sir_item($query)
    {
        return $query->join("tbl_sir_item","tbl_sir_item.sir_id","=","tbl_sir.sir_id")
                     ->join("tbl_item","tbl_item.item_id","=","tbl_sir_item.item_id");
    }
}