<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_tax_period extends Model
{
    protected $table = 'tbl_payroll_tax_period';
	protected $primaryKey = "payroll_tax_period_id";
    public $timestamps = false;


    /* COLUMN NAME REFERENCE */
    // [PRIMARY KEY] 	payroll_tax_period_id
    // [VARCHAR] 		payroll_tax_period
    // [INTEGER] 		shop_id
    // [INTEGER]		is_use

    public function scopecheck($query, $shop_id = 0, $is_use = 1)
    {
    	$query->where('shop_id',$shop_id)->where('is_use', $is_use);
    	return $query;
    }

    public function scopegetperiod($query, $shop_id = 0, $payroll_tax_default_id = 0)
    {
    	$query->join('tbl_payroll_tax_period_default','tbl_payroll_tax_period_default.payroll_tax_period','=','tbl_payroll_tax_period.payroll_tax_period')
    		 ->where('tbl_payroll_tax_period.shop_id',$shop_id)
    		 ->where('tbl_payroll_tax_period_default.payroll_tax_period_default_id',$payroll_tax_default_id);
    	return $query;
    }	
}
