<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_copy_log_requirements extends Model
{
    protected $table = 'tbl_payroll_copy_log_requirements';
	protected $primaryKey = "payroll_copy_log_requirements_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */

	// [PRIMARY KEY] 	payroll_copy_log_requirements_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		requirements_category
	// [DATE TIME] 		requirements_copy_date
}
