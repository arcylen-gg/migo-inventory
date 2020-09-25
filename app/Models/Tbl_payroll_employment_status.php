<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employment_status extends Model
{
    protected $table = 'tbl_payroll_employment_status';
	protected $primaryKey = "payroll_employment_status_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_employment_status_id
	// [VARCHAR] 		employment_status
}
