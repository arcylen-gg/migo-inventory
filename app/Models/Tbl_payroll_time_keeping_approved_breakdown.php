<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_keeping_approved_breakdown extends Model
{
    protected $table = 'tbl_payroll_time_keeping_approved_breakdown';
	protected $primaryKey = "ptkab_id";
    public $timestamps = false;

    public function scopeInsertBreakdown($query, $time_keeping_approve_id, $_breakdown)
    {
		foreach($_breakdown as $key => $cutoff_breakdown)
		{
			$insert_breakdown[$key]["time_keeping_approve_id"] = $time_keeping_approve_id;
			$insert_breakdown[$key]["ptkab_label"] = $cutoff_breakdown["label"];
			$insert_breakdown[$key]["ptkab_type"] = $cutoff_breakdown["type"];
			$insert_breakdown[$key]["ptkab_amount"] = $cutoff_breakdown["amount"];

			if(isset($cutoff_breakdown["description"]))
			{
				$insert_breakdown[$key]["ptkab_description"] = $cutoff_breakdown["description"];
			}
			else
			{
				$insert_breakdown[$key]["ptkab_description"] = "";
			}
			
			$insert_breakdown[$key]["add_gross_pay"] = ($cutoff_breakdown["add.gross_pay"] == true ? 1 : 0);
			$insert_breakdown[$key]["deduct_gross_pay"] = ($cutoff_breakdown["deduct.gross_pay"] == true ? 1 : 0);
			$insert_breakdown[$key]["add_taxable_salary"] = ($cutoff_breakdown["add.taxable_salary"] == true ? 1 : 0);
			$insert_breakdown[$key]["deduct_taxable_salary"] = ($cutoff_breakdown["deduct.taxable_salary"] == true ? 1 : 0);
			$insert_breakdown[$key]["add_net_pay"] = ($cutoff_breakdown["add.net_pay"] == true ? 1 : 0);
			$insert_breakdown[$key]["deduct_net_pay"] = ($cutoff_breakdown["deduct.net_pay"] == true ? 1 : 0);
		}

		$query->insert($insert_breakdown);
    }
}