<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_keeping_approved_daily_breakdown extends Model
{
    protected $table = 'tbl_payroll_time_keeping_approved_daily_breakdown';
	protected $primaryKey = "ptka_daily_breakdown_id";
    public $timestamps = false;

    public function scopeInsertBreakdown($query, $time_keeping_approved_id, $_cutoff_input)
    {
    	$_insert_breakdown = array();
    	foreach($_cutoff_input as $key => $input)
    	{
    		if(isset($input->compute->_breakdown_addition))
    		{
	    		foreach($input->compute->_breakdown_addition as $label => $brcompute)
	    		{
		    		$var["time_keeping_approve_id"] = $time_keeping_approved_id;
		    		$var["payroll_time_sheet_id"] = $input->payroll_time_sheet_id;
		    		$var["ptka_daily_type"] = "additions";
		    		$var["ptka_daily_label"] = $label;
		    		$var["ptka_daily_amount"] = $brcompute["rate"];
		    		array_push($_insert_breakdown, $var);
		    		$var = null;
	    		}
    		}

    		$brcompute = null;

    		if(isset($input->compute->_breakdown_deduction))
    		{
	    		foreach($input->compute->_breakdown_deduction as $label => $brcompute)
	    		{
		    		$var["time_keeping_approve_id"] = $time_keeping_approved_id;
		    		$var["payroll_time_sheet_id"] = $input->payroll_time_sheet_id;
		    		$var["ptka_daily_type"] = "deductions";
		    		$var["ptka_daily_label"] = $label;
		    		$var["ptka_daily_amount"] = $brcompute["rate"];
		    		array_push($_insert_breakdown, $var);
		    		$var = null;
	    		}
    		}
    	}

    	if(count($_insert_breakdown) > 0)
    	{
    		$query->insert($_insert_breakdown);
    	}
    	
    }
}