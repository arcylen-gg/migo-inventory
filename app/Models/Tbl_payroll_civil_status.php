<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_civil_status extends Model
{
    protected $table = 'tbl_payroll_civil_status';
	protected $primaryKey = "payroll_civil_status_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_civil_status_id
	// [VARCHAR] 		payroll_civil_status_name
}
