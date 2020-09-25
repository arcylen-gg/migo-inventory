<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_reports_column extends Model
{
    protected $table = 'tbl_payroll_reports_column';
	protected $primaryKey = "payroll_reports_column_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_reports_column_id
	// [INTEGER] payroll_reports_id
	// [INTEGER] column_entity_id
	// [VARCHAR] column_origin

	public function scopegetcolumn($query, $payroll_reports_id = 0, $column_entity_id = 0, $column_origin = 'payroll entity')
	{
		$query->where('payroll_reports_id', $payroll_reports_id)
		      ->where('column_entity_id', $column_entity_id)
		      ->where('column_origin', $column_origin);
		return $query;
	}
}
