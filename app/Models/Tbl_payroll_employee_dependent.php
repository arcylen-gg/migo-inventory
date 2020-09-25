<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_dependent extends Model
{
    protected $table = 'tbl_payroll_employee_dependent';
	protected $primaryKey = "payroll_employee_dependent_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */

	// [PRIMARY KEY] 	payroll_employee_dependent_id
	// [INTEGER] 		payroll_employee_id
	// [VARCHAR] 		payroll_dependent_name
	// [VARCHAR] 		payroll_dependent_relationship
	// [DATE] 			payroll_dependent_birthdate
}
