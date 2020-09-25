<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_sheet_management extends Model
{
    protected $table = 'tbl_payroll_time_sheet_management';
	protected $primaryKey = "payroll_time_sheet_management_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] payroll_time_sheet_management_id
	// [INTEGER] payroll_time_sheet_id
	// [STRING] [DEFAULT 00:00] payroll_time_sheet_approve_over_time
	// [STRING] [DEFAULT 00:00] payroll_time_sheet_break
}
