<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_philhealth_default extends Model
{
    protected $table = 'tbl_payroll_philhealth_default';
	protected $primaryKey = "payroll_philhealth_default_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_philhealth_default_id
	// [DOUBLE] 		payroll_philhealth_min
	// [DOUBLE] 		payroll_philhealth_max
	// [DOUBLE] 		payroll_philhealth_base
	// [DOUBLE] 		payroll_philhealth_premium
	// [DOUBLE] 		payroll_philhealth_ee_share
	// [DOUBLE] 		payroll_philhealth_er_share
}
