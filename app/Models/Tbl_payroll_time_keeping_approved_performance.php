<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_keeping_approved_performance extends Model
{
    protected $table = 'tbl_payroll_time_keeping_approved_performance';
	protected $primaryKey = "ptka_performance_id";
    public $timestamps = false;

    public function scopeInsertBreakdown($query, $time_keeping_approved_id, $_breakdown)
    {
    	$_insert = array(); 
    	
    	if($_breakdown)
    	{
    		foreach($_breakdown as $key => $breakdown)
    		{
    			$insert["time_keeping_approve_id"] = $time_keeping_approved_id;
    			$insert["ptka_daily_key"] = $key;
    			$insert["ptka_daily_float"] = $breakdown["float"];
    			$insert["ptka_daily_time"] = $breakdown["time"];
    			array_push($_insert, $insert);
    		}

    		$query->insert($_insert);
    	}
    }
}